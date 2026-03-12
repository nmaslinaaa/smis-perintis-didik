<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Services\TwilioService;
use Barryvdh\DomPDF\Facade\Pdf;

class AdminController extends Controller
{
    protected $twilioService;

    public function __construct(TwilioService $twilioService)
    {
        $this->twilioService = $twilioService;
    }

    public function dashboard()
    {
        // Get total students count (only approved and active students)
        $totalStudents = DB::table('student')
            ->where('student_status', 1)
            ->where('verification_status', 'approved')
            ->count();

        // Get total employees count (all employees)
        $totalEmployees = DB::table('employee')
            ->count();

        // Get total classes count
        $totalClasses = DB::table('classes')
            ->count();

                // Get all classes for dropdown
        $classes = DB::table('classes')->get();

        // Get all months for dropdown
        $months = ['January', 'February', 'March', 'April', 'May', 'June',
                  'July', 'August', 'September', 'October', 'November', 'December'];

        // Get current month
        $currentMonth = date('F');

        // Get Darjah 1 class ID (assuming it's the first class or has classID = 4 based on SQL data)
        $defaultClass = DB::table('classes')->where('class_name', 'Darjah 1')->first();
        $defaultClassID = $defaultClass ? $defaultClass->classID : null;

        return view('admin.admin_dashboard', compact('totalStudents', 'totalEmployees', 'totalClasses', 'classes', 'months', 'currentMonth', 'defaultClassID'));
    }

    public function getPerformanceData(Request $request)
    {
        $classID = $request->input('classID');
        $month = $request->input('month');

        if (!$classID || !$month) {
            return response()->json([]);
        }

        // Get all students in the selected class
        $students = DB::table('student')
            ->where('student.student_status', 1)
            ->where('student.verification_status', 'approved')
            ->where('student.classID', $classID)
            ->select('student.studentID', 'student.student_name')
            ->get();

        $result = [];

        foreach ($students as $student) {
            // Get performance data for this student in the selected month
            $performances = DB::table('performance')
                ->join('subjects', 'performance.subjectID', '=', 'subjects.subjectID')
                ->where('performance.studentID', $student->studentID)
                ->where('performance.performance_month', $month)
                ->select('performance.test_score', 'performance.performance_status', 'subjects.subject_name')
                ->get();

            if ($performances->count() > 0) {
                // Calculate average score
                $totalScore = $performances->sum('test_score');
                $averageScore = round($totalScore / $performances->count(), 1);

                // Get subjects by performance status
                $needHelpSubjects = $performances->where('performance_status', 'Need Help')->pluck('subject_name')->toArray();
                $goodSubjects = $performances->where('performance_status', 'Good')->pluck('subject_name')->toArray();
                $excellentSubjects = $performances->where('performance_status', 'Excellent')->pluck('subject_name')->toArray();

                // Count performance statuses
                $needingAttention = count($needHelpSubjects);
                $moderate = count($goodSubjects);
                $mastered = count($excellentSubjects);

                $totalSubjects = $performances->count();

                $result[] = [
                    'studentID' => $student->studentID,
                    'student_name' => $student->student_name,
                    'average_score' => $averageScore,
                    'needing_attention' => $totalSubjects > 0 ? round(($needingAttention / $totalSubjects) * 100) : 0,
                    'moderate' => $totalSubjects > 0 ? round(($moderate / $totalSubjects) * 100) : 0,
                    'mastered' => $totalSubjects > 0 ? round(($mastered / $totalSubjects) * 100) : 0,
                    'need_help_subjects' => $needHelpSubjects,
                    'good_subjects' => $goodSubjects,
                    'excellent_subjects' => $excellentSubjects
                ];
            } else {
                // Student has no performance data for this month
                $result[] = [
                    'studentID' => $student->studentID,
                    'student_name' => $student->student_name,
                    'average_score' => 0,
                    'needing_attention' => 0,
                    'moderate' => 0,
                    'mastered' => 0,
                    'need_help_subjects' => [],
                    'good_subjects' => [],
                    'excellent_subjects' => []
                ];
            }
        }

        return response()->json($result);
    }

    public function getAttendanceData(Request $request)
    {
        $classID = $request->input('classID');
        $subjectID = $request->input('subjectID');

        if (!$classID) {
            return response()->json([]);
        }

        // Get current year
        $year = date('Y');
        $months = ['January', 'February', 'March', 'April', 'May', 'June',
                  'July', 'August', 'September', 'October', 'November', 'December'];

        // Get all subjects for the class if no specific subject selected
        $subjects = DB::table('class_subject')
            ->join('subjects', 'class_subject.subjectID', '=', 'subjects.subjectID')
            ->where('class_subject.classID', $classID)
            ->select('subjects.subjectID', 'subjects.subject_name')
            ->groupBy('subjects.subjectID', 'subjects.subject_name')
            ->get();

        if ($subjectID) {
            $subjects = $subjects->where('subjectID', $subjectID);
        }

        $result = [];

        foreach ($subjects as $subject) {
            $subjectData = [
                'subject_name' => $subject->subject_name,
                'subjectID' => $subject->subjectID,
                'monthly_attendance' => []
            ];

            // Get monthly attendance for this subject
            foreach ($months as $month) {
                $monthNumber = date('n', strtotime($month));

                // Get attendance records for this month and subject
                $attendanceRecords = DB::table('attendance')
                    ->join('student', 'attendance.studentID', '=', 'student.studentID')
                    ->where('student.classID', $classID)
                    ->where('attendance.subjectID', $subject->subjectID)
                    ->whereRaw('MONTH(attendance.attendance_date) = ?', [$monthNumber])
                    ->whereRaw('YEAR(attendance.attendance_date) = ?', [$year])
                    ->where('student.student_status', 1)
                    ->where('student.verification_status', 'approved')
                    ->select('attendance.status')
                    ->get();

                $totalStudents = $attendanceRecords->count();
                $presentCount = $attendanceRecords->where('status', 'Present')->count();
                $leaveCount = $attendanceRecords->where('status', 'Leave with permission')->count();
                $absentCount = $attendanceRecords->where('status', 'Absent')->count();

                // Include all months, even if no data (for consistent chart)
                $attendancePercentage = $totalStudents > 0 ? round(($presentCount / $totalStudents) * 100) : 0;

                $subjectData['monthly_attendance'][] = [
                    'month' => $month,
                    'month_number' => $monthNumber,
                    'total_students' => $totalStudents,
                    'present' => $presentCount,
                    'leave' => $leaveCount,
                    'absent' => $absentCount,
                    'attendance_percentage' => $attendancePercentage
                ];
            }

            // Include all subjects, even those with no attendance data
            $result[] = $subjectData;
        }

        return response()->json($result);
    }

    public function getSubjectsByClass($classID)
    {
        $subjects = DB::table('class_subject')
            ->join('subjects', 'class_subject.subjectID', '=', 'subjects.subjectID')
            ->where('class_subject.classID', $classID)
            ->select('subjects.subjectID', 'subjects.subject_name')
            ->distinct()
            ->get();

        return response()->json($subjects);
    }

    public function testAttendance()
    {
        // Test attendance data for July 2025
        $attendanceData = DB::table('attendance')
            ->join('student', 'attendance.studentID', '=', 'student.studentID')
            ->join('subjects', 'attendance.subjectID', '=', 'subjects.subjectID')
            ->where('student.classID', 4) // Darjah 1
            ->where('attendance.attendance_date', '2025-07-25')
            ->select('attendance.*', 'student.student_name', 'subjects.subject_name')
            ->get();

        return response()->json([
            'total_records' => $attendanceData->count(),
            'data' => $attendanceData
        ]);
    }

        public function addSampleAttendanceData()
    {
        // Add sample attendance data for different months to make the chart more interesting
        $students = [41, 42, 43, 44]; // Student IDs for Darjah 1
        $subjects = [12, 15, 17, 19]; // Subject IDs: English, Mathematics, Bahasa Melayu, Science
        $teacherID = 2;
        $classID = 4;

        // Add data for multiple months
        $monthlyData = [
            '2025-01' => ['dates' => ['2025-01-15', '2025-01-20', '2025-01-25'], 'attendance_rate' => 0.85],
            '2025-02' => ['dates' => ['2025-02-10', '2025-02-15', '2025-02-20'], 'attendance_rate' => 0.90],
            '2025-03' => ['dates' => ['2025-03-05', '2025-03-10', '2025-03-15'], 'attendance_rate' => 0.75],
            '2025-04' => ['dates' => ['2025-04-12', '2025-04-17', '2025-04-22'], 'attendance_rate' => 0.88],
            '2025-05' => ['dates' => ['2025-05-08', '2025-05-13', '2025-05-18'], 'attendance_rate' => 0.92],
            '2025-06' => ['dates' => ['2025-06-03', '2025-06-08', '2025-06-13'], 'attendance_rate' => 0.78],
            '2025-07' => ['dates' => ['2025-07-20', '2025-07-22', '2025-07-24', '2025-07-26', '2025-07-28'], 'attendance_rate' => 0.95],
            '2025-08' => ['dates' => ['2025-08-05', '2025-08-10', '2025-08-15'], 'attendance_rate' => 0.87],
            '2025-09' => ['dates' => ['2025-09-02', '2025-09-07', '2025-09-12'], 'attendance_rate' => 0.83],
            '2025-10' => ['dates' => ['2025-10-14', '2025-10-19', '2025-10-24'], 'attendance_rate' => 0.89],
            '2025-11' => ['dates' => ['2025-11-09', '2025-11-14', '2025-11-19'], 'attendance_rate' => 0.91],
            '2025-12' => ['dates' => ['2025-12-04', '2025-12-09', '2025-12-14'], 'attendance_rate' => 0.76]
        ];

        foreach ($monthlyData as $month => $data) {
            foreach ($data['dates'] as $date) {
                foreach ($subjects as $subjectID) {
                    foreach ($students as $studentID) {
                        // Use attendance rate to determine status
                        $random = rand(1, 100) / 100;
                        if ($random <= $data['attendance_rate']) {
                            $status = 'Present';
                        } elseif ($random <= $data['attendance_rate'] + 0.1) {
                            $status = 'Leave with permission';
                        } else {
                            $status = 'Absent';
                        }

                        DB::table('attendance')->insert([
                            'studentID' => $studentID,
                            'teacherID' => $teacherID,
                            'subjectID' => $subjectID,
                            'classID' => $classID,
                            'attendance_date' => $date,
                            'status' => $status
                        ]);
                    }
                }
            }
        }

        return response()->json(['message' => 'Sample monthly attendance data added successfully']);
    }

    public function getPaymentStatusData(Request $request)
    {
        $classID = $request->input('classID');
        $month = $request->input('month');

        if (!$classID || !$month) {
            return response()->json([]);
        }

        // Get all students for the selected class
        $students = DB::table('student')
            ->where('classID', $classID)
            ->where('student_status', 1)
            ->where('verification_status', 'approved')
            ->select('studentID', 'student_name')
            ->get();

        $result = [];

        foreach ($students as $student) {
            // Get payment status for this student and month
            $feeStatus = DB::table('fee_status')
                ->where('studentID', $student->studentID)
                ->where('feestatus_month', $month)
                ->first();

            // Calculate total subject price for this student
            $totalAmount = DB::table('student_subject')
                ->join('class_subject', 'student_subject.classsubjectID', '=', 'class_subject.classsubjectID')
                ->where('student_subject.studentID', $student->studentID)
                ->sum('class_subject.subject_price');

            $result[] = [
                'student_name' => $student->student_name,
                'status' => $feeStatus ? $feeStatus->status : 'Unpaid',
                'amount' => $totalAmount
            ];
        }

        return response()->json($result);
    }

    public function generateStudentReport(Request $request)
    {
        $studentID = $request->input('studentID');
        $classID = $request->input('classID');
        $month = $request->input('month');

        if (!$studentID || !$classID || !$month) {
            return response()->json(['error' => 'Missing required parameters']);
        }

        try {
            // Get comprehensive student information
            $student = DB::table('student')
                ->join('classes', 'student.classID', '=', 'classes.classID')
                ->join('parent', 'student.parentID', '=', 'parent.parentID')
                ->where('student.studentID', $studentID)
                ->where('student.student_status', 1)
                ->where('student.verification_status', 'approved')
                ->select(
                    'student.studentID',
                    'student.student_name',
                    'student.school_name',
                    'student.address',
                    'student.tuition_startdate',
                    'classes.class_name',
                    'classes.classID',
                    'parent.name as parent_name',
                    'parent.email as parent_email',
                    'parent.phonenumber as parent_phone'
                )
                ->first();

            if (!$student) {
                Log::info('Student not found. Parameters:', [
                    'studentID' => $studentID,
                    'classID' => $classID,
                    'month' => $month
                ]);

                $studentExists = DB::table('student')->where('studentID', $studentID)->first();
                if (!$studentExists) {
                    return response()->json(['error' => 'Student not found']);
                } else {
                    Log::info('Student exists but failed join conditions:', [
                        'student' => $studentExists
                    ]);
                    return response()->json(['error' => 'Student data incomplete']);
                }
            }

            // Get detailed performance data with teacher information
            $performanceData = DB::table('performance')
                ->join('subjects', 'performance.subjectID', '=', 'subjects.subjectID')
                ->leftJoin('employee', 'performance.teacherID', '=', 'employee.employeeID')
                ->where('performance.studentID', $studentID)
                ->where('performance.performance_month', $month)
                ->select(
                    'performance.test_score as score',
                    'performance.performance_status',
                    'performance.teacher_comment',
                    'subjects.subject_name',
                    DB::raw("CONCAT(employee.firstname, ' ', employee.lastname) as teacher_name")
                )
                ->get();

            if ($performanceData->isEmpty()) {
                $anyPerformance = DB::table('performance')
                    ->where('studentID', $studentID)
                    ->first();

                if (!$anyPerformance) {
                    return response()->json(['error' => 'No performance data found for this student']);
                } else {
                    $availableMonths = DB::table('performance')
                        ->where('studentID', $studentID)
                        ->distinct()
                        ->pluck('performance_month')
                        ->toArray();

                    return response()->json(['error' => 'No performance data found for ' . $month . '. Available months: ' . implode(', ', $availableMonths)]);
                }
            }

            // Calculate summary statistics
            $totalSubjects = $performanceData->count();
            $totalScore = $performanceData->sum('score');
            $averageScore = round($totalScore / $totalSubjects);

            $needingAttention = $performanceData->where('score', '<', 60)->count();
            $moderate = $performanceData->whereBetween('score', [60, 79])->count();
            $mastered = $performanceData->where('score', '>=', 80)->count();

            $needingAttentionPercent = round(($needingAttention / $totalSubjects) * 100);
            $moderatePercent = round(($moderate / $totalSubjects) * 100);
            $masteredPercent = round(($mastered / $totalSubjects) * 100);

            // Get attendance data for the month
            $attendanceData = DB::table('attendance')
                ->join('subjects', 'attendance.subjectID', '=', 'subjects.subjectID')
                ->where('attendance.studentID', $studentID)
                ->where('attendance.classID', $classID)
                ->whereMonth('attendance.attendance_date', date('m', strtotime($month . ' 1')))
                ->whereYear('attendance.attendance_date', date('Y'))
                ->select(
                    'subjects.subject_name',
                    'attendance.status',
                    'attendance.attendance_date'
                )
                ->get();

            // Calculate attendance summary
            $totalAttendanceDays = $attendanceData->count();
            $presentDays = $attendanceData->where('status', 'Present')->count();
            $absentDays = $attendanceData->where('status', 'Absent')->count();
            $leaveDays = $attendanceData->where('status', 'Leave with permission')->count();
            $attendancePercentage = $totalAttendanceDays > 0 ? round(($presentDays / $totalAttendanceDays) * 100) : 0;

            // Get fee status
            $feeStatus = DB::table('fee_status')
                ->where('studentID', $studentID)
                ->where('feestatus_month', $month)
                    ->first();

            // Get total fee amount for this student
            $totalFeeAmount = DB::table('student_subject')
                ->join('class_subject', 'student_subject.classsubjectID', '=', 'class_subject.classsubjectID')
                ->where('student_subject.studentID', $studentID)
                ->sum('class_subject.subject_price');

            // Prepare subjects data with detailed information
            $subjects = $performanceData->map(function ($item) {
                return [
                    'subject_name' => $item->subject_name,
                    'score' => $item->score,
                    'performance_status' => $item->performance_status,
                    'teacher_name' => $item->teacher_name,
                    'comments' => $item->teacher_comment
                ];
            });

            // Prepare attendance summary by subject
            $attendanceBySubject = $attendanceData->groupBy('subject_name')->map(function ($group) {
                $total = $group->count();
                $present = $group->where('status', 'Present')->count();
                $absent = $group->where('status', 'Absent')->count();
                $leave = $group->where('status', 'Leave with permission')->count();

                return [
                    'total_days' => $total,
                    'present' => $present,
                    'absent' => $absent,
                    'leave' => $leave,
                    'percentage' => $total > 0 ? round(($present / $total) * 100) : 0
                ];
            });

            $reportData = [
                'student' => [
                    'full_name' => $student->student_name,
                    'school_name' => $student->school_name,
                    'address' => $student->address,
                    'tuition_startdate' => $student->tuition_startdate,
                    'class_name' => $student->class_name
                ],
                'parent' => [
                    'name' => $student->parent_name,
                    'email' => $student->parent_email,
                    'phone' => $student->parent_phone
                ],
                'month' => $month,
                'summary' => [
                    'average_score' => $averageScore,
                    'needing_attention' => $needingAttentionPercent,
                    'moderate' => $moderatePercent,
                    'mastered' => $masteredPercent,
                    'total_subjects' => $totalSubjects
                ],
                'attendance_summary' => [
                    'total_days' => $totalAttendanceDays,
                    'present_days' => $presentDays,
                    'absent_days' => $absentDays,
                    'leave_days' => $leaveDays,
                    'percentage' => $attendancePercentage,
                    'by_subject' => $attendanceBySubject
                ],
                'fee_status' => [
                    'status' => $feeStatus ? $feeStatus->status : 'Unpaid',
                    'amount' => $totalFeeAmount
                ],
                'subjects' => $subjects,
                'studentID' => $studentID,
                'classID' => $classID
            ];

            return response()->json($reportData);

        } catch (\Exception $e) {
            Log::error('Error generating student report: ' . $e->getMessage());
            return response()->json(['error' => 'Error generating report. Please try again.']);
        }
    }

    public function generateReportPage()
    {
        // Check if user is logged in and is an admin
        if (!session()->has('user_id') || session('group_level') != 1) {
            return redirect('/login')->with('error', 'Please login as administrator to access this page.');
        }

        // Get all classes
        $classes = DB::table('classes')
            ->orderBy('class_name')
            ->get();

        // Get current month
        $currentMonth = date('F'); // e.g., "July"

        // Get all months for dropdown
        $months = [
            'January', 'February', 'March', 'April', 'May', 'June',
            'July', 'August', 'September', 'October', 'November', 'December'
        ];

        // Default class ID (Darjah 1)
        $defaultClassID = DB::table('classes')
            ->where('class_name', 'Darjah 1')
            ->value('classID');

        return view('admin.generate_report', compact('classes', 'currentMonth', 'months', 'defaultClassID'));
    }

    public function studentReportCard($studentID, $classID, $month)
    {
        try {
            // Check if user is logged in and is an admin
            if (!session()->has('user_id') || session('group_level') != 1) {
                return redirect('/login')->with('error', 'Please login as administrator to access this page.');
            }

                        // URL decode the month parameter
            $month = urldecode($month);


            // Get student information with parent details
            $student = DB::table('student')
                ->leftJoin('parent', 'student.parentID', '=', 'parent.parentID')
                ->where('student.studentID', $studentID)
                ->where('student.classID', $classID)
                ->select(
                    'student.studentID',
                    'student.student_name',
                    'student.school_name',
                    'student.address',
                    'student.tuition_startdate',
                    'parent.name as parent_name',
                    'parent.email as parent_email',
                    'parent.phonenumber as parent_phone'
                )
                ->first();

            if (!$student) {
                return redirect()->back()->with('error', 'Student not found.');
            }

            // Get class name
            $className = DB::table('classes')
                ->where('classID', $classID)
                ->value('class_name');

            $student->class_name = $className;

            // Get performance data with teacher information
            $performanceData = DB::table('performance')
                ->join('subjects', 'performance.subjectID', '=', 'subjects.subjectID')
                ->leftJoin('employee', 'performance.teacherID', '=', 'employee.employeeID')
                ->where('performance.studentID', $studentID)
                ->where('performance.performance_month', $month)
                ->select(
                    'subjects.subject_name',
                    'performance.test_score as score',
                    'performance.performance_status',
                    'performance.teacher_comment',
                    DB::raw("CONCAT(employee.firstname, ' ', employee.lastname) as teacher_name")
                )
                ->get();

            // Calculate summary statistics
            $totalSubjects = $performanceData->count();
            $averageScore = $totalSubjects > 0 ? round($performanceData->avg('score'), 1) : 0;

            // Prepare subjects data
            $subjects = $performanceData->map(function ($item) {
                return [
                    'subject_name' => $item->subject_name,
                    'score' => $item->score,
                    'performance_status' => $item->performance_status,
                    'teacher_name' => $item->teacher_name,
                    'comments' => $item->teacher_comment
                ];
            });

            // Get attendance data
            $attendanceData = DB::table('attendance')
                ->join('subjects', 'attendance.subjectID', '=', 'subjects.subjectID')
                ->where('attendance.studentID', $studentID)
                ->where('attendance.classID', $classID)
                ->whereMonth('attendance.attendance_date', date('n', strtotime("1 $month 2024")))
                ->whereYear('attendance.attendance_date', 2024)
                ->select('attendance.status', 'subjects.subject_name')
                ->get();

            // Calculate attendance summary
            $totalAttendanceDays = $attendanceData->count();
            $presentDays = $attendanceData->where('status', 'Present')->count();
            $absentDays = $attendanceData->where('status', 'Absent')->count();
            $leaveDays = $attendanceData->where('status', 'Leave with permission')->count();
            $attendancePercentage = $totalAttendanceDays > 0 ? round(($presentDays / $totalAttendanceDays) * 100) : 0;

            // Prepare attendance summary by subject
            $attendanceBySubject = $attendanceData->groupBy('subject_name')->map(function ($group) {
                $total = $group->count();
                $present = $group->where('status', 'Present')->count();
                $absent = $group->where('status', 'Absent')->count();
                $leave = $group->where('status', 'Leave with permission')->count();

                return [
                    'total_days' => $total,
                    'present' => $present,
                    'absent' => $absent,
                    'leave' => $leave,
                    'percentage' => $total > 0 ? round(($present / $total) * 100) : 0
                ];
            });

            // Get fee status
            $feeStatus = DB::table('fee_status')
                ->where('studentID', $studentID)
                ->where('feestatus_month', $month)
                ->first();

            // Calculate total fee amount based on student's enrolled subjects
            $totalFeeAmount = 0;
            $studentSubjects = DB::table('student_subject')
                ->where('student_subject.studentID', $studentID)
                ->get();

            foreach ($studentSubjects as $studentSubject) {
                if ($studentSubject->classsubjectID) {
                    $classSubject = DB::table('class_subject')
                        ->where('classsubjectID', $studentSubject->classsubjectID)
                        ->where('classID', $classID)
                        ->first();
                    if ($classSubject) {
                        $totalFeeAmount += $classSubject->subject_price;
                    }
                }
            }

            // Prepare data for view
            $summary = [
                'average_score' => $averageScore,
                'total_subjects' => $totalSubjects
            ];

            $parent = [
                'name' => $student->parent_name,
                'email' => $student->parent_email,
                'phone' => $student->parent_phone
            ];

            $attendance_summary = [
                'total_days' => $totalAttendanceDays,
                'present_days' => $presentDays,
                'absent_days' => $absentDays,
                'leave_days' => $leaveDays,
                'percentage' => $attendancePercentage,
                'by_subject' => $attendanceBySubject
            ];

            $fee_status = [
                'status' => $feeStatus ? $feeStatus->status : 'Unpaid',
                'amount' => number_format($totalFeeAmount, 2)
            ];

            return view('admin.student_report_card', compact(
                'student',
                'summary',
                'parent',
                'attendance_summary',
                'fee_status',
                'subjects',
                'month'
            ));

        } catch (\Exception $e) {
            Log::error('Error generating student report card: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error generating report card. Please try again.');
        }
    }

    /**
     * Send student report PDF to parent via WhatsApp
     */
    public function sendReportPDFToParent(Request $request, $studentID, $classID, $month)
    {
        try {
            // Get student and parent information
            $student = DB::table('student')
                ->join('parent', 'student.parentID', '=', 'parent.parentID')
                ->join('classes', 'student.classID', '=', 'classes.classID')
                ->where('student.studentID', $studentID)
                ->select(
                    'student.studentID',
                    'student.student_name',
                    'student.school_name',
                    'student.address',
                    'parent.name as parent_name',
                    'parent.email as parent_email',
                    'parent.phonenumber as parent_phone',
                    'classes.class_name'
                )
                ->first();

            if (!$student) {
                return response()->json([
                    'success' => false,
                    'message' => 'Student not found'
                ], 404);
            }

            if (!$student->parent_phone) {
                return response()->json([
                    'success' => false,
                    'message' => 'Parent phone number not found'
                ], 400);
            }

            // Format phone number
            $phoneNumber = $this->formatPhoneNumber($student->parent_phone);

            // Generate PDF file and store it
            $pdfFileName = "student_report_{$studentID}_{$classID}_" . strtolower($month) . "_" . date('Y-m-d_H-i-s') . ".pdf";
            $pdfPath = storage_path("app/public/reports/{$pdfFileName}");

            // Ensure the directory exists
            if (!file_exists(storage_path("app/public/reports"))) {
                mkdir(storage_path("app/public/reports"), 0755, true);
            }

            // Generate PDF using the same data as the report card
            $this->generatePDFReport($studentID, $classID, $month, $pdfPath);

            // Create public URL for the PDF
            // NOTE: In production, this will use your domain (e.g., https://yourdomain.com)
            // In development (localhost), Twilio cannot access this URL
            $pdfUrl = url("storage/reports/{$pdfFileName}");

            // For production deployment, ensure your APP_URL in .env is set to your domain
            // Example: APP_URL=https://yourdomain.com

            // Send WhatsApp message with PDF
            $result = $this->twilioService->sendStudentReportPDF(
                $phoneNumber,
                $student->student_name,
                $month,
                $pdfUrl
            );

            if ($result) {
                Log::info('Student report PDF sent successfully via WhatsApp', [
                    'student_id' => $studentID,
                    'student_name' => $student->student_name,
                    'parent_phone' => $phoneNumber,
                    'month' => $month
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Report PDF sent successfully to parent',
                    'student_name' => $student->student_name,
                    'parent_name' => $student->parent_name,
                    'phone' => $phoneNumber
                ]);
            } else {
                Log::error('Failed to send student report PDF via WhatsApp', [
                    'student_id' => $studentID,
                    'parent_phone' => $phoneNumber
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Failed to send WhatsApp notification'
                ], 500);
            }

        } catch (\Exception $e) {
            Log::error('Error sending student report PDF via WhatsApp', [
                'student_id' => $studentID,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate PDF report for student
     */
    private function generatePDFReport($studentID, $classID, $month, $pdfPath)
    {
        try {
            // Get all the data needed for the report (same as studentReportCard method)
            $student = DB::table('student')
                ->join('parent', 'student.parentID', '=', 'parent.parentID')
                ->join('classes', 'student.classID', '=', 'classes.classID')
                ->where('student.studentID', $studentID)
                ->select(
                    'student.studentID',
                    'student.student_name',
                    'student.school_name',
                    'student.address',
                    'parent.name as parent_name',
                    'parent.email as parent_email',
                    'parent.phonenumber as parent_phone',
                    'classes.class_name'
                )
                ->first();

            // Get performance data
            $subjects = DB::table('performance')
                ->join('subjects', 'performance.subjectID', '=', 'subjects.subjectID')
                ->leftJoin('employee', 'performance.teacherID', '=', 'employee.employeeID')
                ->where('performance.studentID', $studentID)
                ->where('performance.performance_month', $month)
                ->select(
                    'subjects.subject_name',
                    'performance.test_score as score',
                    'performance.performance_status',
                    'performance.teacher_comment as comments',
                    DB::raw("CONCAT(employee.firstname, ' ', employee.lastname) as teacher_name")
                )
                ->get();

            // Calculate average score
            $averageScore = $subjects->count() > 0 ? round($subjects->avg('score'), 1) : 0;
            $totalSubjects = $subjects->count();

            // Get attendance data
            $attendanceData = DB::table('attendance')
                ->join('subjects', 'attendance.subjectID', '=', 'subjects.subjectID')
                ->where('attendance.studentID', $studentID)
                ->where('attendance.classID', $classID)
                ->whereMonth('attendance.attendance_date', date('n', strtotime("1 $month 2024")))
                ->whereYear('attendance.attendance_date', 2024)
                ->select('attendance.status', 'subjects.subject_name')
                ->get();

            // Calculate attendance summary
            $totalAttendanceDays = $attendanceData->count();
            $presentDays = $attendanceData->where('status', 'Present')->count();
            $absentDays = $attendanceData->where('status', 'Absent')->count();
            $leaveDays = $attendanceData->where('status', 'Leave with permission')->count();

            // Get fee status
            $feeStatus = DB::table('fee_status')
                ->where('studentID', $studentID)
                ->where('feestatus_month', $month)
                ->first();

            // Calculate total fee amount
            $totalFeeAmount = 0;
            $studentSubjects = DB::table('student_subject')
                ->where('student_subject.studentID', $studentID)
                ->get();

            foreach ($studentSubjects as $studentSubject) {
                if ($studentSubject->classsubjectID) {
                    $classSubject = DB::table('class_subject')
                        ->where('classsubjectID', $studentSubject->classsubjectID)
                        ->where('classID', $classID)
                        ->first();
                    if ($classSubject) {
                        $totalFeeAmount += $classSubject->subject_price;
                    }
                }
            }

            // Generate HTML content for PDF
            $html = $this->generatePDFHTML($student, $subjects, $attendanceData, $feeStatus, $totalFeeAmount, $month, $averageScore, $totalSubjects, $presentDays, $absentDays, $leaveDays, $totalAttendanceDays);

                        // Generate PDF using Laravel DomPDF
            $pdf = Pdf::loadHTML($html);
            $pdf->setPaper('A4', 'portrait');

            // Save PDF to file
            $pdf->save($pdfPath);

            // Verify file was created
            if (!file_exists($pdfPath)) {
                Log::error('PDF file was not created', ['path' => $pdfPath]);
                return false;
            }

            Log::info('PDF generated successfully', ['path' => $pdfPath, 'size' => filesize($pdfPath)]);
            return true;
        } catch (\Exception $e) {
            Log::error('Error generating PDF report: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Generate HTML content for PDF
     */
    private function generatePDFHTML($student, $subjects, $attendanceData, $feeStatus, $totalFeeAmount, $month, $averageScore, $totalSubjects, $presentDays, $absentDays, $leaveDays, $totalAttendanceDays)
    {
        $html = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <style>
                body { font-family: Arial, sans-serif; margin: 0; padding: 20px; }
                .header { text-align: center; border-bottom: 3px solid #2c3e50; padding-bottom: 10px; margin-bottom: 15px; }
                .section { background: #ecf0f1; padding: 12px; border-radius: 8px; margin-bottom: 12px; border: 2px solid #bdc3c7; }
                .section-title { color: #2c3e50; margin: 0 0 12px 0; font-size: 15px; border-bottom: 2px solid #3498db; padding-bottom: 6px; }
                .grid-2 { display: table; width: 100%; }
                .grid-2 > div { display: table-cell; width: 50%; padding-right: 10px; }
                .info-table { width: 100%; border-collapse: collapse; }
                .info-table td { padding: 5px 0; font-size: 11px; }
                .info-table td:first-child { font-weight: bold; color: #2c3e50; width: 40%; }
                .info-table td:last-child { color: #34495e; border-bottom: 1px solid #bdc3c7; }
                .performance-table { width: 100%; border-collapse: collapse; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1); font-size: 10px; }
                .performance-table th { padding: 8px; text-align: left; font-weight: bold; border-right: 1px solid #2c3e50; background: #34495e; color: white; }
                .performance-table td { padding: 8px; border-right: 1px solid #dee2e6; }
                .performance-table tr:nth-child(even) { background: #f8f9fa; }
                .status-badge { padding: 2px 5px; border-radius: 6px; font-weight: bold; font-size: 9px; }
                .status-excellent { background: #28a745; color: white; }
                .status-good { background: #ffc107; color: #000; }
                .status-need-help { background: #dc3545; color: white; }
                .footer { text-align: center; border-top: 3px solid #2c3e50; padding-top: 12px; color: #7f8c8d; }
            </style>
        </head>
        <body>
            <div class="header">
                <h1 style="color: #2c3e50; margin: 0; font-size: 22px; font-weight: bold;">PUSAT TUISYEN PERINTIS DIDIK</h1>
                <p style="color: #7f8c8d; margin: 2px 0; font-size: 12px;">Academic Excellence Through Dedication</p>
                <h2 style="color: #2c3e50; margin: 0; font-size: 18px; font-weight: bold;">STUDENT REPORT CARD</h2>
                <p style="color: #7f8c8d; margin: 2px 0; font-size: 11px;">Academic Year 2024/2025</p>
            </div>

            <div class="section">
                <h3 class="section-title">STUDENT INFORMATION</h3>
                <div class="grid-2">
                    <div>
                        <table class="info-table">
                            <tr><td>Student Name:</td><td>' . ($student->student_name ?? 'N/A') . '</td></tr>
                            <tr><td>School:</td><td>' . ($student->school_name ?? 'N/A') . '</td></tr>
                            <tr><td>Class:</td><td>' . ($student->class_name ?? 'N/A') . '</td></tr>
                            <tr><td>Address:</td><td>' . ($student->address ?? 'N/A') . '</td></tr>
                        </table>
                    </div>
                    <div>
                        <table class="info-table">
                            <tr><td>Average Score:</td><td><strong>' . $averageScore . '%</strong></td></tr>
                            <tr><td>Total Subjects:</td><td><strong>' . $totalSubjects . '</strong></td></tr>
                            <tr><td>Report Period:</td><td>' . $month . ' 2024</td></tr>
                        </table>
                    </div>
                </div>
            </div>

            <div class="section">
                <h3 class="section-title">PARENT/GUARDIAN INFORMATION</h3>
                <div class="grid-2">
                    <div>
                        <table class="info-table">
                            <tr><td>Parent Name:</td><td>' . ($student->parent_name ?? 'N/A') . '</td></tr>
                            <tr><td>Phone:</td><td>' . ($student->parent_phone ?? 'N/A') . '</td></tr>
                        </table>
                    </div>
                    <div>
                        <table class="info-table">
                            <tr><td>Email:</td><td>' . ($student->parent_email ?? 'N/A') . '</td></tr>
                        </table>
                    </div>
                </div>
            </div>';

        if ($subjects->count() > 0) {
            $html .= '
            <div class="section">
                <h3 class="section-title">ACADEMIC PERFORMANCE</h3>
                <table class="performance-table">
                    <thead>
                        <tr>
                            <th>Subject</th>
                            <th style="text-align: center;">Score (%)</th>
                            <th style="text-align: center;">Status</th>
                            <th style="text-align: center;">Teacher</th>
                            <th style="text-align: center;">Comments</th>
                        </tr>
                    </thead>
                    <tbody>';

            foreach ($subjects as $subject) {
                $statusClass = '';
                if ($subject->performance_status == 'Excellent') $statusClass = 'status-excellent';
                elseif ($subject->performance_status == 'Good') $statusClass = 'status-good';
                else $statusClass = 'status-need-help';

                $html .= '
                        <tr>
                            <td style="font-weight: bold; color: #2c3e50;">' . $subject->subject_name . '</td>
                            <td style="text-align: center; font-weight: bold; font-size: 11px;">' . round($subject->score) . '%</td>
                            <td style="text-align: center;">
                                <span class="status-badge ' . $statusClass . '">' . $subject->performance_status . '</span>
                            </td>
                            <td style="text-align: center; color: #7f8c8d; font-style: italic;">' . ($subject->teacher_name ?? 'N/A') . '</td>
                            <td style="text-align: left; color: #7f8c8d; font-style: italic; max-width: 120px; word-wrap: break-word;">' . ($subject->comments ?? 'No comments available.') . '</td>
                        </tr>';
            }

            $html .= '
                    </tbody>
                </table>
            </div>';
        }

        $html .= '
            <div class="section">
                <h3 class="section-title">ATTENDANCE RECORD</h3>
                <div class="grid-2">
                    <div>
                        <h4 style="color: #2c3e50; margin-bottom: 8px; font-size: 13px;">Overall Attendance Summary</h4>
                        <table class="performance-table">
                            <tr style="background: #34495e; color: white;">
                                <th style="padding: 6px; text-align: center;">Status</th>
                                <th style="padding: 6px; text-align: center;">Days</th>
                                <th style="padding: 6px; text-align: center;">Percentage</th>
                            </tr>
                            <tr>
                                <td style="padding: 6px; text-align: center; background: #d4edda; color: #155724; font-weight: bold;">Present</td>
                                <td style="padding: 6px; text-align: center;">' . $presentDays . '</td>
                                <td style="padding: 6px; text-align: center;">' . ($totalAttendanceDays > 0 ? round(($presentDays / $totalAttendanceDays) * 100) : 0) . '%</td>
                            </tr>
                            <tr>
                                <td style="padding: 6px; text-align: center; background: #f8d7da; color: #721c24; font-weight: bold;">Absent</td>
                                <td style="padding: 6px; text-align: center;">' . $absentDays . '</td>
                                <td style="padding: 6px; text-align: center;">' . ($totalAttendanceDays > 0 ? round(($absentDays / $totalAttendanceDays) * 100) : 0) . '%</td>
                            </tr>
                            <tr>
                                <td style="padding: 6px; text-align: center; background: #fff3cd; color: #856404; font-weight: bold;">Leave</td>
                                <td style="padding: 6px; text-align: center;">' . $leaveDays . '</td>
                                <td style="padding: 6px; text-align: center;">' . ($totalAttendanceDays > 0 ? round(($leaveDays / $totalAttendanceDays) * 100) : 0) . '%</td>
                            </tr>
                            <tr style="background: #f8f9fa; font-weight: bold;">
                                <td style="padding: 6px; text-align: center;">Total</td>
                                <td style="padding: 6px; text-align: center;">' . $totalAttendanceDays . '</td>
                                <td style="padding: 6px; text-align: center;">100%</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <div class="section">
                <h3 class="section-title">FEE STATUS</h3>
                <div style="display: flex; justify-content: space-between; align-items: center; background: white; padding: 12px; border-radius: 8px;">
                    <div>
                        <h4 style="margin: 0 0 4px 0; color: #2c3e50; font-size: 13px;">Monthly Fee Status</h4>
                        <p style="margin: 0; color: #7f8c8d; font-size: 10px;">Period: ' . $month . ' 2024</p>
                    </div>
                    <div style="text-align: right;">
                        <div style="margin-bottom: 4px;">
                            <span style="background: ' . (($feeStatus && $feeStatus->status == 'Paid') ? '#d4edda' : '#f8d7da') . '; color: ' . (($feeStatus && $feeStatus->status == 'Paid') ? '#155724' : '#721c24') . '; padding: 4px 10px; border-radius: 12px; font-weight: bold; font-size: 11px;">
                                ' . ($feeStatus ? $feeStatus->status : 'Unpaid') . '
                            </span>
                        </div>
                        <p style="margin: 0; color: #2c3e50; font-weight: bold; font-size: 14px;">RM ' . number_format($totalFeeAmount, 2) . '</p>
                    </div>
                </div>
            </div>

            <div class="footer">
                <p style="margin: 0 0 6px 0; font-size: 11px;"><strong>Report Generated:</strong> ' . date('l, F j, Y') . '</p>
                <p style="margin: 0; font-size: 9px; font-style: italic;">
                    This is an official academic report from Pusat Tuisyen Perintis Didik.<br>
                    For any inquiries, please contact the administration office.
                </p>
            </div>
        </body>
        </html>';

        return $html;
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


}
