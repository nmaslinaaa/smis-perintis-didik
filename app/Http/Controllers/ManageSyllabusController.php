<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Classes;
use App\Models\Subjects;
use Illuminate\Support\Facades\DB;

class ManageSyllabusController extends Controller
{
    public function index(Request $request)
    {
        $user = session('user');
        $employeeID = $user->employeeID ?? null;
        $classes = Classes::all();
        $selectedClassID = $request->input('classID');
        $selectedSubjectID = $request->input('subjectID');
        $syllabus = collect();
        if ($employeeID && $selectedSubjectID) {
            $syllabus = DB::table('syllabus')
                ->where('teacherID', $employeeID)
                ->where('subjectID', $selectedSubjectID)
                ->orderBy('syllabusID')
                ->get();
        }

        // Only show subjects that the teacher is teaching (from class_subject)
        $subjects = collect();
        if ($employeeID) {
            $subjectIDs = DB::table('class_subject')
                ->where('employeeID', $employeeID)
                ->when($selectedClassID, function($query) use ($selectedClassID) {
                    $query->where('classID', $selectedClassID);
                })
                ->pluck('subjectID')
                ->unique();
            $subjects = Subjects::whereIn('subjectID', $subjectIDs)->get()->unique('subject_name')->values();
        }

        // Do not preselect any class or subject
        // Only show the form if both are selected
        return view('teacher.manage_syllabus', compact('classes', 'subjects', 'selectedClassID', 'selectedSubjectID', 'syllabus'));
    }

    public function update(Request $request)
    {
        $user = session('user');
        $teacherID = $user->employeeID ?? null;
        $subjectID = $request->input('subjectID');
        $newSyllabus = $request->input('new_syllabus', []);
        if ($teacherID && $subjectID && is_array($newSyllabus)) {
            foreach ($newSyllabus as $syllabusName) {
                $syllabusName = trim($syllabusName);
                if ($syllabusName !== '') {
                    DB::table('syllabus')->insert([
                        'teacherID' => $teacherID,
                        'subjectID' => $subjectID,
                        'syllabus_name' => $syllabusName,
                        'status' => 'Not Completed',
                        'date_completed' => null,
                    ]);
                }
            }
        }
        // After insert, fetch the updated syllabus list
        $classes = Classes::all();
        $subjects = collect();
        if ($teacherID) {
            $subjectIDs = DB::table('class_subject')
                ->where('employeeID', $teacherID)
                ->pluck('subjectID')
                ->unique();
            $subjects = Subjects::whereIn('subjectID', $subjectIDs)->get()->unique('subject_name')->values();
        }
        $syllabus = DB::table('syllabus')
            ->where('teacherID', $teacherID)
            ->where('subjectID', $subjectID)
            ->orderBy('syllabusID')
            ->get();
        $selectedClassID = $request->input('classID');
        $selectedSubjectID = $subjectID;
        return view('teacher.manage_syllabus', compact('classes', 'subjects', 'selectedClassID', 'selectedSubjectID', 'syllabus'))
            ->with('success', 'Syllabus items added successfully!');
    }

    public function remove(Request $request)
    {
        $syllabusID = $request->input('syllabusID');
        if ($syllabusID) {
            $deleted = DB::table('syllabus')->where('syllabusID', $syllabusID)->delete();
            if ($deleted) {
                return response()->json(['success' => true]);
            }
        }
        return response()->json(['success' => false], 400);
    }

    public function updateSyllabusStatus(Request $request)
    {
        $syllabusID = $request->input('syllabusID');
        $status = $request->input('status');
        if (!$syllabusID || !in_array($status, ['Completed', 'Not Completed'])) {
            return response()->json(['success' => false], 400);
        }
        $date_completed = null;
        if ($status === 'Completed') {
            $date_completed = date('Y-m-d');
        }
        $updated = DB::table('syllabus')
            ->where('syllabusID', $syllabusID)
            ->update([
                'status' => $status,
                'date_completed' => $date_completed
            ]);
        if ($updated) {
            return response()->json([
                'success' => true,
                'date_completed' => $date_completed ? date('d/m/Y', strtotime($date_completed)) : '-'
            ]);
        }
        return response()->json(['success' => false], 400);
    }

    public function syllabusCoverage(Request $request)
    {
        $user = session('user');
        $employeeID = $user->employeeID ?? null;
        $classes = Classes::all();
        $selectedClassID = $request->input('classID');
        $selectedSubjectID = $request->input('subjectID');
        $syllabus = collect();
        // Only show subjects that the teacher is teaching (from class_subject)
        $subjects = collect();
        if ($employeeID) {
            $subjectIDs = DB::table('class_subject')
                ->where('employeeID', $employeeID)
                ->when($selectedClassID, function($query) use ($selectedClassID) {
                    $query->where('classID', $selectedClassID);
                })
                ->pluck('subjectID')
                ->unique();
            $subjects = Subjects::whereIn('subjectID', $subjectIDs)->get()->unique('subject_name')->values();
        }
        if ($employeeID && $selectedSubjectID) {
            $syllabus = DB::table('syllabus')
                ->where('teacherID', $employeeID)
                ->where('subjectID', $selectedSubjectID)
                ->orderBy('syllabusID')
                ->get();
        }
        return view('teacher.syllabus_coverage', compact('classes', 'subjects', 'selectedClassID', 'selectedSubjectID', 'syllabus'));
    }

    // Add this method to provide class and subject options for the dashboard
    public function syllabusCoverageOptions(Request $request)
    {
        $user = session('user');
        $employeeID = $user->employeeID ?? null;
        $classes = DB::table('classes')->get();
        $subjects = collect();
        if ($employeeID) {
            $subjectRows = DB::table('class_subject')
                ->where('employeeID', $employeeID)
                ->get();
            $subjectIDs = $subjectRows->pluck('subjectID')->unique();
            $subjects = DB::table('subjects')
                ->whereIn('subjectID', $subjectIDs)
                ->get();
            // Attach classID to each subject for filtering
            $subjects = $subjects->map(function($subject) use ($subjectRows) {
                $row = $subjectRows->firstWhere('subjectID', $subject->subjectID);
                $subject->classID = $row ? $row->classID : null;
                return $subject;
            });
        }
        return response()->json([
            'classes' => $classes,
            'subjects' => $subjects
        ]);
    }

    // Add this method to provide syllabus completion percent for the dashboard
    public function syllabusCoveragePercent(Request $request)
    {
        $user = session('user');
        $employeeID = $user->employeeID ?? null;
        $classID = $request->input('classID');
        $subjectID = $request->input('subjectID');
        if (!$employeeID || !$classID || !$subjectID) {
            return response()->json(['percent' => 0]);
        }
        $total = DB::table('syllabus')
            ->where('teacherID', $employeeID)
            ->where('subjectID', $subjectID)
            ->count();
        $completed = DB::table('syllabus')
            ->where('teacherID', $employeeID)
            ->where('subjectID', $subjectID)
            ->where('status', 'Completed')
            ->count();
        $percent = $total > 0 ? round($completed / $total * 100) : 0;
        return response()->json(['percent' => $percent]);
    }
}
