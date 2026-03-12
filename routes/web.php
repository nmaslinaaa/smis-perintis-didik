<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ManageEmployeeController;
use App\Http\Controllers\ManageClassController;
use App\Http\Controllers\ManageSubjectController;
use App\Http\Controllers\ManageProfileController;
use App\Http\Controllers\ManageStudentController;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\ManageSyllabusController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('login');
});

// Route asal dibuang dan diganti dengan route controller
Route::get('/admin/all_employee', [ManageEmployeeController::class, 'allEmployee']);

Route::get('/admin/dashboard', [App\Http\Controllers\AdminController::class, 'dashboard']);
Route::post('/admin/performance-data', [App\Http\Controllers\AdminController::class, 'getPerformanceData']);
Route::post('/admin/attendance-data', [App\Http\Controllers\AdminController::class, 'getAttendanceData']);
Route::get('/admin/class/{classID}/subjects', [App\Http\Controllers\AdminController::class, 'getSubjectsByClass']);
Route::get('/admin/test-attendance', [App\Http\Controllers\AdminController::class, 'testAttendance']);
Route::get('/admin/add-sample-attendance', [App\Http\Controllers\AdminController::class, 'addSampleAttendanceData']);
Route::post('/admin/payment-status-data', [App\Http\Controllers\AdminController::class, 'getPaymentStatusData']);
Route::post('/admin/generate-student-report', [App\Http\Controllers\AdminController::class, 'generateStudentReport']);
Route::get('/admin/generate_report', [App\Http\Controllers\AdminController::class, 'generateReportPage'])->name('admin.generate.report');
Route::get('/admin/student-report-card/{studentID}/{classID}/{month}', [App\Http\Controllers\AdminController::class, 'studentReportCard'])->name('admin.student.report.card');



Route::get('/admin/edit_employee/{id}', [ManageEmployeeController::class, 'editEmployee']);

Route::get('/admin/add_new_employee', function () {
    return view('admin.add_new_employee');
});

Route::post('/admin/add_new_employee', [ManageEmployeeController::class, 'addNewEmployee']);

Route::get('/admin/get-existing-ic-numbers', [ManageEmployeeController::class, 'getExistingICNumbers']);

Route::get('/admin/manage_employee_login', [App\Http\Controllers\ManageEmployeeController::class, 'manageEmployeeLogin']);

Route::get('/admin/verify_new_student', function () {
    return view('admin.verify_new_student');
});

Route::get('/admin/verify_new_student/{id}', [ManageStudentController::class, 'verifyNewStudent'])->name('admin.verify_new_student');

Route::get('/admin/all_student', [ManageStudentController::class, 'allStudents'])->name('admin.all_student');

Route::get('/admin/all_new_student', [ManageStudentController::class, 'listNewStudents'])->name('admin.all_new_student');

Route::get('/admin/manage_parent_login', [\App\Http\Controllers\ManageParentController::class, 'manageParentLogin']);

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::get('/logout', [LoginController::class, 'logout']);

// Signup routes
Route::get('/signup', [LoginController::class, 'showSignupForm'])->name('signup');
Route::post('/signup', [LoginController::class, 'signup']);

Route::get('/admin/manage_classes', [ManageClassController::class, 'manageClasses']);

Route::get('/admin/add_new_classes', [ManageClassController::class, 'createClass']);
Route::post('/admin/add_new_classes', [ManageClassController::class, 'storeClass']);

Route::get('/admin/manage_subjects', [ManageSubjectController::class, 'index'])->name('admin.manage_subjects');

Route::get('/admin/add_new_subjects', [ManageSubjectController::class, 'createSubject']);
Route::post('/admin/add_new_subjects', [ManageSubjectController::class, 'storeSubject']);

Route::get('/admin/manage_fee_status', [App\Http\Controllers\ManageFeeStatusController::class, 'index'])->name('admin.manage_fee_status');

Route::post('/admin/manage_fee_status/save', [App\Http\Controllers\ManageFeeStatusController::class, 'save'])->name('admin.manage_fee_status.save');

Route::get('/admin/view_attendance', [App\Http\Controllers\StudentAttendanceController::class, 'viewAttendance']);

Route::get('/admin/view_performance', [App\Http\Controllers\StudentPerformanceController::class, 'adminViewPerformance'])->name('admin.view_performance');



// Teacher Dashboard Route
Route::get('/teacher/dashboard', function () {
    return view('teacher.teacher_dashboard');
});

Route::get('/teacher/evaluate_performance', [App\Http\Controllers\StudentPerformanceController::class, 'evaluatePerformance'])->name('teacher.evaluate_performance');
Route::get('/teacher/view_performance', function () {
    return view('teacher.view_performance');
});

Route::get('/teacher/record_attendance', [App\Http\Controllers\StudentAttendanceController::class, 'recordAttendance']);

Route::post('/teacher/record_attendance/save', [App\Http\Controllers\StudentAttendanceController::class, 'saveAttendance']);

Route::post('/teacher/send_attendance_notification', [App\Http\Controllers\StudentAttendanceController::class, 'sendAttendanceNotification'])->name('teacher.send_attendance_notification');

Route::get('/teacher/view_attendance', function () {
    return view('teacher.view_attendance');
});
Route::get('/teacher/syllabus_coverage', [App\Http\Controllers\ManageSyllabusController::class, 'syllabusCoverage'])->name('teacher.syllabus_coverage');

Route::get('/teacher/evaluate_student_performance', [App\Http\Controllers\StudentPerformanceController::class, 'evaluateStudentPerformance'])->name('teacher.evaluate_student_performance');
Route::post('/teacher/save_performance', [App\Http\Controllers\StudentPerformanceController::class, 'savePerformance'])->name('teacher.save_performance');

Route::get('/teacher/manage_syllabus', [ManageSyllabusController::class, 'index']);
Route::post('/teacher/manage_syllabus/update', [App\Http\Controllers\ManageSyllabusController::class, 'update']);
Route::post('/teacher/manage_syllabus/remove', [App\Http\Controllers\ManageSyllabusController::class, 'remove'])->name('teacher.syllabus.remove');

//Parent Dashbaord route
Route::get('/parent/dashboard', [App\Http\Controllers\ManageStudentController::class, 'parentDashboard']);

Route::get('/parent/register_children', [ManageStudentController::class, 'showRegisterForm']);
Route::post('/parent/register_children', [ManageStudentController::class, 'registerChild']);
Route::get('/parent/class/{classID}/subjects', [App\Http\Controllers\ManageStudentController::class, 'getSubjectsByClass']);
Route::get('/parent/my_child', [App\Http\Controllers\ManageStudentController::class, 'myChildList']);
Route::get('/parent/child_verification_status', function () {
    $user = session('user');
    if (!$user) {
        return redirect('/login');
    }
    $parentID = null;
    if (is_object($user) && isset($user->parentID)) {
        $parentID = $user->parentID;
    } elseif (is_array($user) && isset($user['parentID'])) {
        $parentID = $user['parentID'];
    } elseif (session()->has('user_id')) {
        $parentID = session('user_id');
    }
    if (!$parentID) {
        return redirect('/login');
    }
    $children = \App\Models\Student::where('parentID', $parentID)->get();
    return view('parent.child_verification_status', compact('children'));
});

Route::put('/admin/update_employee/{id}', [ManageEmployeeController::class, 'updateEmployee']);
Route::delete('/admin/delete_employee/{id}', [ManageEmployeeController::class, 'deleteEmployee']);
Route::post('/admin/update_employee_login/{id}', [App\Http\Controllers\ManageEmployeeController::class, 'updateEmployeeLogin']);

Route::get('/admin/edit_class/{id}', [ManageClassController::class, 'editClass']);
Route::post('/admin/edit_class/{id}/update', [ManageClassController::class, 'updateClass']);
Route::delete('/admin/delete_class/{id}', [ManageClassController::class, 'deleteClass']);

Route::get('/admin/edit_subject/{subjectID}', [ManageSubjectController::class, 'editSubject'])->name('admin.edit_subject');
Route::post('/admin/update_subject/{subjectID}', [ManageSubjectController::class, 'updateSubject'])->name('admin.update_subject');
Route::post('/admin/delete_subject/{subjectID}', [App\Http\Controllers\ManageSubjectController::class, 'deleteSubject'])->name('admin.delete_subject');

Route::get('/manage_profile', [ManageProfileController::class, 'show']);
Route::post('/update_manage_profile', [App\Http\Controllers\ManageProfileController::class, 'update']);

Route::get('/edit_manage_profile', function () {
    $user = session('user');
    if (!$user) {
        return redirect('/login');
    }
    $profile = null;
    $role = null;
    $children = null;

    if ($user->group_level == 1 || $user->group_level == 2) { // 1=Admin, 2=Teacher
        $profile = \App\Models\Employee::where('user_name', $user->user_name)->first();
        $role = $user->group_level == 1 ? 'Administrator' : 'Teacher';
    } elseif ($user->group_level == 3) { // Parent
        $profile = \App\Models\ParentModel::where('user_name', $user->user_name)->first();
        $role = 'Parent';
        $children = $profile ? $profile->students()->with(['class', 'subjects', 'studentSubjects'])->get() : collect();
    }

    return view('edit_manage_profile', compact('profile', 'role', 'children'));
});

Route::post('/update_children_from_parent', [App\Http\Controllers\ManageStudentController::class, 'updateChildrenFromParent']);
Route::post('/admin/approve_new_student/{id}', [ManageStudentController::class, 'approveNewStudent'])->name('admin.approve_new_student');
Route::post('/admin/reject_new_student/{id}', [ManageStudentController::class, 'rejectNewStudent'])->name('admin.reject_new_student');
Route::get('/admin/view_student_information/{id}', [ManageStudentController::class, 'viewStudentInformation'])->name('admin.view_student_information');
Route::get('/admin/edit_student_information/{id}', [ManageStudentController::class, 'editStudentInformation'])->name('admin.edit_student_information');
Route::post('/admin/update_student_information/{id}', [ManageStudentController::class, 'updateStudentInformation'])->name('admin.update_student_information');
Route::delete('/admin/delete_student/{id}', [ManageStudentController::class, 'deleteStudent'])->name('admin.delete_student');
Route::post('/admin/update_parent_login/{id}', [App\Http\Controllers\ManageParentController::class, 'updateParentLogin'])->name('admin.update_parent_login');
Route::post('/admin/update_all_parent_logins', [App\Http\Controllers\ManageParentController::class, 'updateAllParentLogins'])->name('admin.update_all_parent_logins');
Route::post('/admin/send_parent_login_credentials/{id}', [App\Http\Controllers\ManageParentController::class, 'sendLoginCredentials'])->name('admin.send_parent_login_credentials');
Route::post('/admin/send_employee_login_credentials/{id}', [App\Http\Controllers\ManageEmployeeController::class, 'sendLoginCredentials'])->name('admin.send_employee_login_credentials');
Route::post('/admin/send-report-pdf/{studentID}/{classID}/{month}', [App\Http\Controllers\AdminController::class, 'sendReportPDFToParent'])->name('admin.send_report_pdf');
Route::get('/admin/teacher-available-times', [App\Http\Controllers\ManageSubjectController::class, 'teacherAvailableTimes']);

// AJAX route for teacher clash check
Route::get('/api/check-teacher-clash', function(Illuminate\Http\Request $request) {
    $employeeID = $request->employeeID;
    $days = $request->days;
    $start_time = $request->start_time;
    $end_time = $request->end_time;
    $excludeID = $request->excludeID; // for edit mode, to exclude current assignment

    $query = DB::table('class_subject')
        ->where('employeeID', $employeeID)
        ->where('days', $days)
        ->where(function($q) use ($start_time, $end_time) {
            $q->whereBetween('start_time', [$start_time, $end_time])
              ->orWhereBetween('end_time', [$start_time, $end_time])
              ->orWhere(function($qq) use ($start_time, $end_time) {
                  $qq->where('start_time', '<=', $start_time)
                     ->where('end_time', '>=', $end_time);
              });
        });
    if ($excludeID) {
        $query->where('classsubjectID', '!=', $excludeID);
    }
    $clash = $query->exists();
    return response()->json(['clash' => $clash]);
});

Route::get('/parent/view_child_performance', [App\Http\Controllers\StudentPerformanceController::class, 'parentViewChildPerformance'])->name('parent.view_child_performance');
Route::get('/parent/view_child_attendance', [App\Http\Controllers\StudentAttendanceController::class, 'parentViewChildAttendance'])->name('parent.view_child_attendance');
Route::post('/teacher/syllabus_coverage/update_status', [App\Http\Controllers\ManageSyllabusController::class, 'updateSyllabusStatus'])->name('teacher.syllabus_coverage.update_status');

// Syllabus coverage AJAX for dashboard
Route::get('/teacher/syllabus_coverage/options', [App\Http\Controllers\ManageSyllabusController::class, 'syllabusCoverageOptions']);
Route::post('/teacher/syllabus_coverage/percent', [App\Http\Controllers\ManageSyllabusController::class, 'syllabusCoveragePercent']);

Route::post('/teacher/dashboard/attendance_summary', [App\Http\Controllers\StudentAttendanceController::class, 'dashboardAttendanceSummary']);
Route::post('/teacher/dashboard/payment_status', [App\Http\Controllers\ManageFeeStatusController::class, 'dashboardPaymentStatus']);
Route::post('/teacher/dashboard/performance', [App\Http\Controllers\StudentPerformanceController::class, 'dashboardPerformance']);

Route::post('/parent/child_performance', [App\Http\Controllers\ManageStudentController::class, 'getChildPerformance']);
Route::post('/parent/child_attendance', [App\Http\Controllers\StudentAttendanceController::class, 'getChildAttendance']);
Route::post('/parent/payment_status', [App\Http\Controllers\ManageFeeStatusController::class, 'getParentChildrenPaymentStatus']);

// WhatsApp Notification Routes
Route::post('/whatsapp/test', [App\Http\Controllers\WhatsAppNotificationController::class, 'testWhatsApp'])->name('whatsapp.test');
Route::post('/whatsapp/welcome/{parentId}', [App\Http\Controllers\WhatsAppNotificationController::class, 'sendWelcomeMessage'])->name('whatsapp.welcome');
Route::post('/whatsapp/attendance/{attendanceId}', [App\Http\Controllers\WhatsAppNotificationController::class, 'sendAttendanceNotification'])->name('whatsapp.attendance');
Route::post('/whatsapp/performance/{performanceId}', [App\Http\Controllers\WhatsAppNotificationController::class, 'sendPerformanceNotification'])->name('whatsapp.performance');
Route::post('/whatsapp/payment-reminder/{studentId}', [App\Http\Controllers\WhatsAppNotificationController::class, 'sendPaymentReminder'])->name('whatsapp.payment-reminder');
Route::post('/whatsapp/registration/{studentId}', [App\Http\Controllers\WhatsAppNotificationController::class, 'sendStudentRegistrationConfirmation'])->name('whatsapp.registration');

// WhatsApp Test Page
Route::get('/admin/whatsapp-test', function () {
    return view('admin.whatsapp_test');
})->name('admin.whatsapp.test');

// Test student approval notification
Route::post('/admin/test-student-approval/{studentId}', [App\Http\Controllers\ManageStudentController::class, 'testStudentApprovalNotification'])->name('admin.test.student.approval');





