<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard')</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
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
            margin-left: auto;
            margin-right: auto;
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

        /* Performance Badge Animations and Tooltips */
        .performance-badge {
            position: relative;
            cursor: pointer;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .performance-badge:hover {
            transform: scale(1.1);
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
        }

        .performance-badge.tooltip {
            position: relative;
        }

        .performance-badge.tooltip::before {
            content: attr(data-tooltip);
            position: absolute;
            bottom: 125%;
            left: 50%;
            transform: translateX(-50%);
            background: #333;
            color: white;
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 12px;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease, visibility 0.3s ease;
            z-index: 1000;
            max-width: 350px;
            min-width: 150px;
            word-wrap: break-word;
            white-space: normal;
            text-align: center;
        }

        .performance-badge.tooltip::after {
            content: '';
            position: absolute;
            bottom: 115%;
            left: 50%;
            transform: translateX(-50%);
            border: 5px solid transparent;
            border-top-color: #333;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease, visibility 0.3s ease;
        }

        .performance-badge.tooltip:hover::before,
        .performance-badge.tooltip:hover::after {
            opacity: 1;
            visibility: visible;
        }

        /* Row animation for performance table */
        .performance-row {
            opacity: 0;
            transform: translateY(20px);
            animation: fadeInUp 0.6s ease forwards;
        }

        .performance-row:nth-child(1) { animation-delay: 0.1s; }
        .performance-row:nth-child(2) { animation-delay: 0.2s; }
        .performance-row:nth-child(3) { animation-delay: 0.3s; }
        .performance-row:nth-child(4) { animation-delay: 0.4s; }
        .performance-row:nth-child(5) { animation-delay: 0.5s; }

        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Score bar animation - matching teacher dashboard */
        .score-bar-animated {
            background: linear-gradient(90deg, #a4c6ea 0%, #7bb3e8 100%);
            height: 28px;
            border-radius: 10px 0 0 10px;
            display: inline-block;
            transition: width 1.5s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            z-index: 5;
        }

        .score-bar-animated::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            animation: shimmer 2s infinite;
        }

        @keyframes shimmer {
            0% { left: -100%; }
            100% { left: 100%; }
        }

        .score-label-animated {
            transition: all 1.5s cubic-bezier(0.4, 0, 0.2, 1);
            position: absolute;
            left: 0;
            right: 0;
            text-align: center;
            font-weight: bold;
            color: #222;
            font-size: 16px;
            z-index: 10;
            line-height: 28px;
            pointer-events: none;
        }

        /* Ensure text is always visible */
        .score-bar-bg .score-label-animated {
            color: #222;
            text-shadow: 0 0 2px rgba(255,255,255,0.8);
        }

        /* Circle pulse animation */
        .circle {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        /* Payment Status Badge Styles */
        .payment-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: bold;
            min-width: 30px;
            height: 24px;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .payment-badge.paid {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .payment-badge.unpaid {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .payment-badge i {
            font-size: 12px;
            margin-right: 4px;
        }

        .payment-badge:hover {
            transform: scale(1.05);
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }

        /* Payment table row animation */
        .payment-row {
            opacity: 0;
            transform: translateY(20px);
            animation: fadeInUp 0.6s ease forwards;
        }

        .payment-row:nth-child(1) { animation-delay: 0.1s; }
        .payment-row:nth-child(2) { animation-delay: 0.2s; }
        .payment-row:nth-child(3) { animation-delay: 0.3s; }
        .payment-row:nth-child(4) { animation-delay: 0.4s; }
        .payment-row:nth-child(5) { animation-delay: 0.5s; }

        /* Chart container improvements */
        .chart-container {
            position: relative;
            min-height: 400px;
            margin: 20px 0;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        /* Ensure tooltips don't get cut off */
        .chart-container canvas {
            max-height: 380px !important;
        }
    </style>
</head>

<body>

    @include('header') <!-- Include the header -->

   <div class="main-content">
        <!-- Container for content -->
        <div class="container-fluid">
            <div class="row">
                <!-- Column 1: Total Stats -->
                <div class="stats">
                <div class="col-md-3">
                    <a href="{{ url('/admin/all_student') }}" style="text-decoration: none; color: inherit;">
                        <div class="stat-card card-student" style="cursor: pointer; transition: transform 0.2s ease;">
                        <h3>Total Students</h3>
                        <div class="panel-value">
                            <div class="icon">
                                <i class="fas fa-user-graduate"></i> <!-- Student icon -->
                            </div>
                                <p>{{ $totalStudents }}</p>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-md-3">
                    <a href="{{ url('/admin/all_employee') }}" style="text-decoration: none; color: inherit;">
                        <div class="stat-card card-teacher" style="cursor: pointer; transition: transform 0.2s ease;">
                            <h3>Total Employees</h3>
                        <div class="panel-value">
                            <div class="icon">
                                    <i class="fas fa-users"></i> <!-- Employees icon -->
                                </div>
                                <p>{{ $totalEmployees }}</p>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-md-3">
                    <a href="{{ url('/admin/manage_classes') }}" style="text-decoration: none; color: inherit;">
                        <div class="stat-card card-classes" style="cursor: pointer; transition: transform 0.2s ease;">
                        <h3>Total Classes</h3>
                        <div class="panel-value">
                            <div class="icon">
                                <i class="fas fa-clipboard-list"></i> <!-- Class/schedule icon -->
                            </div>
                                <p>{{ $totalClasses }}</p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Column 2: Performance Stats -->
            <div class="row" style="display: flex; justify-content: center;">
                <div class="col-md-8">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="performance-card">
                                <div class="performance-title">
                                    Student Performance
                                    <select id="class-select" class="grade-dropdown">
                                        <option value="">Select Class</option>
                                        @foreach($classes as $class)
                                            <option value="{{ $class->classID }}" @if($class->classID == $defaultClassID) selected @endif>{{ $class->class_name }}</option>
                                        @endforeach
                                    </select>
                                    <select id="month-select" class="grade-dropdown">
                                        <option value="">Select Month</option>
                                        @foreach($months as $month)
                                            <option value="{{ $month }}" @if($month == $currentMonth) selected @endif>{{ $month }}</option>
                                        @endforeach
                                    </select>
                                    {{-- <select id="month-select" class="grade-dropdown">
                                        <option value="">Select Year</option>
                                        <option value="">2025</option>
                                    </select> --}}
                                </div>
                                <table class="performance-table">
                                    <thead>
                                        <tr>
                                            <th style="width: 200px;">Name</th>
                                            <th style="width: 300px;">Average Score</th>
                                            <th style="width: 160px;">Need Help</th>
                                            <th style="width: 120px;">Good</th>
                                            <th style="width: 120px;">Excellent</th>
                                        </tr>
                                    </thead>
                                    <tbody id="performance-tbody">
                                        <tr>
                                            <td colspan="5" style="text-align: center; color: #888; padding: 20px;">
                                                Please select a class and month to view student performance data.
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Attendance and Payment Status Section -->
            <div class="row" style="margin-top: 30px;">
                <!-- Student Attendance Card -->
                <div class="col-md-12">
                    <div class="performance-card">
                        <div class="performance-title" style="margin-bottom: 20px;">
                            Student Attendance
                            <select id="attendance-class-select" class="grade-dropdown">
                                <option value="">Select Class</option>
                                @foreach($classes as $class)
                                    <option value="{{ $class->classID }}" @if($class->classID == $defaultClassID) selected @endif>{{ $class->class_name }}</option>
                                @endforeach
                            </select>
                            <select id="attendance-subject-select" class="grade-dropdown">
                                <option value="">All Subjects</option>
                            </select>
                        </div>
                        <div class="chart-container">
                            <!-- Line Chart Container -->
                            <canvas id="attendanceChart" style="width: 100%; height: 380px;"></canvas>
                        </div>
                        <div style="text-align: center; margin-top: 10px;">
                            <span id="attendance-summary" style="color:#222; font-size:15px;">Select class and month to view attendance data</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Status Section -->
            <div class="row" style="margin-top: 30px;">
                <div class="col-md-12">
                    <div class="performance-card">
                        <div class="performance-title" style="margin-bottom: 20px;">
                            Payment Status
                            <select id="payment-class-select" class="grade-dropdown">
                                <option value="">Select Class</option>
                                @foreach($classes as $class)
                                    <option value="{{ $class->classID }}" @if($class->classID == $defaultClassID) selected @endif>{{ $class->class_name }}</option>
                                @endforeach
                            </select>
                            <select id="payment-month-select" class="grade-dropdown">
                                <option value="">Select Month</option>
                                @foreach($months as $month)
                                    <option value="{{ $month }}" @if($month == $currentMonth) selected @endif>{{ $month }}</option>
                                @endforeach
                            </select>
                        </div>
                        <table class="performance-table" style="margin-top: 0;">
                            <thead>
                                <tr>
                                    <th style="width: 60%;">Student Name</th>
                                    <th style="width: 40%; text-align: center;">Payment Status</th>
                                </tr>
                            </thead>
                            <tbody id="payment-tbody">
                                <tr>
                                    <td colspan="2" style="text-align: center; color: #888; padding: 20px;">
                                        Please select a class and month to view payment status data.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- End Payment Status Section -->
         </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const classSelect = document.getElementById('class-select');
            const monthSelect = document.getElementById('month-select');
            const performanceTbody = document.getElementById('performance-tbody');

            function fetchPerformanceData() {
                const classID = classSelect.value;
                const month = monthSelect.value;

                if (!classID || !month) {
                    performanceTbody.innerHTML = `
                        <tr>
                            <td colspan="5" style="text-align: center; color: #888; padding: 20px;">
                                Please select a class and month to view student performance data.
                            </td>
                        </tr>
                    `;
                    return;
                }

                // Show loading
                performanceTbody.innerHTML = `
                    <tr>
                        <td colspan="5" style="text-align: center; color: #888; padding: 20px;">
                            Loading performance data...
                        </td>
                    </tr>
                `;

                fetch('/admin/performance-data', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')
                    },
                    body: JSON.stringify({ classID, month })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.length === 0) {
                        performanceTbody.innerHTML = `
                            <tr>
                                <td colspan="5" style="text-align: center; color: #888; padding: 20px;">
                                    No performance data found for the selected class and month.
                                </td>
                            </tr>
                        `;
                        return;
                    }

                    performanceTbody.innerHTML = data.map((student, index) => {
                        // Create tooltip content for each badge
                        const needHelpTooltip = student.need_help_subjects.length > 0
                            ? `Need Help: ${student.need_help_subjects.join(', ')}`
                            : 'No subjects need help';

                        const goodTooltip = student.good_subjects.length > 0
                            ? `Good: ${student.good_subjects.join(', ')}`
                            : 'No subjects with good performance';

                        const excellentTooltip = student.excellent_subjects.length > 0
                            ? `Excellent: ${student.excellent_subjects.join(', ')}`
                            : 'No subjects with excellent performance';

                        return `
                            <tr class="performance-row" data-index="${index}">
                                <td><b>${student.student_name}</b></td>
                                <td>
                                    <div style="position: relative; display: inline-block; width: 220px;">
                                        <div class="score-bar-bg">
                                            <div class="score-bar-animated" data-score="${student.average_score}" style="width: 0%;"></div>
                                            <span class="score-label-animated" data-score="${student.average_score}">0%</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="circle red performance-badge tooltip" data-tooltip="${needHelpTooltip}">${student.needing_attention}%</span>
                                </td>
                                <td>
                                    <span class="circle yellow performance-badge tooltip" data-tooltip="${goodTooltip}">${student.moderate}%</span>
                                </td>
                                <td>
                                    <span class="circle green performance-badge tooltip" data-tooltip="${excellentTooltip}">${student.mastered}%</span>
                                </td>
                            </tr>
                        `;
                    }).join('');

                    // Animate score bars after rendering
                    setTimeout(() => {
                        animateScoreBars();
                    }, 100);
                })
                .catch(error => {
                    console.error('Error:', error);
                    performanceTbody.innerHTML = `
                        <tr>
                            <td colspan="5" style="text-align: center; color: #888; padding: 20px;">
                                Error loading performance data. Please try again.
                            </td>
                        </tr>
                    `;
                });
            }

            // Add event listeners
            classSelect.addEventListener('change', fetchPerformanceData);
            monthSelect.addEventListener('change', fetchPerformanceData);

            // Auto-fetch data on page load if both dropdowns have values
            if (classSelect.value && monthSelect.value) {
                fetchPerformanceData();
            }

            function animateScoreBars() {
                const scoreBars = document.querySelectorAll('.score-bar-animated');
                const scoreLabels = document.querySelectorAll('.score-label-animated');
                const circles = document.querySelectorAll('.circle');

                // Animate score bars
                scoreBars.forEach((bar, index) => {
                    const targetScore = parseInt(bar.getAttribute('data-score'));
                    const label = scoreLabels[index];

                    // Animate bar width
                    setTimeout(() => {
                        bar.style.width = targetScore + '%';
                    }, index * 200); // Stagger animation

                    // Animate label text
                    let currentScore = 0;
                    const increment = targetScore / 30; // 30 steps for smooth animation
                    const interval = setInterval(() => {
                        currentScore += increment;
                        if (currentScore >= targetScore) {
                            currentScore = targetScore;
                            clearInterval(interval);
                        }
                        if (label) {
                            label.textContent = Math.round(currentScore) + '%';
                            // Ensure text stays centered and visible
                            label.style.color = '#222';
                            label.style.textShadow = '0 0 2px rgba(255,255,255,0.8)';
                        }
                    }, 50);
                });

                // Animate circles with delay
                circles.forEach((circle, index) => {
                    setTimeout(() => {
                        circle.style.animation = 'pulse 2s infinite';
                    }, 1000 + (index * 100)); // Start after score bars finish
                });
            }
        });

        // Attendance Chart Functionality
        let attendanceChart = null;

        document.addEventListener('DOMContentLoaded', function() {
            const attendanceClassSelect = document.getElementById('attendance-class-select');
            const attendanceSubjectSelect = document.getElementById('attendance-subject-select');
            const attendanceSummary = document.getElementById('attendance-summary');

            function fetchAttendanceData() {
                const classID = attendanceClassSelect.value;
                const subjectID = attendanceSubjectSelect.value;

                if (!classID) {
                    if (attendanceChart) {
                        attendanceChart.destroy();
                        attendanceChart = null;
                    }
                    attendanceSummary.textContent = 'Select class to view attendance data';
                    return;
                }

                // Show loading
                attendanceSummary.textContent = 'Loading attendance data...';

                fetch('/admin/attendance-data', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')
                    },
                    body: JSON.stringify({ classID, subjectID })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.length === 0) {
                        attendanceSummary.textContent = 'No attendance data found for the selected criteria';
                        if (attendanceChart) {
                            attendanceChart.destroy();
                            attendanceChart = null;
                        }
                        return;
                    }

                    renderAttendanceChart(data);
                    updateAttendanceSummary(data);
                })
                .catch(error => {
                    console.error('Error:', error);
                    attendanceSummary.textContent = 'Error loading attendance data. Please try again.';
                });
            }

            function renderAttendanceChart(data) {
                const ctx = document.getElementById('attendanceChart').getContext('2d');

                // Destroy existing chart if it exists
                if (attendanceChart) {
                    attendanceChart.destroy();
                }

                // Prepare chart data
                const labels = data[0].monthly_attendance.map(item => item.month);
                const datasets = [];

                // Define colors for different subjects
                const colors = [
                    '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0',
                    '#9966FF', '#FF9F40', '#FF6384', '#C9CBCF',
                    '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0'
                ];

                data.forEach((subject, index) => {
                    const color = colors[index % colors.length];

                    // Check if subject has any attendance data
                    const hasData = subject.monthly_attendance.some(month => month.total_students > 0);

                    datasets.push({
                        label: subject.subject_name,
                        data: subject.monthly_attendance.map(item => item.attendance_percentage),
                        borderColor: color,
                        backgroundColor: color + '20',
                        borderWidth: hasData ? 3 : 2,
                        fill: false,
                        tension: 0.4,
                        pointBackgroundColor: color,
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: hasData ? 4 : 3,
                        pointHoverRadius: 6,
                        // Make subjects with no data more transparent
                        borderDash: hasData ? [] : [5, 5],
                        opacity: hasData ? 1 : 0.6
                    });
                });

                attendanceChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: datasets
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'top',
                                labels: {
                                    usePointStyle: true,
                                    padding: 15,
                                    font: {
                                        size: 12
                                    },
                                    // Show all subjects in legend, even those with no data
                                    filter: function(legendItem, data) {
                                        return true; // Show all items
                                    },
                                    generateLabels: function(chart) {
                                        const datasets = chart.data.datasets;
                                        return datasets.map((dataset, index) => ({
                                            text: dataset.label,
                                            fillStyle: dataset.borderColor,
                                            strokeStyle: dataset.borderColor,
                                            lineWidth: dataset.borderWidth,
                                            pointStyle: 'circle',
                                            hidden: false,
                                            index: index
                                        }));
                                    }
                                }
                            },
                            tooltip: {
                                mode: 'index',
                                intersect: false,
                                backgroundColor: 'rgba(0, 0, 0, 0.9)',
                                titleColor: '#fff',
                                bodyColor: '#fff',
                                borderColor: '#333',
                                borderWidth: 1,
                                cornerRadius: 8,
                                padding: 12,
                                displayColors: true,
                                callbacks: {
                                    title: function(context) {
                                        return data[0].monthly_attendance[context[0].dataIndex].month;
                                    },
                                    label: function(context) {
                                        const subject = context.dataset.label;
                                        const percentage = context.parsed.y;
                                        const monthData = data[context.datasetIndex].monthly_attendance[context.dataIndex];

                                        if (monthData.total_students === 0) {
                                            return [
                                                `${subject}: No attendance data`,
                                                `No students recorded for this month`
                                            ];
                                        }

                                        return [
                                            `${subject}: ${percentage}%`,
                                            `Present: ${monthData.present}/${monthData.total_students}`,
                                            `Absent: ${monthData.absent} | Leave: ${monthData.leave}`
                                        ];
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                max: 100,
                                ticks: {
                                    callback: function(value) {
                                        return value + '%';
                                    }
                                },
                                grid: {
                                    color: 'rgba(0,0,0,0.1)'
                                }
                            },
                            x: {
                                grid: {
                                    color: 'rgba(0,0,0,0.1)'
                                }
                            }
                        },
                        interaction: {
                            mode: 'nearest',
                            axis: 'x',
                            intersect: false
                        },
                        animation: {
                            duration: 1000,
                            easing: 'easeInOutQuart'
                        }
                    }
                });
            }

            function updateAttendanceSummary(data) {
                if (data.length === 0) {
                    attendanceSummary.textContent = 'No attendance data available';
                    return;
                }

                // Calculate overall statistics
                let totalPresent = 0;
                let totalAbsent = 0;
                let totalLeave = 0;
                let totalRecords = 0;
                let subjectsWithData = 0;

                data.forEach(subject => {
                    let subjectHasData = false;
                    subject.monthly_attendance.forEach(month => {
                        if (month.total_students > 0) {
                            totalPresent += month.present;
                            totalAbsent += month.absent;
                            totalLeave += month.leave;
                            totalRecords += month.total_students;
                            subjectHasData = true;
                        }
                    });
                    if (subjectHasData) {
                        subjectsWithData++;
                    }
                });

                const overallPercentage = totalRecords > 0 ? Math.round((totalPresent / totalRecords) * 100) : 0;

                if (totalRecords === 0) {
                    attendanceSummary.textContent = `No attendance data recorded for ${data.length} subjects`;
                } else {
                    attendanceSummary.textContent = `Overall: ${overallPercentage}% Present | ${totalPresent} Present, ${totalAbsent} Absent, ${totalLeave} Leave | ${subjectsWithData}/${data.length} subjects have data`;
                }
            }

            // Populate subject dropdown when class changes
            function updateSubjectDropdown() {
                const classID = attendanceClassSelect.value;
                if (!classID) {
                    attendanceSubjectSelect.innerHTML = '<option value="">All Subjects</option>';
                    return;
                }

                // Fetch subjects for the selected class
                fetch(`/admin/class/${classID}/subjects`)
                    .then(response => response.json())
                    .then(subjects => {
                        attendanceSubjectSelect.innerHTML = '<option value="">All Subjects</option>';
                        subjects.forEach(subject => {
                            attendanceSubjectSelect.innerHTML += `<option value="${subject.subjectID}">${subject.subject_name}</option>`;
                        });
                    })
                    .catch(error => {
                        console.error('Error fetching subjects:', error);
                    });
            }

            // Event listeners
            attendanceClassSelect.addEventListener('change', function() {
                updateSubjectDropdown();
                fetchAttendanceData();
            });
            attendanceSubjectSelect.addEventListener('change', fetchAttendanceData);

            // Initialize
            if (attendanceClassSelect.value) {
                updateSubjectDropdown();
                setTimeout(fetchAttendanceData, 500); // Small delay to ensure subject dropdown is populated
            }
        });

        // Payment Status Functionality
        document.addEventListener('DOMContentLoaded', function() {
            const paymentClassSelect = document.getElementById('payment-class-select');
            const paymentMonthSelect = document.getElementById('payment-month-select');
            const paymentTbody = document.getElementById('payment-tbody');

            function fetchPaymentStatusData() {
                const classID = paymentClassSelect.value;
                const month = paymentMonthSelect.value;

                if (!classID || !month) {
                    paymentTbody.innerHTML = `
                        <tr>
                            <td colspan="2" style="text-align: center; color: #888; padding: 20px;">
                                Please select a class and month to view payment status data.
                            </td>
                        </tr>
                    `;
                    return;
                }

                // Show loading
                paymentTbody.innerHTML = `
                    <tr>
                        <td colspan="2" style="text-align: center; color: #888; padding: 20px;">
                            Loading payment status data...
                        </td>
                    </tr>
                `;

                fetch('/admin/payment-status-data', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')
                    },
                    body: JSON.stringify({ classID, month })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.length === 0) {
                        paymentTbody.innerHTML = `
                            <tr>
                                <td colspan="2" style="text-align: center; color: #888; padding: 20px;">
                                    No payment status data found for the selected class and month.
                                </td>
                            </tr>
                        `;
                        return;
                    }

                    paymentTbody.innerHTML = data.map((student, index) => {
                        const statusIcon = student.status.toLowerCase() === 'paid' ?
                            '<span style="color:green; font-size:22px;"><i class="fas fa-check-square"></i></span>' :
                            '<span style="color:red; font-size:22px;"><i class="fas fa-times-circle"></i></span>';

                        return `
                            <tr class="payment-row" data-index="${index}">
                                <td><b>${student.student_name}</b></td>
                                <td style="text-align: center;">${statusIcon}</td>
                            </tr>
                        `;
                    }).join('');
                })
                .catch(error => {
                    console.error('Error:', error);
                    paymentTbody.innerHTML = `
                        <tr>
                            <td colspan="2" style="text-align: center; color: #888; padding: 20px;">
                                Error loading payment status data. Please try again.
                            </td>
                        </tr>
                    `;
                });
            }

            // Event listeners
            paymentClassSelect.addEventListener('change', fetchPaymentStatusData);
            paymentMonthSelect.addEventListener('change', fetchPaymentStatusData);

            // Initialize
            if (paymentClassSelect.value && paymentMonthSelect.value) {
                fetchPaymentStatusData();
            }
        });
    </script>
</body>
 @include('footer') <!-- Include the footer -->
</html>

