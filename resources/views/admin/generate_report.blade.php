<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard')</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <!-- Internal CSS -->
    <style>
        /* General styles */
        body {
            font-family: 'Roboto', Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f6f8fa;
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
        /* --- Enhanced Modal Styles --- */
        #report-modal > div {
            box-shadow: 0 10px 40px rgba(0,0,0,0.18);
            border-radius: 18px;
            border: 1px solid #e0e4ea;
        }
        /* --- Export PDF Button --- */
        .export-pdf-btn {
            background: linear-gradient(90deg, #edc10c 0%, #f7d900 100%);
            color: #222;
            border: none;
            border-radius: 22px;
            padding: 10px 28px;
            font-size: 16px;
            font-weight: bold;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            cursor: pointer;
            margin: 18px 0 0 0;
            transition: background 0.2s, box-shadow 0.2s;
        }
        .export-pdf-btn:hover {
            background: linear-gradient(90deg, #f7d900 0%, #edc10c 100%);
            box-shadow: 0 4px 16px rgba(0,0,0,0.13);
        }
        /* --- Modernize Table --- */
        .performance-table th, .performance-table td {
            border-bottom: 1.5px solid #e0e4ea;
        }
        .performance-table tr:hover {
            background: #f3f6fa;
        }
        /* --- Card and Modal Polish --- */
        .performance-card {
            box-shadow: 0 2px 16px rgba(44, 62, 80, 0.07);
            border: 1px solid #e0e4ea;
        }
        /* --- Responsive --- */
        @media (max-width: 900px) {
            .performance-card, #report-modal > div {
                padding: 10px !important;
            }
            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>

<body>

    @include('header') <!-- Include the header -->

   <div class="main-content">
        <!-- Container for content -->
        <div class="container-fluid">
            <!-- Report Generation Header -->
            <div class="row">
                <div class="col-md-12">
                    <div class="performance-card">
                        <div class="performance-title">
                            <i class="fas fa-file-alt" style="margin-right: 15px; color: #edc10c;"></i>
                            Student Report Generation
                            </div>
                        <div style="margin-top: 20px;">
                            <p style="color: #666; font-size: 16px; margin-bottom: 25px;">
                                Select a class and month to view students, then click on a student's name to generate their detailed performance report.
                            </p>
                            </div>
                        </div>
                </div>
                </div>

            <!-- Selection Controls -->
            <div class="row">
                <div class="col-md-12">
                            <div class="performance-card">
                        <div class="performance-title" style="margin-bottom: 20px;">
                            <i class="fas fa-filter" style="margin-right: 10px; color: #edc10c;"></i>
                            Select Criteria
                        </div>
                        <div style="display: flex; gap: 20px; align-items: center; flex-wrap: wrap;">
                            <div>
                                <label style="font-weight: bold; color: #333; margin-bottom: 5px; display: block;">Class:</label>
                                <select id="report-class-select" class="grade-dropdown" style="min-width: 200px;">
                                        @foreach($classes as $class)
                                            <option value="{{ $class->classID }}" @if($class->classID == $defaultClassID) selected @endif>{{ $class->class_name }}</option>
                                        @endforeach
                                    </select>
                            </div>
                            <div>
                                <label style="font-weight: bold; color: #333; margin-bottom: 5px; display: block;">Month:</label>
                                <select id="report-month-select" class="grade-dropdown" style="min-width: 200px;">
                                        @foreach($months as $month)
                                            <option value="{{ $month }}" @if($month == $currentMonth) selected @endif>{{ $month }}</option>
                                        @endforeach
                                    </select>
                                </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Students List -->
            <div class="row">
                <div class="col-md-12">
                    <div class="performance-card">
                        <div class="performance-title" style="margin-bottom: 20px;">
                            <i class="fas fa-users" style="margin-right: 10px; color: #edc10c;"></i>
                            Students List
                        </div>
                        <div id="students-list-container">
                            <div style="text-align: center; color: #888; padding: 40px;">
                                <i class="fas fa-info-circle" style="font-size: 48px; margin-bottom: 15px; color: #ddd;"></i>
                                <p style="font-size: 16px;">Please select a class and month to view the student list.</p>
                        </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Report Modal -->
            <div id="report-modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.7); z-index: 9999; overflow-y: auto;">
                <div style="background: white; margin: 20px auto; max-width: 900px; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.3);">
                    <!-- Modal Header -->
                    <div style="background: #edc10c; padding: 20px; border-radius: 15px 15px 0 0; display: flex; justify-content: space-between; align-items: center;">
                        <h3 style="margin: 0; color: #222; font-weight: bold;">
                            <i class="fas fa-file-alt" style="margin-right: 10px;"></i>
                            Student Report Card
                        </h3>
                        <div style="display: flex; gap: 10px; align-items: center;">
                            <button class="export-pdf-btn" id="export-pdf-btn" style="display:none;">
                                <i class="fas fa-file-pdf" style="margin-right: 7px;"></i> Export PDF
                            </button>
                            <button id="close-modal" style="background: none; border: none; font-size: 24px; color: #222; cursor: pointer; padding: 0; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Modal Content -->
                    <div id="report-content" style="padding: 30px;">
                        <!-- Report will be dynamically loaded here -->
                    </div>
                </div>
         </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const classSelect = document.getElementById('report-class-select');
            const monthSelect = document.getElementById('report-month-select');
            const studentsListContainer = document.getElementById('students-list-container');
            const reportModal = document.getElementById('report-modal');
            const closeModal = document.getElementById('close-modal');
            const reportContent = document.getElementById('report-content');

            // Load Students Function
            function loadStudents() {
                const classID = classSelect.value;
                const month = monthSelect.value;

                if (!classID || !month) {
                    studentsListContainer.innerHTML = `
                        <div style="text-align: center; color: #888; padding: 40px;">
                            <i class="fas fa-exclamation-triangle" style="font-size: 48px; margin-bottom: 15px; color: #f39c12;"></i>
                            <p style="font-size: 16px;">Please select both class and month to load students.</p>
                        </div>
                    `;
                    return;
                }

                // Show loading
                studentsListContainer.innerHTML = `
                    <div style="text-align: center; color: #888; padding: 40px;">
                        <i class="fas fa-spinner fa-spin" style="font-size: 48px; margin-bottom: 15px; color: #edc10c;"></i>
                        <p style="font-size: 16px;">Loading students...</p>
                    </div>
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
                        studentsListContainer.innerHTML = `
                            <div style="text-align: center; color: #888; padding: 40px;">
                                <i class="fas fa-user-slash" style="font-size: 48px; margin-bottom: 15px; color: #e74c3c;"></i>
                                <p style="font-size: 16px;">No students found for the selected class and month.</p>
                            </div>
                        `;
                        return;
                    }

                    // Create students list
                    const studentsHTML = data.map((student, index) => {
                        return `
                        <div class="student-card" style="background: #f8f9fa; border: 2px solid #e9ecef; border-radius: 12px; padding: 20px; margin-bottom: 15px; transition: all 0.3s ease; cursor: pointer;"
                             onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(0,0,0,0.15)'; this.style.backgroundColor='#f0f0f0';"
                             onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 8px rgba(0,0,0,0.1)'; this.style.backgroundColor='#f8f9fa';"
                             onclick="window.location.href='/admin/student-report-card/${student.studentID}/${classID}/${encodeURIComponent(month)}';">
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <div>
                                    <h4 style="margin: 0 0 8px 0; color: #333; font-size: 18px;">
                                        <i class="fas fa-user-graduate" style="margin-right: 10px; color: #edc10c;"></i>
                                            ${student.student_name}
                                    </h4>
                                    <p style="margin: 0; color: #666; font-size: 14px;">
                                        <i class="fas fa-chart-line" style="margin-right: 8px;"></i>
                                        Average Score: <strong>${student.average_score}%</strong>
                                    </p>
                                </div>
                                <div style="text-align: right;">
                                    <p style="margin: 0; color: #666; font-size: 12px;">
                                        <i class="fas fa-file-alt" style="margin-right: 5px;"></i>
                                        Click row for report card
                                    </p>

                                </div>
                            </div>
                        </div>
                    `;
                    }).join('');

                    studentsListContainer.innerHTML = `
                        <div style="margin-bottom: 20px;">
                            <h4 style="color: #333; margin-bottom: 10px;">
                                <i class="fas fa-list" style="margin-right: 8px; color: #edc10c;"></i>
                                Students (${data.length} found)
                            </h4>
                        </div>
                        ${studentsHTML}
                    `;
                })
                .catch(error => {
                    console.error('Error:', error);
                    studentsListContainer.innerHTML = `
                        <div style="text-align: center; color: #888; padding: 40px;">
                            <i class="fas fa-exclamation-circle" style="font-size: 48px; margin-bottom: 15px; color: #e74c3c;"></i>
                            <p style="font-size: 16px;">Error loading students. Please try again.</p>
                        </div>
                    `;
                });
            }

            // Generate Report Function
            window.generateReport = function(studentID, studentName, classID, month) {
                // Show loading in modal
                reportModal.style.display = 'block';
                reportContent.innerHTML = `
                    <div style="text-align: center; padding: 40px;">
                        <i class="fas fa-spinner fa-spin" style="font-size: 48px; margin-bottom: 15px; color: #edc10c;"></i>
                        <p style="font-size: 16px; color: #666;">Generating report for ${studentName}...</p>
                    </div>
                `;
                document.getElementById('export-pdf-btn').style.display = 'none';
                // Fetch detailed report data
                fetch('/admin/generate-student-report', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')
                    },
                    body: JSON.stringify({ studentID, classID, month })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        reportContent.innerHTML = `
                            <div style="text-align: center; padding: 40px;">
                                <i class="fas fa-exclamation-circle" style="font-size: 48px; margin-bottom: 15px; color: #e74c3c;"></i>
                                <p style="font-size: 16px; color: #666;">${data.error}</p>
                            </div>
                        `;
                        return;
                    }
                    // Generate the report HTML
                    const reportHTML = generateReportHTML(data);
                    reportContent.innerHTML = reportHTML;
                    document.getElementById('export-pdf-btn').style.display = 'inline-block';
                })
                .catch(error => {
                    console.error('Error:', error);
                    reportContent.innerHTML = `
                        <div style="text-align: center; padding: 40px;">
                            <i class="fas fa-exclamation-circle" style="font-size: 48px; margin-bottom: 15px; color: #e74c3c;"></i>
                            <p style="font-size: 16px; color: #666;">Error generating report. Please try again.</p>
                        </div>
                    `;
                });
            };

            // Generate Report HTML
            function generateReportHTML(data) {
                const today = new Date().toLocaleDateString('en-US', {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                });

                return `
                    <div style="font-family: 'Times New Roman', serif; max-width: 210mm; margin: 0 auto; background: white; padding: 10mm 20mm 20mm 20mm; box-shadow: 0 0 20px rgba(0,0,0,0.1); min-height: 297mm; box-sizing: border-box;">
                        <!-- School Header -->
                        <div style="text-align: center; border-bottom: 3px solid #2c3e50; padding-bottom: 10px; margin-bottom: 15px;">
                            <div style="display: flex; align-items: center; justify-content: center; margin-bottom: 8px;">
                                <img src="/images/smislogo.png" alt="School Logo" style="width: 50px; height: 50px; margin-right: 12px;">
                                <div>
                                    <h1 style="color: #2c3e50; margin: 0; font-size: 22px; font-weight: bold;">PUSAT TUISYEN PERINTIS DIDIK</h1>
                                    <p style="color: #7f8c8d; margin: 2px 0; font-size: 12px;">Academic Excellence Through Dedication</p>
                                </div>
                            </div>
                            <h2 style="color: #2c3e50; margin: 0; font-size: 18px; font-weight: bold;">STUDENT REPORT CARD</h2>
                            <p style="color: #7f8c8d; margin: 2px 0; font-size: 11px;">Academic Year 2024/2025</p>
                        </div>

                        <!-- Student Information Section -->
                        <div style="background: #ecf0f1; padding: 12px; border-radius: 8px; margin-bottom: 12px; border: 2px solid #bdc3c7;">
                            <h3 style="color: #2c3e50; margin: 0 0 12px 0; font-size: 15px; border-bottom: 2px solid #3498db; padding-bottom: 6px;">
                                <i class="fas fa-user-graduate" style="color: #3498db; margin-right: 8px;"></i>
                                STUDENT INFORMATION
                            </h3>
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                                <div>
                                    <table style="width: 100%; border-collapse: collapse;">
                                        <tr>
                                            <td style="padding: 5px 0; font-weight: bold; color: #2c3e50; width: 40%; font-size: 11px;">Student Name:</td>
                                            <td style="padding: 5px 0; color: #34495e; border-bottom: 1px solid #bdc3c7; font-size: 11px;">${data.student.student_name}</td>
                                        </tr>
                                        <tr>
                                            <td style="padding: 5px 0; font-weight: bold; color: #2c3e50; font-size: 11px;">School:</td>
                                            <td style="padding: 5px 0; color: #34495e; border-bottom: 1px solid #bdc3c7; font-size: 11px;">${data.student.school_name || 'N/A'}</td>
                                        </tr>
                                        <tr>
                                            <td style="padding: 5px 0; font-weight: bold; color: #2c3e50; font-size: 11px;">Class:</td>
                                            <td style="padding: 5px 0; color: #34495e; border-bottom: 1px solid #bdc3c7; font-size: 11px;">${data.student.class_name}</td>
                                        </tr>
                                        <tr>
                                            <td style="padding: 5px 0; font-weight: bold; color: #2c3e50; font-size: 11px;">Address:</td>
                                            <td style="padding: 5px 0; color: #34495e; border-bottom: 1px solid #bdc3c7; font-size: 11px;">${data.student.address}</td>
                                        </tr>
                                    </table>
                                </div>
                                <div>
                                    <table style="width: 100%; border-collapse: collapse;">
                                        <tr>
                                            <td style="padding: 5px 0; font-weight: bold; color: #2c3e50; width: 40%; font-size: 11px;">Average Score:</td>
                                            <td style="padding: 5px 0; color: #34495e; border-bottom: 1px solid #bdc3c7; font-weight: bold; font-size: 11px;">${data.summary.average_score}%</td>
                                        </tr>
                                        <tr>
                                            <td style="padding: 5px 0; font-weight: bold; color: #2c3e50; font-size: 11px;">Total Subjects:</td>
                                            <td style="padding: 5px 0; color: #34495e; border-bottom: 1px solid #bdc3c7; font-weight: bold; font-size: 11px;">${data.summary.total_subjects}</td>
                                        </tr>
                                        <tr>
                                            <td style="padding: 5px 0; font-weight: bold; color: #2c3e50; font-size: 11px;">Report Period:</td>
                                            <td style="padding: 5px 0; color: #34495e; border-bottom: 1px solid #bdc3c7; font-size: 11px;">${data.month} 2024</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Parent Information Section -->
                        <div style="background: #ecf0f1; padding: 12px; border-radius: 8px; margin-bottom: 12px; border: 2px solid #bdc3c7;">
                            <h3 style="color: #2c3e50; margin: 0 0 12px 0; font-size: 15px; border-bottom: 2px solid #e67e22; padding-bottom: 6px;">
                                <i class="fas fa-users" style="color: #e67e22; margin-right: 8px;"></i>
                                PARENT/GUARDIAN INFORMATION
                            </h3>
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                                <div>
                                    <table style="width: 100%; border-collapse: collapse;">
                                        <tr>
                                            <td style="padding: 5px 0; font-weight: bold; color: #2c3e50; width: 40%; font-size: 11px;">Parent Name:</td>
                                            <td style="padding: 5px 0; color: #34495e; border-bottom: 1px solid #bdc3c7; font-size: 11px;">${data.parent.name}</td>
                                        </tr>
                                        <tr>
                                            <td style="padding: 5px 0; font-weight: bold; color: #2c3e50; font-size: 11px;">Phone:</td>
                                            <td style="padding: 5px 0; color: #34495e; border-bottom: 1px solid #bdc3c7; font-size: 11px;">${data.parent.phone || 'N/A'}</td>
                                        </tr>
                                    </table>
                                </div>
                                <div>
                                    <table style="width: 100%; border-collapse: collapse;">
                                        <tr>
                                            <td style="padding: 5px 0; font-weight: bold; color: #2c3e50; width: 40%; font-size: 11px;">Email:</td>
                                            <td style="padding: 5px 0; color: #34495e; border-bottom: 1px solid #bdc3c7; font-size: 11px;">${data.parent.email || 'N/A'}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Academic Performance Section -->
                        <div style="background: #ecf0f1; padding: 12px; border-radius: 8px; margin-bottom: 12px; border: 2px solid #bdc3c7;">
                            <h3 style="color: #2c3e50; margin: 0 0 12px 0; font-size: 15px; border-bottom: 2px solid #e74c3c; padding-bottom: 6px;">
                                <i class="fas fa-chart-line" style="color: #e74c3c; margin-right: 8px;"></i>
                                ACADEMIC PERFORMANCE
                            </h3>
                            <table style="width: 100%; border-collapse: collapse; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1); font-size: 10px;">
                                <thead>
                                    <tr style="background: #34495e; color: white;">
                                        <th style="padding: 8px; text-align: left; font-weight: bold; border-right: 1px solid #2c3e50;">Subject</th>
                                        <th style="padding: 8px; text-align: center; font-weight: bold; border-right: 1px solid #2c3e50;">Score (%)</th>
                                        <th style="padding: 8px; text-align: center; font-weight: bold; border-right: 1px solid #2c3e50;">Status</th>
                                        <th style="padding: 8px; text-align: center; font-weight: bold; border-right: 1px solid #2c3e50;">Teacher</th>
                                        <th style="padding: 8px; text-align: center; font-weight: bold;">Comments</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${data.subjects.map((subject, index) => {
                                        return `
                                            <tr style="background: ${index % 2 === 0 ? '#f8f9fa' : 'white'};">
                                                <td style="padding: 8px; font-weight: bold; color: #2c3e50; border-right: 1px solid #dee2e6;">${subject.subject_name}</td>
                                                <td style="padding: 8px; text-align: center; border-right: 1px solid #dee2e6; font-weight: bold; font-size: 11px;">
                                                    ${Math.round(subject.score)}%
                                                </td>
                                                <td style="padding: 8px; text-align: center; border-right: 1px solid #dee2e6;">
                                                    <span style="background: ${subject.performance_status === 'Excellent' ? '#28a745' : subject.performance_status === 'Good' ? '#ffc107' : '#dc3545'}; color: ${subject.performance_status === 'Good' ? '#000' : 'white'}; padding: 2px 5px; border-radius: 6px; font-weight: bold; font-size: 9px;">${subject.performance_status}</span>
                                                </td>
                                                <td style="padding: 8px; text-align: center; border-right: 1px solid #dee2e6; color: #7f8c8d; font-style: italic;">${subject.teacher_name || 'N/A'}</td>
                                                <td style="padding: 8px; text-align: left; color: #7f8c8d; font-style: italic; max-width: 120px; word-wrap: break-word;">${subject.comments || 'No comments available.'}</td>
                                            </tr>
                                        `;
                                    }).join('')}
                                </tbody>
                            </table>
                        </div>

                        <!-- Attendance Section -->
                        <div style="background: #ecf0f1; padding: 12px; border-radius: 8px; margin-bottom: 12px; border: 2px solid #bdc3c7;">
                            <h3 style="color: #2c3e50; margin: 0 0 12px 0; font-size: 15px; border-bottom: 2px solid #9b59b6; padding-bottom: 6px;">
                                <i class="fas fa-calendar-check" style="color: #9b59b6; margin-right: 8px;"></i>
                                ATTENDANCE RECORD
                            </h3>
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                                <div>
                                    <h4 style="color: #2c3e50; margin-bottom: 8px; font-size: 13px;">Overall Attendance Summary</h4>
                                    <table style="width: 100%; border-collapse: collapse; background: white; border-radius: 8px; overflow: hidden; font-size: 10px;">
                                        <tr style="background: #34495e; color: white;">
                                            <th style="padding: 6px; text-align: center;">Status</th>
                                            <th style="padding: 6px; text-align: center;">Days</th>
                                            <th style="padding: 6px; text-align: center;">Percentage</th>
                                        </tr>
                                        <tr>
                                            <td style="padding: 6px; text-align: center; background: #d4edda; color: #155724; font-weight: bold;">Present</td>
                                            <td style="padding: 6px; text-align: center;">${data.attendance_summary.present_days}</td>
                                            <td style="padding: 6px; text-align: center;">${data.attendance_summary.total_days > 0 ? Math.round((data.attendance_summary.present_days / data.attendance_summary.total_days) * 100) : 0}%</td>
                                        </tr>
                                        <tr>
                                            <td style="padding: 6px; text-align: center; background: #f8d7da; color: #721c24; font-weight: bold;">Absent</td>
                                            <td style="padding: 6px; text-align: center;">${data.attendance_summary.absent_days}</td>
                                            <td style="padding: 6px; text-align: center;">${data.attendance_summary.total_days > 0 ? Math.round((data.attendance_summary.absent_days / data.attendance_summary.total_days) * 100) : 0}%</td>
                                        </tr>
                                        <tr>
                                            <td style="padding: 6px; text-align: center; background: #fff3cd; color: #856404; font-weight: bold;">Leave</td>
                                            <td style="padding: 6px; text-align: center;">${data.attendance_summary.leave_days}</td>
                                            <td style="padding: 6px; text-align: center;">${data.attendance_summary.total_days > 0 ? Math.round((data.attendance_summary.leave_days / data.attendance_summary.total_days) * 100) : 0}%</td>
                                        </tr>
                                        <tr style="background: #f8f9fa; font-weight: bold;">
                                            <td style="padding: 6px; text-align: center;">Total</td>
                                            <td style="padding: 6px; text-align: center;">${data.attendance_summary.total_days}</td>
                                            <td style="padding: 6px; text-align: center;">100%</td>
                                        </tr>
                                    </table>
                                </div>
                                <div>
                                    <h4 style="color: #2c3e50; margin-bottom: 8px; font-size: 13px;">Attendance by Subject</h4>
                                    ${Object.keys(data.attendance_summary.by_subject).length > 0 ?
                                        Object.entries(data.attendance_summary.by_subject).map(([subject, stats]) => `
                                            <div style="background: white; padding: 8px; border-radius: 8px; margin-bottom: 6px; border-left: 4px solid #9b59b6;">
                                                <h5 style="margin: 0 0 4px 0; color: #2c3e50; font-size: 11px;">${subject}</h5>
                                                <div style="display: flex; justify-content: space-between; font-size: 10px;">
                                                    <span>Present: ${stats.present}/${stats.total_days}</span>
                                                    <span style="color: ${stats.percentage >= 80 ? '#28a745' : stats.percentage >= 60 ? '#ffc107' : '#dc3545'}; font-weight: bold;">${stats.percentage}%</span>
                                                </div>
                                            </div>
                                        `).join('') :
                                        '<p style="color: #7f8c8d; font-style: italic; font-size: 10px;">No attendance data available for this month.</p>'
                                    }
                                </div>
                            </div>
                        </div>

                        <!-- Fee Status Section -->
                        <div style="background: #ecf0f1; padding: 12px; border-radius: 8px; margin-bottom: 12px; border: 2px solid #bdc3c7;">
                            <h3 style="color: #2c3e50; margin: 0 0 12px 0; font-size: 15px; border-bottom: 2px solid #f39c12; padding-bottom: 6px;">
                                <i class="fas fa-money-bill-wave" style="color: #f39c12; margin-right: 8px;"></i>
                                FEE STATUS
                            </h3>
                            <div style="display: flex; justify-content: space-between; align-items: center; background: white; padding: 12px; border-radius: 8px;">
                                <div>
                                    <h4 style="margin: 0 0 4px 0; color: #2c3e50; font-size: 13px;">Monthly Fee Status</h4>
                                    <p style="margin: 0; color: #7f8c8d; font-size: 10px;">Period: ${data.month} 2024</p>
                                </div>
                                <div style="text-align: right;">
                                    <div style="margin-bottom: 4px;">
                                        <span style="background: ${data.fee_status.status === 'Paid' ? '#d4edda' : '#f8d7da'}; color: ${data.fee_status.status === 'Paid' ? '#155724' : '#721c24'}; padding: 4px 10px; border-radius: 12px; font-weight: bold; font-size: 11px;">
                                            ${data.fee_status.status}
                                        </span>
                                    </div>
                                    <p style="margin: 0; color: #2c3e50; font-weight: bold; font-size: 14px;">RM ${data.fee_status.amount}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Report Footer -->
                        <div style="text-align: center; border-top: 3px solid #2c3e50; padding-top: 12px; color: #7f8c8d;">
                            <p style="margin: 0 0 6px 0; font-size: 11px;">
                                <strong>Report Generated:</strong> ${today}
                            </p>
                            <p style="margin: 0; font-size: 9px; font-style: italic;">
                                This is an official academic report from Pusat Tuisyen Perintis Didik.<br>
                                For any inquiries, please contact the administration office.
                            </p>
                        </div>
                    </div>
                `;
            }

            // Event Listeners
            classSelect.addEventListener('change', loadStudents);
            monthSelect.addEventListener('change', loadStudents);
            closeModal.addEventListener('click', () => {
                reportModal.style.display = 'none';
            });

            // Close modal when clicking outside
            reportModal.addEventListener('click', (e) => {
                if (e.target === reportModal) {
                    reportModal.style.display = 'none';
                }
            });

            // Auto-load students if both dropdowns have values on page load
            if (classSelect.value && monthSelect.value) {
                loadStudents();
            }

            // PDF Export Handler
            document.getElementById('export-pdf-btn').addEventListener('click', function() {
                const element = document.getElementById('report-content');
                const opt = {
                    margin: [10, 10, 10, 10], // [top, right, bottom, left] in mm
                    filename: 'student_report.pdf',
                    image: { type: 'jpeg', quality: 0.98 },
                    html2canvas: {
                        scale: 2,
                        useCORS: true,
                        letterRendering: true,
                        allowTaint: true
                    },
                    jsPDF: {
                        unit: 'mm',
                        format: 'a4',
                        orientation: 'portrait',
                        compress: true
                    },
                    pagebreak: { mode: 'avoid-all' }
                };
                html2pdf().set(opt).from(element).save();
            });
        });
    </script>
</body>
 @include('footer') <!-- Include the footer -->
</html>


