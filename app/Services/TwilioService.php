<?php

namespace App\Services;

use Twilio\Rest\Client;
use Illuminate\Support\Facades\Log;

class TwilioService
{
    protected $client;
    protected $fromNumber;

    public function __construct()
    {
        $sid = config('services.twilio.sid');
        $token = config('services.twilio.token');
    
        if (!$sid || !$token) {
            \Log::error('Twilio credentials missing');
            return;
        }
    
        $this->client = new \Twilio\Rest\Client($sid, $token);
        $this->fromNumber = config('services.twilio.whatsapp_number');
    }

    /**
     * Send WhatsApp message
     *
     * @param string $toNumber Phone number in format: +1234567890
     * @param string $message Message content
     * @return bool
     */
    public function sendWhatsAppMessage($toNumber, $message)
    {
        try {
            // Format the number for WhatsApp
            $whatsappNumber = 'whatsapp:' . $toNumber;

            $message = $this->client->messages->create(
                $whatsappNumber,
                [
                    'from' => $this->fromNumber,
                    'body' => $message
                ]
            );

            Log::info('WhatsApp message sent successfully', [
                'to' => $toNumber,
                'message_sid' => $message->sid,
                'status' => $message->status
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send WhatsApp message', [
                'to' => $toNumber,
                'error' => $e->getMessage()
            ]);

            return false;
        }
    }

    /**
     * Send attendance notification to parent
     *
     * @param string $parentPhone Parent's phone number
     * @param string $studentName Student's name
     * @param string $subject Subject name
     * @param string $status Attendance status
     * @param string $date Date of attendance
     * @return bool
     */
    public function sendAttendanceNotification($parentPhone, $studentName, $subject, $status, $date)
    {
        $message = "📚 *Attendance Update*\n\n";
        $message .= "Student: *{$studentName}*\n";
        $message .= "Subject: *{$subject}*\n";
        $message .= "Date: *{$date}*\n";
        $message .= "Status: *{$status}*\n\n";
        $message .= "Thank you for your attention.\n";
        $message .= "Pusat Tuisyen Perintis Didik";

        return $this->sendWhatsAppMessage($parentPhone, $message);
    }

    /**
     * Send performance notification to parent
     *
     * @param string $parentPhone Parent's phone number
     * @param string $studentName Student's name
     * @param string $subject Subject name
     * @param string $score Test score
     * @param string $status Performance status
     * @param string $month Month
     * @return bool
     */
    public function sendPerformanceNotification($parentPhone, $studentName, $subject, $score, $status, $month)
    {
        $message = "📊 *Performance Update*\n\n";
        $message .= "Student: *{$studentName}*\n";
        $message .= "Subject: *{$subject}*\n";
        $message .= "Month: *{$month}*\n";
        $message .= "Score: *{$score}%*\n";
        $message .= "Status: *{$status}*\n\n";
        $message .= "Keep up the great work!\n";
        $message .= "Pusat Tuisyen Perintis Didik";

        return $this->sendWhatsAppMessage($parentPhone, $message);
    }

    /**
     * Send payment reminder to parent
     *
     * @param string $parentPhone Parent's phone number
     * @param string $studentName Student's name
     * @param string $amount Amount due
     * @param string $month Month
     * @return bool
     */
    public function sendPaymentReminder($parentPhone, $studentName, $amount, $month)
    {
        $message = "💰 *Payment Reminder*\n\n";
        $message .= "Student: *{$studentName}*\n";
        $message .= "Month: *{$month}*\n";
        $message .= "Amount Due: *RM {$amount}*\n\n";
        $message .= "Please settle your payment to avoid any inconvenience.\n";
        $message .= "Thank you for your cooperation.\n";
        $message .= "Pusat Tuisyen Perintis Didik";

        return $this->sendWhatsAppMessage($parentPhone, $message);
    }

    /**
     * Send welcome message to new parent
     *
     * @param string $parentPhone Parent's phone number
     * @param string $parentName Parent's name
     * @return bool
     */
    public function sendWelcomeMessage($parentPhone, $parentName)
    {
        $message = "🎉 *Welcome to Pusat Tuisyen Perintis Didik!*\n\n";
        $message .= "Dear *{$parentName}*,\n\n";
        $message .= "Thank you for registering with us. We're excited to have you and your child as part of our learning community!\n\n";
        $message .= "You will receive notifications about:\n";
        $message .= "• Attendance updates\n";
        $message .= "• Performance reports\n";
        $message .= "• Payment reminders\n\n";
        $message .= "If you have any questions, please don't hesitate to contact us.\n\n";
        $message .= "Best regards,\n";
        $message .= "Pusat Tuisyen Perintis Didik Team";

        return $this->sendWhatsAppMessage($parentPhone, $message);
    }

    /**
     * Send student registration confirmation
     *
     * @param string $parentPhone Parent's phone number
     * @param string $studentName Student's name
     * @param string $className Class name
     * @return bool
     */
    public function sendStudentRegistrationConfirmation($parentPhone, $studentName, $className)
    {
        $message = "✅ *Student Registration Confirmed*\n\n";
        $message .= "Student: *{$studentName}*\n";
        $message .= "Class: *{$className}*\n";
        $message .= "Status: *Registration Successful*\n\n";
        $message .= "Your child has been successfully registered. You will receive updates about their progress.\n\n";
        $message .= "Thank you for choosing Pusat Tuisyen Perintis Didik!";

        return $this->sendWhatsAppMessage($parentPhone, $message);
    }

    /**
     * Send student approval notification with more details
     *
     * @param string $parentPhone Parent's phone number
     * @param string $studentName Student's name
     * @param string $className Class name
     * @param string $startDate Start date
     * @param array $subjects Enrolled subjects
     * @return bool
     */
    public function sendStudentApprovalNotification($parentPhone, $studentName, $className, $startDate = null, $subjects = [])
    {
        $message = "🎉 *Welcome to Pusat Tuisyen Perintis Didik!*\n\n";
        $message .= "Dear Parent,\n\n";
        $message .= "We are pleased to inform you that your child's registration has been *APPROVED*!\n\n";
        $message .= "📋 *Registration Details:*\n";
        $message .= "Student: *{$studentName}*\n";
        $message .= "Class: *{$className}*\n";

        if ($startDate) {
            $message .= "Start Date: *{$startDate}*\n";
        }

        if (!empty($subjects)) {
            $message .= "Enrolled Subjects:\n";
            foreach ($subjects as $subject) {
                $message .= "• *{$subject}*\n";
            }
        }

        $message .= "\n📚 *What's Next:*\n";
        $message .= "• Your child can now attend classes\n";
        $message .= "• You'll receive attendance updates\n";
        $message .= "• Performance reports will be shared\n";
        $message .= "• Payment reminders will be sent\n\n";
        $message .= "📞 *Contact Us:*\n";
        $message .= "If you have any questions, please don't hesitate to contact us.\n\n";
        $message .= "Welcome to our learning community!\n";
        $message .= "Pusat Tuisyen Perintis Didik Team";

        return $this->sendWhatsAppMessage($parentPhone, $message);
    }

    /**
     * Send WhatsApp message with PDF attachment
     *
     * @param string $toNumber Phone number in format: +1234567890
     * @param string $message Message content
     * @param string $pdfUrl Public URL of the PDF file
     * @return bool
     */
    public function sendWhatsAppMessageWithPDF($toNumber, $message, $pdfUrl)
    {
        try {
            // Format the number for WhatsApp
            $whatsappNumber = 'whatsapp:' . $toNumber;

            $message = $this->client->messages->create(
                $whatsappNumber,
                [
                    'from' => $this->fromNumber,
                    'body' => $message,
                    'mediaUrl' => [$pdfUrl]
                ]
            );

            Log::info('WhatsApp message with PDF sent successfully', [
                'to' => $toNumber,
                'pdf_url' => $pdfUrl,
                'message_sid' => $message->sid,
                'status' => $message->status
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send WhatsApp message with PDF', [
                'to' => $toNumber,
                'pdf_url' => $pdfUrl,
                'error' => $e->getMessage()
            ]);

            return false;
        }
    }

    /**
     * Send student report PDF to parent
     *
     * @param string $parentPhone Parent's phone number
     * @param string $studentName Student's name
     * @param string $month Report month
     * @param string $pdfUrl Public URL of the PDF file
     * @return bool
     */
    public function sendStudentReportPDF($parentPhone, $studentName, $month, $pdfUrl)
    {
        $message = "📋 *Student Report Card*\n\n";
        $message .= "Dear Parent,\n\n";
        $message .= "Please find attached the report card for:\n";
        $message .= "Student: *{$studentName}*\n";
        $message .= "Period: *{$month} 2024*\n\n";
        $message .= "This report contains:\n";
        $message .= "• Academic performance\n";
        $message .= "• Attendance records\n";
        $message .= "• Fee status\n\n";
        $message .= "If you have any questions, please contact us.\n\n";
        $message .= "Best regards,\n";
        $message .= "Pusat Tuisyen Perintis Didik";

        return $this->sendWhatsAppMessageWithPDF($parentPhone, $message, $pdfUrl);
    }

    /**
     * Send SMS message (alternative to WhatsApp)
     *
     * @param string $toNumber Phone number in format: +1234567890
     * @param string $message Message content
     * @return bool
     */
    public function sendSMSMessage($toNumber, $message)
    {
        try {
            $message = $this->client->messages->create(
                $toNumber,
                [
                    'from' => '+14155238886', // Your Twilio phone number
                    'body' => $message
                ]
            );

            Log::info('SMS message sent successfully', [
                'to' => $toNumber,
                'message_sid' => $message->sid,
                'status' => $message->status
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send SMS message', [
                'to' => $toNumber,
                'error' => $e->getMessage()
            ]);

            return false;
        }
    }

    /**
     * Send attendance notification via SMS
     */
    public function sendAttendanceNotificationSMS($parentPhone, $studentName, $subject, $status, $date)
    {
        $message = "Attendance Update\n\n";
        $message .= "Student: {$studentName}\n";
        $message .= "Subject: {$subject}\n";
        $message .= "Date: {$date}\n";
        $message .= "Status: {$status}\n\n";
        $message .= "Thank you for your attention.\n";
        $message .= "Pusat Tuisyen Perintis Didik";

        return $this->sendSMSMessage($parentPhone, $message);
    }
}
