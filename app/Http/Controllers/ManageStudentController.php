<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\StudentSubject;
use Illuminate\Support\Facades\DB;
use App\Services\TwilioService;
use Illuminate\Support\Facades\Log;

class ManageStudentController extends Controller
{
    protected $twilioService;

    public function __construct(TwilioService $twilioService)
    {
        $this->twilioService = $twilioService;
    }

    public function showRegisterForm()
    {
        $classes = \App\Models\Classes::all();
        $subjects = \App\Models\Subjects::all();
        return view('parent.register_children', compact('classes', 'subjects'));
    }

    public function registerChild(Request $request)
    {
        $request->validate([
            'child_name' => 'required|string|max:150',
            'address' => 'required|string|max:255',
            'class' => 'required|integer',
            'start_date' => 'required',
            'subject_taken' => 'required|array|min:1',
        ]);

        $parent = session('user');
        $parentID = null;
        if (is_object($parent) && isset($parent->parentID)) {
            $parentID = $parent->parentID;
        } elseif (is_array($parent) && isset($parent['parentID'])) {
            $parentID = $parent['parentID'];
        } elseif (session()->has('user_id')) {
            $parentID = session('user_id');
        }
        if (!$parentID) {
            return redirect('/login');
        }

        $student = new Student();
        $student->student_name = $request->input('child_name');
        $student->school_name = $request->input('school_name');
        $student->address = $request->input('address');
        $student->classID = $request->input('class');
        $student->tuition_startdate = $request->input('start_date');
        $student->student_status = 1;
        $student->parentID = $parentID;
        $student->save();

        $subjectIDs = $request->input('subject_taken', []);
        $timingIDs = $request->input('subject_timing', []);
        foreach ($subjectIDs as $i => $subjectID) {
            $classsubjectID = $timingIDs[$i] ?? null;
            if ($subjectID && $classsubjectID) {
                StudentSubject::create([
                    'studentID' => $student->studentID,
                    'subjectID' => $subjectID,
                    'classsubjectID' => $classsubjectID,
                ]);
            }
        }

        return redirect()->back()->with('success', 'Child registered successfully!');
    }

    public function myChildList()
    {
        $parent = session('user');
        $parentID = null;
        if (is_object($parent) && isset($parent->parentID)) {
            $parentID = $parent->parentID;
        } elseif (is_array($parent) && isset($parent['parentID'])) {
            $parentID = $parent['parentID'];
        } elseif (session()->has('user_id')) {
            $parentID = session('user_id');
        }
        if (!$parentID) {
            return redirect('/login');
        }
        $students = \App\Models\Student::where('parentID', $parentID)
            ->with(['class', 'subjects'])
            ->get();
        return view('parent.my_child', compact('students'));
    }

    public function updateChildrenFromParent(Request $request)
    {
        $children = $request->input('children', []);
        foreach ($children as $childData) {
            if (isset($childData['studentID'])) {
                $student = \App\Models\Student::find($childData['studentID']);
                if ($student) {
                    $student->student_name = $childData['student_name'] ?? $student->student_name;
                    $student->school_name = $childData['school_name'] ?? $student->school_name;
                    $student->address = $childData['address'] ?? $student->address;
                    $student->tuition_startdate = $childData['tuition_startdate'] ?? $student->tuition_startdate;
                    $student->student_status = $childData['student_status'] ?? $student->student_status;
                    $student->save();
                }
            }
        }
        return redirect('/manage_profile')->with('success', 'Children details updated successfully!');
    }

    public function getSubjectsByClass($classID)
    {
        $class = \App\Models\Classes::find($classID);
        if (!$class) {
            return response()->json([]);
        }
        // Get all subject assignments for this class, including teacher and timing
        $assignments = DB::table('class_subject')
            ->join('subjects', 'class_subject.subjectID', '=', 'subjects.subjectID')
            ->join('employee', 'class_subject.employeeID', '=', 'employee.employeeID')
            ->where('class_subject.classID', $classID)
            ->select(
                'subjects.subjectID',
                'subjects.subject_name',
                'class_subject.classsubjectID',
                'class_subject.days',
                'class_subject.start_time',
                'class_subject.end_time',
                'employee.employeeID',
                'employee.firstname',
                'employee.lastname'
            )
            ->get();
        // Group by subjectID
        $subjects = [];
        foreach ($assignments as $row) {
            if (!isset($subjects[$row->subjectID])) {
                $subjects[$row->subjectID] = [
                    'subjectID' => $row->subjectID,
                    'subject_name' => $row->subject_name,
                    'options' => []
                ];
            }
            $subjects[$row->subjectID]['options'][] = [
                'classsubjectID' => $row->classsubjectID,
                'days' => $row->days,
                'start_time' => $row->start_time,
                'end_time' => $row->end_time,
                'employeeID' => $row->employeeID,
                'teacher_name' => trim($row->firstname . ' ' . $row->lastname)
            ];
        }
        // Re-index as array
        $subjects = array_values($subjects);
        return response()->json($subjects);
    }

    public function listNewStudents()
    {
        $newStudents = \App\Models\Student::where('verification_status', 'pending')
            ->with(['parent', 'class'])
            ->get();
        return view('admin.all_new_student', compact('newStudents'));
    }

    public function verifyNewStudent($id)
    {
        $student = \App\Models\Student::with(['parent', 'class', 'subjects'])->findOrFail($id);
        return view('admin.verify_new_student', compact('student'));
    }

    public function approveNewStudent($id)
    {
        $student = \App\Models\Student::with(['parent', 'class'])->findOrFail($id);
        $student->verification_status = 'approved';
        $student->rejection_reason = null;
        $student->save();

        // Send WhatsApp notification to parent about student approval
        try {
            if ($student->parent && $student->parent->phonenumber) {
                $phoneNumber = $this->formatPhoneNumber($student->parent->phonenumber);

                // Get enrolled subjects for the student
                $enrolledSubjects = DB::table('student_subject')
                    ->join('subjects', 'student_subject.subjectID', '=', 'subjects.subjectID')
                    ->where('student_subject.studentID', $student->studentID)
                    ->pluck('subjects.subject_name')
                    ->toArray();

                $result = $this->twilioService->sendStudentApprovalNotification(
                    $phoneNumber,
                    $student->student_name,
                    $student->class->class_name,
                    $student->tuition_startdate,
                    $enrolledSubjects
                );

                if ($result) {
                    Log::info('Student approval WhatsApp notification sent successfully', [
                        'student_id' => $student->studentID,
                        'student_name' => $student->student_name,
                        'parent_phone' => $phoneNumber
                    ]);
                } else {
                    Log::error('Failed to send student approval WhatsApp notification', [
                        'student_id' => $student->studentID,
                        'parent_phone' => $phoneNumber
                    ]);
                }
            } else {
                Log::warning('No parent or phone number found for student approval notification', [
                    'student_id' => $student->studentID
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error sending student approval WhatsApp notification', [
                'student_id' => $student->studentID,
                'error' => $e->getMessage()
            ]);
        }

        return redirect()->route('admin.all_new_student')->with('success', 'Student approved successfully and notification sent to parent.');
    }

    public function rejectNewStudent(Request $request, $id)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:255',
        ]);
        $student = \App\Models\Student::with(['parent', 'class'])->findOrFail($id);
        $student->verification_status = 'rejected';
        $student->rejection_reason = $request->input('rejection_reason');
        $student->save();

        // Send WhatsApp notification to parent about student rejection
        try {
            if ($student->parent && $student->parent->phonenumber) {
                $phoneNumber = $this->formatPhoneNumber($student->parent->phonenumber);
                $rejectionReason = $request->input('rejection_reason');

                $message = "❌ *Student Registration Update*\n\n";
                $message .= "Student: *{$student->student_name}*\n";
                $message .= "Class: *{$student->class->class_name}*\n";
                $message .= "Status: *Registration Rejected*\n\n";
                $message .= "Reason: *{$rejectionReason}*\n\n";
                $message .= "Please contact us for more information or to reapply.\n\n";
                $message .= "Thank you for your understanding.\n";
                $message .= "Pusat Tuisyen Perintis Didik";

                $result = $this->twilioService->sendWhatsAppMessage($phoneNumber, $message);

                if ($result) {
                    Log::info('Student rejection WhatsApp notification sent successfully', [
                        'student_id' => $student->studentID,
                        'student_name' => $student->student_name,
                        'parent_phone' => $phoneNumber,
                        'rejection_reason' => $rejectionReason
                    ]);
                } else {
                    Log::error('Failed to send student rejection WhatsApp notification', [
                        'student_id' => $student->studentID,
                        'parent_phone' => $phoneNumber
                    ]);
                }
            } else {
                Log::warning('No parent or phone number found for student rejection notification', [
                    'student_id' => $student->studentID
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error sending student rejection WhatsApp notification', [
                'student_id' => $student->studentID,
                'error' => $e->getMessage()
            ]);
        }

        return redirect()->route('admin.all_new_student')->with('success', 'Student rejected with reason and notification sent to parent.');
    }

    public function allStudents(Request $request)
    {
        $user = session('user');
        if ($user && isset($user->group_level) && $user->group_level == 2) { // Teacher
            // Get class IDs taught by this teacher
            $teacherClassIds = \App\Models\TeacherClass::where('employeeID', $user->employeeID)->pluck('classID')->toArray();
            $classes = \App\Models\Classes::whereIn('classID', $teacherClassIds)->get();
            $query = \App\Models\Student::where('verification_status', 'approved')
                ->whereIn('classID', $teacherClassIds)
                ->with('class');
        } else { // Admin or others
            $classes = \App\Models\Classes::all();
            $query = \App\Models\Student::where('verification_status', 'approved')->with('class');
        }
        if ($request->filled('classID')) {
            $query->where('classID', $request->input('classID'));
        }
        $students = $query->get();
        return view('admin.all_student', compact('students', 'classes'));
    }

    public function viewStudentInformation($id)
    {
        $student = \App\Models\Student::with(['class', 'parent', 'subjects'])->findOrFail($id);
        return view('admin.view_student_information', compact('student'));
    }

    public function editStudentInformation($id)
    {
        $student = \App\Models\Student::with(['class', 'parent', 'subjects'])->findOrFail($id);
        $classes = \App\Models\Classes::all();
        // Get all subjects for the student's class
        $subjects = collect();
        if ($student->classID) {
            $class = \App\Models\Classes::find($student->classID);
            $subjects = $class ? $class->subjects : collect();
        }
        return view('admin.edit_student_information', compact('student', 'classes', 'subjects'));
    }

    public function updateStudentInformation(Request $request, $id)
    {
        $student = \App\Models\Student::findOrFail($id);
        $request->validate([
            'student_name' => 'required|string|max:150',
            'school_name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'classID' => 'required|integer',
            'student_status' => 'required|in:0,1',
            'subject_taken' => 'required|array|min:1',
            'subject_timing' => 'required|array|min:1',
        ]);
        $student->student_name = $request->input('student_name');
        $student->school_name = $request->input('school_name');
        $student->address = $request->input('address');
        $student->classID = $request->input('classID');
        $student->student_status = $request->input('student_status');
        $student->save();
        // Update subjects and timings
        $subjectIDs = $request->input('subject_taken', []);
        $timingIDs = $request->input('subject_timing', []);
        \App\Models\StudentSubject::where('studentID', $student->studentID)->delete();
        foreach ($subjectIDs as $i => $subjectID) {
            $classsubjectID = $timingIDs[$i] ?? null;
            if ($subjectID && $classsubjectID) {
                \App\Models\StudentSubject::create([
                    'studentID' => $student->studentID,
                    'subjectID' => $subjectID,
                    'classsubjectID' => $classsubjectID,
                ]);
            }
        }
        return redirect()->route('admin.all_student')->with('success', 'Student information updated successfully.');
    }

    /**
     * Format phone number for WhatsApp
     */
    private function formatPhoneNumber($phoneNumber)
    {
        // Remove any non-digit characters
        $phoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber);

        // If number starts with 0, replace with +60 (Malaysia)
        if (substr($phoneNumber, 0, 1) === '0') {
            $phoneNumber = '+60' . substr($phoneNumber, 1);
        }
        // If number doesn't start with +, add +60
        elseif (substr($phoneNumber, 0, 1) !== '+') {
            $phoneNumber = '+60' . $phoneNumber;
        }

        return $phoneNumber;
    }

    /**
     * Test student approval notification
     */
    public function testStudentApprovalNotification($studentId)
    {
        try {
            $student = \App\Models\Student::with(['parent', 'class'])->findOrFail($studentId);

            if (!$student->parent || !$student->parent->phonenumber) {
                return response()->json([
                    'success' => false,
                    'message' => 'No parent or phone number found for this student'
                ], 400);
            }

            $phoneNumber = $this->formatPhoneNumber($student->parent->phonenumber);

            // Get enrolled subjects for the student
            $enrolledSubjects = DB::table('student_subject')
                ->join('subjects', 'student_subject.subjectID', '=', 'subjects.subjectID')
                ->where('student_subject.studentID', $student->studentID)
                ->pluck('subjects.subject_name')
                ->toArray();

            $result = $this->twilioService->sendStudentApprovalNotification(
                $phoneNumber,
                $student->student_name,
                $student->class->class_name,
                $student->tuition_startdate,
                $enrolledSubjects
            );

            if ($result) {
                return response()->json([
                    'success' => true,
                    'message' => 'Student approval notification sent successfully',
                    'student' => $student->student_name,
                    'parent' => $student->parent->name,
                    'phone' => $phoneNumber,
                    'subjects' => $enrolledSubjects
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to send student approval notification'
                ], 500);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function deleteStudent($id)
    {
        $student = \App\Models\Student::findOrFail($id);
        $student->delete();
        return redirect()->route('admin.all_student')->with('success', 'Student deleted successfully.');
    }

    public function childViewPerformance(Request $request)
    {
        $parent = session('user');
        $parentID = null;
        if (is_object($parent) && isset($parent->parentID)) {
            $parentID = $parent->parentID;
        } elseif (is_array($parent) && isset($parent['parentID'])) {
            $parentID = $parent['parentID'];
        } elseif (session()->has('user_id')) {
            $parentID = session('user_id');
        }
        if (!$parentID) {
            return redirect('/login');
        }
        $children = \App\Models\Student::where('parentID', $parentID)
            ->with(['class', 'subjects'])
            ->get();
        $selectedChildID = $request->query('childID', $children->first()->studentID ?? null);

        $months = [
            'January','February','March','April','May','June',
            'July','August','September','October','November','December'
        ];
        $selectedMonth = $request->query('month', null);

        $performances = collect();
        if ($selectedChildID) {
            $student = \App\Models\Student::with('subjects')->find($selectedChildID);
            $subjects = $student ? $student->subjects : collect();
            $performanceRows = \App\Models\Performance::where('studentID', $selectedChildID)
                ->when($selectedMonth, function($query) use ($selectedMonth) {
                    return $query->where('performance_month', $selectedMonth);
                })
                ->get();
            $performances = $subjects->map(function($subject) use ($performanceRows) {
                $perf = $performanceRows->where('subjectID', $subject->subjectID)->sortByDesc('performance_month')->first();
                $teacherName = null;
                if ($perf && $perf->teacherID) {
                    $teacher = \App\Models\Employee::find($perf->teacherID);
                    $teacherName = $teacher ? ($teacher->firstname . ' ' . $teacher->lastname) : null;
                }
                return [
                    'subject_name' => $subject->subject_name,
                    'test_score' => $perf->test_score ?? null,
                    'performance_status' => $perf->performance_status ?? null,
                    'performance_month' => $perf->performance_month ?? null,
                    'teacher_comment' => $perf->teacher_comment ?? null,
                    'teacher_name' => $teacherName,
                ];
            });
        }

        return view('parent.view_child_performance', compact('children', 'selectedChildID', 'performances', 'months', 'selectedMonth'));
    }

    public function parentDashboard()
    {
        $parent = session('user');
        $parentID = null;
        if (is_object($parent) && isset($parent->parentID)) {
            $parentID = $parent->parentID;
        } elseif (is_array($parent) && isset($parent['parentID'])) {
            $parentID = $parent['parentID'];
        } elseif (session()->has('user_id')) {
            $parentID = session('user_id');
        }
        if (!$parentID) {
            return redirect('/login');
        }
        $children = \App\Models\Student::where('parentID', $parentID)->get();
        return view('parent.parent_dashboard', compact('children'));
    }

    public function getChildPerformance(Request $request)
    {
        $studentID = $request->input('studentID');
        $month = $request->input('month');
        if (!$studentID) {
            return response()->json([]);
        }
        $query = \App\Models\Performance::where('studentID', $studentID)
            ->join('subjects', 'performance.subjectID', '=', 'subjects.subjectID')
            ->select('subjects.subject_name', 'performance.test_score', 'performance.performance_status');
        if ($month) {
            $query->where('performance_month', $month);
        }
        $performances = $query->get();
        return response()->json($performances);
    }
}

