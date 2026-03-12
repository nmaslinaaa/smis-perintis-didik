<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ParentModel;
use App\Services\TwilioService;
use Illuminate\Support\Facades\Log;

class ManageParentController extends Controller
{
    protected $twilioService;

    public function __construct(TwilioService $twilioService)
    {
        $this->twilioService = $twilioService;
    }

    public function manageParentLogin()
    {
        $parents = ParentModel::all();
        return view('admin.manage_parent_login', compact('parents'));
    }

    public function updateParentLogin(Request $request, $id)
    {
        $request->validate([
            'user_name' => 'required',
            'password' => 'required',
        ]);
        $parent = ParentModel::findOrFail($id);
        $parent->user_name = $request->input('user_name');
        $parent->password = $request->input('password');
        $parent->save();
        return response()->json(['success' => true, 'message' => 'Parent login updated successfully!']);
    }

    public function updateAllParentLogins(Request $request)
    {
        $data = $request->input('parents', []);
        $updated = 0;
        foreach ($data as $row) {
            if (!empty($row['id']) && !empty($row['user_name']) && !empty($row['password'])) {
                $parent = ParentModel::find($row['id']);
                if ($parent) {
                    $parent->user_name = $row['user_name'];
                    $parent->password = $row['password'];
                    $parent->save();
                    $updated++;
                }
            }
        }
        return response()->json(['success' => true, 'message' => "$updated parent(s) updated."]);
    }

    /**
     * Send WhatsApp notification with login credentials
     */
    public function sendLoginCredentials(Request $request, $id)
    {
        try {
            $parent = ParentModel::findOrFail($id);

            if (!$parent->phonenumber) {
                return response()->json([
                    'success' => false,
                    'message' => 'No phone number found for this parent'
                ], 400);
            }

            $phoneNumber = $this->formatPhoneNumber($parent->phonenumber);

            $message = "🔐 *Login Credentials - Pusat Tuisyen Perintis Didik*\n\n";
            $message .= "Dear *{$parent->name}*,\n\n";
            $message .= "Here are your login credentials for the parent portal:\n\n";
            $message .= "📱 *Username:* `{$parent->user_name}`\n";
            $message .= "🔑 *Password:* `{$parent->password}`";

            $result = $this->twilioService->sendWhatsAppMessage($phoneNumber, $message);

            if ($result) {
                Log::info('Login credentials WhatsApp notification sent successfully', [
                    'parent_id' => $parent->parentID,
                    'parent_name' => $parent->name,
                    'phone' => $phoneNumber
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Login credentials sent successfully via WhatsApp',
                    'parent_name' => $parent->name,
                    'phone' => $phoneNumber
                ]);
            } else {
                Log::error('Failed to send login credentials WhatsApp notification', [
                    'parent_id' => $parent->parentID,
                    'phone' => $phoneNumber
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Failed to send WhatsApp notification'
                ], 500);
            }
        } catch (\Exception $e) {
            Log::error('Error sending login credentials WhatsApp notification', [
                'parent_id' => $id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
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
}
