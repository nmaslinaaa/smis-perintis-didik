<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'My Child')</title>
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
        .mychild-table-closedgrid {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            margin-top: 18px;
            /* Remove border-radius and overflow for closed borders */
            box-shadow: none;
            border: 1px solid #d9d9d9;
        }
        .mychild-table-closedgrid th,
        .mychild-table-closedgrid td {
            border: 1px solid #d9d9d9;
            padding: 16px 10px;
        }
        .mychild-table-closedgrid th {
            font-weight: 700;
            font-size: 1.08rem;
            background: #fff;
        }
        .mychild-table-closedgrid td {
            font-size: 1.08rem;
            background: #fff;
        }
        .mychild-table-closedgrid td:first-child,
        .mychild-table-closedgrid th:first-child {
            text-align: center;
            width: 40px;
        }
        .mychild-table-closedgrid td {
            font-weight: 400;
        }
        .mychild-table-closedgrid td.name-cell {
            font-weight: 600;
        }
        /* Ensure last row has bottom border */
        .mychild-table-closedgrid tr:last-child td {
            border-bottom: 1px solid #d9d9d9;
        }
    </style>
</head>

<body>

    @include('header') <!-- Include the header -->

<style>
    .student-list-container {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 4px 16px rgba(0,0,0,0.08);
        max-width: 1100px;
        margin: 0 auto;
        padding: 36px 36px 24px 36px;
    }
    .student-list-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0;
    }
    .student-list-title {
        font-size: 2rem;
        font-weight: 700;
        margin: 0;
    }
    .student-list-divider {
        width: 100%;
        height: 4px;
        background: #ffd600;
        margin-bottom: 24px;
        margin-top: 8px;
        border-radius: 2px;
    }
    .select-class-dropdown {
        background: #ffd600;
        color: #222;
        font-weight: 600;
        font-size: 1.08rem;
        border: none;
        border-radius: 22px;
        padding: 10px 28px 10px 22px;
        cursor: pointer;
        box-shadow: 0 2px 6px rgba(255,214,0,0.08);
        transition: background 0.2s;
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        outline: none;
        min-width: 160px;
    }
    .select-class-dropdown:focus {
        background: #ffe066;
    }
    .student-table {
        width: 100%;
        border-collapse: collapse;
        background: #fff;
        border-radius: 10px;
        overflow: hidden;
        margin-top: 0;
    }
    .student-table th {
        font-weight: 700;
        font-size: 1.08rem;
        text-align: left;
        padding: 12px 10px;
        border-bottom: 2px solid #e0e0e0;
        background: none;
    }
    .student-table td {
        font-size: 1.05rem;
        padding: 12px 10px;
        border-bottom: 1px solid #e0e0e0;
        background: #fff;
    }
    .student-table tr:last-child td {
        border-bottom: none;
    }
    .student-table .action-cell {
        white-space: nowrap;
    }
    .action-btn {
        border: none;
        border-radius: 4px;
        font-size: 0.98rem;
        font-weight: 600;
        padding: 6px 14px;
        margin-right: 4px;
        cursor: pointer;
        transition: background 0.2s;
        box-shadow: none;
        display: inline-block;
        vertical-align: middle;
    }
    .btn-view {
        background: #2d8bba;
        color: #fff;
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
    th.action-header {
        text-align: center;
    }
</style>

<div class="main-content" style="background:#f8f9fb;min-height:100vh;">
    <div style="background:#fff;border-radius:8px;box-shadow:none;max-width:1000px;margin:24px auto 0 auto;padding:24px 18px 18px 18px;">
        <div style="font-size:2rem;font-weight:700;margin-bottom:0;color:#222;line-height:1.1;">Children List</div>
        <div style="width:100%;height:4px;background:#ffd600;margin-bottom:0;margin-top:8px;border-radius:2px;"></div>
        <table class="mychild-table-closedgrid">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Class</th>
                    <th>Subject Taken</th>
                    <th>Fee Amount (RM)</th>
                </tr>
            </thead>
            <tbody>
                @forelse($students as $i => $student)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td class="name-cell">{{ $student->student_name }}</td>
                    <td>{{ $student->class ? $student->class->class_name : '-' }}</td>
                    <td>
                        @if($student->subjects && $student->subjects->count())
                            {{ $student->subjects->pluck('subject_name')->join(', ') }}
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        @php
                            $amount = 0;
                            foreach ($student->studentSubjects as $studentSubject) {
                                if ($studentSubject->classsubjectID) {
                                    $classSubject = DB::table('class_subject')->where('classsubjectID', $studentSubject->classsubjectID)->first();
                                    if ($classSubject) {
                                        $amount += $classSubject->subject_price;
                                    }
                                }
                            }
                        @endphp
                        {{ number_format($amount, 2) }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align:center;">No children found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

</body>
 @include('footer') <!-- Include the footer -->
</html>

