<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use App\Services\TwilioService;
use Illuminate\Support\Facades\Log;

class ManageEmployeeController extends Controller
{
    protected $twilioService;

    public function __construct(TwilioService $twilioService)
    {
        $this->twilioService = $twilioService;
    }

    public function allEmployee()
    {
        $employees = Employee::all();
        return view('admin.all_employee', compact('employees'));
    }

    // (Optional) You may include edit, update, delete methods later

    public function editEmployee($id)
    {
        $employee = Employee::findOrFail($id);
        return view('admin.edit_employee', compact('employee'));
    }

    public function updateEmployee($id, Request $request)
    {
        $employee = Employee::findOrFail($id);

        $request->validate([
            'firstname' => 'required',
            'lastname' => 'required',
            'ic_number' => 'required|string|max:20|unique:employee,ic_number,' . $id . ',employeeID',
            'phonenumber' => 'nullable|string|max:15',
            'address' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:150',
            'group_level' => 'required',
        ], [
            'ic_number.unique' => 'An employee with this IC number already exists. Please check the IC number and try again.',
        ]);

        // Check if IC number already exists (excluding current employee)
        $existingEmployee = Employee::where('ic_number', $request->input('ic_number'))
            ->where('employeeID', '!=', $id)
            ->first();
        if ($existingEmployee) {
            return redirect()->back()->withErrors(['ic_number' => 'An employee with this IC number already exists. Please check the IC number and try again.'])->withInput();
        }

        $employee->firstname = $request->input('firstname');
        $employee->lastname = $request->input('lastname');
        $employee->ic_number = $request->input('ic_number');
        $employee->phonenumber = $request->input('phonenumber');
        $employee->address = $request->input('address');
        $employee->email = $request->input('email');
        $employee->group_level = $request->input('group_level');

        // Replace profile function
        if ($request->hasFile('profile_picture')) {
            $file = $request->file('profile_picture');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/profile_pictures'), $filename);
            $employee->profile_picture = $filename;
        }

        $employee->save();

        return redirect('/admin/all_employee')->with('success', 'Employee updated successfully!');
    }

    // Add new employee
    public function addNewEmployee(Request $request)
    {
        $request->validate([
            'firstname' => 'required',
            'lastname' => 'required',
            'group_level' => 'required',
            'user_name' => 'required',
            'password' => 'required',
            'ic_number' => 'required|string|max:20|unique:employee,ic_number',
        ], [
            'ic_number.unique' => 'An employee with this IC number already exists. Please check the IC number and try again.',
        ]);

        // Check if IC number already exists (additional validation)
        $existingEmployee = Employee::where('ic_number', $request->input('ic_number'))->first();
        if ($existingEmployee) {
            return redirect()->back()->withErrors(['ic_number' => 'An employee with this IC number already exists. Please check the IC number and try again.'])->withInput();
        }

        $employee = new Employee();
        $employee->firstname = $request->input('firstname');
        $employee->lastname = $request->input('lastname');
        $employee->group_level = $request->input('group_level');
        $employee->user_name = $request->input('user_name');
        $employee->password = $request->input('password');

        // Optional fields, set to empty string if not provided or if null
        $employee->ic_number = $request->input('ic_number');
        $employee->phonenumber = $request->input('phonenumber') ?? '';
        $employee->address = $request->input('address') ?? '';
        $employee->email = $request->input('email') ?? '';
        $employee->educational_background = $request->input('educational_background') ?? '';
        $employee->current_job = $request->input('current_job') ?? '';

        if ($request->hasFile('profile_picture')) {
            $file = $request->file('profile_picture');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/profile_pictures'), $filename);
            $employee->profile_picture = $filename;
        }

        $employee->save();

        return redirect('/admin/all_employee')->with('success', 'Employee added successfully!');
    }

    // Delete employee
    public function deleteEmployee($id)
    {
        $employee = Employee::findOrFail($id);
        $employee->delete();
        return redirect('/admin/all_employee')->with('success', 'Employee deleted successfully!');
    }

    public function manageEmployeeLogin()
    {
        $employees = Employee::all();
        return view('admin.manage_employee_login', compact('employees'));
    }

    public function updateEmployeeLogin($id, Request $request)
    {
        $request->validate([
            'user_name' => 'required',
            'password' => 'required',
        ]);
        $employee = Employee::findOrFail($id);
        $employee->user_name = $request->input('user_name');
        $employee->password = $request->input('password');
        $employee->save();
        return response()->json(['success' => true, 'message' => 'Employee login updated successfully!']);
    }

    /**
     * Get existing IC numbers for frontend validation
     */
    public function getExistingICNumbers()
    {
        $icNumbers = Employee::whereNotNull('ic_number')
            ->where('ic_number', '!=', '')
            ->pluck('ic_number')
            ->toArray();

        return response()->json([
            'ic_numbers' => $icNumbers
        ]);
    }

    /**
     * Send WhatsApp notification with login credentials
     */
    public function sendLoginCredentials(Request $request, $id)
    {
        try {
            $employee = Employee::findOrFail($id);

            if (!$employee->phonenumber) {
                return response()->json([
                    'success' => false,
                    'message' => 'No phone number found for this employee'
                ], 400);
            }

            $phoneNumber = $this->formatPhoneNumber($employee->phonenumber);

            $message = "🔐 *Login Credentials - Pusat Tuisyen Perintis Didik*\n\n";
            $message .= "Dear *{$employee->firstname} {$employee->lastname}*,\n\n";
            $message .= "Here are your login credentials for the employee portal:\n\n";
            $message .= "📱 *Username:* `{$employee->user_name}`\n";
            $message .= "🔑 *Password:* `{$employee->password}`";

            $result = $this->twilioService->sendWhatsAppMessage($phoneNumber, $message);

            if ($result) {
                Log::info('Employee login credentials WhatsApp notification sent successfully', [
                    'employee_id' => $employee->employeeID,
                    'employee_name' => $employee->firstname . ' ' . $employee->lastname,
                    'phone' => $phoneNumber
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Login credentials sent successfully via WhatsApp',
                    'employee_name' => $employee->firstname . ' ' . $employee->lastname,
                    'phone' => $phoneNumber
                ]);
            } else {
                Log::error('Failed to send employee login credentials WhatsApp notification', [
                    'employee_id' => $employee->employeeID,
                    'phone' => $phoneNumber
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Failed to send WhatsApp notification'
                ], 500);
            }
        } catch (\Exception $e) {
            Log::error('Error sending employee login credentials WhatsApp notification', [
                'employee_id' => $id,
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
