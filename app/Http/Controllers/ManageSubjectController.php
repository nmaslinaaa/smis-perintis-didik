<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Classes;
use App\Models\Subjects;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ManageSubjectController extends Controller
{
    public function index(Request $request)
    {
        // Fetch all classes (darjah)
        $classes = Classes::all();
        $selectedClassId = $request->query('class_id');
        if (!$selectedClassId && $classes->count() > 0) {
            $selectedClassId = $classes->first()->classID;
        }

        // Fetch subjects for the selected class using the class_subject pivot table
        $subjects = [];
        if ($selectedClassId) {
            // Get one price per subject for this class
            $subjectPrices = DB::table('class_subject')
                ->join('subjects', 'class_subject.subjectID', '=', 'subjects.subjectID')
                ->where('class_subject.classID', $selectedClassId)
                ->select(
                    'subjects.subjectID',
                    'subjects.subject_name',
                    'class_subject.subject_price'
                )
                ->groupBy('subjects.subjectID', 'subjects.subject_name', 'class_subject.subject_price')
                ->get();

            $subjects = [];
            foreach ($subjectPrices as $row) {
                $subjects[$row->subjectID] = [
                    'subjectID' => $row->subjectID,
                    'subject_name' => $row->subject_name,
                    'subject_price' => $row->subject_price
                ];
            }
            $subjects = array_values($subjects);
        }

        return view('admin.manage_subjects', [
            'classes' => $classes,
            'selectedClassId' => $selectedClassId,
            'subjects' => $subjects,
        ]);
    }

    public function editSubject($subjectID)
    {
        $subject = Subjects::findOrFail($subjectID);

        // Get the class ID from the first assignment
        $classAssignment = DB::table('class_subject')
            ->where('subjectID', $subjectID)
            ->first();

        $classID = $classAssignment ? $classAssignment->classID : null;
        $subject_price = $classAssignment ? $classAssignment->subject_price : null;

        // Get only teachers assigned to this class
        $teachers = [];
        if ($classID) {
            $teacherIds = DB::table('teacher_class')
                ->where('classID', $classID)
                ->pluck('employeeID');

            $teachers = \App\Models\Employee::whereIn('employeeID', $teacherIds)->get();
        }

        $classes = \App\Models\Classes::all();
        $days = ['Isnin', 'Selasa', 'Rabu', 'Khamis', 'Jumaat', 'Sabtu', 'Ahad'];
        $assignments = DB::table('class_subject')
            ->where('subjectID', $subjectID)
            ->select('classsubjectID', 'employeeID', 'classID', 'days', 'start_time', 'end_time')
            ->get();

        return view('admin.edit_subject', [
            'subject' => $subject,
            'teachers' => $teachers,
            'classes' => $classes,
            'days' => $days,
            'assignments' => $assignments,
            'subject_price' => $subject_price,
        ]);
    }

    public function updateSubject(Request $request, $subjectID)
    {
        // Validate and update subject name only
        $subject = \App\Models\Subjects::findOrFail($subjectID);
        $subject->subject_name = $request->input('subject_name');

        // Check for subject duplication within the same class (excluding current subject)
        $existingAssignment = DB::table('class_subject')->where('subjectID', $subjectID)->first();
        $classID = $existingAssignment ? $existingAssignment->classID : null;

        if ($classID) {
            $existingSubject = DB::table('class_subject')
                ->join('subjects', 'class_subject.subjectID', '=', 'subjects.subjectID')
                ->where('class_subject.classID', $classID)
                ->where('subjects.subject_name', $subject->subject_name)
                ->where('subjects.subjectID', '!=', $subjectID)
                ->first();

            if ($existingSubject) {
                return redirect()->back()->withErrors(['subject_name' => 'A subject with this name already exists in this class. Please choose a different name.'])->withInput();
            }
        }

        $subject->save();

        $subject_price = $request->input('subject_price');
        $assignments = $request->input('assignments', []);
        $existingAssignment = DB::table('class_subject')->where('subjectID', $subjectID)->first();
        $classID = $existingAssignment ? $existingAssignment->classID : null;

        // --- 1. CHECK FOR DUPLICATE SLOTS (SAME TEACHER, SAME DAY, SAME TIME) ---
        $seenSlots = [];
        foreach ($assignments as $index => $row) {
            if (!empty($row['employeeID']) && !empty($row['days']) && !empty($row['start_time']) && !empty($row['end_time'])) {
                $slotKey = $row['employeeID'] . '_' . $row['days'] . '_' . $row['start_time'] . '_' . $row['end_time'];
                if (in_array($slotKey, $seenSlots)) {
                    return redirect()->back()->withErrors(['Duplicate slot detected! Teacher cannot be assigned to the same time slot multiple times.'])->withInput();
                }
                $seenSlots[] = $slotKey;
            }
        }

        // --- 2. CHECK FOR TEACHER TIME CLASHES WITH OTHER CLASSES/SUBJECTS ---
        foreach ($assignments as $row) {
            if (!empty($row['employeeID']) && !empty($row['days']) && !empty($row['start_time']) && !empty($row['end_time'])) {
                $clash = DB::table('class_subject')
                    ->where('employeeID', $row['employeeID'])
                    ->where('days', $row['days'])
                    ->where('subjectID', '!=', $subjectID)
                    ->where(function($query) use ($row) {
                        $query->whereBetween('start_time', [$row['start_time'], $row['end_time']])
                              ->orWhereBetween('end_time', [$row['start_time'], $row['end_time']])
                              ->orWhere(function($q) use ($row) {
                                  $q->where('start_time', '<=', $row['start_time'])
                                    ->where('end_time', '>=', $row['end_time']);
                              });
                    })
                    ->when(isset($row['classsubjectID']), function($query) use ($row) {
                        return $query->where('classsubjectID', '!=', $row['classsubjectID']);
                    })
                    ->exists();
                if ($clash) {
                    return redirect()->back()->withErrors(['This teacher already has another subject/class at this time!'])->withInput();
                }
            }
        }

        // --- 3. CHECK FOR OVERLAPPING TEACHERS IN SAME SUBJECT/CLASS/DAY ---
        for ($i = 0; $i < count($assignments); $i++) {
            for ($j = $i + 1; $j < count($assignments); $j++) {
                if (
                    isset($assignments[$i]['days'], $assignments[$i]['employeeID'], $assignments[$i]['start_time'], $assignments[$i]['end_time']) &&
                    isset($assignments[$j]['days'], $assignments[$j]['employeeID'], $assignments[$j]['start_time'], $assignments[$j]['end_time']) &&
                    !empty($assignments[$i]['employeeID']) && !empty($assignments[$j]['employeeID']) &&
                    $assignments[$i]['days'] == $assignments[$j]['days'] &&
                    $assignments[$i]['employeeID'] != $assignments[$j]['employeeID'] &&
                    (
                        ($assignments[$i]['start_time'] < $assignments[$j]['end_time'] && $assignments[$i]['end_time'] > $assignments[$j]['start_time'])
                    )
                ) {
                    return redirect()->back()->withErrors(['Multiple teachers cannot be assigned to the same subject at overlapping times on the same day!'])->withInput();
                }
            }
        }
        // Only delete and insert after validation passes
        $oldAssignments = DB::table('class_subject')->where('subjectID', $subjectID)->get();
        $assignmentMap = [];
        foreach ($assignments as $row) {
            $assignmentMap[] = [
                'employeeID' => $row['employeeID'],
                'days' => $row['days'],
                'start_time' => $row['start_time'],
                'end_time' => $row['end_time'],
                'old_id' => $row['classsubjectID'] ?? null,
            ];
        }
        DB::table('class_subject')->where('subjectID', $subjectID)->delete();
        $newAssignmentIDs = [];
        foreach ($assignments as $idx => $row) {
            if (!empty($row['employeeID']) && !empty($row['days']) && !empty($row['start_time']) && !empty($row['end_time']) && $classID) {
                $newID = DB::table('class_subject')->insertGetId([
                    'subjectID' => $subjectID,
                    'employeeID' => $row['employeeID'],
                    'classID' => $classID,
                    'days' => $row['days'],
                    'start_time' => $row['start_time'],
                    'end_time' => $row['end_time'],
                    'subject_price' => $subject_price,
                ]);
                $newAssignmentIDs[] = [
                    'new_id' => $newID,
                    'employeeID' => $row['employeeID'],
                    'days' => $row['days'],
                    'start_time' => $row['start_time'],
                    'end_time' => $row['end_time'],
                    'old_id' => $row['classsubjectID'] ?? null,
                ];
            }
        }
        // Robust update: For every old classsubjectID, update all student_subject rows to best-matching new assignment
        foreach ($oldAssignments as $old) {
            // Cari assignment baru yang padan penuh (teacher, day, start_time, end_time)
            $newAssignment = collect($newAssignmentIDs)->first(function($new) use ($old) {
                return $new['employeeID'] == $old->employeeID
                    && $new['days'] == $old->days
                    && $new['start_time'] == $old->start_time
                    && $new['end_time'] == $old->end_time;
            });
            // Jika tak jumpa padanan penuh, cari padanan tanpa masa (teacher, day sahaja)
            if (!$newAssignment) {
                $newAssignment = collect($newAssignmentIDs)->first(function($new) use ($old) {
                    return $new['employeeID'] == $old->employeeID
                        && $new['days'] == $old->days;
                });
            }
            // Jika masih tak jumpa, fallback ke mana-mana assignment baru
            if (!$newAssignment && count($newAssignmentIDs) > 0) {
                $newAssignment = $newAssignmentIDs[0];
            }
            if ($newAssignment) {
                DB::table('student_subject')
                    ->where('classsubjectID', $old->classsubjectID)
                    ->update(['classsubjectID' => $newAssignment['new_id']]);
            }
        }
        // Force assign all student_subject with NULL classsubjectID for this subject to the first new assignment
        if (count($newAssignmentIDs) > 0) {
            $firstNewID = $newAssignmentIDs[0]['new_id'];
            DB::table('student_subject')
                ->whereNull('classsubjectID')
                ->where('subjectID', $subjectID)
                ->update(['classsubjectID' => $firstNewID]);
        }
        return redirect()->route('admin.manage_subjects')->with('success', 'Subject updated successfully!');
    }

    public function createSubject()
    {
        $teachers = \App\Models\Employee::all();
        $classes = \App\Models\Classes::all();
        $teacherClasses = DB::table('teacher_class')->get();
        return view('admin.add_new_subjects', compact('teachers', 'classes', 'teacherClasses'));
    }

    public function storeSubject(Request $request)
    {
        // Validate input (basic)
        $validator = Validator::make($request->all(), [
            'subject_name' => 'required|string|max:150',
            'subject_price' => 'required|numeric',
            'classID' => 'required|integer',
            'assignments' => 'required|array|min:1',
            'assignments.*.employeeID' => 'required|integer',
            'assignments.*.days' => 'required|string',
            'assignments.*.start_time' => 'required|string',
            'assignments.*.end_time' => 'required|string',
        ]);

        if ($validator->fails()) {
            $classID = $request->input('classID');
            $teacherCount = DB::table('teacher_class')->where('classID', $classID)->count();
            if ($validator->errors()->has('assignments.0.employeeID') && $teacherCount == 0) {
                return redirect()->back()->withErrors(['custom' => 'No teachers are available for the selected class. Please assign a teacher to this class first.'])->withInput();
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Check for subject duplication within the same class
        $subjectName = $request->input('subject_name');
        $classID = $request->input('classID');

        $existingSubject = DB::table('class_subject')
            ->join('subjects', 'class_subject.subjectID', '=', 'subjects.subjectID')
            ->where('class_subject.classID', $classID)
            ->where('subjects.subject_name', $subjectName)
            ->first();

        if ($existingSubject) {
            return redirect()->back()->withErrors(['subject_name' => 'A subject with this name already exists in the selected class. Please choose a different name or select a different class.'])->withInput();
        }

        $assignments = $request->input('assignments');
        $classID = $request->input('classID');

        // --- 1. CHECK FOR DUPLICATE SLOTS (SAME TEACHER, SAME DAY, SAME TIME) ---
        $seenSlots = [];
        foreach ($assignments as $index => $row) {
            $slotKey = $row['employeeID'] . '_' . $row['days'] . '_' . $row['start_time'] . '_' . $row['end_time'];
            if (in_array($slotKey, $seenSlots)) {
                return redirect()->back()->withErrors(['Duplicate slot detected! Teacher cannot be assigned to the same time slot multiple times.'])->withInput();
            }
            $seenSlots[] = $slotKey;
        }

        // --- 2. CHECK FOR TEACHER TIME CLASHES WITH OTHER CLASSES/SUBJECTS ---
        foreach ($assignments as $row) {
            $clash = DB::table('class_subject')
                ->where('employeeID', $row['employeeID'])
                ->where('days', $row['days'])
                ->where(function($query) use ($row) {
                    $query->whereBetween('start_time', [$row['start_time'], $row['end_time']])
                          ->orWhereBetween('end_time', [$row['start_time'], $row['end_time']])
                          ->orWhere(function($q) use ($row) {
                              $q->where('start_time', '<=', $row['start_time'])
                                ->where('end_time', '>=', $row['end_time']);
                          });
                })
                ->exists();
            if ($clash) {
                return redirect()->back()->withErrors(['This teacher already has another subject/class at this time!'])->withInput();
            }
        }

        // --- 3. CHECK FOR OVERLAPPING TEACHERS IN SAME SUBJECT/CLASS/DAY ---
        for ($i = 0; $i < count($assignments); $i++) {
            for ($j = $i + 1; $j < count($assignments); $j++) {
                if (
                    isset($assignments[$i]['days'], $assignments[$i]['employeeID'], $assignments[$i]['start_time'], $assignments[$i]['end_time']) &&
                    isset($assignments[$j]['days'], $assignments[$j]['employeeID'], $assignments[$j]['start_time'], $assignments[$j]['end_time']) &&
                    $assignments[$i]['days'] == $assignments[$j]['days'] &&
                    $assignments[$i]['employeeID'] != $assignments[$j]['employeeID'] &&
                    (
                        ($assignments[$i]['start_time'] < $assignments[$j]['end_time'] && $assignments[$i]['end_time'] > $assignments[$j]['start_time'])
                    )
                ) {
                    return redirect()->back()->withErrors(['Multiple teachers cannot be assigned to the same subject at overlapping times on the same day!'])->withInput();
                }
            }
        }
        // --- CREATE SUBJECT & ASSIGNMENTS ---
        $subject = new \App\Models\Subjects();
        $subject->subject_name = $request->input('subject_name');
        $subject->save();
        $subject_price = $request->input('subject_price');
        foreach ($assignments as $row) {
            DB::table('class_subject')->insert([
                'classID' => $classID,
                'subjectID' => $subject->subjectID,
                'employeeID' => $row['employeeID'],
                'days' => $row['days'],
                'start_time' => $row['start_time'],
                'end_time' => $row['end_time'],
                'subject_price' => $subject_price,
            ]);
        }
        return redirect()->back()->with('success', 'Subject created successfully!');
    }

    public function deleteSubject($subjectID)
    {
        try {
            // Delete all assignments for this subject
            DB::table('class_subject')->where('subjectID', $subjectID)->delete();
            // Delete the subject itself
            Subjects::where('subjectID', $subjectID)->delete();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false]);
        }
    }

    public function teacherAvailableTimes(Request $request)
    {
        $teacherID = $request->query('teacherID');
        $day = $request->query('day');
        $excludeID = $request->query('excludeID');

        $query = DB::table('class_subject')
            ->where('employeeID', $teacherID)
            ->where('days', $day);
        if ($excludeID) {
            $query->where('classsubjectID', '!=', $excludeID);
        }
        // This will now check for all subjects and classes, not just the current one
        $booked = $query->select('start_time', 'end_time')->get();
        return response()->json(['booked' => $booked]);
    }
}
