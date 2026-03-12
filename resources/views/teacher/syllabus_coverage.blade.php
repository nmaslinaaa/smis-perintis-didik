<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Syllabus Coverage')</title>
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
    <div class="syllabus-container">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div class="syllabus-title">Syllabus Coverage</div>
            <form method="GET" style="display: flex; gap: 18px; align-items: center;">
                <select name="classID" onchange="this.form.submit()" style="padding: 10px 24px; border-radius: 10px; border: 2px solid #ffe066; font-size: 1.08rem; min-width: 160px; background: #fffbe6; font-weight: 700;">
                    <option value="" disabled {{ !$selectedClassID ? 'selected' : '' }}>Select Class</option>
                    @foreach($classes as $class)
                        <option value="{{ $class->classID }}" {{ $selectedClassID == $class->classID ? 'selected' : '' }}>{{ $class->class_name }}</option>
                    @endforeach
                </select>
                <select name="subjectID" onchange="this.form.submit()" style="padding: 10px 24px; border-radius: 10px; border: 2px solid #ffe066; font-size: 1.08rem; min-width: 160px; background: #fffbe6; font-weight: 700;">
                    <option value="" disabled {{ !$selectedSubjectID ? 'selected' : '' }}>Select Subject</option>
                    @foreach($subjects as $subject)
                        <option value="{{ $subject->subjectID }}" {{ $selectedSubjectID == $subject->subjectID ? 'selected' : '' }}>{{ $subject->subject_name }}</option>
                    @endforeach
                </select>
            </form>
        </div>
        <div class="syllabus-divider"></div>
        <div class="syllabus-table-scroll">
            <table class="syllabus-table">
                <thead>
                    <tr>
                        <th style="width: 40px;">#</th>
                        <th style="width: 340px;">Chapter</th>
                        <th style="width: 220px; text-align: center;">Status</th>
                        <th style="width: 180px; text-align: center;">Completion Date</th>
                    </tr>
                </thead>
                <tbody>
                @if($selectedClassID && $selectedSubjectID)
                    @forelse($syllabus as $i => $item)
                        <tr>
                            <td>{{ $i+1 }}</td>
                            <td>{{ $item->syllabus_name }}</td>
                            <td style="text-align:center;">
                                <div class="status-dropdown-pill">
                                    <select class="syllabus-status-select" data-syllabus-id="{{ $item->syllabusID }}">
                                        <option value="Completed" {{ $item->status == 'Completed' ? 'selected' : '' }}>Completed</option>
                                        <option value="Not Completed" {{ $item->status == 'Not Completed' ? 'selected' : '' }}>Not Completed</option>
                                    </select>
                                    <span class="dropdown-arrow"><i class="fas fa-chevron-down"></i></span>
                                </div>
                            </td>
                            <td class="completion-date" style="text-align:center;">{{ $item->date_completed ? date('d/m/Y', strtotime($item->date_completed)) : '-' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" style="text-align:center; color:#888;">No syllabus items found for this class and subject.</td></tr>
                    @endforelse
                @else
                    <tr><td colspan="4" style="text-align:center; color:#888;">Please select both a class and a subject to view syllabus coverage.</td></tr>
                @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
.syllabus-container {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.07);
    max-width: 1045px;
    margin: 10px auto 0 auto;
    padding: 32px 28px 0px 28px;
    border: 1px solid #ececec;
    min-height: 600px;
    display: flex;
    flex-direction: column;
}
.syllabus-title {
    font-size: 2rem;
    font-weight: 700;
    color: #222;
    letter-spacing: -1px;
}
.syllabus-divider {
    width: 100%;
    height: 3px;
    background: #edc10c;
    margin-bottom: 18px;
    margin-top: 14px;
    border-radius: 2px;
}
.syllabus-table-scroll {
    margin-top: 0;
    height: 480px;
    overflow-y: auto;
    border-radius: 8px;
    background: #fff;
}
.syllabus-table {
    width: 100%;
    border-collapse: collapse;
    background: #fff;
    font-size: 1.08rem;
    border: 1px solid #e0e0e0;
}
.syllabus-table th, .syllabus-table td {
    border: 1px solid #e0e0e0;
    padding: 10px 0 10px 12px;
    background: #fff;
    color: #222;
    vertical-align: middle;
}
.syllabus-table th {
    font-weight: 700;
    font-size: 1.08rem;
    background: none;
    color: #222;
    text-align: left;
}
.syllabus-table th:nth-child(3),
.syllabus-table td:nth-child(3),
.syllabus-table th:nth-child(4),
.syllabus-table td:nth-child(4) {
    text-align: center;
}
.status-dropdown-pill {
    display: inline-flex;
    align-items: center;
    background: #e0e0e0;
    border-radius: 18px;
    padding: 0 0 0 0;
    min-width: 140px;
    height: 38px;
    position: relative;
    box-shadow: none;
}
.syllabus-status-select {
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
    border: none;
    outline: none;
    font-size: 1.08rem;
    font-weight: 600;
    border-radius: 18px;
    padding: 8px 38px 8px 18px;
    min-width: 140px;
    text-align: left;
    color: #444;
    background: transparent;
    cursor: pointer;
    box-shadow: none;
    height: 38px;
    z-index: 2;
}
.status-dropdown-pill .dropdown-arrow {
    position: absolute;
    right: 16px;
    top: 50%;
    transform: translateY(-50%);
    pointer-events: none;
    color: #888;
    font-size: 1.1rem;
    z-index: 3;
}
.syllabus-status-select option {
    color: #222;
    text-align: left;
}
</style>
<script>
function getTodayDateStr() {
    const today = new Date();
    let d = today.getDate();
    let m = today.getMonth() + 1;
    let y = today.getFullYear();
    return `${m}/${d}/${y}`;
}
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.syllabus-status-select').forEach(function(select) {
        select.addEventListener('change', function() {
            const row = select.closest('tr');
            const dateCell = row.querySelector('.completion-date');
            const syllabusID = select.getAttribute('data-syllabus-id');
            const status = select.value;
            fetch("/teacher/syllabus_coverage/update_status", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ syllabusID, status })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    dateCell.textContent = data.date_completed;
                } else {
                    dateCell.textContent = '-';
                }
            });
        });
    });
});
</script>


</body>
 @include('footer') <!-- Include the footer -->
</html>

