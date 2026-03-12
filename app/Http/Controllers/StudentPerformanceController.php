<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentPerformanceController extends Controller
{
    public function evaluatePerformance(Request $request)
    {
        $user = session('user');
        if (!$user || !isset($user->employeeID)) {
            return redirect('/login');
        }
        $employeeID = $user->employeeID;

        // Get all unique subject names the teacher teaches (from class_subject)
        $subjectRows = DB::table('class_subject')
            ->join('subjects', 'class_subject.subjectID', '=', 'subjects.subjectID')
            ->where('class_subject.employeeID', $employeeID)
            ->select('subjects.subject_name')
            ->distinct()
            ->get();
        $subjects = $subjectRows->unique('subject_name')->values();

        // For dropdowns: get selected month/subject (default to first if not set)
        $months = [
            'January','February','March','April','May','June',
            'July','August','September','October','November','December'
        ];
        $selectedMonth = $request->query('month', $months[0]);
        $selectedSubjectName = $request->query('subject', $subjects->first()->subject_name ?? null);

        // Filter classes to only those that have the selected subject name assigned to this teacher
        $classRows = DB::table('teacher_class')
            ->join('classes', 'teacher_class.classID', '=', 'classes.classID')
            ->join('class_subject', function($join) use ($employeeID) {
                $join->on('teacher_class.classID', '=', 'class_subject.classID')
                    ->where('class_subject.employeeID', $employeeID);
            })
            ->join('subjects', 'class_subject.subjectID', '=', 'subjects.subjectID')
            ->where('subjects.subject_name', $selectedSubjectName)
            ->where('teacher_class.employeeID', $employeeID)
            ->select('classes.classID', 'classes.class_name')
            ->distinct()
            ->get();
        $classes = $classRows->map(function($row) {
            return [
                'classID' => $row->classID,
                'class_name' => trim($row->class_name)
            ];
        })->sortBy(function($c) {
            // Extract number from 'Darjah X' for natural sort
            if (preg_match('/(\d+)/', $c['class_name'], $m)) {
                return (int)$m[1];
            }
            return $c['class_name'];
        })->values();

        return view('teacher.evaluate_performance', [
            'subjects' => $subjects,
            'classes' => $classes,
            'months' => $months,
            'selectedMonth' => $selectedMonth,
            'selectedSubjectName' => $selectedSubjectName,
        ]);
    }

    public function evaluateStudentPerformance(Request $request)
    {
        $user = session('user');
        $teacherID = $user && isset($user->employeeID) ? $user->employeeID : null;
        if (!$teacherID) {
            return redirect('/login');
        }
        $classID = $request->query('classID');
        $subjectName = $request->query('subject');
        $month = $request->query('month');
        $selectedClassSubjectID = $request->query('class_subject_id') ?? null;
        $timeSlots = [];

        // Get class info
        $class = \App\Models\Classes::find($classID);

        // Get subjectID from subjectName
        $subject = \App\Models\Subjects::where('subject_name', $subjectName)->first();
        $subjectID = $subject ? $subject->subjectID : null;

        // Fetch all time slots (class_subject) for this class, subject, and teacher
        if ($classID && $subjectID && $teacherID) {
            $timeSlots = DB::table('class_subject')
                ->where('classID', $classID)
                ->where('subjectID', $subjectID)
                ->where('employeeID', $teacherID)
                ->get();
        }
        // Default to first slot if not set
        if (!$selectedClassSubjectID && count($timeSlots) > 0) {
            $selectedClassSubjectID = $timeSlots[0]->classsubjectID;
        }

        // Get students for this class and subject (regardless of slot)
        $students = collect();
        if ($classID && $subjectID) {
            $studentIDs = \App\Models\StudentSubject::where('subjectID', $subjectID)
                ->whereHas('student', function($q) use ($classID) {
                    $q->where('classID', $classID)
                        ->where('student_status', 1)
                        ->where('verification_status', 'approved');
                })
                ->pluck('studentID');
            $students = \App\Models\Student::whereIn('studentID', $studentIDs)
                ->where('classID', $classID)
                ->where('student_status', 1)
                ->where('verification_status', 'approved')
                ->get();
        }
        // If slot is selected, filter students to only those registered for that slot
        if ($selectedClassSubjectID) {

            $slotStudentIDs = \App\Models\StudentSubject::where('classsubjectID', $selectedClassSubjectID)
                ->pluck('studentID');
        
            if ($slotStudentIDs->count() > 0) {
                $students = $students->whereIn('studentID', $slotStudentIDs);
            }
        
        }
        // Fetch existing performance data for this class, subject, month, and slot
        $performanceData = [];
        if ($subjectID && $selectedClassSubjectID) {
            $performanceData = \App\Models\Performance::where('classID', $classID)
                ->where('subjectID', $subjectID)
                ->where('performance_month', $month)
                ->where('classsubjectID', $selectedClassSubjectID)
                ->get()
                ->keyBy('studentID');
        }

        return view('teacher.evaluate_student_performance', [
            'students' => $students,
            'class' => $class,
            'subjectName' => $subjectName,
            'month' => $month,
            'performanceData' => $performanceData,
            'timeSlots' => $timeSlots,
            'selectedClassSubjectID' => $selectedClassSubjectID,
        ]);
    }

    public function savePerformance(Request $request)
    {
        $user = session('user');
        $teacherID = $user && isset($user->employeeID) ? $user->employeeID : null;
        if (!$teacherID) {
            return redirect('/login');
        }
        $classID = $request->input('classID');
        $subjectName = $request->input('subjectName');
        $month = $request->input('month');
        $selectedClassSubjectID = $request->input('class_subject_id');
        $performances = $request->input('performances', []);

        // Get subjectID from subjectName
        $subject = \App\Models\Subjects::where('subject_name', $subjectName)->first();
        $subjectID = $subject ? $subject->subjectID : null;

        foreach ($performances as $studentID => $data) {
            if (!isset($data['test_score']) || $data['test_score'] === null || $data['test_score'] === '' || !isset($data['performance_status']) || $data['performance_status'] === '') continue;

            // Round test score to integer
            $testScore = round((float)$data['test_score']);

            \App\Models\Performance::updateOrCreate(
                [
                    'studentID' => $studentID,
                    'teacherID' => $teacherID,
                    'subjectID' => $subjectID,
                    'classID' => $classID,
                    'performance_month' => $month,
                    'classsubjectID' => $selectedClassSubjectID,
                ],
                [
                    'test_score' => $testScore,
                    'performance_status' => $data['performance_status'],
                    'teacher_comment' => $data['teacher_comment'] ?? '',
                ]
            );
        }
        return back()->with('success', 'Performance saved!');
    }

    // ADMIN: View performance by class, subject, and slot
    public function adminViewPerformance(Request $request)
    {
        // Get all classes
        $classes = \App\Models\Classes::all();
        $selectedClassID = $request->query('classID');
        $selectedSubjectID = $request->query('subjectID');
        $selectedSlotID = $request->query('slotID');
        $months = [
            'January','February','March','April','May','June',
            'July','August','September','October','November','December'
        ];
        $selectedMonth = $request->query('month', date('F'));
        $subjects = collect();
        $slots = collect();
        if ($selectedClassID) {
            // Get all subjects for this class (from class_subject)
            $subjects = DB::table('class_subject')
                ->join('subjects', 'class_subject.subjectID', '=', 'subjects.subjectID')
                ->where('class_subject.classID', $selectedClassID)
                ->select('subjects.subjectID', 'subjects.subject_name')
                ->distinct()
                ->get();
            if ($selectedSubjectID) {
                // Get all slots for this class and subject
                $slots = DB::table('class_subject')
                    ->join('employee', 'class_subject.employeeID', '=', 'employee.employeeID')
                    ->where('class_subject.classID', $selectedClassID)
                    ->where('class_subject.subjectID', $selectedSubjectID)
                    ->select(
                        'class_subject.classsubjectID',
                        'class_subject.days',
                        'class_subject.start_time',
                        'class_subject.end_time',
                        DB::raw("CONCAT(employee.firstname, ' ', employee.lastname) as teacher_name")
                    )
                    ->get();
            }
        }
        $students = collect();
        $performanceData = [];
        if ($selectedClassID && $selectedSubjectID && $selectedSlotID) {
            // Get studentIDs registered for this classsubject (slot)
            $studentIDs = \App\Models\StudentSubject::where('classsubjectID', $selectedSlotID)
                ->pluck('studentID');
            $students = \App\Models\Student::whereIn('studentID', $studentIDs)
                ->where('classID', $selectedClassID)
                ->where('student_status', 1)
                ->where('verification_status', 'approved')
                ->get();

            // Get performance data for these students and selected month
            $performanceData = \App\Models\Performance::where('classID', $selectedClassID)
                ->where('subjectID', $selectedSubjectID)
                ->where('classsubjectID', $selectedSlotID)
                ->where('performance_month', $selectedMonth)
                ->get()
                ->keyBy('studentID');
        }
        return view('admin.view_performance', [
            'classes' => $classes,
            'subjects' => $subjects,
            'slots' => $slots,
            'selectedClassID' => $selectedClassID,
            'selectedSubjectID' => $selectedSubjectID,
            'selectedSlotID' => $selectedSlotID,
            'students' => $students,
            'performanceData' => $performanceData,
            'months' => $months,
            'selectedMonth' => $selectedMonth,
        ]);
    }

    // PARENT: View child's performance
    public function parentViewChildPerformance(Request $request)
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

        // Fetch performances for the selected child and month
        $performances = collect();
        if ($selectedChildID) {
            $performances = \App\Models\Performance::where('studentID', $selectedChildID)
                ->where('performance_month', $selectedMonth)
                ->join('subjects', 'performance.subjectID', '=', 'subjects.subjectID')
                ->select('subjects.subject_name', 'performance.test_score', 'performance.performance_status')
                ->get()
                ->map(function($row) {
                    return [
                        'subject_name' => $row->subject_name,
                        'test_score' => $row->test_score,
                        'performance_status' => $row->performance_status,
                    ];
                });
        }

        return view('parent.view_child_performance', compact('children', 'selectedChildID', 'months', 'selectedMonth', 'performances'));
    }

    // Dashboard: Student performance for class and month
    public function dashboardPerformance(Request $request)
    {
        $user = session('user');
        $teacherID = $user->employeeID ?? null;
        $classID = $request->input('classID');
        $month = $request->input('month');
        if (!$teacherID || !$classID || !$month) {
            return response()->json([]);
        }

        // Get all classsubjectID taught by this teacher for this class
        $classSubjects = DB::table('class_subject')
            ->where('employeeID', $teacherID)
            ->where('classID', $classID)
            ->get();
        $classSubjectIDs = $classSubjects->pluck('classsubjectID')->toArray();

        if (empty($classSubjectIDs)) {
            return response()->json([]);
        }

        // Get all students in this class (using correct table name 'student')
        $students = DB::table('student_subject')
            ->join('student', 'student_subject.studentID', '=', 'student.studentID')
            ->whereIn('student_subject.classsubjectID', $classSubjectIDs)
            ->where('student.student_status', 1) // Only active students
            ->where('student.verification_status', 'approved') // Only approved students
            ->select('student.studentID', 'student.student_name')
            ->distinct()
            ->get();

        $result = [];
        foreach ($students as $student) {
            // Get all performance records for this student in this class and month
            $performances = DB::table('performance')
                ->join('subjects', 'performance.subjectID', '=', 'subjects.subjectID')
                ->where('performance.studentID', $student->studentID)
                ->where('performance.performance_month', $month)
                ->whereIn('performance.classsubjectID', $classSubjectIDs)
                ->select(
                    'subjects.subject_name',
                    'performance.test_score',
                    'performance.performance_status'
                )
                ->get();

            // If no performance data for this month, still show student with 0 scores
            if ($performances->isEmpty()) {
                $result[] = [
                    'student_name' => $student->student_name,
                    'average_score' => 0,
                    'needing_attention' => 0,
                    'moderate' => 0,
                    'mastered' => 0,
                    'needing_attention_subjects' => [],
                    'moderate_subjects' => [],
                    'mastered_subjects' => []
                ];
                continue;
            }

            // Calculate average score
            $totalScore = $performances->sum('test_score');
            $averageScore = round($totalScore / $performances->count(), 1);

            // Categorize subjects by performance level
            $needingAttention = [];
            $moderate = [];
            $mastered = [];

            foreach ($performances as $perf) {
                if ($perf->performance_status === 'Need Help') {
                    $needingAttention[] = $perf->subject_name;
                } elseif ($perf->performance_status === 'Good') {
                    $moderate[] = $perf->subject_name;
                } elseif ($perf->performance_status === 'Excellent') {
                    $mastered[] = $perf->subject_name;
                }
            }

            // Calculate percentages
            $totalSubjects = $performances->count();
            $needingAttentionPercent = $totalSubjects > 0 ? round((count($needingAttention) / $totalSubjects) * 100) : 0;
            $moderatePercent = $totalSubjects > 0 ? round((count($moderate) / $totalSubjects) * 100) : 0;
            $masteredPercent = $totalSubjects > 0 ? round((count($mastered) / $totalSubjects) * 100) : 0;

            $result[] = [
                'student_name' => $student->student_name,
                'average_score' => $averageScore,
                'needing_attention' => $needingAttentionPercent,
                'moderate' => $moderatePercent,
                'mastered' => $masteredPercent,
                'needing_attention_subjects' => $needingAttention,
                'moderate_subjects' => $moderate,
                'mastered_subjects' => $mastered
            ];
        }

        return response()->json($result);
    }
}
