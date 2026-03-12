<?php

namespace App\Http\Controllers;

use App\Models\Classes;
use Illuminate\Http\Request;

class ManageClassController extends Controller
{
    public function manageClasses()
    {
        $classes = Classes::with('students')->get();
        return view('admin.manage_classes', compact('classes'));
    }

    public function editClass($id)
    {
        $class = \App\Models\Classes::findOrFail($id);
        // Only select employees with group_level = 2 (Teacher)
        $teachers = \App\Models\Employee::where('group_level', 2)->get();
        // Dapatkan guru yang telah assign ke kelas ini
        $assignedTeacherIds = \App\Models\TeacherClass::where('classID', $id)->pluck('employeeID')->toArray();
        $assignedTeachers = \App\Models\Employee::whereIn('employeeID', $assignedTeacherIds)->get();
        return view('admin.edit_class', [
            'class' => $class,
            'teachers' => $teachers,
            'assignedTeachers' => $assignedTeachers
        ]);
    }

    public function updateClass(Request $request, $id)
    {
        $request->validate([
            'class_name' => 'required|string|max:255|unique:classes,class_name,' . $id . ',classID',
        ], [
            'class_name.unique' => 'A class with this name already exists. Please choose a different name.',
        ]);

        $class = \App\Models\Classes::findOrFail($id);
        $class->class_name = $request->class_name;
        $class->save();

        // Get current and new teacher assignments
        $currentTeachers = \App\Models\TeacherClass::where('classID', $id)->pluck('employeeID')->toArray();
        $newTeachers = $request->assigned_teachers ?? [];

        // Find teachers that were removed
        $removedTeachers = array_diff($currentTeachers, $newTeachers);

        // If any teachers were removed, remove their subject assignments for this class
        if (!empty($removedTeachers)) {
            \Illuminate\Support\Facades\DB::table('class_subject')
                ->where('classID', $id)
                ->whereIn('employeeID', $removedTeachers)
                ->delete();
        }

        // Sync teacher assignments
        \App\Models\TeacherClass::where('classID', $id)->delete();
        foreach ($newTeachers as $employeeID) {
            \App\Models\TeacherClass::create([
                'classID' => $id,
                'employeeID' => $employeeID
            ]);
        }

        return redirect()->back()->with('success', 'Class updated successfully!');
    }

    public function createClass()
    {
        // Only select employees with group_level = 2 (Teacher)
        $teachers = \App\Models\Employee::where('group_level', 2)->get();
        return view('admin.add_new_classes', compact('teachers'));
    }

    public function storeClass(Request $request)
    {
        $request->validate([
            'class_name' => 'required|string|max:255|unique:classes,class_name',
            'assigned_teachers' => 'array',
        ], [
            'class_name.unique' => 'A class with this name already exists. Please choose a different name.',
        ]);

        // Simpan kelas baru
        $class = new \App\Models\Classes();
        $class->class_name = $request->class_name;
        $class->save();

        // Simpan guru assign
        $assigned = $request->assigned_teachers ?? [];
        foreach ($assigned as $employeeID) {
            \App\Models\TeacherClass::create([
                'classID' => $class->classID,
                'employeeID' => $employeeID
            ]);
        }
        return redirect('/admin/manage_classes')->with('success', 'Class created successfully!');
    }

    public function deleteClass($id)
    {
        // Delete all subject assignments for this class
        \Illuminate\Support\Facades\DB::table('class_subject')->where('classID', $id)->delete();

        // Delete teacher assignments
        \App\Models\TeacherClass::where('classID', $id)->delete();

        // Delete the class
        \App\Models\Classes::where('classID', $id)->delete();

        return redirect()->back()->with('success', 'Class deleted successfully!');
    }
}
