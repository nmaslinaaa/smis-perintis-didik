<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TwilioService;
use App\Models\ParentModel;
use App\Models\Student;
use App\Models\Attendance;
use App\Models\Performance;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class WhatsAppNotificationController extends Controller
{
    protected $twilioService;

    public function __construct(TwilioService $twilioService)
    {
        $this->twilioService = $twilioService;
    }

    /**
     * Send welcome message to new parent
     */
    public function sendWelcomeMessage($parentId)
    {
        try {
            $parent = ParentModel::find($parentId);

            if (!$parent || !$parent->phonenumber) {
                Log::warning('Parent not found or no phone number for welcome message', ['parent_id' => $parentId]);
                return false;
            }

            $phoneNumber = $this->formatPhoneNumber($parent->phonenumber);
            $result = $this->twilioService->sendWelcomeMessage($phoneNumber, $parent->name);

            if ($result) {
                Log::info('Welcome message sent successfully', ['parent_id' => $parentId, 'phone' => $phoneNumber]);
            } else {
                Log::error('Failed to send welcome message', ['parent_id' => $parentId, 'phone' => $phoneNumber]);
            }

            return $result;
        } catch (\Exception $e) {
            Log::error('Error sending welcome message', [
                'parent_id' => $parentId,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Send attendance notification
     */
    public function sendAttendanceNotification($attendanceId)
    {
        try {
            $attendance = Attendance::with(['student.parent', 'subject'])
                ->find($attendanceId);

            if (!$attendance || !$attendance->student || !$attendance->student->parent) {
                Log::warning('Attendance record not found or missing parent info', ['attendance_id' => $attendanceId]);
                return false;
            }

            $parent = $attendance->student->parent;
            $phoneNumber = $this->formatPhoneNumber($parent->phonenumber);

            if (!$phoneNumber) {
                Log::warning('No valid phone number for attendance notification', ['parent_id' => $parent->parentID]);
                return false;
            }

            $result = $this->twilioService->sendAttendanceNotification(
                $phoneNumber,
                $attendance->student->student_name,
                $attendance->subject->subject_name,
                $attendance->status,
                $attendance->attendance_date
            );

            if ($result) {
                Log::info('Attendance notification sent successfully', [
                    'attendance_id' => $attendanceId,
                    'student' => $attendance->student->student_name,
                    'phone' => $phoneNumber
                ]);
            } else {
                Log::error('Failed to send attendance notification', [
                    'attendance_id' => $attendanceId,
                    'phone' => $phoneNumber
                ]);
            }

            return $result;
        } catch (\Exception $e) {
            Log::error('Error sending attendance notification', [
                'attendance_id' => $attendanceId,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Send performance notification
     */
    public function sendPerformanceNotification($performanceId)
    {
        try {
            $performance = Performance::with(['student.parent', 'subject'])
                ->find($performanceId);

            if (!$performance || !$performance->student || !$performance->student->parent) {
                Log::warning('Performance record not found or missing parent info', ['performance_id' => $performanceId]);
                return false;
            }

            $parent = $performance->student->parent;
            $phoneNumber = $this->formatPhoneNumber($parent->phonenumber);

            if (!$phoneNumber) {
                Log::warning('No valid phone number for performance notification', ['parent_id' => $parent->parentID]);
                return false;
            }

            $result = $this->twilioService->sendPerformanceNotification(
                $phoneNumber,
                $performance->student->student_name,
                $performance->subject->subject_name,
                $performance->test_score,
                $performance->performance_status,
                $performance->performance_month
            );

            if ($result) {
                Log::info('Performance notification sent successfully', [
                    'performance_id' => $performanceId,
                    'student' => $performance->student->student_name,
                    'phone' => $phoneNumber
                ]);
            } else {
                Log::error('Failed to send performance notification', [
                    'performance_id' => $performanceId,
                    'phone' => $phoneNumber
                ]);
            }

            return $result;
        } catch (\Exception $e) {
            Log::error('Error sending performance notification', [
                'performance_id' => $performanceId,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Send payment reminder
     */
    public function sendPaymentReminder($studentId, $month, $amount = null)
    {
        try {
            $student = Student::with(['parent', 'class'])->find($studentId);

            if (!$student || !$student->parent) {
                Log::warning('Student not found or missing parent info', ['student_id' => $studentId]);
                return false;
            }

            $parent = $student->parent;
            $phoneNumber = $this->formatPhoneNumber($parent->phonenumber);

            if (!$phoneNumber) {
                Log::warning('No valid phone number for payment reminder', ['parent_id' => $parent->parentID]);
                return false;
            }

            // If amount is not provided, calculate it
            if (!$amount) {
                $amount = $this->calculateStudentFee($studentId);
            }

            $result = $this->twilioService->sendPaymentReminder(
                $phoneNumber,
                $student->student_name,
                number_format($amount, 2),
                $month
            );

            if ($result) {
                Log::info('Payment reminder sent successfully', [
                    'student_id' => $studentId,
                    'student' => $student->student_name,
                    'phone' => $phoneNumber,
                    'amount' => $amount
                ]);
            } else {
                Log::error('Failed to send payment reminder', [
                    'student_id' => $studentId,
                    'phone' => $phoneNumber
                ]);
            }

            return $result;
        } catch (\Exception $e) {
            Log::error('Error sending payment reminder', [
                'student_id' => $studentId,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Send student registration confirmation
     */
    public function sendStudentRegistrationConfirmation($studentId)
    {
        try {
            $student = Student::with(['parent', 'class'])->find($studentId);

            if (!$student || !$student->parent) {
                Log::warning('Student not found or missing parent info', ['student_id' => $studentId]);
                return false;
            }

            $parent = $student->parent;
            $phoneNumber = $this->formatPhoneNumber($parent->phonenumber);

            if (!$phoneNumber) {
                Log::warning('No valid phone number for registration confirmation', ['parent_id' => $parent->parentID]);
                return false;
            }

            $result = $this->twilioService->sendStudentRegistrationConfirmation(
                $phoneNumber,
                $student->student_name,
                $student->class->class_name
            );

            if ($result) {
                Log::info('Student registration confirmation sent successfully', [
                    'student_id' => $studentId,
                    'student' => $student->student_name,
                    'phone' => $phoneNumber
                ]);
            } else {
                Log::error('Failed to send student registration confirmation', [
                    'student_id' => $studentId,
                    'phone' => $phoneNumber
                ]);
            }

            return $result;
        } catch (\Exception $e) {
            Log::error('Error sending student registration confirmation', [
                'student_id' => $studentId,
                'error' => $e->getMessage()
            ]);
            return false;
        }
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
     * Calculate student fee amount
     */
    private function calculateStudentFee($studentId)
    {
        try {
            $totalFee = DB::table('student_subject')
                ->join('class_subject', 'student_subject.classsubjectID', '=', 'class_subject.classsubjectID')
                ->where('student_subject.studentID', $studentId)
                ->sum('class_subject.subject_price');

            return $totalFee ?: 0;
        } catch (\Exception $e) {
            Log::error('Error calculating student fee', [
                'student_id' => $studentId,
                'error' => $e->getMessage()
            ]);
            return 0;
        }
    }

    /**
     * Test WhatsApp message sending
     */
    public function testWhatsApp(Request $request)
    {
        $request->validate([
            'phone_number' => 'required|string',
            'message' => 'required|string'
        ]);

        try {
            $phoneNumber = $this->formatPhoneNumber($request->phone_number);
            $result = $this->twilioService->sendWhatsAppMessage($phoneNumber, $request->message);

            if ($result) {
                return response()->json([
                    'success' => true,
                    'message' => 'Test message sent successfully',
                    'phone' => $phoneNumber
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to send test message',
                    'phone' => $phoneNumber
                ], 500);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error sending test message: ' . $e->getMessage()
            ], 500);
        }
    }
}
