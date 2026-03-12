<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard')</title>
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
    </style>
    <style>
        /* New Dashboard Section Styles */
        .dashboard-section {
            max-width: 1170px;
            margin: 40px 47px 65px 115px;
            display: flex;
            gap: 40px;
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
            display: inline-block;
            border-radius: 4px;
            padding: 2px 8px;
            color: #fff;
        }
        .payment-paid { background: #3cc13b; }
        .payment-unpaid { background: #f55d5d; }
        .payment-badge i { margin: 0; }
        /* Remove inline margin-bottom for Payment Status header */
        .dashboard-card-header.payment-status-header {
            margin-bottom: 18px;
        }
    </style>
</head>

<body>

    @include('header') <!-- Include the header -->

   <div class="main-content">
    <div style="max-width: 900px; margin: 40px auto 0 auto; background: #fff; border-radius: 14px; box-shadow: 0 2px 8px rgba(0,0,0,0.04); padding: 40px 40px 32px 40px;">
        <div style="font-size: 2rem; font-weight: 700; margin-bottom: 24px; color: #222;">Child Verification Status</div>
        <table style="width:100%;border-collapse:collapse;background:#fff;border-radius:8px;overflow:hidden;box-shadow:none;border:1px solid #d9d9d9;">
            <thead>
                <tr>
                    <th style="padding:14px 8px;font-weight:700;font-size:1.08rem;border:1px solid #d9d9d9;text-align:center;background:#fff;">#</th>
                    <th style="padding:14px 8px;font-weight:700;font-size:1.08rem;border:1px solid #d9d9d9;text-align:left;background:#fff;">Name</th>
                    <th style="padding:14px 8px;font-weight:700;font-size:1.08rem;border:1px solid #d9d9d9;text-align:left;background:#fff;">School Name</th>
                    <th style="padding:14px 8px;font-weight:700;font-size:1.08rem;border:1px solid #d9d9d9;text-align:left;background:#fff;">Class</th>
                    <th style="padding:14px 8px;font-weight:700;font-size:1.08rem;border:1px solid #d9d9d9;text-align:center;background:#fff;">Verification Status</th>
                    <th style="padding:14px 8px;font-weight:700;font-size:1.08rem;border:1px solid #d9d9d9;text-align:center;background:#fff;">Rejection Reason</th>
                </tr>
            </thead>
            <tbody>
                @forelse($children as $i => $child)
                <tr>
                    <td style="text-align:center;border:1px solid #d9d9d9;padding:14px 8px;">{{ $i + 1 }}</td>
                    <td style="font-weight:600;border:1px solid #d9d9d9;padding:14px 8px;">{{ $child->student_name }}</td>
                    <td style="border:1px solid #d9d9d9;padding:14px 8px;">{{ $child->school_name ?? '-' }}</td>
                    <td style="border:1px solid #d9d9d9;padding:14px 8px;">{{ $child->class ? $child->class->class_name : '-' }}</td>
                    <td style="text-align:center;border:1px solid #d9d9d9;padding:14px 8px;">
                        @if(isset($child->verification_status))
                            @if($child->verification_status == 'pending')
                                <span style="background:#ffe066;color:#222;padding:6px 18px;border-radius:8px;font-weight:700;">Pending</span>
                            @elseif($child->verification_status == 'approved')
                                <span style="background:#c8f7c5;color:#137a13;padding:6px 18px;border-radius:8px;font-weight:700;">Approved</span>
                            @elseif($child->verification_status == 'rejected')
                                <span style="background:#ffd6d6;color:#b71c1c;padding:6px 18px;border-radius:8px;font-weight:700;">Rejected</span>
                            @else
                                <span>-</span>
                            @endif
                        @else
                            <span>-</span>
                        @endif
                    </td>
                    <td style="text-align:center;border:1px solid #d9d9d9;padding:14px 8px;">
                        @if(isset($child->verification_status) && $child->verification_status == 'rejected')
                            {{ $child->rejection_reason ?? '-' }}
                        @else
                            -
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align:center;border:1px solid #d9d9d9;padding:14px 8px;">No children found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

</div>
</body>
 @include('footer') <!-- Include the footer -->
</html>

