<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Subject</title>
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
        font-size: 1.75rem;
        font-weight: 700;
        margin: 0 0 2px 0;
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
        border-collapse: collapse;
        background: #fff;
        margin-top: 0;
        font-size: 1.08rem;
        border: 1px solid #e0e0e0;
    }
    .class-table th, .class-table td {
        border: 1px solid #e0e0e0;
    }
    .class-table th {
        font-weight: 700;
        font-size: 1.08rem;
        padding: 12px 0 12px 12px;
        background: none;
        color: #222;
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
    @if($errors->any())
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Duplicate Time Slot',
            text: @json($errors->first()),
            showConfirmButton: true,
            customClass: {
                icon: 'swal2-error swal2-animate-error-icon',
            }
        });
    </script>
@endif
    <div id="statusMessage" style="display:none; background: #e6ffe6; border: 1.5px solid #19d219; color: #137a13; font-weight:bold; padding: 12px 30px; border-radius: 8px; margin: 0 auto 18px auto; max-width: 500px; text-align: center; box-shadow: 0 2px 8px rgba(25,210,25,0.07);"></div>
    <div class="class-list-container" style="max-width: 1100px; margin: 0 auto; padding: 32px 32px 32px 32px; border-radius: 12px;">
        <div class="class-list-title" style="font-size: 2rem; font-weight: 700; margin-bottom: 18px;">Edit Subject ({{ isset($subject) ? $subject->subject_name : '' }})
    @php
        $className = '';
        if (isset($assignments) && count($assignments) > 0 && isset($classes)) {
            $classID = $assignments[0]->classID ?? null;
            $classObj = $classes->firstWhere('classID', $classID);
            $className = $classObj ? $classObj->class_name : '';
        }
    @endphp
    <span style="font-size: 1.1rem; font-weight: 400; color: #555;">@if($className) (Class: {{ $className }}) @endif</span>
</div>
        <div class="class-list-divider" style="margin-bottom: 32px;"></div>
        <form id="editSubjectForm" method="POST" action="{{ url('/admin/update_subject/' . ($subject->subjectID ?? 0)) }}">
            @csrf
            <div style="display: flex; gap: 40px; margin-bottom: 32px;">
                <div style="flex: 1;">
                    <label style="font-size: 1.2rem; font-weight: 600; display: block; margin-bottom: 10px;">Subject Name <span style="color: red;">*</span></label>
                    <input type="text" name="subject_name" id="subject_name" value="{{ isset($subject) ? $subject->subject_name : '' }}" required
                           style="width: 100%; max-width: 500px; padding: 10px 14px; font-size: 1.1rem; border: 1px solid #ccc; border-radius: 6px; background: #f9f9f9;">
                    <div id="subject_name_error" style="color: #f44336; font-size: 0.9rem; margin-top: 5px; display: none;"></div>
                    <div id="existing_subjects" style="color: #666; font-size: 0.9rem; margin-top: 5px; display: none;">
                        <strong>Existing subjects in this class:</strong> <span id="existing_subjects_list"></span>
                    </div>
                </div>
                <div style="flex: 1;">
                    <label style="font-size: 1.2rem; font-weight: 600; display: block; margin-bottom: 10px;">Price (RM) <span style="color: red;">*</span></label>
                    <input type="number" step="0.01" min="0" name="subject_price" value="{{ isset($subject_price) ? number_format($subject_price, 2, '.', '') : '' }}" required
                           style="width: 100%; max-width: 250px; padding: 10px 14px; font-size: 1.1rem; border: 1px solid #ccc; border-radius: 6px; background: #f9f9f9;">
                </div>
            </div>
            <div style="font-size: 1.15rem; font-weight: 600; margin-bottom: 10px;">Assign <span style="color: red;">*</span></div>
            <table id="assignmentsTable" style="width: 100%; border-collapse: collapse; background: #fff; margin-bottom: 32px;">
                <thead>
                    <tr style="background: #fafafa;">
                        <th style="padding: 12px 0; font-weight: 700; font-size: 1.08rem; border-bottom: 2px solid #edc10c;">Teacher <span style="color: red;">*</span></th>
                        <th style="padding: 12px 0; font-weight: 700; font-size: 1.08rem; border-bottom: 2px solid #edc10c;">Day <span style="color: red;">*</span></th>
                        <th style="padding: 12px 0; font-weight: 700; font-size: 1.08rem; border-bottom: 2px solid #edc10c;">Start Time <span style="color: red;">*</span></th>
                        <th style="padding: 12px 0; font-weight: 700; font-size: 1.08rem; border-bottom: 2px solid #edc10c;">End Time <span style="color: red;">*</span></th>
                        <th style="padding: 12px 0; font-weight: 700; font-size: 1.08rem; border-bottom: 2px solid #edc10c;">Action</th>
                    </tr>
                </thead>
                <tbody id="assignmentRows">
                    @php
                        $assignmentsToShow = old('assignments') ?? $assignments;
                    @endphp
                    @foreach($assignmentsToShow as $i => $assignment)
                        @php $a = (array) $assignment; @endphp
                        <tr data-classsubjectid="{{ $a['classsubjectID'] ?? '' }}">
                            <input type="hidden" name="assignments[{{ $i }}][classsubjectID]" value="{{ $a['classsubjectID'] ?? '' }}">
                            <td style="padding: 10px 0; text-align: center;">
                                <select name="assignments[{{ $i }}][employeeID]" required style="width: 200px; padding: 7px 10px; border-radius: 5px; border: 1px solid #ccc; font-size: 1rem;">
                                    @if(count($teachers) > 0)
                                        <option value="">Select Teacher</option>
                                        @foreach($teachers as $teacher)
                                            <option value="{{ $teacher->employeeID }}" {{ ($a['employeeID'] ?? '') == $teacher->employeeID ? 'selected' : '' }}>
                                                {{ $teacher->firstname }} {{ $teacher->lastname }}
                                            </option>
                                        @endforeach
                                    @else
                                        <option value="">No teachers assigned to this class</option>
                                    @endif
                                </select>
                            </td>
                            <td style="padding: 10px 0; text-align: center;">
                                <select name="assignments[{{ $i }}][days]" required style="width: 110px; padding: 7px 10px; border-radius: 5px; border: 1px solid #ccc; font-size: 1rem;">
                                    <option value="">Select Day</option>
                                    @foreach($days as $day)
                                        <option value="{{ $day }}" {{ ($a['days'] ?? '') == $day ? 'selected' : '' }}>{{ $day }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td style="padding: 10px 0; text-align: center;">
                                <input type="time" name="assignments[{{ $i }}][start_time]" required value="{{ $a['start_time'] ?? '' }}" style="width: 120px; padding: 7px 10px; border-radius: 5px; border: 1px solid #ccc; font-size: 1rem;">
                            </td>
                            <td style="padding: 10px 0; text-align: center;">
                                <input type="time" name="assignments[{{ $i }}][end_time]" required value="{{ $a['end_time'] ?? '' }}" style="width: 120px; padding: 7px 10px; border-radius: 5px; border: 1px solid #ccc; font-size: 1rem;">
                            </td>
                            <td style="padding: 10px 0; text-align: center;">
                                @if($i === 0)
                                    <button type="button" id="addRowBtn" style="background: #1da1f2; color: #fff; border: none; border-radius: 50%; width: 32px; height: 32px; font-size: 1.2rem; vertical-align: middle;">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                @else
                                    <button type="button" class="remove-row-btn" style="background: #f44336; color: #fff; border: none; border-radius: 50%; width: 32px; height: 32px; font-size: 1.2rem; vertical-align: middle;">
                                        <i class="fas fa-times"></i>
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div style="display: flex; gap: 18px; margin-top: 18px;">
                <button type="submit" id="updateBtn" style="background: #43b324; color: #fff; font-size: 1.15rem; font-weight: 700; border: none; border-radius: 6px; padding: 12px 48px; cursor: pointer;">Update</button>
                <button type="button" style="background: #f44336; color: #fff; font-size: 1.15rem; font-weight: 700; border: none; border-radius: 6px; padding: 12px 48px; cursor: pointer;" onclick="window.location='{{ route('admin.manage_subjects') }}'">Cancel</button>
            </div>
        </form>
    </div>
</div>

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

<script>
const teachers = @json($teachers);
const classes = @json($classes);
const days = @json($days);

// Get current subject and class information
const currentSubjectID = {{ $subject->subjectID ?? 0 }};
const currentSubjectName = '{{ $subject->subject_name ?? "" }}';
const currentClassID = {{ $existingAssignment->classID ?? 0 }};

// Store existing subjects for the current class (excluding current subject)
const existingSubjects = [
    @php
        if(isset($existingAssignment) && $existingAssignment) {
            $subjects = DB::table('class_subject')
                ->join('subjects', 'class_subject.subjectID', '=', 'subjects.subjectID')
                ->where('class_subject.classID', $existingAssignment->classID)
                ->where('subjects.subjectID', '!=', $subject->subjectID ?? 0)
                ->pluck('subjects.subject_name')
                ->toArray();
            foreach($subjects as $subjectName) {
                echo "'" . addslashes($subjectName) . "',";
            }
        }
    @endphp
];

// Real-time validation for subject name
document.getElementById('subject_name').addEventListener('input', function() {
    const subjectName = this.value.trim();
    const errorDiv = document.getElementById('subject_name_error');
    const existingDiv = document.getElementById('existing_subjects');
    const existingList = document.getElementById('existing_subjects_list');
    const submitBtn = document.getElementById('updateBtn');

    if (subjectName === '') {
        errorDiv.style.display = 'none';
        existingDiv.style.display = 'none';
        submitBtn.disabled = false;
        return;
    }

    if (existingSubjects.length > 0) {
        // Show existing subjects for this class
        existingList.textContent = existingSubjects.join(', ');
        existingDiv.style.display = 'block';

        // Check for duplicates (case-insensitive)
        const isDuplicate = existingSubjects.some(existing =>
            existing.toLowerCase() === subjectName.toLowerCase()
        );

        if (isDuplicate) {
            errorDiv.textContent = 'A subject with this name already exists in this class. Please choose a different name.';
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

function createAssignmentRow(index, assignment = {}) {
    // Get currently selected teachers
    const selectedTeachers = Array.from(document.querySelectorAll('select[name^="assignments"][name$="[employeeID]"]'))
        .map(select => select.value);

    // Filter out already selected teachers
    const availableTeachers = teachers.filter(t => !selectedTeachers.includes(t.employeeID.toString()));

    // If no teachers available, show message
    if (availableTeachers.length === 0) {
        return `<tr><td colspan="5" style="text-align: center; padding: 20px; color: #666;">
            All available teachers have been assigned to this subject
        </td></tr>`;
    }

    // If index is 0, show + button, else show X button
    return `<tr>
        <td style=\"padding: 10px 0; text-align: center;\">
            <select name=\"assignments[${index}][employeeID]\" required style=\"width: 200px; padding: 7px 10px; border-radius: 5px; border: 1px solid #ccc; font-size: 1rem;\">
                <option value="">Select Teacher</option>
                ${availableTeachers.length > 0
                    ? availableTeachers.map(t => `<option value=\"${t.employeeID}\"${assignment.employeeID == t.employeeID ? ' selected' : ''}>${t.firstname} ${t.lastname}</option>`).join('')
                    : '<option value="">No teachers available</option>'
                }
            </select>
        </td>
        <td style=\"padding: 10px 0; text-align: center;\">
            <select name=\"assignments[${index}][days]\" required style=\"width: 110px; padding: 7px 10px; border-radius: 5px; border: 1px solid #ccc; font-size: 1rem;\">
                <option value="">Select Day</option>
                ${days.map(d => `<option value=\"${d}\"${assignment.days == d ? ' selected' : ''}>${d}</option>`).join('')}
            </select>
        </td>
        <td style=\"padding: 10px 0; text-align: center;\">
            <input type=\"time\" name=\"assignments[${index}][start_time]\" required value=\"${assignment.start_time || ''}\" style=\"width: 120px; padding: 7px 10px; border-radius: 5px; border: 1px solid #ccc; font-size: 1rem;\">
        </td>
        <td style=\"padding: 10px 0; text-align: center;\">
            <input type=\"time\" name=\"assignments[${index}][end_time]\" required value=\"${assignment.end_time || ''}\" style=\"width: 120px; padding: 7px 10px; border-radius: 5px; border: 1px solid #ccc; font-size: 1rem;\">
        </td>
        <td style=\"padding: 10px 0; text-align: center;\">
            ${index === 0
                ? `<button type=\"button\" id=\"addRowBtn\" style=\"background: #1da1f2; color: #fff; border: none; border-radius: 50%; width: 32px; height: 32px; font-size: 1.2rem; vertical-align: middle;\"><i class=\"fas fa-plus\"></i></button>`
                : `<button type=\"button\" class=\"remove-row-btn\" style=\"background: #f44336; color: #fff; border: none; border-radius: 50%; width: 32px; height: 32px; font-size: 1.2rem; vertical-align: middle;\"><i class=\"fas fa-times\"></i></button>`
            }
        </td>
    </tr>`;
}

function checkTeacherClash(employeeID, days, startTime, endTime, excludeID, callback) {
    if (!employeeID || !days || !startTime || !endTime) return callback(false);
    fetch(`/api/check-teacher-clash?employeeID=${employeeID}&days=${days}&start_time=${startTime}&end_time=${endTime}&excludeID=${excludeID}`)
        .then(res => res.json())
        .then(data => callback(data.clash));
}

function attachClashCheck(row) {
    const teacherSelect = row.querySelector('select[name*="[employeeID]"]');
    const daySelect = row.querySelector('select[name*="[days]"]');
    const startTime = row.querySelector('input[name*="[start_time]"]');
    const endTime = row.querySelector('input[name*="[end_time]"]');
    const classsubjectID = row.getAttribute('data-classsubjectid') || '';
    [teacherSelect, daySelect, startTime, endTime].forEach(input => {
        if (input) {
            input.addEventListener('change', function() {
                const empID = teacherSelect.value;
                const day = daySelect.value;
                const st = startTime.value;
                const et = endTime.value;
                checkTeacherClash(empID, day, st, et, classsubjectID, function(clash) {
                    if (clash) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Schedule Clash',
                            text: 'This teacher already has another subject/class at this time!',
                            confirmButtonColor: '#3085d6'
                        });
                        startTime.value = '';
                        endTime.value = '';
                    }
                });
            });
        }
    });
}

function checkForDuplicateAssignments(changedInput) {
    const rows = document.querySelectorAll('#assignmentRows tr');
    const seen = new Map();
    let hasDuplicate = false;
    let duplicateRow = null;
    rows.forEach(row => {
        const teacher = row.querySelector('select[name*="[employeeID]"]')?.value;
        const day = row.querySelector('select[name*="[days]"]')?.value;
        const start = row.querySelector('input[name*="[start_time]"]')?.value;
        const end = row.querySelector('input[name*="[end_time]"]')?.value;
        if (teacher && day && start && end) {
            const key = `${teacher}|${day}|${start}|${end}`;
            if (seen.has(key)) {
                hasDuplicate = true;
                duplicateRow = row;
                row.style.background = '#ffeaea';
                seen.get(key).style.background = '#ffeaea';
            } else {
                row.style.background = '';
                seen.set(key, row);
            }
        } else {
            row.style.background = '';
        }
    });
    if (hasDuplicate && changedInput) {
        Swal.fire({
            icon: 'error',
            title: 'Duplicate Assignment',
            text: 'A teacher cannot be assigned to the same day and time range in the same class more than once!',
            confirmButtonColor: '#3085d6'
        });
        // Clear the changed field
        changedInput.value = '';
        changedInput.focus();
        return true;
    }
    return hasDuplicate;
}

function checkForDuplicateDayTime(changedInput) {
    const rows = document.querySelectorAll('#assignmentRows tr');
    const keyToRows = {};
    let hasDuplicate = false;
    let duplicateKeys = [];
    // Build map of key to rows
    rows.forEach(row => {
        const day = row.querySelector('select[name*="[days]"]')?.value;
        const start = row.querySelector('input[name*="[start_time]"]')?.value;
        const end = row.querySelector('input[name*="[end_time]"]')?.value;
        if (day && start && end) {
            const key = `${day}|${start}|${end}`;
            if (!keyToRows[key]) keyToRows[key] = [];
            keyToRows[key].push(row);
        }
    });
    // Highlight and detect duplicates
    rows.forEach(row => {
        const day = row.querySelector('select[name*="[days]"]')?.value;
        const start = row.querySelector('input[name*="[start_time]"]')?.value;
        const end = row.querySelector('input[name*="[end_time]"]')?.value;
        if (day && start && end) {
            const key = `${day}|${start}|${end}`;
            if (keyToRows[key] && keyToRows[key].length > 1) {
                row.style.background = '#ffeaea';
                hasDuplicate = true;
                if (!duplicateKeys.includes(key)) duplicateKeys.push(key);
            } else {
                row.style.background = '';
            }
        } else {
            row.style.background = '';
        }
    });
    if (hasDuplicate && changedInput) {
        Swal.fire({
            icon: 'error',
            title: 'Duplicate Time Slot',
            text: 'Cannot assign two teachers to the same day and time range in the same class!',
            confirmButtonColor: '#3085d6'
        });
        changedInput.value = '';
        changedInput.focus();
        return true;
    }
    return hasDuplicate;
}

function attachDuplicateCheckToAllFields() {
    document.querySelectorAll('select[name*="[days]"], input[name*="[start_time]"], input[name*="[end_time]"]').forEach(function(input) {
        input.removeEventListener('input', duplicateInputHandler);
        input.removeEventListener('change', duplicateInputHandler);
        input.addEventListener('input', duplicateInputHandler);
        input.addEventListener('change', duplicateInputHandler);
    });
}

function duplicateInputHandler(e) {
    checkForDuplicateDayTime(e.target);
}

document.addEventListener('DOMContentLoaded', function() {
    attachDuplicateCheckToAllFields();
    let assignmentIndex = {{ count($assignments) }};
    const assignmentRows = document.getElementById('assignmentRows');
    const form = document.getElementById('editSubjectForm');

    // Attach clash check to all initial rows
    document.querySelectorAll('#assignmentRows tr').forEach(row => attachClashCheck(row));
    // When adding a new row, attach the clash check
    document.getElementById('assignmentRows').addEventListener('DOMNodeInserted', function(e) {
        if (e.target.tagName === 'TR') attachClashCheck(e.target);
        attachDuplicateCheckToAllFields();
    });

    // Add form submission handler
    form.addEventListener('submit', function(e) {
        e.preventDefault();

        // Validate all rows
        let isValid = true;
        const rows = assignmentRows.querySelectorAll('tr');

        // Check for duplicate day and time range across teachers
        // OLD CODE (remove this block)
        // const timeAssignments = [];
        // rows.forEach((row) => {
        //     const teacherSelect = row.querySelector('select[name*="[employeeID]"]');
        //     const daySelect = row.querySelector('select[name*="[days]"]');
        //     const startTime = row.querySelector('input[name*="[start_time]"]');
        //     const endTime = row.querySelector('input[name*="[end_time]"]');
        //     if (teacherSelect && daySelect && startTime && endTime) {
        //         const key = `${daySelect.value}-${startTime.value}-${endTime.value}`;
        //         if (timeAssignments.includes(key)) {
        //             isValid = false;
        //             Swal.fire({
        //                 icon: 'error',
        //                 title: 'Duplicate Time Range',
        //                 text: 'No two teachers can be assigned the same day and time range.',
        //                 confirmButtonColor: '#3085d6'
        //             });
        //         }
        //         timeAssignments.push(key);
        //     }
        // });
        // NEW CODE: Only block if the SAME teacher is assigned to the same day and time range more than once
        const teacherTimeAssignments = [];
        rows.forEach((row) => {
            const teacherSelect = row.querySelector('select[name*="[employeeID]"]');
            const daySelect = row.querySelector('select[name*="[days]"]');
            const startTime = row.querySelector('input[name*="[start_time]"]');
            const endTime = row.querySelector('input[name*="[end_time]"]');
            if (teacherSelect && daySelect && startTime && endTime) {
                const key = `${teacherSelect.value}-${daySelect.value}-${startTime.value}-${endTime.value}`;
                if (teacherTimeAssignments.includes(key)) {
                    isValid = false;
                    Swal.fire({
                        icon: 'error',
                        title: 'Duplicate Assignment',
                        text: 'A teacher cannot be assigned to the same day and time range more than once.',
                        confirmButtonColor: '#3085d6'
                    });
                }
                teacherTimeAssignments.push(key);
            }
        });

        // Existing validation for time range and duplicate teacher-day
        let teacherDayAssignments = [];
        rows.forEach((row) => {
            const teacherSelect = row.querySelector('select[name*="[employeeID]"]');
            const daySelect = row.querySelector('select[name*="[days]"]');
            const startTime = row.querySelector('input[name*="[start_time]"]');
            const endTime = row.querySelector('input[name*="[end_time]"]');
            if (startTime && endTime) {
                if (startTime.value >= endTime.value) {
                    isValid = false;
                    Swal.fire({
                        icon: 'error',
                        title: 'Invalid Time Range',
                        text: 'End time must be later than start time',
                        confirmButtonColor: '#3085d6'
                    });
                }
            }
            if (teacherSelect && daySelect) {
                const key = `${teacherSelect.value}-${daySelect.value}`;
                if (teacherDayAssignments.includes(key)) {
                    isValid = false;
                    Swal.fire({
                        icon: 'error',
                        title: 'Duplicate Assignment',
                        text: 'A teacher cannot be assigned to the same day multiple times',
                        confirmButtonColor: '#3085d6'
                    });
                }
                teacherDayAssignments.push(key);
            }
        });

        if (isValid) {
            form.submit();
        }
    });

    // Add event listener for the entire table body
    assignmentRows.addEventListener('click', function(e) {
        if (e.target.closest('#addRowBtn')) {
            // Check if there are any available teachers before adding a row
            const selectedTeachers = Array.from(document.querySelectorAll('select[name^="assignments"][name$="[employeeID]"]'))
                .map(select => select.value);
            const availableTeachers = teachers.filter(t => !selectedTeachers.includes(t.employeeID.toString()));

            if (availableTeachers.length === 0) {
                // Show alert if no teachers available
                Swal.fire({
                    icon: 'info',
                    title: 'No Available Teachers',
                    text: 'All teachers have been assigned to this subject',
                    confirmButtonColor: '#3085d6'
                });
                return;
            }

            // Add new row
            assignmentRows.insertAdjacentHTML('beforeend', createAssignmentRow(assignmentIndex++));
            // Re-render action buttons
            renderActionButtons();
        }
        if (e.target.closest('.remove-row-btn')) {
            // Remove the specific row
            const row = e.target.closest('tr');
            row.remove();
            // Re-render action buttons
            renderActionButtons();
            // Re-enable the add button if it was disabled
            const addBtn = document.getElementById('addRowBtn');
            if (addBtn) {
                addBtn.disabled = false;
                addBtn.style.opacity = '1';
            }
        }
    });

    // Add event listener for teacher selection changes
    assignmentRows.addEventListener('change', function(e) {
        if (e.target.name && e.target.name.includes('[employeeID]')) {
            // Update available teachers in all dropdowns
            updateTeacherDropdowns();
        }
    });

    function updateTeacherDropdowns() {
        const selectedTeachers = Array.from(document.querySelectorAll('select[name^="assignments"][name$="[employeeID]"]'))
            .map(select => select.value);

        // Update each dropdown
        document.querySelectorAll('select[name^="assignments"][name$="[employeeID]"]').forEach(select => {
            const currentValue = select.value;
            const availableTeachers = teachers.filter(t =>
                !selectedTeachers.includes(t.employeeID.toString()) ||
                t.employeeID.toString() === currentValue
            );

            // Keep current selection and update other options
            select.innerHTML = availableTeachers
                .map(t => `<option value="${t.employeeID}"${t.employeeID.toString() === currentValue ? ' selected' : ''}>${t.firstname} ${t.lastname}</option>`)
                .join('');
        });

        // Update add button state
        const addBtn = document.getElementById('addRowBtn');
        if (addBtn) {
            const availableTeachers = teachers.filter(t => !selectedTeachers.includes(t.employeeID.toString()));
            addBtn.disabled = availableTeachers.length === 0;
            addBtn.style.opacity = availableTeachers.length === 0 ? '0.5' : '1';
        }
    }

    function renderActionButtons() {
        const rows = assignmentRows.querySelectorAll('tr');
        rows.forEach((row, idx) => {
            const actionCell = row.querySelector('td:last-child');
            if (actionCell) {
                if (idx === 0) {
                    // First row always has add button
                    actionCell.innerHTML = `<button type="button" id="addRowBtn" style="background: #1da1f2; color: #fff; border: none; border-radius: 50%; width: 32px; height: 32px; font-size: 1.2rem; vertical-align: middle;"><i class="fas fa-plus"></i></button>`;
                } else {
                    // Other rows have remove button
                    actionCell.innerHTML = `<button type="button" class="remove-row-btn" style="background: #f44336; color: #fff; border: none; border-radius: 50%; width: 32px; height: 32px; font-size: 1.2rem; vertical-align: middle;"><i class="fas fa-times"></i></button>`;
                }
            }
        });

        // Update teacher dropdowns after rendering buttons
        updateTeacherDropdowns();
    }

    // Initial render to fix any issues
    renderActionButtons();

    // Immediately check for duplicates on page load
    if (checkForDuplicateDayTime()) {
        Swal.fire({
            icon: 'error',
            title: 'Duplicate Time Slot',
            text: 'There are duplicate day and time assignments. Please fix them before saving.',
            confirmButtonColor: '#3085d6'
        });
    }

    // Attach duplicate check to all relevant field changes
    document.getElementById('assignmentRows').addEventListener('change', function(e) {
        const target = e.target;
        if (
            target.matches('select[name*="[employeeID]"]') ||
            target.matches('select[name*="[days]"]') ||
            target.matches('input[name*="[start_time]"]') ||
            target.matches('input[name*="[end_time]"]')
        ) {
            checkForDuplicateAssignments(target);
            validateAssignments(); // Add new validation
        } else {
            checkForDuplicateAssignments();
            validateAssignments(); // Add new validation
        }
    });

    // Add validation to individual assignment fields
    document.getElementById('assignmentRows').addEventListener('input', function(e) {
        const target = e.target;
        if (
            target.matches('select[name*="[days]"]') ||
            target.matches('input[name*="[start_time]"]') ||
            target.matches('input[name*="[end_time]"]')
        ) {
            checkForDuplicateDayTime(target);
            validateAssignments(); // Add new validation
        }
    });
    var editForm = document.getElementById('editSubjectForm');
    if (editForm) {
        editForm.addEventListener('submit', function(e) {
            // Always check for duplicates on submit
            if (checkForDuplicateDayTime() || checkDuplicateSlots() || checkOverlappingTeachers()) {
                Swal.fire({
                    icon: 'error',
                    title: 'Validation Error',
                    text: 'Please fix all validation errors before saving.',
                    confirmButtonColor: '#3085d6'
                });
                e.preventDefault();
                return false;
            }
        });
    }

});

// Enhanced validation functions for edit form
function checkDuplicateSlots() {
    const rows = document.querySelectorAll('#assignmentRows tr');
    const seenSlots = new Set();
    let hasDuplicate = false;

    rows.forEach(row => {
        const teacher = row.querySelector('select[name*="[employeeID]"]')?.value;
        const day = row.querySelector('select[name*="[days]"]')?.value;
        const startTime = row.querySelector('input[name*="[start_time]"]')?.value;
        const endTime = row.querySelector('input[name*="[end_time]"]')?.value;

        if (teacher && day && startTime && endTime) {
            const slotKey = `${teacher}_${day}_${startTime}_${endTime}`;
            if (seenSlots.has(slotKey)) {
                hasDuplicate = true;
                row.style.backgroundColor = '#ffeaea';
                row.querySelector('select[name*="[employeeID]"]').style.borderColor = '#f44336';
            } else {
                seenSlots.add(slotKey);
                row.style.backgroundColor = '';
                row.querySelector('select[name*="[employeeID]"]').style.borderColor = '';
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

            const teacher1 = row1.querySelector('select[name*="[employeeID]"]')?.value;
            const day1 = row1.querySelector('select[name*="[days]"]')?.value;
            const start1 = row1.querySelector('input[name*="[start_time]"]')?.value;
            const end1 = row1.querySelector('input[name*="[end_time]"]')?.value;

            const teacher2 = row2.querySelector('select[name*="[employeeID]"]')?.value;
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
                        row1.querySelector('select[name*="[employeeID]"]').style.borderColor = '#ffc107';
                        row2.querySelector('select[name*="[employeeID]"]').style.borderColor = '#ffc107';
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
    const submitBtn = document.getElementById('updateBtn');

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
            document.getElementById('editSubjectForm').parentNode.insertBefore(errorDiv, document.getElementById('editSubjectForm'));
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
</script>

<style>
.grade-dropdown-btn {
    background: #edc10c;
    color: #222;
    font-weight: 600;
    font-size: 1.08rem;
    border: none;
    border-radius: 22px;
    padding: 10px 32px 10px 22px;
    cursor: pointer;
    box-shadow: 0 2px 6px rgba(255,214,0,0.08);
    display: flex;
    align-items: center;
    gap: 8px;
    transition: background 0.2s;
}
.grade-dropdown-btn:focus,
.grade-dropdown-btn:hover {
    background: #ffe066;
}
</style>

</body>
 @include('footer') <!-- Include the footer -->
</html>

