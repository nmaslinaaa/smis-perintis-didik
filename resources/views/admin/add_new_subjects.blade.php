<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Subjects</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <!-- Internal CSS -->
    <style>
        /* General styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f7fa;
        }

        /* Header styles */
        .header {
            display: flex;
            justify-content: space-between;
            padding-top: 15px;
            padding-bottom: 11px;
            padding-left: 0px;
            background-color: #fff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            position: fixed; /* Fixed header */
            top: 0;
            width: 100%; /* Ensure the header spans the full width */
            z-index: 900; /* Middle layer, above the content but below the sidebar */
        }
        .header-right {
            display: flex;
            align-items: center;
        }

        .header-right span {
            margin-right: 15px;
        }

        .profile-dropdown {
            position: relative;
            display: inline-block;
        }

        .profile-dropdown ul {
            display: none;
            position: absolute;
            top: 100%;
            right: 0;
            background-color: #fff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            padding: 10px 0;
            min-width: 150px;
        }

        .profile-dropdown:hover ul {
            display: block;
        }

        .profile-dropdown a {
            padding: 10px;
            display: block;
            text-decoration: none;
            color: #333;
        }

        .profile-dropdown a:hover {
            background-color: #f1f1f1;
        }

        /* Sidebar styles */
        .sidebar {
            width: 220px; /* Fixed width for the sidebar */
            background-color: #241f1f;
            position: fixed;
            height: 100%;
            top: 0;
            left: 0;
            z-index: 1000; /* Higher z-index to keep sidebar on top of the header */
        }

        .sidebar .logo {
            display: flex;
            background-color: #edc10c;
            justify-content: left;
            align-items: center;
        }

        .sidebar .logo img {
            width: 100px; /* Adjust the width for the logo image */
            padding-bottom: 10px;

        }

        .sidebar .logotext h1{
            font-size: 15px;
            font-weight: bold;
            color: #000000; /* Dark color for the text */
            margin: 0;
        }
        .sidebar .logotext2 h1{
            padding-left: 1em;
        }

        .sidebar nav ul {
            list-style-type: none;
            padding: 0;
        }

        .sidebar nav ul li a {
            color: #ecf0f1;
            padding: 15px 20px;
            display: block;
            text-decoration: none;
        }

        .sidebar nav ul li a:hover,
        .sidebar nav ul li a.active {
            background-color: #edc10c;
        }

        /* Panel Heading Style */
        .panel-heading {
            display: flex;
            justify-content: flex-start; /* Distribute space between title and dropdowns */
            align-items: center; /* Vertically center align */
            padding-top: 15px;
            padding-bottom: 15px;
            padding-left:10px;
            padding-right:10px;
            font-size: 18px;
            font-weight: bold;
        }

        /* Panel Body Style */
        .panel-body {
            padding: 10px;
        }

        /* Dropdown Alignment */
        .grade-dropdown {
            padding: 5px;
            font-size: 16px;
            background-color: #f1c40f; /* Yellow background */
            border: 1px solid #ddd;
            border-radius: 5px;
            width: 150px; /* Set width for dropdowns */
            margin-left: 10px; /* Adds space between dropdowns */
        }
        /* Container for the dropdowns */
        .dropdown-container {
            display: flex;
            gap: 20px; /* Space between the dropdowns */
            align-items: center; /* Align the dropdowns */
        }

        /* Main content styles */
        .main-content {
            margin-top: 80px; /* Add top margin to push the content below the fixed header */
            margin-left: 220px; /* Ensure content starts after the sidebar */
            padding: 20px;
            position: relative;
        }

       /* Flexbox for side-by-side layout of the cards */
       .stats {
            display: flex;
            justify-content: space-between; /* Aligns items to the left and right with space in between */
            margin-bottom: 30px;
            padding: 0 20px; /* Adds some padding around the section */
        }

        .stat-card {
            background-color: #f8d8c9; /* Light peach color for Total Students */
            padding-top: 20px;
            border-radius: 15px; /* Rounded corners for the box */
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); /* Soft shadow for depth */
            text-align: center;
            width: 320px; /* Set a specific width for the card */
        }

        .stat-card .icon {
            font-size: 50px; /* Larger icon size for emphasis */
            margin-right: 20px; /* Space between the icon and number */
            display: inline-block; /* Ensures icon and number are in a row */
            vertical-align: middle; /* Aligns icon and number vertically */
        }

        .stat-card h3 {
            margin: 0;
            font-size: 22px; /* Font size for the title */
            color: #333; /* Dark color for the title */
            font-weight: bold;
            margin-bottom: 10px; /* Adds spacing below the title */
        }

        .stat-card .panel-value {
            display: flex;
            align-items: center; /* Aligns the icon and number in the center */
            justify-content: center; /* Centers both items horizontally */
        }

        .stat-card p {
            color: #777; /* Light gray for the description text */
            font-size: 40px; /* Larger font size for the total number */
            font-weight: bold;
        }

        .card-student {
            background-color: #f8d8c9; /* Light peach color for Total Students */
        }

        .card-teacher {
            background-color: #fdf5b5; /* Light yellow color for Total Teachers */
        }

        .card-classes {
            background-color: #d4f0d3; /* Light green color for Total Classes */
                .performance-section h2 {
                    font-size: 20px;
                    margin-bottom: 20px;
                }
            }
        .performance-table {
            width: 100%;
            border-collapse: collapse;
        }

        .performance-table th,
        .performance-table td {
            padding: 10px;
            text-align: center;
            border: 1px solid #ddd;
        }

        .progress-bar {
            background-color: #e0e0e0;
            height: 20px;
            border-radius: 5px;
        }

        .progress-bar.red {
            background-color: red;
        }

        .progress-bar.yellow {
            background-color: yellow;
        }

        .progress-bar.green {
            background-color: green;
        }

        .footer {
            background-color: #fff;
            text-align: center;
            padding: 10px 105px;;
            position: fixed;
            width: 100%;
            bottom: 0;
            box-shadow: 0 -2px 5px rgba(0, 0, 0, 0.1);
        }

        .header-date {
            display: flex;
            align-items: center;
        }
        .date-left {
            margin-left: 235px; /* Same as sidebar width */
            font-weight: bold;
            color: #333;
        }

        /* --- Student Performance Card --- */
        .performance-card {
            background: #fff;
            border-radius: 25px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
            padding: 30px 30px 20px 30px;
            margin-top: 30px;
            margin-bottom: 30px;
            width: 100%;
            max-width: 1170px;
        }
        .performance-title {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
        }
        .performance-title .grade-dropdown {
            margin-left: 15px;
            background: #ffd600;
            color: #222;
            font-weight: bold;
            border: none;
            border-radius: 20px;
            padding: 6px 18px;
            font-size: 18px;
            box-shadow: 0 1px 2px rgba(0,0,0,0.04);
        }
        .performance-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin-top: 15px;
        }
        .performance-table th {
            font-size: 18px;
            font-weight: bold;
            background: none;
            border: none;
            border-top: 4px solid #222;
            padding: 14px 0 14px 0;
            text-align: left;
        }
        .performance-table td {
            font-size: 17px;
            border: none;
            padding: 16px 0 16px 0;
            text-align: left;
        }
        .score-bar-bg {
            background: #e6eaf0;
            border-radius: 10px;
            width: 220px;
            height: 28px;
            display: inline-block;
            vertical-align: middle;
            margin-right: 10px;
        }
        .score-bar {
            background: #a4c6ea;
            height: 28px;
            border-radius: 10px 0 0 10px;
            display: inline-block;
        }
        .score-label {
            position: absolute;
            left: 0;
            right: 0;
            text-align: center;
            font-weight: bold;
            color: #222;
            font-size: 16px;
        }
        .circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 17px;
            color: #fff;
        }
        .circle.red { background: #f00; }
        .circle.yellow { background: #ffd600; color: #222; }
        .circle.green { background: #3cc13b; }
    </style>
</head>

<body>

    @include('header') <!-- Include the header -->

<style>
    .main-content {
        margin-top: 64px;
        margin-left: 220px;
        padding: 40px 0 0 0;
        min-height: 100vh;
        background: #f4f7fa;
    }
    .class-list-container {
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.07);
        max-width: 1200px;
        margin: 0 auto;
        padding: 32px 32px 18px 32px;
        border: 1px solid #ececec;
    }
    .class-list-title {
        font-size: 2.1rem;
        font-weight: 700;
        margin: 0;
        color: #222;
        letter-spacing: -1px;
    }
    .class-list-divider {
        width: 100%;
        height: 3px;
        background: #edc10c;
        margin-bottom: 18px;
        margin-top: 14px;
        border-radius: 2px;
    }
    .class-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        background: #fff;
        margin-top: 0;
        font-size: 1.08rem;
    }
    .class-table th {
        font-weight: 700;
        font-size: 1.08rem;
        padding: 12px 0 12px 12px;
        border-bottom: 2px solid #e0e0e0;
        background: none;
        color: #222;
    }
    .class-table th:first-child,
    .class-table td:first-child {
        text-align: left;
        padding-left: 18px;
    }
    .class-table th:nth-child(2),
    .class-table td:nth-child(2) {
        text-align: left;
    }
    .class-table th:nth-child(3),
    .class-table td:nth-child(3) {
        text-align: center;
    }
    .class-table th.action-header,
    .class-table td.action-cell {
        text-align: center;
        padding-right: 0;
    }
    .class-table td {
        font-size: 1.05rem;
        padding: 12px 0 12px 12px;
        border-bottom: 1px solid #e0e0e0;
        background: #fff;
        color: #222;
        vertical-align: middle;
    }
    .class-table td.action-cell {
        white-space: nowrap;
        padding-right: 0;
    }
    .class-table tr:last-child td {
        border-bottom: none;
    }
    .action-btn {
        border: none;
        border-radius: 6px;
        font-size: 1rem;
        font-weight: 600;
        padding: 7px 24px;
        margin-right: 8px;
        cursor: pointer;
        transition: background 0.2s;
        box-shadow: none;
        display: inline-block;
        vertical-align: middle;
        outline: none;
    }
    .btn-edit {
        background: #2196f3;
        color: #fff;
    }
    .btn-delete {
        background: #f44336;
        color: #fff;
    }
    .action-btn:last-child {
        margin-right: 0;
    }
</style>

<div class="main-content">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @if(session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: @json(session('success')),
            showConfirmButton: false,
            timer: 1800,
            customClass: {
                icon: 'swal2-success swal2-animate-success-icon',
            }
        });
    </script>
    @endif
    @if($errors->any())
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: @json($errors->first()),
            showConfirmButton: false,
            timer: 2200,
            customClass: {
                icon: 'swal2-error swal2-animate-error-icon',
            }
        });
    </script>
    @endif
        <div class="class-list-container" style="max-width: 1100px; margin: 0 auto; padding: 32px 32px 32px 32px; border-radius: 12px;">
        <div class="class-list-title" style="font-size: 2rem; font-weight: 700; margin-bottom: 18px;">Create New Subject</div>
        <div class="class-list-divider" style="margin-bottom: 32px;"></div>
        <form id="addSubjectForm" method="POST" action="{{ url('/admin/add_new_subjects') }}">
            @csrf
            <div style="display: flex; gap: 40px; margin-bottom: 32px;">
                <div style="flex: 1;">
                    <label style="font-size: 1.2rem; font-weight: 600; display: block; margin-bottom: 10px;">Subject Name</label>
                    <input type="text" name="subject_name" id="subject_name" placeholder="Name of subject"
                           style="width: 100%; max-width: 500px; padding: 10px 14px; font-size: 1.1rem; border: 1px solid #ccc; border-radius: 6px; background: #f9f9f9;"
                           required value="{{ old('subject_name') }}">
                    <div id="subject_name_error" style="color: #f44336; font-size: 0.9rem; margin-top: 5px; display: none;"></div>
                    <div id="existing_subjects" style="color: #666; font-size: 0.9rem; margin-top: 5px; display: none;">
                        <strong>Existing subjects in this class:</strong> <span id="existing_subjects_list"></span>
                    </div>
                </div>
                <div style="flex: 1;">
                    <label style="font-size: 1.2rem; font-weight: 600; display: block; margin-bottom: 10px;">Price (RM)</label>
                    <input type="text" name="subject_price" placeholder="eg., 65" style="width: 100%; max-width: 250px; padding: 10px 14px; font-size: 1.1rem; border: 1px solid #ccc; border-radius: 6px; background: #f9f9f9;" required value="{{ old('subject_price') }}">
                </div>
                <div style="flex: 1;">
                    <label style="font-size: 1.2rem; font-weight: 600; display: block; margin-bottom: 10px;">Class</label>
                    <select name="classID" style="width: 100%; max-width: 250px; padding: 10px 14px; font-size: 1.1rem; border: 1px solid #ccc; border-radius: 6px; background: #f9f9f9;" required>
                        <option value="">Select</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->classID }}" {{ old('classID') == $class->classID ? 'selected' : '' }}>{{ $class->class_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div style="font-size: 1.15rem; font-weight: 600; margin-bottom: 10px;">Assign</div>
            <table id="assignmentsTable" style="width: 100%; border-collapse: collapse; background: #fff; margin-bottom: 32px;">
                <thead>
                    <tr style="background: #fafafa;">
                        <th style="padding: 12px 0; font-weight: 700; font-size: 1.08rem; border-bottom: 2px solid #edc10c;">Teacher</th>
                        <th style="padding: 12px 0; font-weight: 700; font-size: 1.08rem; border-bottom: 2px solid #edc10c;">Day</th>
                        <th style="padding: 12px 0; font-weight: 700; font-size: 1.08rem; border-bottom: 2px solid #edc10c;">Start Time</th>
                        <th style="padding: 12px 0; font-weight: 700; font-size: 1.08rem; border-bottom: 2px solid #edc10c;">End Time</th>
                        <th style="padding: 12px 0; font-weight: 700; font-size: 1.08rem; border-bottom: 2px solid #edc10c;">Action</th>
                    </tr>
                </thead>
                <tbody id="assignmentRows">
                @if(old('assignments'))
                    @foreach(old('assignments') as $i => $row)
                        <tr>
                            <td style="padding: 10px 0; text-align: center;">
                                <select name="assignments[{{ $i }}][employeeID]" class="teacher-select" data-row="{{ $i }}" style="width: 200px; padding: 7px 10px; border-radius: 5px; border: 1px solid #ccc; font-size: 1rem;" required>
                                    <option value="">Select</option>
                                    @foreach($teachers as $teacher)
                                        <option value="{{ $teacher->employeeID }}" {{ old('assignments.'.$i.'.employeeID') == $teacher->employeeID ? 'selected' : '' }}>{{ $teacher->firstname }} {{ $teacher->lastname }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td style="padding: 10px 0; text-align: center;">
                                <select name="assignments[{{ $i }}][days]" style="width: 110px; padding: 7px 10px; border-radius: 5px; border: 1px solid #ccc; font-size: 1rem;" required>
                                    <option value="">Select</option>
                                    @foreach(['Isnin','Selasa','Rabu','Khamis','Jumaat','Sabtu','Ahad'] as $day)
                                        <option value="{{ $day }}" {{ old('assignments.'.$i.'.days') == $day ? 'selected' : '' }}>{{ $day }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td style="padding: 10px 0; text-align: center;">
                                <input type="time" name="assignments[{{ $i }}][start_time]" style="width: 120px; padding: 7px 10px; border-radius: 5px; border: 1px solid #ccc; font-size: 1rem;" required value="{{ old('assignments.'.$i.'.start_time') }}">
                            </td>
                            <td style="padding: 10px 0; text-align: center;">
                                <input type="time" name="assignments[{{ $i }}][end_time]" style="width: 120px; padding: 7px 10px; border-radius: 5px; border: 1px solid #ccc; font-size: 1rem;" required value="{{ old('assignments.'.$i.'.end_time') }}">
                            </td>
                            <td style="padding: 10px 0; text-align: center;">
                                <button type="button" id="addRowBtn" style="background: #1da1f2; color: #fff; border: none; border-radius: 50%; width: 32px; height: 32px; font-size: 1.2rem; vertical-align: middle;">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td style="padding: 10px 0; text-align: center;">
                            <select name="assignments[0][employeeID]" class="teacher-select" data-row="0" style="width: 200px; padding: 7px 10px; border-radius: 5px; border: 1px solid #ccc; font-size: 1rem;" required>
                                <option value="">Select</option>
                                @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->employeeID }}">{{ $teacher->firstname }} {{ $teacher->lastname }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td style="padding: 10px 0; text-align: center;">
                            <select name="assignments[0][days]" style="width: 110px; padding: 7px 10px; border-radius: 5px; border: 1px solid #ccc; font-size: 1rem;" required>
                                <option value="">Select</option>
                                @foreach(['Isnin','Selasa','Rabu','Khamis','Jumaat','Sabtu','Ahad'] as $day)
                                    <option value="{{ $day }}">{{ $day }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td style="padding: 10px 0; text-align: center;">
                            <input type="time" name="assignments[0][start_time]" style="width: 120px; padding: 7px 10px; border-radius: 5px; border: 1px solid #ccc; font-size: 1rem;" required>
                        </td>
                        <td style="padding: 10px 0; text-align: center;">
                            <input type="time" name="assignments[0][end_time]" style="width: 120px; padding: 7px 10px; border-radius: 5px; border: 1px solid #ccc; font-size: 1rem;" required>
                        </td>
                        <td style="padding: 10px 0; text-align: center;">
                            <button type="button" id="addRowBtn" style="background: #1da1f2; color: #fff; border: none; border-radius: 50%; width: 32px; height: 32px; font-size: 1.2rem; vertical-align: middle;">
                                <i class="fas fa-plus"></i>
                            </button>
                        </td>
                    </tr>
                @endif
                </tbody>
            </table>
            <div style="display: flex; gap: 18px; margin-top: 18px;">
                <button type="submit" style="background: #43b324; color: #fff; font-size: 1.15rem; font-weight: 700; border: none; border-radius: 6px; padding: 12px 48px; cursor: pointer;">Create Subject</button>
                <button type="reset" style="background: #f44336; color: #fff; font-size: 1.15rem; font-weight: 700; border: none; border-radius: 6px; padding: 12px 48px; cursor: pointer;">Reset</button>
            </div>
        </form>
    </div>
</div>
<script>
const teachers = @json($teachers);
const classes = @json($classes);
const teacherClasses = @json($teacherClasses);
const days = ["Isnin","Selasa","Rabu","Khamis","Jumaat","Sabtu","Ahad"];

// Store existing subjects for each class
const existingSubjects = {};
@foreach($classes as $class)
    @php
        $subjects = DB::table('class_subject')
            ->join('subjects', 'class_subject.subjectID', '=', 'subjects.subjectID')
            ->where('class_subject.classID', $class->classID)
            ->pluck('subjects.subject_name')
            ->toArray();
    @endphp
    existingSubjects[{{ $class->classID }}] = [
        @foreach($subjects as $subjectName)
            '{{ addslashes($subjectName) }}',
        @endforeach
    ];
@endforeach

// Real-time validation for subject name
document.getElementById('subject_name').addEventListener('input', function() {
    const subjectName = this.value.trim();
    const classID = document.querySelector('select[name="classID"]').value;
    const errorDiv = document.getElementById('subject_name_error');
    const existingDiv = document.getElementById('existing_subjects');
    const existingList = document.getElementById('existing_subjects_list');
    const submitBtn = document.querySelector('#addSubjectForm button[type="submit"]');

    if (subjectName === '') {
        errorDiv.style.display = 'none';
        existingDiv.style.display = 'none';
        submitBtn.disabled = false;
        return;
    }

    if (classID && existingSubjects[classID]) {
        // Show existing subjects for this class
        existingList.textContent = existingSubjects[classID].join(', ');
        existingDiv.style.display = 'block';

        // Check for duplicates (case-insensitive)
        const isDuplicate = existingSubjects[classID].some(existing =>
            existing.toLowerCase() === subjectName.toLowerCase()
        );

        if (isDuplicate) {
            errorDiv.textContent = 'A subject with this name already exists in the selected class. Please choose a different name or select a different class.';
            errorDiv.style.display = 'block';
            submitBtn.disabled = true;
        } else {
            errorDiv.style.display = 'none';
            submitBtn.disabled = false;
        }
    } else {
        existingDiv.style.display = 'none';
        errorDiv.style.display = 'none';
        submitBtn.disabled = false;
    }
});

// Update validation when class changes
document.querySelector('select[name="classID"]').addEventListener('change', function() {
    // Trigger the subject name validation
    document.getElementById('subject_name').dispatchEvent(new Event('input'));
});

function getTeachersForClass(classID) {
    const allowedTeacherIDs = teacherClasses.filter(tc => tc.classID == classID).map(tc => tc.employeeID);
    return teachers.filter(t => allowedTeacherIDs.includes(t.employeeID));
}

function updateSubmitButtonState() {
    const classSelect = document.querySelector('select[name="classID"]');
    const submitBtn = document.querySelector('#addSubjectForm button[type="submit"]');
    const filteredTeachers = classSelect.value ? getTeachersForClass(classSelect.value) : [];
    if (filteredTeachers.length === 0) {
        submitBtn.disabled = true;
        submitBtn.style.opacity = '0.5';
    } else {
        submitBtn.disabled = false;
        submitBtn.style.opacity = '1';
    }
}

function createAssignmentRow(index, filteredTeachers, selectedTeacherIDs = []) {
    // Only show teachers not already selected, except for the current value
    let teacherOptions = '';
    if (filteredTeachers.length === 0) {
        teacherOptions = '<option value="" disabled selected>No available teacher</option>';
    } else {
        teacherOptions = '<option value="">Select</option>' +
            filteredTeachers
                .filter(t => !selectedTeacherIDs.includes(t.employeeID.toString()))
                .map(t => `<option value="${t.employeeID}">${t.firstname} ${t.lastname}</option>`)
                .join('');
    }
    return `<tr>
        <td style=\"padding: 10px 0; text-align: center;\">
            <select name=\"assignments[${index}][employeeID]\" class=\"teacher-select\" data-row=\"${index}\" style=\"width: 200px; padding: 7px 10px; border-radius: 5px; border: 1px solid #ccc; font-size: 1rem;\" required ${filteredTeachers.length === 0 ? 'disabled' : ''}>
                ${teacherOptions}
            </select>
        </td>
        <td style=\"padding: 10px 0; text-align: center;\">
            <select name=\"assignments[${index}][days]\" style=\"width: 110px; padding: 7px 10px; border-radius: 5px; border: 1px solid #ccc; font-size: 1rem;\" required ${filteredTeachers.length === 0 ? 'disabled' : ''}>
                <option value=\"\">Select</option>
                ${days.map(d => `<option value=\"${d}\">${d}</option>`).join('')}
            </select>
        </td>
        <td style=\"padding: 10px 0; text-align: center;\">
            <input type=\"time\" name=\"assignments[${index}][start_time]\" style=\"width: 120px; padding: 7px 10px; border-radius: 5px; border: 1px solid #ccc; font-size: 1rem;\" required ${filteredTeachers.length === 0 ? 'disabled' : ''}>
        </td>
        <td style=\"padding: 10px 0; text-align: center;\">
            <input type=\"time\" name=\"assignments[${index}][end_time]\" style=\"width: 120px; padding: 7px 10px; border-radius: 5px; border: 1px solid #ccc; font-size: 1rem;\" required ${filteredTeachers.length === 0 ? 'disabled' : ''}>
        </td>
        <td style=\"padding: 10px 0; text-align: center;\">
            <button type=\"button\" id=\"addRowBtn\" style=\"background: #1da1f2; color: #fff; border: none; border-radius: 50%; width: 32px; height: 32px; font-size: 1.2rem; vertical-align: middle;\" ${filteredTeachers.length === 0 ? 'disabled' : ''}>
                <i class=\"fas fa-plus\"></i>
            </button>
        </td>
    </tr>`;
}

function refreshAllTeacherDropdowns() {
    const selectedClassID = document.querySelector('select[name="classID"]').value;
    const filteredTeachers = selectedClassID ? getTeachersForClass(selectedClassID) : [];
    document.querySelectorAll('.teacher-select').forEach((select) => {
        const currentValue = select.value;
        let teacherOptions = '<option value="">Select</option>';
        // If currentValue is not in filteredTeachers, add it (so old input stays visible)
        let filtered = filteredTeachers.slice();
        if (currentValue && !filtered.some(t => t.employeeID.toString() === currentValue)) {
            // Find the teacher in all teachers
            const t = teachers.find(t => t.employeeID.toString() === currentValue);
            if (t) filtered.push(t);
        }
        teacherOptions += filtered
            .map(t => `<option value="${t.employeeID}"${t.employeeID.toString() === currentValue ? ' selected' : ''}>${t.firstname} ${t.lastname}</option>`)
            .join('');
        select.innerHTML = teacherOptions;
        select.value = currentValue;
    });
    // Disable add button if all teachers are selected
    const addBtn = document.getElementById('addRowBtn');
    if (addBtn) {
        const selectedTeacherIDs = getSelectedTeacherIDs();
        const availableTeachers = filteredTeachers.filter(t => !selectedTeacherIDs.includes(t.employeeID.toString()));
        addBtn.disabled = availableTeachers.length === 0;
        addBtn.style.opacity = availableTeachers.length === 0 ? '0.5' : '1';
    }
}

function checkTeacherClash(employeeID, days, startTime, endTime, callback) {
    if (!employeeID || !days || !startTime || !endTime) return callback(false);
    fetch(`/api/check-teacher-clash?employeeID=${employeeID}&days=${days}&start_time=${startTime}&end_time=${endTime}`)
        .then(res => res.json())
        .then(data => callback(data.clash));
}

// Enhanced validation functions
function checkDuplicateSlots() {
    const rows = document.querySelectorAll('#assignmentRows tr');
    const seenSlots = new Set();
    let hasDuplicate = false;

    rows.forEach(row => {
        const teacher = row.querySelector('.teacher-select')?.value;
        const day = row.querySelector('select[name*="[days]"]')?.value;
        const startTime = row.querySelector('input[name*="[start_time]"]')?.value;
        const endTime = row.querySelector('input[name*="[end_time]"]')?.value;

        if (teacher && day && startTime && endTime) {
            const slotKey = `${teacher}_${day}_${startTime}_${endTime}`;
            if (seenSlots.has(slotKey)) {
                hasDuplicate = true;
                row.style.backgroundColor = '#ffeaea';
                row.querySelector('.teacher-select').style.borderColor = '#f44336';
            } else {
                seenSlots.add(slotKey);
                row.style.backgroundColor = '';
                row.querySelector('.teacher-select').style.borderColor = '';
            }
        }
    });

    return hasDuplicate;
}

function checkOverlappingTeachers() {
    const rows = document.querySelectorAll('#assignmentRows tr');
    let hasOverlap = false;

    for (let i = 0; i < rows.length; i++) {
        for (let j = i + 1; j < rows.length; j++) {
            const row1 = rows[i];
            const row2 = rows[j];

            const teacher1 = row1.querySelector('.teacher-select')?.value;
            const day1 = row1.querySelector('select[name*="[days]"]')?.value;
            const start1 = row1.querySelector('input[name*="[start_time]"]')?.value;
            const end1 = row1.querySelector('input[name*="[end_time]"]')?.value;

            const teacher2 = row2.querySelector('.teacher-select')?.value;
            const day2 = row2.querySelector('select[name*="[days]"]')?.value;
            const start2 = row2.querySelector('input[name*="[start_time]"]')?.value;
            const end2 = row2.querySelector('input[name*="[end_time]"]')?.value;

            if (teacher1 && teacher2 && day1 && day2 && start1 && end1 && start2 && end2) {
                if (day1 === day2 && teacher1 !== teacher2) {
                    // Check for time overlap
                    const overlap = (start1 < end2 && end1 > start2);
                    if (overlap) {
                        hasOverlap = true;
                        row1.style.backgroundColor = '#fff3cd';
                        row2.style.backgroundColor = '#fff3cd';
                        row1.querySelector('.teacher-select').style.borderColor = '#ffc107';
                        row2.querySelector('.teacher-select').style.borderColor = '#ffc107';
                    }
                }
            }
        }
    }

    return hasOverlap;
}

function validateAssignments() {
    const duplicateSlots = checkDuplicateSlots();
    const overlappingTeachers = checkOverlappingTeachers();
    const submitBtn = document.querySelector('#addSubjectForm button[type="submit"]');

    if (duplicateSlots || overlappingTeachers) {
        submitBtn.disabled = true;
        submitBtn.style.opacity = '0.5';

        let errorMsg = '';
        if (duplicateSlots) errorMsg += 'Duplicate time slots detected. ';
        if (overlappingTeachers) errorMsg += 'Multiple teachers assigned at overlapping times. ';

        // Show error message
        let errorDiv = document.getElementById('assignmentErrorMsg');
        if (!errorDiv) {
            errorDiv = document.createElement('div');
            errorDiv.id = 'assignmentErrorMsg';
            errorDiv.style.background = '#ffeaea';
            errorDiv.style.border = '1.5px solid #f44336';
            errorDiv.style.color = '#b71c1c';
            errorDiv.style.fontWeight = 'bold';
            errorDiv.style.padding = '12px 30px';
            errorDiv.style.borderRadius = '8px';
            errorDiv.style.margin = '0 auto 18px auto';
            errorDiv.style.maxWidth = '800px';
            errorDiv.style.textAlign = 'center';
            document.getElementById('addSubjectForm').parentNode.insertBefore(errorDiv, document.getElementById('addSubjectForm'));
        }
        errorDiv.innerText = errorMsg;
        errorDiv.style.display = 'block';
    } else {
        submitBtn.disabled = false;
        submitBtn.style.opacity = '1';

        // Hide error message
        const errorDiv = document.getElementById('assignmentErrorMsg');
        if (errorDiv) {
            errorDiv.style.display = 'none';
        }
    }
}

function attachClashCheck(row) {
    const teacherSelect = row.querySelector('.teacher-select');
    const daySelect = row.querySelector('select[name*="[days]"]');
    const startTime = row.querySelector('input[name*="[start_time]"]');
    const endTime = row.querySelector('input[name*="[end_time]"]');
    [teacherSelect, daySelect, startTime, endTime].forEach(input => {
        if (input) {
            input.addEventListener('change', function() {
                const empID = teacherSelect.value;
                const day = daySelect.value;
                const st = startTime.value;
                const et = endTime.value;
                checkTeacherClash(empID, day, st, et, function(clash) {
                    if (clash) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Schedule Clash',
                            text: 'This teacher already has another subject/class at this time!',
                            confirmButtonColor: '#3085d6'
                        });
                        startTime.value = '';
                        endTime.value = '';
                        validateAssignments(); // Re-validate after clearing
                    }
                });
            });
        }
    });
}

document.addEventListener('DOMContentLoaded', function() {
    let assignmentIndex = 1;
    const assignmentRows = document.getElementById('assignmentRows');
    const classSelect = document.querySelector('select[name="classID"]');

    document.getElementById('addRowBtn').addEventListener('click', function() {
        const selectedClassID = classSelect.value;
        const filteredTeachers = selectedClassID ? getTeachersForClass(selectedClassID) : [];
        const selectedTeacherIDs = getSelectedTeacherIDs();
        assignmentRows.insertAdjacentHTML('beforeend', createAssignmentRow(assignmentIndex++, filteredTeachers, selectedTeacherIDs));
        refreshAllTeacherDropdowns();
        // Attach clash check to the newly added row
        const newRow = assignmentRows.lastElementChild;
        attachClashCheck(newRow);
    });
    assignmentRows.addEventListener('click', function(e) {
        if (e.target.closest('.remove-row-btn')) {
            e.target.closest('tr').remove();
            refreshAllTeacherDropdowns();
        }
    });
    assignmentRows.addEventListener('change', function(e) {
        if (e.target.classList.contains('teacher-select')) {
            refreshAllTeacherDropdowns();
        }
        // Trigger validation on any assignment field change
        validateAssignments();
    });

    // Add validation to individual assignment fields
    assignmentRows.addEventListener('input', function(e) {
        if (e.target.matches('select[name*="[days]"], input[name*="[start_time]"], input[name*="[end_time]"]')) {
            validateAssignments();
        }
    });
    classSelect.addEventListener('change', function() {
        refreshAllTeacherDropdowns();
        updateSubmitButtonState();
    });
    document.getElementById('addSubjectForm').addEventListener('reset', function() {
        const selectedClassID = classSelect.value;
        const filteredTeachers = selectedClassID ? getTeachersForClass(selectedClassID) : [];
        assignmentRows.innerHTML = createAssignmentRow(0, filteredTeachers);
        assignmentIndex = 1;
        document.getElementById('addRowBtn').addEventListener('click', function() {
            assignmentRows.insertAdjacentHTML('beforeend', createAssignmentRow(assignmentIndex++, filteredTeachers));
            // Attach clash check to the newly added row
            const newRow = assignmentRows.lastElementChild;
            attachClashCheck(newRow);
        });
        refreshAllTeacherDropdowns();

        // Clear validation errors
        document.getElementById('subject_name_error').style.display = 'none';
        document.getElementById('existing_subjects').style.display = 'none';
        document.querySelector('#addSubjectForm button[type="submit"]').disabled = false;

        // Clear assignment validation errors
        const assignmentErrorDiv = document.getElementById('assignmentErrorMsg');
        if (assignmentErrorDiv) {
            assignmentErrorDiv.style.display = 'none';
        }

        // Clear row highlighting
        document.querySelectorAll('#assignmentRows tr').forEach(row => {
            row.style.backgroundColor = '';
            const teacherSelect = row.querySelector('.teacher-select');
            if (teacherSelect) {
                teacherSelect.style.borderColor = '';
            }
        });
    });
    // Initial trigger for first row
    setTimeout(() => {
        refreshAllTeacherDropdowns();
        updateSubmitButtonState();
        // Attach clash check to all initial rows
        document.querySelectorAll('#assignmentRows tr').forEach(row => attachClashCheck(row));
    }, 100);
});

document.getElementById('addSubjectForm').addEventListener('submit', function(e) {
    // Remove any previous error message
    let errorDiv = document.getElementById('clientErrorMsg');
    if (errorDiv) errorDiv.remove();

    const subjectName = this.subject_name.value.trim();
    const subjectPrice = this.subject_price.value.trim();
    const classID = this.classID.value;
    const assignmentRows = document.querySelectorAll('#assignmentRows tr');
    let hasAssignment = false;
    let assignmentError = false;
    assignmentRows.forEach(row => {
        const teacher = row.querySelector('.teacher-select');
        const day = row.querySelector('select[name*="[days]"]');
        const startTime = row.querySelector('input[name*="[start_time]"]');
        const endTime = row.querySelector('input[name*="[end_time]"]');
        if (
            teacher && !teacher.disabled && teacher.value &&
            day && day.value &&
            startTime && startTime.value &&
            endTime && endTime.value
        ) {
            hasAssignment = true;
        } else if (teacher && !teacher.disabled) {
            assignmentError = true;
        }
    });
    let errorMsg = '';
    if (!subjectName) errorMsg = 'Subject Name is required.';
    else if (!subjectPrice) errorMsg = 'Price is required.';
    else if (!classID) errorMsg = 'Class is required.';
    else if (!hasAssignment) errorMsg = assignmentError ? 'Please complete all assignment fields.' : 'At least one assignment is required.';
    else if (checkDuplicateSlots()) errorMsg = 'Duplicate time slots detected.';
    else if (checkOverlappingTeachers()) errorMsg = 'Multiple teachers assigned at overlapping times.';

    if (errorMsg) {
        e.preventDefault();
        const form = document.getElementById('addSubjectForm');
        const errorDiv = document.createElement('div');
        errorDiv.id = 'clientErrorMsg';
        errorDiv.style.background = '#ffeaea';
        errorDiv.style.border = '1.5px solid #f44336';
        errorDiv.style.color = '#b71c1c';
        errorDiv.style.fontWeight = 'bold';
        errorDiv.style.padding = '12px 30px';
        errorDiv.style.borderRadius = '8px';
        errorDiv.style.margin = '0 auto 18px auto';
        errorDiv.style.maxWidth = '500px';
        errorDiv.style.textAlign = 'center';
        errorDiv.innerText = errorMsg;
        form.parentNode.insertBefore(errorDiv, form);
        return false;
    }
    const filteredTeachers = classSelect.value ? getTeachersForClass(classSelect.value) : [];
    if (filteredTeachers.length === 0) {
        e.preventDefault();
        Swal.fire({
            icon: 'error',
            title: 'No Available Teacher',
            text: 'Cannot add subject because there is no available teacher for this class.',
            confirmButtonColor: '#3085d6'
        });
        return false;
    }
    });
</script>

</body>
 @include('footer') <!-- Include the footer -->
</html>

