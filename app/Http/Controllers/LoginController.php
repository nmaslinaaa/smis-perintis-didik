<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use App\Models\Employee;
use App\Models\ParentModel;
use App\Services\TwilioService;

class LoginController extends Controller
{
    protected $twilioService;

    public function __construct(TwilioService $twilioService)
    {
        $this->twilioService = $twilioService;
    }

    public function showLoginForm()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        // Try admin or teacher (employee table)
        $user = Employee::where('user_name', $request->username)
            ->whereIn('group_level', [1, 2])
            ->first();

        if ($user && $user->password === $request->password) {
            Session::put('user', $user); // Store the whole user object
            Session::put('user_id', $user->employeeID);
            Session::put('user_name', $user->user_name);
            Session::put('group_level', $user->group_level);
            if ($user->group_level == 1) {
                return redirect('/admin/dashboard');
            } elseif ($user->group_level == 2) {
                return redirect('/teacher/dashboard');
            }
        }

        // Try parent (parent table)
        $parent = ParentModel::where('user_name', $request->username)->first();
        if ($parent && $parent->password === $request->password) {
            Session::put('user', $parent); // Store the whole parent object
            Session::put('user_id', $parent->parentID);
            Session::put('user_name', $parent->user_name);
            Session::put('group_level', $parent->group_level);
            return redirect('/parent/dashboard');
        }

        return redirect()->back()->with('login_error', 'Invalid username or password.');
    }

    public function logout()
    {
        Session::flush();
        return redirect('/login');
    }

    public function showSignupForm()
    {
        return view('parent.sign_up');
    }

    public function signup(Request $request)
    {
        $request->validate([
            'fullname' => 'required|string|max:150',
            'username' => 'required|string|max:150|unique:parent,user_name',
            'password' => 'required|string|min:4',
            'email' => 'nullable|email|max:150',
            'phone' => 'required|string|max:15',
        ], [
            'username.unique' => 'This username is already taken. Please choose another one.',
            'password.min' => 'Password must be at least 4 characters long.',
            'email.email' => 'Please enter a valid email address.',
        ]);

        try {
            // Check if username already exists
            $existingUser = ParentModel::where('user_name', $request->username)->first();
            if ($existingUser) {
                return redirect()->back()
                    ->with('error', 'Username already exists. Please choose a different username.')
                    ->withInput();
            }

            // Create new parent account
            $parent = new ParentModel();
            $parent->name = $request->fullname;
            $parent->user_name = $request->username;
            $parent->password = $request->password;
            $parent->email = $request->email;
            $parent->phonenumber = $request->phone;
            $parent->group_level = 3; // Parent level
            $parent->save();

            // Send welcome WhatsApp message
            try {
                if ($parent->phonenumber) {
                    $phoneNumber = $this->formatPhoneNumber($parent->phonenumber);
                    $this->twilioService->sendWelcomeMessage($phoneNumber, $parent->name);
                    Log::info('Welcome WhatsApp message sent to new parent', ['parent_id' => $parent->parentID]);
                }
            } catch (\Exception $e) {
                Log::error('Failed to send welcome WhatsApp message', [
                    'parent_id' => $parent->parentID,
                    'error' => $e->getMessage()
                ]);
            }

            return redirect('/login')->with('success', 'Registration successful! You can now login with your username and password.');
        } catch (\Exception $e) {
            Log::error('Signup error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Registration failed. Please try again.')
                ->withInput();
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
