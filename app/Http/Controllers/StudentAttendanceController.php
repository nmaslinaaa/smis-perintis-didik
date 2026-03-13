<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\TwilioService;
use Illuminate\Support\Facades\Log;

class StudentAttendanceController extends Controller
{
    protected $twilioService;

    public function __construct(TwilioService $twilioService)
    {
        $this->twilioService = $twilioService;
    }

    public function recordAttendance(Request $request)
    {
        $user = session('user');
        if (!$user || !isset($user->employeeID)) {
            return redirect('/login');
        }
        $employeeID = $user->employeeID;

        // Get all classes assigned to this teacher
        $classRows = DB::table('teacher_class')
            ->join('classes', 'teacher_class.classID', '=', 'classes.classID')
            ->where('teacher_class.employeeID', $employeeID)
            ->select('classes.classID', 'classes.class_name')
            ->distinct()
            ->get();
        $classes = $classRows->map(function($row) {
            return [
                'classID' => $row->classID,
                'class_name' => trim($row->class_name)
            ];
        });

        // Get selected class
        $selectedClassID = $request->query('classID', $classes->first()['classID'] ?? null);

        // Get all subjects assigned to this teacher for the selected class
        $subjectRows = collect();
        if ($selectedClassID) {
            $subjectRows = DB::table('class_subject')
                ->join('subjects', 'class_subject.subjectID', '=', 'subjects.subjectID')
                ->where('class_subject.classID', $selectedClassID)
                ->where('class_subject.employeeID', $employeeID)
                ->select('subjects.subjectID', 'subjects.subject_name')
                ->distinct()
                ->get();
        }
        $subjects = $subjectRows->map(function($row) {
            return [
                'subjectID' => $row->subjectID,
                'subject_name' => trim($row->subject_name)
            ];
        });

        // Get selected subject
        $selectedSubjectID = $request->query('subjectID', $subjects->first()['subjectID'] ?? null);

        // Get slot for this teacher, class, and subject
        $slot = null;
        if ($selectedClassID && $selectedSubjectID) {
            $slot = DB::table('class_subject')
                ->where('classID', $selectedClassID)
                ->where('subjectID', $selectedSubjectID)
                ->where('employeeID', $employeeID)
                ->first();
        }

        // Fetch students for this slot (classsubjectID)
        $students = collect();
        if ($slot) {
            $studentIDs = DB::table('student_subject')
                ->where('classsubjectID', $slot->classsubjectID)
                ->pluck('studentID');
           $students = DB::table('student')
            ->leftJoin('parent', 'student.parentID', '=', 'parent.parentID')
            ->whereIn('student.studentID', $studentIDs)
            ->where('student.student_status', 1)
            ->where('student.verification_status', 'approved')
            ->select('student.*', DB::raw("SUBSTRING_INDEX(parent.name, ' ', 1) as parent_name"))
            ->get();
        }

        // Fetch existing attendance for today, this slot, and teacher
        $attendance_date = date('Y-m-d');
        $attendanceData = [];
        if ($slot) {
            $attendanceRows = DB::table('attendance')
                ->where('classID', $selectedClassID)
                ->where('subjectID', $selectedSubjectID)
                ->where('teacherID', $employeeID)
                ->where('attendance_date', $attendance_date)
                ->get();
            foreach ($attendanceRows as $row) {
                $attendanceData[$row->studentID] = $row->status;
            }
        }

        // Pass to view
        return view('teacher.record_attendance', compact('classes', 'subjects', 'selectedClassID', 'selectedSubjectID', 'slot', 'students', 'attendanceData'));
    }

    public function saveAttendance(Request $request)
    {
        $user = session('user');
        if (!$user || !isset($user->employeeID)) {
            return redirect('/login');
        }
        $employeeID = $user->employeeID;
        $classID = $request->input('classID');
        $subjectID = $request->input('subjectID');
        $slot = $request->input('slot'); // classsubjectID
        $attendance = $request->input('attendance', []);
        $attendance_date = date('Y-m-d');

        foreach ($attendance as $studentID => $status) {
            // Map status to database value
            if ($status === 'P') $dbStatus = 'Present';
            elseif ($status === 'A') $dbStatus = 'Absent';
            elseif ($status === 'L') $dbStatus = 'Leave with permission';
            else $dbStatus = $status;
            // Insert or update attendance
            DB::table('attendance')->updateOrInsert(
                [
                    'studentID' => $studentID,
                    'teacherID' => $employeeID,
                    'subjectID' => $subjectID,
                    'classID' => $classID,
                    'attendance_date' => $attendance_date,
                ],
                [
                    'status' => $dbStatus,
                ]
            );
        }

        return redirect()->to('/teacher/record_attendance?classID=' . $classID . '&subjectID=' . $subjectID)
            ->with('success', 'Attendance saved successfully!');
    }

    /**
     * Send WhatsApp notification for individual student attendance
     */
    public function sendAttendanceNotification(Request $request)
    {
        try {
            $studentID = $request->input('studentID');
            $classID = $request->input('classID');
            $subjectID = $request->input('subjectID');
            $attendance_date = date('Y-m-d');

            // Get student and parent information
            $student = DB::table('student')
                ->join('parent', 'student.parentID', '=', 'parent.parentID')
                ->where('student.studentID', $studentID)
                ->select('student.student_name', 'parent.name as parent_name', 'parent.phonenumber as parent_phone')
                ->first();

            if (!$student) {
                return response()->json(['success' => false, 'message' => 'Student not found']);
            }

            // Get attendance status for today
            $attendance = DB::table('attendance')
                ->where('studentID', $studentID)
                ->where('classID', $classID)
                ->where('subjectID', $subjectID)
                ->where('attendance_date', $attendance_date)
                ->first();

            if (!$attendance) {
                return response()->json(['success' => false, 'message' => 'No attendance record found for today']);
            }

            // Get subject name
            $subject = DB::table('subjects')
                ->where('subjectID', $subjectID)
                ->first();

            // Format phone number
            $phoneNumber = $this->formatPhoneNumber($student->parent_phone);
            if (!$phoneNumber) {
                return response()->json(['success' => false, 'message' => 'Invalid phone number']);
            }

            // Send WhatsApp notification
            $result = $this->twilioService->sendAttendanceNotification(
                $phoneNumber,
                $student->student_name,
                $subject->subject_name,
                $attendance->status,
                $attendance_date
            );

            if ($result) {
                Log::info('Individual attendance notification sent successfully', [
                    'student_id' => $studentID,
                    'student_name' => $student->student_name,
                    'parent_phone' => $phoneNumber,
                    'status' => $attendance->status
                ]);
                return response()->json(['success' => true, 'message' => 'WhatsApp notification sent successfully']);
            } else {
                Log::error('Failed to send individual attendance notification', [
                    'student_id' => $studentID,
                    'parent_phone' => $phoneNumber
                ]);
                return response()->json(['success' => false, 'message' => 'Failed to send WhatsApp notification']);
            }

        } catch (\Exception $e) {
            Log::error('Error sending individual attendance notification', [
                'student_id' => $studentID ?? null,
                'error' => $e->getMessage()
            ]);
            return response()->json(['success' => false, 'message' => 'Error sending WhatsApp notification']);
        }
    }

    /**
     * Format phone number for WhatsApp
     */
    private function formatPhoneNumber($phoneNumber)
    {
        // Remove any non-digit characters
        $phoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber);

        // If it starts with 0, replace with 60 (Malaysia country code)
        if (substr($phoneNumber, 0, 1) === '0') {
            $phoneNumber = '60' . substr($phoneNumber, 1);
        }

        // If it doesn't start with +, add it
        if (substr($phoneNumber, 0, 1) !== '+') {
            $phoneNumber = '+' . $phoneNumber;
        }

        return $phoneNumber;
    }

    // ADMIN: View attendance for all students (with filters)
    public function viewAttendance(Request $request)
    {
        $selectedDate = $request->query('date', date('Y-m-d'));
        $classes = DB::table('classes')->get();
        $selectedClassID = $request->query('classID', $classes[0]->classID ?? null);
        $subjects = collect();
        $selectedSubjectID = $request->query('subjectID', null);
        $slots = collect();
        $selectedSlotID = $request->query('slotID', null);
        $students = collect();
        $attendanceData = [];
        if ($selectedClassID) {
            $subjects = DB::table('class_subject')
                ->join('subjects', 'class_subject.subjectID', '=', 'subjects.subjectID')
                ->where('class_subject.classID', $selectedClassID)
                ->select('subjects.subjectID', 'subjects.subject_name')
                ->distinct()->get();
        }
        if ($selectedClassID && $selectedSubjectID) {
            $slots = DB::table('class_subject')
                ->join('employee', 'class_subject.employeeID', '=', 'employee.employeeID')
                ->where('class_subject.classID', $selectedClassID)
                ->where('class_subject.subjectID', $selectedSubjectID)
                ->select('class_subject.classsubjectID', 'class_subject.days', 'class_subject.start_time', 'class_subject.end_time', DB::raw("CONCAT(employee.firstname, ' ', employee.lastname) as teacher_name"))
                ->get();
        }
        if ($selectedClassID && $selectedSubjectID && $selectedSlotID) {
            $studentIDs = DB::table('student_subject')
                ->where('classsubjectID', $selectedSlotID)
                ->pluck('studentID');
            $students = DB::table('student')
                ->whereIn('studentID', $studentIDs)
                ->where('classID', $selectedClassID)
                ->where('student_status', 1)
                ->where('verification_status', 'approved')
                ->get();
            // Get attendance for each student for the selected date only
            $attendanceRows = DB::table('attendance')
                ->where('classID', $selectedClassID)
                ->where('subjectID', $selectedSubjectID)
                ->where('attendance_date', $selectedDate)
                ->get();
            foreach ($attendanceRows as $row) {
                $attendanceData[$row->studentID] = $row->status;
            }
        }
        return view('admin.view_attendance', compact('selectedDate', 'classes', 'selectedClassID', 'subjects', 'selectedSubjectID', 'slots', 'selectedSlotID', 'students', 'attendanceData'));
    }

    // PARENT: View child's attendance
    public function parentViewChildAttendance(Request $request)
    {
        $user = session('user');
        if (!$user || !isset($user->parentID)) {
            return redirect('/login');
        }
        $parentID = $user->parentID;

        // Fetch children
        $children = \App\Models\Student::where('parentID', $parentID)->get();

        // Get selected child and month
        $selectedChildID = $request->input('childID', $children->first()->studentID ?? null);
        $months = [
            'January','February','March','April','May','June',
            'July','August','September','October','November','December'
        ];
        $selectedMonth = $request->input('month', date('F'));

        // Fetch attendance for the selected child and month, with student and subject name
        $attendances = collect();
        if ($selectedChildID) {
            $monthIndex = array_search($selectedMonth, $months) + 1;
            $attendances = \App\Models\Attendance::where('attendance.studentID', $selectedChildID)
                ->whereMonth('attendance.attendance_date', $monthIndex)
                ->join('student', 'attendance.studentID', '=', 'student.studentID')
                ->join('subjects', 'attendance.subjectID', '=', 'subjects.subjectID')
                ->select(
                    'attendance.*',
                    'student.student_name',
                    'subjects.subject_name'
                )
                ->orderBy('attendance.attendance_date', 'asc')
                ->orderBy('attendance.attendanceID', 'asc')
                ->get();
        }

        return view('parent.view_child_attendance', compact('children', 'selectedChildID', 'months', 'selectedMonth', 'attendances'));
    }

    // Dashboard: Attendance summary for pie chart
    public function dashboardAttendanceSummary(Request $request)
    {
        $user = session('user');
        $teacherID = $user->employeeID ?? null;
        $classID = $request->input('classID');
        $subjectID = $request->input('subjectID');
        $date = $request->input('date');
        if (!$teacherID || !$classID || !$subjectID) {
            return response()->json(['present' => 0, 'absent' => 0, 'leave' => 0, 'total' => 0, 'used_date' => null]);
        }
        $rows = DB::table('attendance')
            ->where('teacherID', $teacherID)
            ->where('classID', $classID)
            ->where('subjectID', $subjectID)
            ->where('attendance_date', $date)
            ->get();
        $usedDate = $date;
        if ($rows->count() === 0) {
            // Fallback: get latest available date for this class/subject/teacher
            $latest = DB::table('attendance')
                ->where('teacherID', $teacherID)
                ->where('classID', $classID)
                ->where('subjectID', $subjectID)
                ->orderByDesc('attendance_date')
                ->first();
            if ($latest) {
                $usedDate = $latest->attendance_date;
                $rows = DB::table('attendance')
                    ->where('teacherID', $teacherID)
                    ->where('classID', $classID)
                    ->where('subjectID', $subjectID)
                    ->where('attendance_date', $usedDate)
                    ->get();
            }
        }
        $present = $rows->where('status', 'Present')->count();
        $absent = $rows->where('status', 'Absent')->count();
        $leave = $rows->where('status', 'Leave with permission')->count();
        $total = $rows->count();
        return response()->json([
            'present' => $present,
            'absent' => $absent,
            'leave' => $leave,
            'total' => $total,
            'used_date' => $usedDate
        ]);
    }

    public function getChildAttendance(Request $request)
    {
        $studentID = $request->input('studentID');
        $date = $request->input('date'); // Optional date filter
        if (!$studentID) {
            return response()->json([]);
        }

        $query = DB::table('attendance')
            ->join('subjects', 'attendance.subjectID', '=', 'subjects.subjectID')
            ->join('class_subject', function($join) {
                $join->on('attendance.subjectID', '=', 'class_subject.subjectID')
                     ->on('attendance.classID', '=', 'class_subject.classID');
            })
            ->where('attendance.studentID', $studentID)
            ->select(
                'subjects.subject_name',
                'attendance.attendance_date',
                'class_subject.start_time',
                'class_subject.end_time',
                'attendance.status'
            )
            ->orderBy('attendance.attendance_date', 'desc')
            ->orderBy('class_subject.start_time', 'asc');

        // Filter by specific date if provided
        if ($date && $date !== '') {
            $query->where('attendance.attendance_date', $date);
        }

        $attendances = $query->get();

        return response()->json($attendances);
    }
}
