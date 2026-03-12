<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Fee Status</title>
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
    @if(session('success'))
        <div id="success-popup" style="position: fixed; top: 32px; left: 50%; z-index: 9999; min-width: 260px; background: #d4edda; color: #155724; padding: 16px 28px; border-radius: 8px; box-shadow: 0 2px 12px rgba(0,0,0,0.12); font-size: 1.1rem; opacity: 0; transform: translate(-50%, -20px); transition: opacity 0.4s, transform 0.4s; display: flex; align-items: center; gap: 12px;">
            <span style="display: flex; align-items: center;">
                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="12" cy="12" r="12" fill="#28a745"/>
                    <path d="M7 13.5L11 17L17 9.5" stroke="#fff" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </span>
            <span>{{ session('success') }}</span>
        </div>
        <script>
            window.addEventListener('DOMContentLoaded', function() {
                var popup = document.getElementById('success-popup');
                if (popup) {
                    setTimeout(function() {
                        popup.style.opacity = '1';
                        popup.style.transform = 'translate(-50%, 0)';
                    }, 100);
                    setTimeout(function() {
                        popup.style.opacity = '0';
                        popup.style.transform = 'translate(-50%, -20px)';
                    }, 2500);
                }
            });
        </script>
    @endif
    <div class="class-list-container">
        <form method="GET" action="{{ route('admin.manage_fee_status') }}" style="display: flex; gap: 10px; margin-bottom: 18px; align-items: center;">
            <select name="class_id" onchange="this.form.submit()" style="padding: 8px 18px; border-radius: 8px; font-size: 1.08rem;">
                @foreach($classes as $class)
                    <option value="{{ $class->classID }}" {{ $selectedClassId == $class->classID ? 'selected' : '' }}>
                        {{ $class->class_name }}
                    </option>
                @endforeach
            </select>
            <select name="month" onchange="this.form.submit()" style="padding: 8px 18px; border-radius: 8px; font-size: 1.08rem;">
                @foreach($months as $month)
                    <option value="{{ $month }}" {{ $selectedMonth == $month ? 'selected' : '' }}>{{ $month }}</option>
                @endforeach
            </select>
        </form>
        <form method="POST" action="{{ route('admin.manage_fee_status.save') }}">
            @csrf
            <input type="hidden" name="class_id" value="{{ $selectedClassId }}">
            <input type="hidden" name="month" value="{{ $selectedMonth }}">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div class="class-list-title" style="font-size:2rem; font-weight:700;">Fee Status</div>
                <button type="submit" class="save-btn">Save</button>
            </div>
            <div class="class-list-divider"></div>
            <table class="class-table">
                <thead>
                    <tr>
                        <th style="width: 60px;">#</th>
                        <th style="width: 320px;">Name</th>
                        <th style="width: 180px; text-align: center;">Fee Amount (RM)</th>
                        <th style="width: 200px; text-align: center;">Status</th>
                        <th class="action-header" style="width: 120px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($feeStatuses as $i => $row)
                    <tr>
                        <td>{{ $i+1 }}</td>
                        <td>{{ $row['student']->student_name }}</td>
                        <td style="text-align: center;">{{ number_format($row['amount'], 2) }}</td>
                        <td style="text-align: center;">
                            <input type="hidden" name="students[{{ $i }}][studentID]" value="{{ $row['student']->studentID }}">
                            <select name="students[{{ $i }}][status]" class="status-select" onchange="updateStatusColor(this)">
                                <option value="Paid" {{ $row['status'] == 'Paid' ? 'selected' : '' }}>Paid</option>
                                <option value="Unpaid" {{ $row['status'] == 'Unpaid' ? 'selected' : '' }}>Unpaid</option>
                            </select>
                        </td>
                        <td class="action-cell">
                            <span class="wa-icon"><i class="fab fa-whatsapp"></i></span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </form>
    </div>
</div>

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
.status-select {
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
    border: none;
    outline: none;
    font-size: 1rem;
    font-weight: 600;
    border-radius: 18px;
    padding: 4px 28px 4px 18px;
    min-width: 80px;
    text-align: center;
    text-align-last: center;
    color: #fff;
    background-color: #38e13b;
    background-image: url('data:image/svg+xml;utf8,<svg fill="%23222" height="16" viewBox="0 0 24 24" width="16" xmlns="http://www.w3.org/2000/svg"><path d="M7 10l5 5 5-5z"/></svg>');
    background-repeat: no-repeat;
    background-position: right 10px center;
    background-size: 18px 18px;
    cursor: pointer;
    transition: background 0.2s;
    box-shadow: none;
}
.status-select.unpaid {
    background-color: #ff6b6b;
    color: #fff;
}
.status-select:focus {
    outline: none;
}
.status-select option {
    color: #222;
    background: #fff;
    text-align: center;
}
.status-badge {
    display: inline-flex;
    align-items: center;
    font-size: 1rem;
    font-weight: 600;
    border-radius: 18px;
    padding: 4px 28px 4px 18px;
    margin: 0 0 0 0;
    min-width: 80px;
    justify-content: center;
    position: relative;
}
.status-badge.paid {
    background: #38e13b;
    color: #fff;
}
.status-badge.unpaid {
    background: #ff6b6b;
    color: #fff;
}
.status-badge .arrow {
    font-size: 1.1em;
    margin-left: 10px;
    color: #222;
    font-weight: bold;
    vertical-align: middle;
}
.wa-icon {
    color: #25d366;
    font-size: 1.7em;
    display: flex;
    align-items: center;
    justify-content: center;
}
</style>

<script>
function updateStatusColor(select) {
    if (select.value === 'Paid') {
        select.classList.remove('unpaid');
        select.style.backgroundColor = '#38e13b';
        select.style.color = '#fff';
    } else {
        select.classList.add('unpaid');
        select.style.backgroundColor = '#ff6b6b';
        select.style.color = '#fff';
    }
}
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.status-select').forEach(function(select) {
        updateStatusColor(select);
        select.addEventListener('change', function() {
            updateStatusColor(this);
        });
    });
});
</script>

<style>
.status-select {
    text-align: center;
    text-align-last: center;
}
/* Center option text in most browsers */
.status-select option {
    text-align: center;
    direction: rtl; /* Trick for Chrome/Edge */
}
</style>
<script>
// For Firefox, force option text to center by toggling direction
// This is a workaround for browser inconsistencies
function fixOptionAlignment() {
    document.querySelectorAll('.status-select').forEach(function(select) {
        select.addEventListener('mousedown', function() {
            for (let opt of select.options) {
                opt.style.textAlign = 'center';
            }
        });
    });
}
document.addEventListener('DOMContentLoaded', fixOptionAlignment);
</script>

</body>
 @include('footer') <!-- Include the footer -->
</html>

