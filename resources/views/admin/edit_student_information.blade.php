<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Edit Student Information')</title>
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
            padding-bottom: 15px;
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

        /* --- Dashboard Card Styles (Only for dashboard content) --- */
        .dashboard-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
            max-width: 950px;
            margin: 0 auto;
            padding: 36px 40px 32px 40px;

        }
        .dashboard-title {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 18px;
            color: #222;
        }
        .dashboard-divider {
            border: none;
            border-top: 3px solid #e6c200;
            margin-bottom: 30px;
            margin-top: 0;
        }
        .dashboard-info {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            margin-bottom: 30px;
        }
        .dashboard-info-col {
            width: 48%;
        }
        .dashboard-info-col ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .dashboard-info-col li {
            margin-bottom: 18px;
        }
        .dashboard-info-col li strong {
            font-size: 1.1rem;
            font-weight: bold;
            color: #111;
        }
        .dashboard-info-col li span {
            display: block;
            margin-top: 2px;
            font-size: 1.08rem;
            color: #222;
        }
        .dashboard-info-col .subject-list {
            margin-top: 2px;
            margin-left: 18px;
        }
        .dashboard-info-col .subject-list li {
            margin-bottom: 0;
            font-size: 1.08rem;
            color: #222;
        }
        .dashboard-actions {
            display: flex;
            justify-content: flex-start;
            gap: 18px;
            margin-top: 18px;
        }
        .dashboard-actions button {
            min-width: 120px;
            padding: 12px 0;
            border: none;
            border-radius: 6px;
            font-size: 1.1rem;
            font-weight: bold;
            color: #fff;
            cursor: pointer;
            transition: background 0.2s;
        }
        .dashboard-actions .accept-btn {
            background: #19d219;
        }
        .dashboard-actions .accept-btn:hover {
            background: #13b013;
        }
        .dashboard-actions .reject-btn {
            background: #e02a2a;
        }
        .dashboard-actions .reject-btn:hover {
            background: #b81d1d;
        }
        @media (max-width: 1100px) {
            .dashboard-card {
                padding: 24px 10px 20px 10px;
            }
            .dashboard-info {
                flex-direction: column;
            }
            .dashboard-info-col {
                width: 100%;
                margin-bottom: 18px;
            }
        }
        .timing-select {
            max-width: 320px;
            min-width: 220px;
            width: auto;
            margin-left: 6px;
            box-sizing: border-box;
        }
        .subject-select {
            min-width: 160px;
            margin-right: 6px;
        }
        .subject-dropdown-row {
            width: 100%;
            display: flex;
            align-items: center;
            gap: 8px;
        }
    </style>
</head>

<body>

    @include('header') <!-- Include the header -->

   <div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div style="background: #fff; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.04); padding: 28px 20px 18px 20px; max-width: 1100px; margin: 0 auto;">
                    <h2 style="font-size: 24px; font-weight: 700; margin: 0 0 8px 0;">Edit {{ $student->student_name }}</h2>
                    <div style="width: 100%; height: 3px; background: #ffd600; margin-bottom: 18px;"></div>
                    <form style="display: flex; flex-wrap: wrap; gap: 20px 24px;" method="POST" action="{{ route('admin.update_student_information', $student->studentID) }}">
                        @csrf
                        <div style="flex: 1 1 240px; min-width: 200px;">
                            <div style="margin-bottom: 12px;">
                                <label style="font-weight: bold; font-size: 16px; display: block; margin-bottom: 4px;">Student Name</label>
                                <input type="text" name="student_name" value="{{ $student->student_name }}" style="width: 100%; padding: 7px; font-size: 15px; border: 1px solid #ccc; border-radius: 4px;">
                            </div>
                            <div style="margin-bottom: 12px;">
                                <label style="font-weight: bold; font-size: 16px; display: block; margin-bottom: 4px;">School Name</label>
                                <input type="text" name="school_name" value="{{ $student->school_name }}" style="width: 100%; padding: 7px; font-size: 15px; border: 1px solid #ccc; border-radius: 4px;">
                            </div>
                            <div style="margin-bottom: 12px;">
                                <label style="font-weight: bold; font-size: 16px; display: block; margin-bottom: 4px;">Address</label>
                                <input type="text" name="address" value="{{ $student->address }}" style="width: 100%; padding: 7px; font-size: 15px; border: 1px solid #ccc; border-radius: 4px;">
                            </div>
                            <div style="margin-bottom: 12px;">
                                <label style="font-weight: bold; font-size: 16px; display: block; margin-bottom: 4px;">Start Date</label>
                                <input type="date" name="tuition_startdate" value="{{ $student->tuition_startdate }}" style="width: 100%; padding: 7px; font-size: 15px; border: 1px solid #ccc; border-radius: 4px;" disabled>
                            </div>
                            <div style="margin-bottom: 12px;">
                                <label style="font-weight: bold; font-size: 16px; display: block; margin-bottom: 4px;">Status</label>
                                <select name="student_status" style="width: 100%; padding: 7px; font-size: 15px; border: 1px solid #ccc; border-radius: 4px;">
                                    <option value="1" {{ $student->student_status == 1 ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ $student->student_status == 0 ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                        </div>
                        <div style="flex: 1 1 240px; min-width: 200px;">
                            <div style="margin-bottom: 12px;">
                                <label style="font-weight: bold; font-size: 16px; display: block; margin-bottom: 4px;">Parent/Guardian Name</label>
                                <input type="text" value="{{ $student->parent ? $student->parent->name : '-' }}" style="width: 100%; padding: 7px; font-size: 15px; border: 1px solid #ccc; border-radius: 4px;" disabled>
                            </div>
                            <div style="margin-bottom: 12px;">
                                <label style="font-weight: bold; font-size: 16px; display: block; margin-bottom: 4px;">Parent Email</label>
                                <input type="text" value="{{ $student->parent ? $student->parent->email : '-' }}" style="width: 100%; padding: 7px; font-size: 15px; border: 1px solid #ccc; border-radius: 4px;" disabled>
                            </div>
                            <div style="margin-bottom: 12px;">
                                <label style="font-weight: bold; font-size: 16px; display: block; margin-bottom: 4px;">Parent Phone</label>
                                <input type="text" value="{{ $student->parent ? $student->parent->phonenumber : '-' }}" style="width: 100%; padding: 7px; font-size: 15px; border: 1px solid #ccc; border-radius: 4px;" disabled>
                            </div>
                            <div style="margin-bottom: 12px;">
                                <label style="font-weight: bold; font-size: 16px; display: block; margin-bottom: 4px;">Class</label>
                                <select name="classID" style="width: 100%; padding: 7px; font-size: 15px; border: 1px solid #ccc; border-radius: 4px;">
                                    @foreach($classes as $class)
                                        <option value="{{ $class->classID }}" {{ $student->classID == $class->classID ? 'selected' : '' }}>{{ $class->class_name }}</option>
                                    @endforeach
                                </select>
                </div>
                            <div style="margin-bottom: 12px;">
                                <label style="font-weight: bold; font-size: 16px; display: block; margin-bottom: 4px;">Subject Taken</label>
                                <div id="subject-dropdowns">
                                @foreach($student->subjects as $i => $subject)
                                    <div class="subject-dropdown-row" style="display:flex;align-items:center;gap:8px;">
                                        <select class="add-employee-select subject-select" name="subject_taken[]" required>
                                            <option value="" disabled>Select Subject</option>
                                            <!-- Options will be populated by JS -->
                                        </select>
                                        <select class="add-employee-select timing-select" name="subject_timing[]" style="display:none;" required>
                                            <option value="" disabled>Select Timing</option>
                                            <!-- Options will be populated by JS -->
                                        </select>
                                        @if($i > 0)
                                        <button type="button" class="remove-subject-btn" style="background:#f44336;color:#fff;border:none;border-radius:50%;width:28px;height:28px;font-size:1rem;cursor:pointer;display:flex;align-items:center;justify-content:center;margin-top:2px;">✖</button>
                                        @endif
                                    </div>
                                    @endforeach
                                    @if($student->subjects->count() == 0)
                                    <div class="subject-dropdown-row" style="display:flex;align-items:center;gap:8px;">
                                        <select class="add-employee-select subject-select" name="subject_taken[]" required>
                                            <option value="" selected>Select Subject</option>
                                        </select>
                                        <select class="add-employee-select timing-select" name="subject_timing[]" style="display:none;" required>
                                            <option value="" selected>Select Timing</option>
                                        </select>
                                    </div>
                                    @endif
                                </div>
                                <button type="button" id="add-more-subject" style="margin-top:8px; background:#5a8ecb; color:#fff; border:none; border-radius:5px; padding:6px 16px; font-size:1rem; cursor:pointer;">add more</button>
                            </div>
                            <script>
                            window.addEventListener('DOMContentLoaded', function() {
                                const addMoreBtn = document.getElementById('add-more-subject');
                                const subjectDropdownsDiv = document.getElementById('subject-dropdowns');
                                const classSelect = document.querySelector('select[name="classID"]');
                                let subjectsData = [];
                                let initialSubjects = @json($student->subjects);
                                let initialTimings = @json(\App\Models\StudentSubject::where('studentID', $student->studentID)->pluck('classsubjectID'));

                                function updateSubjectDropdowns(subjects) {
                                    subjectsData = subjects;
                                    const subjectRows = subjectDropdownsDiv.querySelectorAll('.subject-dropdown-row');
                                    subjectRows.forEach((row, idx) => {
                                        const subjectSelect = row.querySelector('.subject-select');
                                        const timingSelect = row.querySelector('.timing-select');
                                        subjectSelect.options.length = 1;
                                        if (subjects.length === 0) {
                                            const option = document.createElement('option');
                                            option.value = '';
                                            option.textContent = 'No subjects available';
                                            option.disabled = true;
                                            subjectSelect.appendChild(option);
                                            subjectSelect.selectedIndex = 1;
                                            timingSelect.style.display = 'none';
                                        } else {
                                            subjects.forEach(subject => {
                                                const option = document.createElement('option');
                                                option.value = subject.subjectID;
                                                option.textContent = subject.subject_name;
                                                subjectSelect.appendChild(option);
                                            });
                                            timingSelect.style.display = 'none';
                                            timingSelect.options.length = 1;
                                        }
                                    });
                                }
                                function updateSubjectDropdownOptions() {
                                    const subjectSelects = subjectDropdownsDiv.querySelectorAll('.subject-select');
                                    const selectedValues = Array.from(subjectSelects)
                                        .map(select => select.value)
                                        .filter(val => val !== "");
                                    subjectSelects.forEach(select => {
                                        const currentValue = select.value;
                                        Array.from(select.options).forEach(option => {
                                            if (option.value === "" || option.value === currentValue) {
                                                option.disabled = false;
                                            } else if (selectedValues.includes(option.value)) {
                                                option.disabled = true;
                                            } else {
                                                option.disabled = false;
                                            }
                                        });
                                    });
                                }
                                function timeRangesOverlap(a, b) {
                                    if (!a || !b) return false;
                                    if (a.day !== b.day) return false;
                                    return (a.start < b.end && b.start < a.end);
                                }
                                function parseTimingOption(option) {
                                    const match = option.textContent.match(/^(\S+)\s(\d{2}:\d{2}:\d{2})-(\d{2}:\d{2}:\d{2})/);
                                    if (!match) return null;
                                    return { day: match[1], start: match[2], end: match[3] };
                                }
                                function updateTimingDropdownOptions() {
                                    const timingSelects = subjectDropdownsDiv.querySelectorAll('.timing-select');
                                    const selectedTimings = Array.from(timingSelects)
                                        .map(select => {
                                            const selectedOption = select.options[select.selectedIndex];
                                            return selectedOption && selectedOption.value ? { value: selectedOption.value, ...parseTimingOption(selectedOption) } : null;
                                        })
                                        .filter(val => val && val.value);
                                    timingSelects.forEach(select => {
                                        const currentValue = select.value;
                                        Array.from(select.options).forEach(option => {
                                            if (option.value === "" || option.value === currentValue) {
                                                option.disabled = false;
                                            } else {
                                                const thisTiming = parseTimingOption(option);
                                                const clash = selectedTimings.some(sel => sel.value !== option.value && timeRangesOverlap(thisTiming, sel));
                                                option.disabled = clash;
                                            }
                                        });
                                    });
                                }
                                function attachSubjectSelectListeners() {
                                    const subjectSelects = subjectDropdownsDiv.querySelectorAll('.subject-select');
                                    subjectSelects.forEach(select => {
                                        select.removeEventListener('change', handleSubjectChange);
                                        select.addEventListener('change', handleSubjectChange);
                                        select.removeEventListener('change', updateSubjectDropdownOptions);
                                        select.addEventListener('change', updateSubjectDropdownOptions);
                                    });
                                }
                                function attachTimingSelectListeners() {
                                    const timingSelects = subjectDropdownsDiv.querySelectorAll('.timing-select');
                                    timingSelects.forEach(select => {
                                        select.removeEventListener('change', updateTimingDropdownOptions);
                                        select.addEventListener('change', updateTimingDropdownOptions);
                                    });
                                }
                                function attachAllListeners() {
                                    attachSubjectSelectListeners();
                                    attachTimingSelectListeners();
                                }
                                function handleSubjectChange(e) {
                                    const subjectSelect = e.target;
                                    const row = subjectSelect.closest('.subject-dropdown-row');
                                    const timingSelect = row.querySelector('.timing-select');
                                    const subjectID = subjectSelect.value;
                                    timingSelect.options.length = 1;
                                    if (!subjectID) {
                                        timingSelect.style.display = 'none';
                                        timingSelect.disabled = true;
                                        updateTimingDropdownOptions();
                                        return;
                                    }
                                    const subject = subjectsData.find(s => s.subjectID == subjectID);
                                    if (subject && subject.options.length > 0) {
                                        subject.options.forEach(opt => {
                                            const option = document.createElement('option');
                                            option.value = opt.classsubjectID;
                                            option.textContent = `${opt.days} ${opt.start_time}-${opt.end_time} (${opt.teacher_name || ''})`;
                                            timingSelect.appendChild(option);
                                        });
                                        timingSelect.style.display = '';
                                        timingSelect.disabled = false;
                                    } else {
                                        timingSelect.style.display = 'none';
                                        timingSelect.disabled = true;
                                    }
                                    updateTimingDropdownOptions();
                                }
                                function refreshSubjectDropdowns(subjects) {
                                    updateSubjectDropdowns(subjects);
                                        updateSubjectDropdownOptions();
                                    attachAllListeners();
                                    updateTimingDropdownOptions();
                                }
                                classSelect.addEventListener('change', function() {
                                    const classID = this.value;
                                    if (!classID) {
                                        refreshSubjectDropdowns([]);
                                        return;
                                    }
                                    fetch(`/parent/class/${classID}/subjects`)
                                        .then(response => response.json())
                                        .then(subjects => {
                                            refreshSubjectDropdowns(subjects);
                                        });
                                });
                                // Initial fetch for current class
                                if (classSelect.value) {
                                    fetch(`/parent/class/${classSelect.value}/subjects`)
                                        .then(response => response.json())
                                        .then(subjects => {
                                            refreshSubjectDropdowns(subjects);
                                            // Preselect current values
                                            const subjectRows = subjectDropdownsDiv.querySelectorAll('.subject-dropdown-row');
                                            subjectRows.forEach((row, idx) => {
                                                const subjectSelect = row.querySelector('.subject-select');
                                                const timingSelect = row.querySelector('.timing-select');
                                                if (initialSubjects[idx]) {
                                                    subjectSelect.value = initialSubjects[idx].subjectID;
                                                    subjectSelect.dispatchEvent(new Event('change'));
                                                    setTimeout(() => {
                                                        if (timingSelect && initialTimings[idx]) {
                                                            timingSelect.value = initialTimings[idx];
                                                            timingSelect.style.display = '';
                                                        }
                                                    }, 100);
                                                }
                                            });
                                        });
                                }
                                // Add more button
                                addMoreBtn.addEventListener('click', function() {
                                    const firstRow = subjectDropdownsDiv.querySelector('.subject-dropdown-row');
                                    const select = firstRow.querySelector('.subject-select');
                                    const timingSelect = firstRow.querySelector('.timing-select');
                                            const newRow = document.createElement('div');
                                            newRow.className = 'subject-dropdown-row';
                                            newRow.style.display = 'flex';
                                            newRow.style.alignItems = 'center';
                                            newRow.style.gap = '8px';
                                    const newSelect = select.cloneNode(true);
                                    newSelect.value = '';
                                    const newTimingSelect = timingSelect.cloneNode(true);
                                    newTimingSelect.value = '';
                                    newTimingSelect.style.display = 'none';
                                            const removeBtn = document.createElement('button');
                                            removeBtn.type = 'button';
                                            removeBtn.innerHTML = '✖';
                                            removeBtn.title = 'Remove';
                                            removeBtn.style.background = '#f44336';
                                            removeBtn.style.color = '#fff';
                                            removeBtn.style.border = 'none';
                                            removeBtn.style.borderRadius = '50%';
                                            removeBtn.style.width = '28px';
                                            removeBtn.style.height = '28px';
                                            removeBtn.style.fontSize = '1rem';
                                            removeBtn.style.cursor = 'pointer';
                                            removeBtn.style.display = 'flex';
                                            removeBtn.style.alignItems = 'center';
                                            removeBtn.style.justifyContent = 'center';
                                            removeBtn.style.marginTop = '2px';
                                    removeBtn.addEventListener('click', function() {
                                        newRow.remove();
                                        updateSubjectDropdownOptions();
                                        updateTimingDropdownOptions();
                                    });
                                            newRow.appendChild(newSelect);
                                    newRow.appendChild(newTimingSelect);
                                            newRow.appendChild(removeBtn);
                                            subjectDropdownsDiv.appendChild(newRow);
                                    attachAllListeners();
                                            updateSubjectDropdownOptions();
                                    updateTimingDropdownOptions();
                                });
                                attachAllListeners();
                                updateTimingDropdownOptions();
                            });
                            </script>
                        </div>
                        <div style="width: 100%; display: flex; gap: 14px; justify-content: flex-start; margin-top: 6px;">
                            <button type="submit" style="background: #19d219; color: #fff; font-size: 16px; font-weight: bold; border: none; border-radius: 6px; padding: 8px 28px; cursor: pointer;">Update</button>
                            <a href="{{ url('/admin/all_student') }}"><button type="button" style="background: #f44336; color: #fff; font-size: 16px; font-weight: bold; border: none; border-radius: 6px; padding: 8px 28px; cursor: pointer;">Cancel</button></a>
                        </div>
                    </form>
                </div>
                </div>
            </div>
        </div>
    </div>

<!-- SweetAlert2 for modern rejection popup -->
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
<form id="rejectForm" method="POST" action="{{ route('admin.reject_new_student', $student->studentID) }}" style="display:none;">
    @csrf
    <input type="hidden" name="rejection_reason" id="rejection_reason_input">
</form>

</body>
 @include('footer') <!-- Include the footer -->
</html>

