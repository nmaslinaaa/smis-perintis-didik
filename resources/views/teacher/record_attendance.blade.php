<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Record Attendance')</title>
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
            padding: 2px;
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
            padding: 0px 105px;;
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

<div class="main-content">
    <div class="class-list-container attendance-container">
        <div class="attendance-header-bar">
            <div class="attendance-header-title">Record Attendance</div>
            <form method="GET" class="attendance-header-controls" style="margin:0; padding:0;">
                <select name="classID" onchange="this.form.submit()">
                    @foreach($classes as $class)
                        <option value="{{ $class['classID'] }}" {{ $selectedClassID == $class['classID'] ? 'selected' : '' }}>{{ $class['class_name'] }}</option>
                    @endforeach
                </select>
                <select name="subjectID" onchange="this.form.submit()">
                    @foreach($subjects as $subject)
                        <option value="{{ $subject['subjectID'] }}" {{ $selectedSubjectID == $subject['subjectID'] ? 'selected' : '' }}>{{ $subject['subject_name'] }}</option>
                    @endforeach
                </select>
                @if($slot)
                    <span class="attendance-slot-pill">{{ $slot->days }} {{ substr($slot->start_time,0,5) }}-{{ substr($slot->end_time,0,5) }}</span>
                    <input type="hidden" id="slot-day" value="{{ $slot->days }}">
                @endif
            </form>
            <div class="attendance-indicators">
                <span class="status-pill present mini">P</span>
                <span class="legend-label">Present</span>
                <span class="status-pill late mini">L</span>
                <span class="legend-label">Leave with permission</span>
                <span class="status-pill absent mini">A</span>
                <span class="legend-label">Absent</span>
                <span class="legend-label" style="margin-left: 15px; color: #25d366; font-size: 0.9rem;">
                    <i class="fab fa-whatsapp"></i> Click to notify parent
                </span>
                <span class="legend-label" style="margin-left: 10px; color: #666; font-size: 0.8rem;">
                    (Parents must opt-in first via WhatsApp)
                </span>
                <div style="margin-top: 5px; font-size: 0.75rem; color: #888;">
                    <i class="fas fa-info-circle"></i>
                    Parents send "join <sandbox-code>" to +14155238886 to receive notifications
                </div>
                <button class="save-btn" type="button" id="saveAttendanceBtn" onclick="document.getElementById('attendance-form').submit();">Save</button>
            </div>
        </div>
        <form id="attendance-form" method="POST" action="{{ url('/teacher/record_attendance/save') }}">
            @csrf
            <input type="hidden" name="classID" value="{{ $selectedClassID }}">
            <input type="hidden" name="subjectID" value="{{ $selectedSubjectID }}">
            <input type="hidden" name="slot" value="{{ $slot->classsubjectID ?? '' }}">
            <div class="class-list-divider" style="height:3px; background:#edc10c; margin-bottom:12px; margin-top:14px; border-radius:2px;"></div>
            <div class="attendance-table-scroll">
                <table class="attendance-table">
                    <thead>
                        <tr>
                            <th style="width: 40px; text-align:center;">#</th>
                            <th style="width: 220px; text-align:left;">Name</th>
                            <th style="width: 180px; text-align:center;">Parent</th>
                            <th style="width: 220px; text-align: center;">Status</th>
                            <th style="width: 80px; text-align: center;">Notify</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students as $i => $student)
                        <tr>
                            <td style="text-align:center;">{{ $i+1 }}</td>
                            <td style="text-align:left;">{{ $student->student_name }}</td>
                            <td style="text-align:center;">{{ $student->parent_name ?? '' }}</td>
                            <td style="text-align:center;">
                                <label class="status-pill selectable" data-status="P" style="cursor:pointer;">
                                    <input type="radio" name="attendance[{{ $student->studentID }}]" value="P" style="display:none;" required
                                        {{ (isset($attendanceData[$student->studentID]) && $attendanceData[$student->studentID] == 'Present') ? 'checked' : '' }}>
                                    P
                                </label>
                                <label class="status-pill selectable" data-status="L" style="cursor:pointer;">
                                    <input type="radio" name="attendance[{{ $student->studentID }}]" value="L" style="display:none;"
                                        {{ (isset($attendanceData[$student->studentID]) && $attendanceData[$student->studentID] == 'Leave with permission') ? 'checked' : '' }}>
                                    L
                                </label>
                                <label class="status-pill selectable" data-status="A" style="cursor:pointer;">
                                    <input type="radio" name="attendance[{{ $student->studentID }}]" value="A" style="display:none;"
                                        {{ (isset($attendanceData[$student->studentID]) && $attendanceData[$student->studentID] == 'Absent') ? 'checked' : '' }}>
                                    A
                                </label>
                            </td>
                            <td style="text-align:center;">
                                @php
                                    $hasAttendance = isset($attendanceData[$student->studentID]);
                                @endphp
                                <i class="fab fa-whatsapp notify-icon {{ $hasAttendance ? 'clickable' : 'disabled' }}"
                                   @if($hasAttendance)
                                   onclick="sendAttendanceNotification({{ $student->studentID }}, {{ $selectedClassID }}, {{ $selectedSubjectID }})"
                                   title="Send WhatsApp notification to parent"
                                   @else
                                   title="Record attendance first to send notification"
                                   @endif
                                   style="cursor: {{ $hasAttendance ? 'pointer' : 'not-allowed' }}; opacity: {{ $hasAttendance ? '1' : '0.5' }};"></i>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" style="text-align:center; color:#b71c1c; font-weight:bold;">No students registered for this slot.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </form>
    </div>
</div>

<style>
.attendance-container {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.07);
    max-width: 1045px;
    margin: 10px auto 24px auto;
    padding: 32px 28px 0px 28px;
    border: 1px solid #ececec;
    /* min-height: 600px; */
    display: flex;
    flex-direction: column;
}
.attendance-table-scroll {
    margin-top: 0;
    /* height: 480px; */
    overflow-y: auto;
    border-radius: 8px;
    background: #fff;
    padding-bottom: 24px;
}
.attendance-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    background: #fff;
    font-size: 1.08rem;
    border: 1px solid #e0e0e0;
    border-radius: 12px;
    overflow: hidden;
}
.attendance-table th, .attendance-table td {
    border-bottom: 1px solid #e0e0e0;
    border-right: 1px solid #e0e0e0;
    padding: 14px 0 14px 16px;
    background: #fff;
    color: #222;
    vertical-align: middle;
}
.attendance-table th:first-child, .attendance-table td:first-child {
    border-left: none;
}
.attendance-table th {
    font-weight: 700;
    font-size: 1.08rem;
    background: #fafafa;
    color: #222;
    text-align: left;
    border-top: none;
}
.attendance-table tr:last-child td {
    border-bottom: none;
}
.attendance-table th:nth-child(4),
.attendance-table td:nth-child(4),
.attendance-table th:nth-child(5),
.attendance-table td:nth-child(5) {
    text-align: center;
}
.status-pill {
    display: inline-block;
    width: 32px;
    height: 32px;
    line-height: 32px;
    border-radius: 50%;
    background: #e0e0e0;
    color: #444;
    font-weight: bold;
    font-size: 1.08rem;
    text-align: center;
    margin: 0 4px;
    vertical-align: middle;
    transition: background 0.2s, color 0.2s, box-shadow 0.2s;
    cursor: pointer;
    border: 2px solid transparent;
    user-select: none;
}
.status-pill.present { background: #e0e0e0; color: #444; }
.status-pill.late { background: #e0e0e0; color: #444; }
.status-pill.absent { background: #e0e0e0; color: #444; }
.status-pill.selected.present { background: #3cc13b; color: #fff; border-color: #3cc13b; }
.status-pill.selected.late { background: #ffd600; color: #222; border-color: #ffd600; }
.status-pill.selected.absent { background: #ff6b6b; color: #fff; border-color: #ff6b6b; }
.status-pill.selected { box-shadow: 0 0 0 2px #222; border-color: #222; }
.status-pill.selectable:hover { box-shadow: 0 0 0 2px #edc10c; border-color: #edc10c; }
.status-pill.mini {
    width: 18px;
    height: 18px;
    line-height: 18px;
    font-size: 13px;
    margin: 0 2px;
    border-width: 2.5px;
}
/* Always show color for legend mini pills */
.attendance-legend-mini .status-pill.present.mini {
    background: #3cc13b;
    color: #fff;
    border-color: #3cc13b;
}
.attendance-legend-mini .status-pill.late.mini {
    background: #ffd600;
    color: #222;
    border-color: #ffd600;
}
.attendance-legend-mini .status-pill.absent.mini {
    background: #ff6b6b;
    color: #fff;
    border-color: #ff6b6b;
}
.attendance-legend-mini {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 0.98rem;
    font-weight: 500;
    color: #444;
    margin-left: 10px;
}
.legend-label {
    font-size: 0.93rem;
    color: #444;
    margin-right: 7px;
    margin-left: 2px;
}
.save-btn {
    background: #e3eef7;
    color: #222;
    font-weight: 600;
    font-size: 1.08rem;
    border: none;
    border-radius: 18px;
    padding: 8px 32px;
    cursor: pointer;
    box-shadow: 0 2px 6px rgba(0,0,0,0.04);
    transition: background 0.2s;
    margin-right: 2px;
}
.save-btn:focus,
.save-btn:hover {
    background: #d0e6f3;
}
.notify-icon {
    color: #25d366;
    font-size: 1.6rem;
    vertical-align: middle;
    transition: all 0.3s ease;
}

.notify-icon.clickable:hover {
    transform: scale(1.2);
    color: #128c7e;
}

.notify-icon.disabled {
    color: #ccc;
    opacity: 0.5;
}
.attendance-header-bar {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    padding: 24px 32px 18px 32px;
    margin-bottom: 18px;
    display: flex;
    align-items: center;
    gap: 24px;
    flex-wrap: wrap;
    border: 1px solid #ececec;
}
.attendance-header-title {
    font-size: 2rem;
    font-weight: 700;
    margin-right: 18px;
    color: #222;
    letter-spacing: -1px;
}
.attendance-header-controls {
    display: flex;
    align-items: center;
    gap: 16px;
    flex-wrap: wrap;
}
.attendance-header-controls select {
    padding: 10px 22px;
    border-radius: 12px;
    border: 1.5px solid #e0e0e0;
    font-size: 1.08rem;
    font-weight: 600;
    background: #f7f7f7;
    min-width: 140px;
    transition: border 0.2s;
}
.attendance-header-controls select:focus {
    border: 1.5px solid #ffd600;
    outline: none;
}
.attendance-slot-pill {
    padding: 7px 18px;
    border-radius: 18px;
    font-size: 1.08rem;
    background: #f7f7f7;
    border: 1px solid #ccc;
    display: inline-block;
    min-width: 180px;
    margin-left: 8px;
    color: #333;
    font-weight: 500;
}
.attendance-indicators {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-left: auto;
}
.attendance-indicators .status-pill {
    width: 22px;
    height: 22px;
    line-height: 22px;
    font-size: 1rem;
    margin: 0 2px;
    border-width: 2px;
}
.attendance-indicators .legend-label {
    font-size: 1rem;
    color: #444;
    margin-right: 8px;
    margin-left: 2px;
}
.attendance-header-bar .save-btn {
    margin-left: 18px;
    padding: 8px 32px;
    font-size: 1.08rem;
    border-radius: 18px;
    background: #e3eef7;
    color: #222;
    font-weight: 600;
    border: none;
    box-shadow: 0 2px 6px rgba(0,0,0,0.04);
    transition: background 0.2s;
}
.attendance-header-bar .save-btn:focus,
.attendance-header-bar .save-btn:hover {
    background: #d0e6f3;
}
@media (max-width: 900px) {
    .attendance-header-bar {
        flex-direction: column;
        align-items: flex-start;
        padding: 18px 10px 10px 10px;
    }
    .attendance-header-controls {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }
    .attendance-indicators {
        margin-left: 0;
        margin-top: 10px;
    }
}
</style>
<style>
.attendance-indicators .status-pill.present {
    background: #3cc13b !important;
    color: #fff !important;
    border-color: #3cc13b !important;
}
.attendance-indicators .status-pill.late {
    background: #ffd600 !important;
    color: #222 !important;
    border-color: #ffd600 !important;
}
.attendance-indicators .status-pill.absent {
    background: #ff6b6b !important;
    color: #fff !important;
    border-color: #ff6b6b !important;
}
</style>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function getTodayMalayDay() {
    const days = ['Ahad', 'Isnin', 'Selasa', 'Rabu', 'Khamis', 'Jumaat', 'Sabtu'];
    const today = new Date();
    return days[today.getDay()];
}
function updateSaveButtonState() {
    const slotDayInput = document.getElementById('slot-day');
    const saveBtn = document.getElementById('saveAttendanceBtn');
    const slotDay = slotDayInput ? slotDayInput.value : null;
    const todayMalay = getTodayMalayDay();
    if (slotDay && saveBtn) {
        if (slotDay !== todayMalay) {
            saveBtn.disabled = true;
            saveBtn.title = `You can only record attendance on ${slotDay}. Today is ${todayMalay}.`;
            saveBtn.style.opacity = 0.5;
        } else {
            saveBtn.disabled = false;
            saveBtn.title = '';
            saveBtn.style.opacity = 1;
        }
    }
}
document.addEventListener('DOMContentLoaded', function() {
    updateSaveButtonState();
    document.querySelectorAll('td[style*="text-align:center;"]').forEach(function(cell) {
        var pills = cell.querySelectorAll('.status-pill.selectable');
        pills.forEach(function(pill) {
            pill.addEventListener('click', function() {
                pills.forEach(function(p) {
                    p.classList.remove('selected', 'present', 'late', 'absent');
                    // Reset to gray
                    if (p.dataset.status === 'P') p.classList.add('present');
                    else if (p.dataset.status === 'L') p.classList.add('late');
                    else if (p.dataset.status === 'A') p.classList.add('absent');
                });
                this.classList.add('selected');
                if (this.dataset.status === 'P') this.classList.add('present');
                else if (this.dataset.status === 'L') this.classList.add('late');
                else if (this.dataset.status === 'A') this.classList.add('absent');
            });
        });
        // Set all to gray on load
        pills.forEach(function(p) {
            p.classList.remove('selected', 'present', 'late', 'absent');
            if (p.dataset.status === 'P') p.classList.add('present');
            else if (p.dataset.status === 'L') p.classList.add('late');
            else if (p.dataset.status === 'A') p.classList.add('absent');
        });
    });

    const form = document.getElementById('attendance-form');
    if (form) {
        form.addEventListener('submit', function(e) {
            const slotDay = document.getElementById('slot-day') ? document.getElementById('slot-day').value : null;
            if (slotDay) {
                const todayMalay = getTodayMalayDay();
                if (slotDay !== todayMalay) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'warning',
                        title: 'Attendance Not Allowed',
                        text: `Today is not ${slotDay}. You can only record attendance on ${slotDay} for this slot.`,
                        confirmButtonColor: '#ffd600',
                        showClass: { popup: 'animate__animated animate__shakeX' },
                        hideClass: { popup: 'animate__animated animate__fadeOut' }
                    });
                }
            }
        });
    }

    // Attendance status pill color logic
    document.querySelectorAll('.status-pill input[type="radio"]').forEach(function(radio) {
        radio.addEventListener('change', function() {
            var row = this.closest('tr');
            var pills = row.querySelectorAll('.status-pill');
            pills.forEach(function(pill) {
                pill.classList.remove('selected', 'present', 'late', 'absent');
            });
            var label = this.closest('label');
            label.classList.add('selected');
            if (this.value === 'P') label.classList.add('present');
            else if (this.value === 'L') label.classList.add('late');
            else if (this.value === 'A') label.classList.add('absent');
        });
        // Highlight on page load if checked
        if (radio.checked) {
            var label = radio.closest('label');
            label.classList.add('selected');
            if (radio.value === 'P') label.classList.add('present');
            else if (radio.value === 'L') label.classList.add('late');
            else if (radio.value === 'A') label.classList.add('absent');
        }
    });
});
</script>

<script>
function sendAttendanceNotification(studentID, classID, subjectID) {
    // Check if the icon is disabled (no attendance recorded)
    const icon = event.target;
    if (icon.classList.contains('disabled')) {
        Swal.fire({
            icon: 'warning',
            title: 'No Attendance Recorded',
            text: 'Please record attendance for this student first before sending a notification.',
            confirmButtonColor: '#ffd600'
        });
        return;
    }

    // Show loading state
    const originalClass = icon.className;
    icon.className = 'fas fa-spinner fa-spin';
    icon.style.color = '#ffd600';

    // Get CSRF token
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Send AJAX request
    fetch('/teacher/send_attendance_notification', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token
        },
        body: JSON.stringify({
            studentID: studentID,
            classID: classID,
            subjectID: subjectID
        })
    })
    .then(response => response.json())
    .then(data => {
        // Reset icon
        icon.className = originalClass;

        if (data.success) {
            // Show success message
            Swal.fire({
                icon: 'success',
                title: 'Notification Sent!',
                text: 'WhatsApp notification has been sent to the parent successfully.',
                showConfirmButton: false,
                timer: 2000,
                showClass: { popup: 'animate__animated animate__fadeInDown' },
                hideClass: { popup: 'animate__animated animate__fadeOutUp' }
            });

            // Change icon color to green temporarily
            icon.style.color = '#25d366';
            setTimeout(() => {
                icon.style.color = '#25d366'; // Keep WhatsApp green
            }, 2000);
        } else {
            // Show error message
            Swal.fire({
                icon: 'error',
                title: 'Failed to Send',
                text: data.message || 'Failed to send WhatsApp notification. Please try again.',
                confirmButtonColor: '#ffd600'
            });

            // Reset icon color
            icon.style.color = '#25d366';
        }
    })
    .catch(error => {
        console.error('Error:', error);

        // Reset icon
        icon.className = originalClass;
        icon.style.color = '#25d366';

        // Show error message
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'An error occurred while sending the WhatsApp notification. Please try again.',
            confirmButtonColor: '#ffd600'
        });
    });
}
</script>
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
    },
    showClass: { popup: 'animate__animated animate__fadeInDown' },
    hideClass: { popup: 'animate__animated animate__fadeOutUp' }
});
</script>
@endif
</body>
 @include('footer') <!-- Include the footer -->
</html>

