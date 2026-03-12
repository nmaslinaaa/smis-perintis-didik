<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Classes;
use App\Models\Student;
use App\Models\FeeStatus;
use Illuminate\Support\Facades\DB;

class ManageFeeStatusController extends Controller
{
    public function index(Request $request)
    {
        $classes = Classes::all();
        $months = [
            'January','February','March','April','May','June',
            'July','August','September','October','November','December'
        ];
        $selectedClassId = $request->query('class_id', $classes->first()->classID ?? null);
        $selectedMonth = $request->query('month');
        if (!$selectedMonth || !in_array($selectedMonth, $months)) {
            $selectedMonth = date('F');
        }

        // Get students for class
        $students = Student::where('classID', $selectedClassId)
            ->where('student_status', 1) // Only active students
            ->where('verification_status', 'approved') // Only approved students
            ->get();

        // Get fee status for each student for selected month
        $feeStatuses = [];
        foreach ($students as $student) {
            $fee = FeeStatus::where('studentID', $student->studentID)
                ->where('feestatus_month', $selectedMonth)
                ->first();
            // Calculate total subject price for this student from class_subject
            $amount = 0;
            foreach ($student->studentSubjects as $studentSubject) {
                if ($studentSubject->classsubjectID) {
                    $classSubject = DB::table('class_subject')->where('classsubjectID', $studentSubject->classsubjectID)->first();
                    if ($classSubject) {
                        $amount += $classSubject->subject_price;
                    }
                }
            }
            $feeStatuses[] = [
                'student' => $student,
                'status' => $fee ? $fee->status : 'Unpaid',
                'amount' => $amount
            ];
        }

        return view('admin.manage_fee_status', compact(
            'classes', 'selectedClassId', 'months', 'selectedMonth', 'feeStatuses'
        ));
    }

    public function save(Request $request)
    {
        $classId = $request->input('class_id');
        $month = $request->input('month');
        $students = $request->input('students', []);

        foreach ($students as $studentData) {
            $studentID = $studentData['studentID'];
            $status = $studentData['status'];
            \App\Models\FeeStatus::updateOrCreate(
                [
                    'studentID' => $studentID,
                    'feestatus_month' => $month,
                ],
                [
                    'status' => $status,
                ]
            );
        }

        return redirect()->route('admin.manage_fee_status', [
            'class_id' => $classId,
            'month' => $month
        ])->with('success', 'Fee statuses updated successfully.');
    }

    // Dashboard: Payment status for current month, class, subject, teacher
    public function dashboardPaymentStatus(Request $request)
    {
        $user = session('user');
        $teacherID = $user->employeeID ?? null;
        // Use month name (e.g. 'July') for DB query
        $month = date('F');
        $classID = $request->input('classID');
        $subjectID = $request->input('subjectID');
        if (!$teacherID) {
            return response()->json([]);
        }
        $result = [];
        if ($classID && $subjectID) {
            // Old logic: specific class/subject
            $students = DB::table('student')
                ->where('classID', $classID)
                ->where('student_status', 1)
                ->where('verification_status', 'approved')
                ->get();
            $studentIDs = $students->pluck('studentID');
            $subjectStudentIDs = DB::table('student_subject')
                ->whereIn('studentID', $studentIDs)
                ->where('subjectID', $subjectID)
                ->pluck('studentID');
            $filteredStudents = $students->whereIn('studentID', $subjectStudentIDs);
            foreach ($filteredStudents as $student) {
                $fee = DB::table('fee_status')
                    ->where('studentID', $student->studentID)
                    ->where('feestatus_month', $month)
                    ->first();
                $result[] = [
                    'student_name' => $student->student_name,
                    'status' => $fee ? $fee->status : 'Unpaid',
                    'classID' => $student->classID
                ];
            }
        } else {
            // Only show students that the teacher actually teaches (class, subject, slot match)
            $classSubjects = DB::table('class_subject')
                ->where('employeeID', $teacherID)
                ->get();
            $classSubjectIDs = $classSubjects->pluck('classsubjectID')->all();
            $studentSubjects = DB::table('student_subject')
                ->whereIn('classsubjectID', $classSubjectIDs)
                ->get();
            $studentMap = [];
            foreach ($studentSubjects as $ss) {
                $student = DB::table('student')
                    ->where('studentID', $ss->studentID)
                    ->where('student_status', 1)
                    ->where('verification_status', 'approved')
                    ->first();
                if ($student) {
                    $classSubject = $classSubjects->firstWhere('classsubjectID', $ss->classsubjectID);
                    if ($classSubject) {
                        $studentMap[$classSubject->classID . '-' . $student->studentID] = [
                            'student_name' => $student->student_name,
                            'classID' => $classSubject->classID
                        ];
                    }
                }
            }
            foreach ($studentMap as $key => $info) {
                $parts = explode('-', $key);
                $studentID = $parts[1];
                $fee = DB::table('fee_status')
                    ->where('studentID', $studentID)
                    ->where('feestatus_month', $month)
                    ->first();
                $result[] = [
                    'student_name' => $info['student_name'],
                    'status' => $fee ? $fee->status : 'Unpaid',
                    'classID' => $info['classID']
                ];
            }
        }
        return response()->json($result);
    }

    // Get payment status for parent's children
    public function getParentChildrenPaymentStatus(Request $request)
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
            return response()->json([]);
        }

        // Get current month in YYYY-MM format
        $month = $request->input('month');
        if (!$month) {
            $now = new \DateTime();
            $month = $now->format('F'); // Current month name
        }

        // Get all children of this parent with their payment status
        $children = DB::table('student')
            ->leftJoin('fee_status', function($join) use ($month) {
                $join->on('student.studentID', '=', 'fee_status.studentID')
                     ->where('fee_status.feestatus_month', '=', $month);
            })
            ->where('student.parentID', $parentID)
            ->where('student.student_status', 1)
            ->where('student.verification_status', 'approved')
            ->select(
                'student.studentID',
                'student.student_name',
                'fee_status.status as payment_status'
            )
            ->get();

        $result = [];
        foreach ($children as $child) {
            $result[] = [
                'studentID' => $child->studentID,
                'student_name' => $child->student_name,
                'payment_status' => $child->payment_status ?: 'Unpaid' // Default to Unpaid if no record
            ];
        }

        return response()->json($result);
    }
}
