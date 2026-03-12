<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Employee;
use App\Models\ParentModel;

class ManageProfileController extends Controller
{
    public function show()
    {
        $user = session('user');
        if (!$user) {
            return redirect('/login');
        }
        $profile = null;
        $role = null;

        if ($user->group_level == 1 || $user->group_level == 2) { // 1=Admin, 2=Teacher
            $profile = \App\Models\Employee::where('user_name', $user->user_name)->first();
            $role = $user->group_level == 1 ? 'Administrator' : 'Teacher';
            return view('manage_profile', compact('profile', 'role'));
        } elseif ($user->group_level == 3) { // Parent
            $profile = \App\Models\ParentModel::where('user_name', $user->user_name)->first();
            $role = 'Parent';
            $children = $profile ? $profile->students()->with(['class', 'subjects', 'studentSubjects'])->get() : collect();
            return view('manage_profile', compact('profile', 'role', 'children'));
        }
    }

    public function update(Request $request)
    {
        $user = session('user');
        if (!$user) {
            return redirect('/login');
        }
        $childrenUpdated = false;
        if ($user->group_level == 1 || $user->group_level == 2) {
            $request->validate([
                'firstname' => 'required|string|max:150',
                'lastname' => 'required|string|max:150',
                'ic_number' => 'required|string|max:20|unique:employee,ic_number,' . $user->employeeID . ',employeeID',
                'phonenumber' => 'required|string|max:15',
                'address' => 'required|string|max:255',
                'email' => 'required|email|max:150',
                'user_name' => 'required|string|max:150',
                'password' => 'required|string',
            ], [
                'ic_number.unique' => 'An employee with this IC number already exists. Please check the IC number and try again.',
            ]);

            // Additional check for IC number duplication
            $existingEmployee = \App\Models\Employee::where('ic_number', $request->input('ic_number'))
                ->where('employeeID', '!=', $user->employeeID)
                ->first();
            if ($existingEmployee) {
                return redirect()->back()->withErrors(['ic_number' => 'An employee with this IC number already exists. Please check the IC number and try again.'])->withInput();
            }

            $profile = \App\Models\Employee::where('user_name', $user->user_name)->first();
            if ($profile) {
                $profile->firstname = $request->input('firstname');
                $profile->lastname = $request->input('lastname');
                $profile->ic_number = $request->input('ic_number');
                $profile->phonenumber = $request->input('phonenumber');
                $profile->address = $request->input('address');
                $profile->email = $request->input('email');
                $profile->educational_background = $request->input('educational_background');
                $profile->current_job = $request->input('current_job');
                $profile->user_name = $request->input('user_name');
                $profile->password = $request->input('password');
                if ($request->hasFile('profile_picture')) {
                    $file = $request->file('profile_picture');
                    $filename = time() . '_' . $file->getClientOriginalName();
                    $file->move(public_path('uploads/profile_pictures'), $filename);
                    $profile->profile_picture = $filename;
                }
                $profile->save();
            }
        } elseif ($user->group_level == 3) {
            $request->validate([
                'name' => 'required|string|max:150',
                'phonenumber' => 'required|string|max:15',
                'email' => 'required|email|max:150',
                'user_name' => 'required|string|max:150',
                'password' => 'required|string',
            ]);
            $profile = \App\Models\ParentModel::where('user_name', $user->user_name)->first();
            if ($profile) {
                $profile->name = $request->input('name');
                $profile->phonenumber = $request->input('phonenumber');
                $profile->email = $request->input('email');
                $profile->user_name = $request->input('user_name');
                $profile->password = $request->input('password');
                if ($request->hasFile('profile_picture')) {
                    $file = $request->file('profile_picture');
                    $filename = time() . '_' . $file->getClientOriginalName();
                    $file->move(public_path('uploads/profile_pictures'), $filename);
                    $profile->profile_picture = $filename;
                }
                $profile->save();
            }
            // Update children if present
            $children = $request->input('children', []);
            foreach ($children as $childData) {
                if (isset($childData['studentID'])) {
                    $student = \App\Models\Student::find($childData['studentID']);
                    if ($student) {
                        $student->student_name = $childData['student_name'] ?? $student->student_name;
                        $student->school_name = $childData['school_name'] ?? $student->school_name;
                        $student->address = $childData['address'] ?? $student->address;
                        $student->tuition_startdate = $childData['tuition_startdate'] ?? $student->tuition_startdate;
                        $student->save();
                        $childrenUpdated = true;
                    }
                }
            }
        }
        $msg = $childrenUpdated ? 'Profile and children details updated successfully!' : 'Profile updated successfully!';
        return redirect('/manage_profile')->with('success', $msg);
    }
}
