<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
            margin-top: 50px; /* Add top margin to push the content below the fixed header */
            margin-left: 150px; /* Ensure content starts after the sidebar */
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

        /* Parent Dashboard Score Bar Animation */
        .parent-score-bar-animated {
            transition: width 1.5s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .parent-score-label-animated {
            transition: all 1.5s cubic-bezier(0.4, 0, 0.2, 1);
        }

        @keyframes parentShimmer {
            0% { left: -100%; }
            100% { left: 100%; }
        }

        .parent-shimmer {
            animation: parentShimmer 2s infinite;
        }

        /* Row animation for parent dashboard */
        .parent-performance-row {
            opacity: 0;
            transform: translateY(20px);
            animation: parentFadeInUp 0.6s ease forwards;
        }

        .parent-performance-row:nth-child(1) { animation-delay: 0.1s; }
        .parent-performance-row:nth-child(2) { animation-delay: 0.2s; }
        .parent-performance-row:nth-child(3) { animation-delay: 0.3s; }
        .parent-performance-row:nth-child(4) { animation-delay: 0.4s; }
        .parent-performance-row:nth-child(5) { animation-delay: 0.5s; }

        @keyframes parentFadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Payment Status Row Animation */
        .payment-status-row {
            opacity: 0;
            transform: translateX(-20px);
            transition: all 0.6s ease;
        }

        .payment-status-row.show {
            opacity: 1;
            transform: translateX(0);
        }
    </style>
    <style>
        /* New Dashboard Section Styles */
        .dashboard-section {
            max-width: 1170px;
            margin: 40px auto 65px auto;
            display: flex;
            gap: 40px;
            justify-content: center;
            padding-left: 68px;
        }
        .dashboard-section .dashboard-card {
            width: 48%;
            max-width: 900px;
        }
        .dashboard-card {
            background: #fff;
            border-radius: 28px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
            padding: 36px;
            min-width: 0;
            width: 100%;
            max-width: 1170px;
        }
        .dashboard-card-header {
            display: flex;
            align-items: center;
            gap: 18px;
            margin-bottom: 18px;
        }
        .dashboard-title {
            font-size: 1.6rem;
            font-weight: bold;
            color: #222;
        }
        .dashboard-dropdown {
            background: #edc10c;
            color: #222;
            font-weight: bold;
            border: none;
            border-radius: 12px;
            padding: 6px 24px;
            font-size: 1rem;
            min-width: 100px;
        }
        .dashboard-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }
        .dashboard-table th, .dashboard-table td {
            text-align: left;
            font-size: 1rem;
            font-weight: bold;
            padding: 12px 18px;
            border-bottom: 3px solid #222;
        }

        .dashboard-table th:last-child,
        .dashboard-table td:last-child {
            text-align: center;
        }
        .dashboard-table td {
            color: #222;
            border-bottom: none;
        }
        /* Remove .name-cell padding, now handled by th/td */
        .name-cell {
            padding-left: 0;
            padding-right: 0;
        }
        .attendance-badge {
            display: inline-block;
            font-weight: bold;
            border-radius: 4px;
            padding: 2px 12px;
            color: #fff;
        }
        .attendance-present { background: #3cc13b; }
        .attendance-absent { background: #f55d5d; }
        .attendance-late { background: #ffe066; color: #222; }
                .payment-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            padding: 4px 8px;
            color: #fff;
            font-size: 12px;
            font-weight: bold;
            min-width: 30px;
            height: 24px;
        }
        .payment-paid { background: #3cc13b; }
        .payment-unpaid { background: #f55d5d; }
        .payment-badge i {
            margin: 0;
            font-size: 12px;
        }
        /* Remove inline margin-bottom for Payment Status header */
        .dashboard-card-header.payment-status-header {
            margin-bottom: 18px;
        }
    </style>
</head>

<body>

    @include('header') <!-- Include the header -->

   <div class="main-content">
    <div style="max-width: 1100px; margin: 40px auto 0 auto;">
        <div style="display: flex; align-items: center; gap: 18px; margin-bottom: 18px;">
            <span style="font-size: 2rem; font-weight: bold; color: #222;">Child’s Performance</span>
            <select id="child-select" style="background: #edc10c; color: #222; font-weight: bold; border: none; border-radius: 12px; padding: 8px 32px; font-size: 1.1rem; min-width: 120px;">
                @if($children->count() > 0)
                @foreach($children as $child)
                    <option value="{{ $child->studentID }}">{{ $child->student_name }}</option>
                @endforeach
                @else
                    <option value="">No childs found</option>
                @endif
            </select>
            <select id="month-select" style="background: #edc10c; color: #222; font-weight: bold; border: none; border-radius: 12px; padding: 8px 32px; font-size: 1.1rem; min-width: 120px;">
                @php
                    $months = ['January','February','March','April','May','June','July','August','September','October','November','December'];
                    $currentMonth = date('F');
                @endphp
                @foreach($months as $month)
                    <option value="{{ $month }}" @if($month == $currentMonth) selected @endif>{{ $month }}</option>
                @endforeach
            </select>
        </div>
        <div style="background: #fff; border-radius: 28px; box-shadow: 0 2px 8px rgba(0,0,0,0.04); width: 100%; padding: 36px 36px 36px 36px;">
            <table style="width: 100%; border-collapse: separate; border-spacing: 0;">
                <thead>
                    <tr style="border-bottom: 3px solid #222;">
                        <th style="text-align: left; font-size: 1.1rem; font-weight: bold; padding-bottom: 12px;">Subject Name</th>
                        <th style="text-align: center; font-size: 1.1rem; font-weight: bold; padding-bottom: 12px;">Test Score</th>
                        <th style="text-align: center; font-size: 1.1rem; font-weight: bold; padding-bottom: 12px;">Learning Level</th>
                    </tr>
                </thead>
                <tbody id="child-performance-tbody">
                    <!-- Akan diisi oleh JS -->
                </tbody>
            </table>
        </div>
    </div>
    <!-- New Dashboard Section: Student Attendance & Payment Status -->
    <div class="dashboard-section">
        <!-- Student Attendance Card -->
        <div class="dashboard-card">
            <div class="dashboard-card-header">
                <span class="dashboard-title">Student Attendance</span>
                <select id="attendance-child-select" class="dashboard-dropdown">
                    @if($children->count() > 0)
                    @foreach($children as $child)
                        <option value="{{ $child->studentID }}">{{ $child->student_name }}</option>
                    @endforeach
                    @else
                        <option value="">No childs found</option>
                    @endif
                </select>
                <select id="attendance-date-select" class="dashboard-dropdown">
                    <option value="">All Dates</option>
                    <option value="{{ date('Y-m-d') }}">Today</option>
                    <option value="{{ date('Y-m-d', strtotime('-1 day')) }}">Yesterday</option>
                    <option value="{{ date('Y-m-d', strtotime('-7 days')) }}">Last Week</option>
                    <option value="{{ date('Y-m-d', strtotime('-30 days')) }}">Last Month</option>
                </select>

            </div>
            <table class="dashboard-table">
                <thead>
                    <tr>
                        <th>Subject Name</th>
                        <th>Date - Time</th>
                        <th>Attendance</th>
                    </tr>
                </thead>
                <tbody id="attendance-tbody">
                    <!-- Akan diisi oleh JS -->
                </tbody>
            </table>
        </div>
        <!-- Payment Status Card -->
        <div class="dashboard-card">
            <div class="dashboard-card-header payment-status-header">
                <span class="dashboard-title">Payment Status</span>
                <select id="payment-month-select" class="dashboard-dropdown">
                    @php
                        $months = ['January','February','March','April','May','June','July','August','September','October','November','December'];
                        $currentMonth = date('F');
                    @endphp
                    @foreach($months as $month)
                        <option value="{{ $month }}" @if($month == $currentMonth) selected @endif>{{ $month }}</option>
                    @endforeach
                </select>
            </div>
            <table class="dashboard-table">
                <thead>
                    <tr>
                        <th>Child's Name</th>
                        <th style="text-align: center;">Payment Status</th>
                    </tr>
                </thead>
                <tbody id="payment-status-tbody">
                    <!-- Akan diisi oleh JS -->
                </tbody>
            </table>
        </div>
    </div>
    <!-- End New Dashboard Section -->
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const dropdown = document.getElementById('child-select');
    const monthDropdown = document.getElementById('month-select');
    const tbody = document.getElementById('child-performance-tbody');
    const attendanceDropdown = document.getElementById('attendance-child-select');
    const attendanceDateSelect = document.getElementById('attendance-date-select');
    const attendanceTbody = document.getElementById('attendance-tbody');
    const paymentMonthSelect = document.getElementById('payment-month-select');
    const paymentTbody = document.getElementById('payment-status-tbody');

    function renderPerformance(data) {
        if (!data.length) {
            tbody.innerHTML = '<tr><td colspan="3" style="text-align:center;color:#888;">No performance data found.</td></tr>';
            return;
        }
        tbody.innerHTML = data.map((row, index) => {
            // Bar progress with animation
            let percent = row.test_score || 0;
            let bar = `
                <div style="background: #ededed; border-radius: 20px; height: 28px; width: 320px; position: relative; margin: 0 auto; overflow: hidden;">
                    <div class="parent-score-bar-animated" data-score="${percent}" style="width: 0%; background: linear-gradient(90deg, #9cc7ec 0%, #7bb3e8 100%); height: 100%; border-radius: 20px; display: flex; align-items: center; justify-content: flex-end; position: relative;">
                        <div class="parent-shimmer" style="position: absolute; top: 0; left: -100%; width: 100%; height: 100%; background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);"></div>
                        <span class="parent-score-label-animated" data-score="${percent}" style="font-weight: bold; color: #222; margin-right: 12px; position: relative; z-index: 10;">0%</span>
                    </div>
                </div>
            `;
            // Status badge
            let status = row.performance_status || '';
            let badgeColor = '#f55d5d';
            if (status === 'Moderate' || status === 'Good') badgeColor = '#ffe066';
            if (status === 'Mastered' || status === 'Excellent') badgeColor = '#6ee66e';
            return `
                <tr class="parent-performance-row" style="height: 60px;" data-index="${index}">
                    <td style="font-weight: bold; color: #222;">${row.subject_name}</td>
                    <td>${bar}</td>
                    <td style="text-align: center;">
                        <span style="display: inline-block; background: ${badgeColor}; color: #222; font-weight: bold; border-radius: 8px; padding: 8px 56px; font-size: 1.1rem;">${status}</span>
                    </td>
                </tr>
            `;
        }).join('');

        // Animate score bars after rendering
        setTimeout(() => {
            animateParentScoreBars();
        }, 100);
    }

    // Function to animate parent score bars
    function animateParentScoreBars() {
        const scoreBars = document.querySelectorAll('.parent-score-bar-animated');
        const scoreLabels = document.querySelectorAll('.parent-score-label-animated');

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
                }
            }, 50);
        });
    }

    function renderAttendance(data) {
        if (!data.length) {
            attendanceTbody.innerHTML = '<tr><td colspan="3" style="text-align:center;color:#888;">No attendance data found.</td></tr>';
            return;
        }
        attendanceTbody.innerHTML = data.map(row => {
            // Format date
            let date = new Date(row.attendance_date);
            let formattedDate = date.toLocaleDateString('en-GB'); // DD/MM/YYYY format

            // Format time
            let startTime = row.start_time ? row.start_time.substring(0, 5) : '';
            let endTime = row.end_time ? row.end_time.substring(0, 5) : '';
            let timeSlot = startTime && endTime ? `${startTime}-${endTime}` : '';

            // Attendance badge
            let status = row.status || '';
            let badgeClass = 'attendance-present';
            let badgeText = 'P';
            if (status === 'Absent') {
                badgeClass = 'attendance-absent';
                badgeText = 'A';
            } else if (status === 'Leave with permission') {
                badgeClass = 'attendance-late';
                badgeText = 'L';
            }

            return `
                <tr>
                    <td class="name-cell">${row.subject_name}</td>
                    <td>${formattedDate} (${timeSlot})</td>
                    <td><span class="attendance-badge ${badgeClass}">${badgeText}</span></td>
                </tr>
            `;
        }).join('');
    }

    function fetchPerformance(studentID, month) {
        fetch('/parent/child_performance', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')
            },
            body: JSON.stringify({ studentID, month })
        })
        .then(res => res.json())
        .then(renderPerformance);
    }

    function renderPaymentStatus(data) {
        if (!data.length) {
            paymentTbody.innerHTML = '<tr><td colspan="2" style="text-align:center;color:#888;">No payment data found.</td></tr>';
            return;
        }
        paymentTbody.innerHTML = data.map((row, index) => {
            const isPaid = row.payment_status === 'Paid';
            const badgeClass = isPaid ? 'payment-paid' : 'payment-unpaid';
            const icon = isPaid ? 'fas fa-check' : 'fas fa-times';

            return `
                <tr class="payment-status-row" data-index="${index}">
                    <td class="name-cell">${row.student_name}</td>
                    <td style="text-align: center;"><span class="payment-badge ${badgeClass}"><i class="${icon}"></i></span></td>
                </tr>
            `;
        }).join('');

        // Animate payment status rows
        setTimeout(() => {
            animatePaymentStatusRows();
        }, 100);
    }

    function animatePaymentStatusRows() {
        const rows = document.querySelectorAll('.payment-status-row');
        rows.forEach((row, index) => {
            setTimeout(() => {
                row.classList.add('show');
            }, index * 150);
        });
    }

    function fetchAttendance(studentID, date) {
        fetch('/parent/child_attendance', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')
            },
            body: JSON.stringify({
                studentID: studentID,
                date: date || ''
            })
        })
        .then(res => res.json())
        .then(renderAttendance);
    }

    function fetchPaymentStatus(month) {
        fetch('/parent/payment_status', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')
            },
            body: JSON.stringify({ month })
        })
        .then(res => res.json())
        .then(renderPaymentStatus);
    }

    // Performance card event listeners
    dropdown.addEventListener('change', function() {
        fetchPerformance(this.value, monthDropdown.value);
    });
    monthDropdown.addEventListener('change', function() {
        fetchPerformance(dropdown.value, this.value);
    });

    // Attendance card event listeners
    attendanceDropdown.addEventListener('change', function() {
        fetchAttendance(this.value, attendanceDateSelect.value);
    });
    attendanceDateSelect.addEventListener('change', function() {
        fetchAttendance(attendanceDropdown.value, this.value);
    });

    // Payment status card event listeners
    paymentMonthSelect.addEventListener('change', function() {
        fetchPaymentStatus(this.value);
    });

    // Auto-load for first child and month
    if (dropdown.value && monthDropdown.value && dropdown.value !== '') fetchPerformance(dropdown.value, monthDropdown.value);
    if (attendanceDropdown.value && attendanceDropdown.value !== '') fetchAttendance(attendanceDropdown.value, attendanceDateSelect.value);
    if (paymentMonthSelect.value) fetchPaymentStatus(paymentMonthSelect.value);

    // Check if no children are found and show appropriate messages
    if (dropdown.options.length === 1 && dropdown.options[0].value === '') {
        tbody.innerHTML = '<tr><td colspan="3" style="text-align:center;color:#888;">No children registered. Please register your children first.</td></tr>';
    }
    if (attendanceDropdown.options.length === 1 && attendanceDropdown.options[0].value === '') {
        attendanceTbody.innerHTML = '<tr><td colspan="3" style="text-align:center;color:#888;">No children registered. Please register your children first.</td></tr>';
    }
    if (paymentMonthSelect.value) {
        // For payment status, we'll show the message even if no children
        fetchPaymentStatus(paymentMonthSelect.value);
    }
});
</script>
</body>
 @include('footer') <!-- Include the footer -->
</html>

