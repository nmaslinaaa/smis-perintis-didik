<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard')</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
            position: relative;
        }
        .score-bar {
            background: #a4c6ea;
            height: 28px;
            border-radius: 10px 0 0 10px;
            display: inline-block;
        }

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

        @keyframes syllabusShimmer {
            0% { left: -100%; }
            100% { left: 100%; }
        }

        .syllabus-percent-anim {
            transition: all 0.3s ease;
        }

        .syllabus-bar-anim {
            transition: width 1.2s cubic-bezier(0.4, 0, 0.2, 1);
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

        .circle {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        /* Performance tooltip styling */
        .performance-tooltip {
            cursor: help;
            position: relative;
        }

        .performance-tooltip:hover::after {
            content: attr(title);
            position: absolute;
            bottom: 125%;
            left: 50%;
            transform: translateX(-50%);
            background: #333;
            color: white;
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 14px;
            white-space: nowrap;
            z-index: 1000;
            box-shadow: 0 2px 8px rgba(0,0,0,0.3);
        }

        .performance-tooltip:hover::before {
            content: '';
            position: absolute;
            bottom: 120%;
            left: 50%;
            transform: translateX(-50%);
            border: 5px solid transparent;
            border-top-color: #333;
            z-index: 1000;
        }
    </style>
</head>

<body>

    @include('header') <!-- Include the header -->

   <div class="main-content">
        <!-- Container for content -->
        <div class="container-fluid">
            <!-- Column : Syllabus Coverage -->
            <div class="row">
                <!-- Syllabus Coverage Card -->
                <div style="width: 100%; display: flex; justify-content: center; align-items: center; margin-top: 40px; margin-bottom: 40px;">
                    <div style="background: #fff; border-radius: 24px; box-shadow: 0 2px 8px rgba(0,0,0,0.04); width: 560px; padding: 32px 32px 32px 32px; display: flex; flex-direction: column; align-items: flex-start; justify-content: flex-start; position: relative;">
                        <div style="width: 100%; display: flex; justify-content: space-between; align-items: flex-start;">
                            <span style="font-size: 1.5rem; font-weight: bold; color: #222;">Syllabus Coverage</span>
                            <select id="syllabus-class-select" style="background: #edc10c; color: #222; font-weight: bold; border: none; border-radius: 10px; padding: 6px 18px; font-size: 1rem; min-width: 130px;"></select>
                        </div>
                        <div style="margin-top: 28px; width: 100%;">
                            <div id="syllabus-coverage-list"></div>
                        </div>
                    </div>
                </div>
            </div>
                <!-- Attendance and Payment Status Section -->
                <div class="row" style="margin-top: 30px;">
                    <div style="display: flex; gap: 40px; justify-content: center;">
                        <!-- Student Attendance Card -->
                        <div class="performance-card" style="width: 540px; min-width: 400px;">
                            <div class="performance-title" style="margin-bottom: 20px;">
                                Student Attendance
                                <select class="grade-dropdown attendance-card-class"></select>
                                <select class="grade-dropdown attendance-card-subject" style="margin-left:10px;"></select>
                            </div>
                            <div class="attendance-chart-container" style="display:flex;flex-direction:column;align-items:center;justify-content:center;min-height:220px;width:100%;">
                                <div id="attendance-date-label" style="text-align:center;color:#888;font-size:1.05rem;margin-bottom:8px;"></div>
                                <canvas id="attendance-pie-canvas" width="400" height="220"></canvas>
                            </div>
                            <div style="text-align: center; margin-top: 10px;">
                                <span id="attendance-pie-text" style="color:#222; font-size:15px;"></span>
                            </div>
                        </div>

                        <!-- Payment Status Card -->
                        <div class="performance-card" style="width: 540px; min-width: 400px;">
                            <div class="performance-title" style="margin-bottom: 20px;">
                                Payment Status
                                <select id="payment-status-class-select" style="background: #edc10c; color: #222; font-weight: bold; border: none; border-radius: 10px; padding: 6px 18px; font-size: 1rem; min-width: 130px; margin-left: 20px;"></select>
                            </div>
                            <div id="payment-status-label" style="text-align:center;color:#888;font-size:1.05rem;margin-bottom:8px;"></div>
                            <div style="max-height: 220px; overflow-y: auto;">
                                <table class="performance-table" id="payment-status-table" style="margin-top: 0;">
                                    <thead>
                                        <tr>
                                            <th style="width: 60%;">Student Name</th>
                                            <th style="width: 60px; text-align:center;">Payment Status</th>
                                        </tr>
                                    </thead>
                                    <tbody id="payment-status-tbody"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Attendance and Payment Status Section -->
            <!-- Column 4: Performance Stats -->
            <div class="row">
                <div style="display: flex; justify-content: center; width: 100%;">
                    <div class="panel panel-default" style="width: auto;">
                        <div class="panel-body">
                            <div class="performance-card">
                                <div class="performance-title">
                                    Student Performance
                                    <select class="grade-dropdown" id="performance-class-select"></select>
                                    <select class="grade-dropdown" id="performance-month-select"></select>
                                </div>
                                <table class="performance-table" id="performance-table">
                                    <thead>
                                        <tr>
                                            <th style="width: 200px;">Name</th>
                                            <th style="width: 300px;">Average Score</th>
                                            <th style="width: 160px;">Needing Help</th>
                                            <th style="width: 120px;">Good</th>
                                            <th style="width: 120px;">Excellent</th>
                                        </tr>
                                    </thead>
                                    <tbody id="performance-tbody"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
         </div>
        </div>
    </div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    let allSubjects = [];
    fetch("{{ url('/teacher/syllabus_coverage/options') }}")
        .then(response => response.json())
        .then(data => {
            const classSelect = document.getElementById('syllabus-class-select');
            allSubjects = data.subjects;
            // In syllabus coverage card, remove 'Select Class' option
            classSelect.innerHTML = data.classes.map(c => `<option value=\"${c.classID}\">${c.class_name}</option>`).join('');
            // Auto-select first class if available
            if (data.classes.length > 0) {
                classSelect.value = data.classes[0].classID;
                updateCoverageList();
            }
            classSelect.addEventListener('change', updateCoverageList);
        });
    function updateCoverageList() {
        const classID = document.getElementById('syllabus-class-select').value;
        const coverageList = document.getElementById('syllabus-coverage-list');
        coverageList.innerHTML = '';
        if (!classID) return;
        // Filter subjects for this class
        const subjects = allSubjects.filter(s => s.classID == classID);
        if (subjects.length === 0) {
            coverageList.innerHTML = '<div style="color:#888;">No subjects found for this class.</div>';
            return;
        }
        subjects.forEach((subject, idx) => {
            // Create subject div with initial animation state
            const subjectDiv = document.createElement('div');
            subjectDiv.style.marginBottom = '18px';
            subjectDiv.style.opacity = '0';
            subjectDiv.style.transform = 'translateX(-20px)';
            subjectDiv.style.transition = 'all 0.6s ease';

            // For each subject, fetch percent
            fetch("{{ url('/teacher/syllabus_coverage/percent') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name=\'csrf-token\']').getAttribute('content')
                },
                body: JSON.stringify({ classID, subjectID: subject.subjectID })
            })
            .then(response => response.json())
            .then(data => {
                const percent = data.percent || 0;
                // Color palette for subjects
                const palette = ['#3cc13b', '#edc10c', '#3c8dbc', '#e74c3c', '#a569bd', '#f39c12', '#16a085', '#d35400'];
                const barColor = palette[idx % palette.length];
                                subjectDiv.innerHTML = `
                    <div style=\"font-weight: bold; color: #444; margin-bottom: 6px;\">
                        ${subject.subject_name}
                        <span class=\"syllabus-percent-anim\" style=\"float:right; color:${barColor}; font-weight: bold;\">0%</span>
                    </div>
                    <div style=\"background: #f0f0f0; border-radius: 12px; height: 18px; width: 100%; position: relative; overflow: hidden;\">
                        <div class=\"syllabus-bar-anim\" style=\"width: 0%; background: ${barColor}; height: 100%; border-radius: 12px; position: relative;\">
                            <div class=\"syllabus-shimmer\" style=\"position: absolute; top: 0; left: -100%; width: 100%; height: 100%; background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);\"></div>
                        </div>
                    </div>
                `;
                coverageList.appendChild(subjectDiv);

                // Get elements for animation
                const bar = subjectDiv.querySelector('.syllabus-bar-anim');
                const percentSpan = subjectDiv.querySelector('.syllabus-percent-anim');
                const shimmer = subjectDiv.querySelector('.syllabus-shimmer');

                // Animate the bar and percentage
                let start = 0;
                const end = percent;
                const duration = 1200; // ms
                const startTime = performance.now();

                function animateBar(now) {
                    const elapsed = now - startTime;
                    const progress = Math.min(elapsed / duration, 1);
                    const current = start + (end - start) * progress;

                    // Animate bar width
                    bar.style.width = current + '%';

                    // Animate percentage text
                    if (percentSpan) {
                        percentSpan.textContent = Math.round(current) + '%';
                    }

                    if (progress < 1) {
                        requestAnimationFrame(animateBar);
                    } else {
                        // Final values
                        bar.style.width = end + '%';
                        if (percentSpan) {
                            percentSpan.textContent = end + '%';
                        }

                        // Start shimmer animation after bar is complete
                        if (shimmer) {
                            shimmer.style.animation = 'syllabusShimmer 2s infinite';
                        }
                    }
                }

                // Stagger animation for each subject
                setTimeout(() => {
                    // Fade in the subject div
                    subjectDiv.style.opacity = '1';
                    subjectDiv.style.transform = 'translateX(0)';

                    // Start bar animation
                    requestAnimationFrame(animateBar);
                }, idx * 200); // 200ms delay between each subject
            });
        });
    }
});

// Attendance card dropdown logic
(function() {
    let allSubjects = [];
    let allClasses = [];
    // Fetch class/subject options from the same endpoint as coverage
    fetch("{{ url('/teacher/syllabus_coverage/options') }}")
        .then(response => response.json())
        .then(data => {
            allClasses = data.classes;
            allSubjects = data.subjects;
            const classSelect = document.querySelector('.attendance-card-class');
            const subjectSelect = document.querySelector('.attendance-card-subject');
            if (!classSelect || !subjectSelect) return;
            // In attendance card dropdowns, remove the 'Select' option from class and subject
            classSelect.innerHTML = allClasses.map(c => `<option value="${c.classID}">${c.class_name}</option>`).join('');
            subjectSelect.innerHTML = '<option value="">Select</option>';
            // Auto-select first class and subject 'English' if available
            if (allClasses.length > 0) {
                classSelect.value = allClasses[0].classID;
                const filteredSubjects = allSubjects.filter(s => s.classID == allClasses[0].classID);
                subjectSelect.innerHTML = filteredSubjects.map(s => `<option value="${s.subjectID}">${s.subject_name}</option>`).join('');
                let englishSubject = filteredSubjects.find(s => s.subject_name.toLowerCase() === 'english');
                if (englishSubject) {
                    subjectSelect.value = englishSubject.subjectID;
                } else if (filteredSubjects.length > 0) {
                    subjectSelect.value = filteredSubjects[0].subjectID;
                }
                updateAttendanceChart();
            }
            classSelect.addEventListener('change', function() {
                const classID = this.value;
                subjectSelect.innerHTML = '<option value="">Select</option>';
                if (classID) {
                    const filteredSubjects = allSubjects.filter(s => s.classID == classID);
                    subjectSelect.innerHTML = filteredSubjects.map(s => `<option value="${s.subjectID}">${s.subject_name}</option>`).join('');
                }
                updateAttendanceChart();
            });
            subjectSelect.addEventListener('change', updateAttendanceChart);
        });
    let attendancePieChart = null;
    function updateAttendanceChart() {
        const classID = document.querySelector('.attendance-card-class').value;
        const subjectID = document.querySelector('.attendance-card-subject').value;
        const chartCanvas = document.getElementById('attendance-pie-canvas');
        const chartText = document.getElementById('attendance-pie-text');
        let noDataMsg = document.getElementById('attendance-no-data-msg');
        const dateLabel = document.getElementById('attendance-date-label');
        if (!noDataMsg) {
            noDataMsg = document.createElement('div');
            noDataMsg.id = 'attendance-no-data-msg';
            noDataMsg.style.textAlign = 'center';
            noDataMsg.style.color = '#888';
            noDataMsg.style.fontSize = '1.1rem';
            noDataMsg.style.margin = '20px 0 0 0';
            chartCanvas.parentNode.insertBefore(noDataMsg, chartCanvas);
        }
        if (!classID || !subjectID) {
            if (attendancePieChart) { attendancePieChart.destroy(); attendancePieChart = null; }
            if (chartText) chartText.textContent = '';
            if (dateLabel) dateLabel.textContent = '';
            noDataMsg.textContent = '';
            return;
        }
        // Use today as default date
        const today = new Date().toISOString().slice(0, 10);
        fetch("{{ url('/teacher/dashboard/attendance_summary') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name=\'csrf-token\']').getAttribute('content')
            },
            body: JSON.stringify({ classID, subjectID, date: today })
        })
        .then(res => res.json())
        .then(data => {
            const present = data.present || 0;
            const absent = data.absent || 0;
            const leave = data.leave || 0;
            const total = data.total || 0;
            const usedDate = data.used_date;
            if (!usedDate) {
                if (attendancePieChart) { attendancePieChart.destroy(); attendancePieChart = null; }
                if (chartText) chartText.textContent = '';
                if (dateLabel) dateLabel.textContent = '';
                noDataMsg.textContent = 'No attendance data available.';
                return;
            }
            // Format date label
            const d = new Date(usedDate);
            const dayName = d.toLocaleDateString('en-US', { weekday: 'long' });
            const day = d.getDate();
            const month = d.toLocaleDateString('en-US', { month: 'long' });
            const year = d.getFullYear();
            if (dateLabel) dateLabel.textContent = `Showing attendance for: ${dayName}, ${day} ${month} ${year}`;
            if (total === 0) {
                if (attendancePieChart) { attendancePieChart.destroy(); attendancePieChart = null; }
                if (chartText) chartText.textContent = '';
                noDataMsg.textContent = 'No attendance data for this date.';
                noDataMsg.style.display = 'flex';
                noDataMsg.style.justifyContent = 'center';
                noDataMsg.style.alignItems = 'center';
                noDataMsg.style.height = '220px';
                noDataMsg.style.width = '100%';
                noDataMsg.style.fontSize = '1.15rem';
                return;
            } else {
                noDataMsg.textContent = '';
                noDataMsg.style.display = 'none';
            }
            // Update chart
            if (attendancePieChart) attendancePieChart.destroy();
            attendancePieChart = new Chart(chartCanvas, {
                type: 'pie',
                data: {
                    labels: ['Present', 'Absent', 'Leave with permission'],
                    datasets: [{
                        data: [present, absent, leave],
                        backgroundColor: ['#3cc13b', '#e74c3c', '#f1c40f'],
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: false,
                    animation: { animateRotate: true, animateScale: true },
                    plugins: {
                        legend: { display: true, position: 'bottom' }
                    }
                }
            });
            if (chartText) chartText.textContent = `Present ${present} | Absent ${absent} | Leaves ${leave}`;
        });
    }
})();

// Add JS to update payment status card
let paymentStatusAllStudents = [];
let paymentStatusAllClasses = [];
function updatePaymentStatusCard() {
    const label = document.getElementById('payment-status-label');
    const tbody = document.getElementById('payment-status-tbody');
    const classSelect = document.getElementById('payment-status-class-select');
    // Get current month in YYYY-MM format
    const now = new Date();
    const month = now.getFullYear() + '-' + String(now.getMonth() + 1).padStart(2, '0');
    // Format label
    const monthLabel = now.toLocaleDateString('en-US', { month: 'long', year: 'numeric' });
    if (label) label.textContent = `Payment status for: ${monthLabel}`;
    fetch("{{ url('/teacher/dashboard/payment_status') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name=\'csrf-token\']').getAttribute('content')
        },
        body: JSON.stringify({ month })
    })
    .then(res => res.json())
    .then(data => {
        paymentStatusAllStudents = data;
        // Fetch all classes for dropdown
        fetch("{{ url('/teacher/syllabus_coverage/options') }}")
            .then(res2 => res2.json())
            .then(data2 => {
                paymentStatusAllClasses = data2.classes;
                if (classSelect) {
                    classSelect.innerHTML = paymentStatusAllClasses.map(c => `<option value="${c.classID}">${c.class_name}</option>`).join('');
                }
                // Auto-select first class if not selected
                if (classSelect && !classSelect.value && paymentStatusAllClasses.length > 0) {
                    classSelect.value = paymentStatusAllClasses[0].classID;
                }
                renderPaymentStatusTable();
            });
    });
}
function renderPaymentStatusTable() {
    const tbody = document.getElementById('payment-status-tbody');
    const classSelect = document.getElementById('payment-status-class-select');
    const selectedClassID = classSelect ? classSelect.value : null;
    let filtered = paymentStatusAllStudents;
    if (selectedClassID) {
        filtered = filtered.filter(row => row.classID == selectedClassID);
    }
    if (tbody) {
        if (!filtered.length) {
            tbody.innerHTML = '<tr><td colspan="2" style="text-align:center;color:#888;">No students found.</td></tr>';
            return;
        }
        tbody.innerHTML = filtered.map(row => {
            let icon = row.status === 'Paid' ? '<span style="color:green; font-size:22px;"><i class="fas fa-check-square"></i></span>' : '<span style="color:red; font-size:22px;"><i class="fas fa-times-circle"></i></span>';
            return `<tr><td><b>${row.student_name}</b></td><td style="width:60px;text-align:center;">${icon}</td></tr>`;
        }).join('');
    }
}
// Add event listener for class dropdown
setTimeout(() => {
    const classSelect = document.getElementById('payment-status-class-select');
    if (classSelect) {
        classSelect.addEventListener('change', renderPaymentStatusTable);
    }
}, 500);
// Call once on page load
updatePaymentStatusCard();

// Student Performance Card logic
let performanceAllClasses = [];
function updatePerformanceCard() {
    const classSelect = document.getElementById('performance-class-select');
    const monthSelect = document.getElementById('performance-month-select');
    const tbody = document.getElementById('performance-tbody');
    // Fetch classes for dropdown
    fetch("{{ url('/teacher/syllabus_coverage/options') }}")
        .then(res => res.json())
        .then(data => {
            performanceAllClasses = data.classes;
            if (classSelect) {
                classSelect.innerHTML = performanceAllClasses.map(c => `<option value="${c.classID}">${c.class_name}</option>`).join('');
            }
            // Auto-select first class if not selected
            if (classSelect && !classSelect.value && performanceAllClasses.length > 0) {
                classSelect.value = performanceAllClasses[0].classID;
            }
            // Month dropdown
            const months = ['January','February','March','April','May','June','July','August','September','October','November','December'];
            if (monthSelect) {
                monthSelect.innerHTML = months.map(m => `<option value="${m}">${m}</option>`).join('');
            }
            // Auto-select current month
            const now = new Date();
            const currentMonth = months[now.getMonth()];
            if (monthSelect && !monthSelect.value) monthSelect.value = currentMonth;
            renderPerformanceTable();
        });
}
function renderPerformanceTable() {
    const classSelect = document.getElementById('performance-class-select');
    const monthSelect = document.getElementById('performance-month-select');
    const tbody = document.getElementById('performance-tbody');
    const classID = classSelect ? classSelect.value : null;
    const month = monthSelect ? monthSelect.value : null;
    if (!classID || !month) {
        if (tbody) tbody.innerHTML = '';
        return;
    }
    fetch("{{ url('/teacher/dashboard/performance') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name=\'csrf-token\']').getAttribute('content')
        },
        body: JSON.stringify({ classID, month })
    })
    .then(res => res.json())
    .then(data => {
        if (tbody) {
            if (!data.length) {
                tbody.innerHTML = '<tr><td colspan="5" style="text-align:center;color:#888;">No student performance data found.</td></tr>';
                return;
            }
                        tbody.innerHTML = data.map((row, index) => {
                // Score bar with animation
                let score = row.average_score || 0;
                let bar = `<div style="position: relative; display: inline-block; width: 220px;">
                    <div class="score-bar-bg">
                        <div class="score-bar-animated" data-score="${score}" style="width: 0%;"></div>
                        <span class="score-label-animated" data-score="${score}">0%</span>
                    </div>
                </div>`;

                // Create tooltip content for each performance level
                const needingAttentionSubjects = row.needing_attention_subjects || [];
                const moderateSubjects = row.moderate_subjects || [];
                const masteredSubjects = row.mastered_subjects || [];

                const needingAttentionTooltip = needingAttentionSubjects.length > 0 ?
                    `Need Help: ${needingAttentionSubjects.join(', ')}` : 'No subjects need help';
                const moderateTooltip = moderateSubjects.length > 0 ?
                    `Good: ${moderateSubjects.join(', ')}` : 'No subjects with good performance';
                const masteredTooltip = masteredSubjects.length > 0 ?
                    `Excellent: ${masteredSubjects.join(', ')}` : 'No subjects with excellent performance';

                return `<tr class="performance-row" data-index="${index}">
                    <td><b>${row.student_name}</b></td>
                    <td>${bar}</td>
                    <td><span class="circle red performance-tooltip" title="${needingAttentionTooltip}">${row.needing_attention}%</span></td>
                    <td><span class="circle yellow performance-tooltip" title="${moderateTooltip}">${row.moderate}%</span></td>
                    <td><span class="circle green performance-tooltip" title="${masteredTooltip}">${row.mastered}%</span></td>
                </tr>`;
            }).join('');

            // Animate score bars after rendering
            setTimeout(() => {
                animateScoreBars();
            }, 100);
        }
    });
}
// Add event listeners for dropdowns
setTimeout(() => {
    const classSelect = document.getElementById('performance-class-select');
    const monthSelect = document.getElementById('performance-month-select');
    if (classSelect) classSelect.addEventListener('change', renderPerformanceTable);
    if (monthSelect) monthSelect.addEventListener('change', renderPerformanceTable);
}, 500);
// Call once on page load
updatePerformanceCard();

// Function to animate score bars
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
</script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</body>
 @include('footer') <!-- Include the footer -->
</html>

