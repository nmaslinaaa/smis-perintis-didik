<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'View Child Attendance')</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
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
        .main-content {
            margin-top: 80px;
            margin-left: 220px;
            padding: 20px;
            position: relative;
            background: #f4f7fa;
            min-height: 100vh;
        }
        .card { max-width: 1100px; margin: 32px auto 0 auto; background: #fff; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); border: 1px solid #e0e0e0; padding: 0; }
        .card-header { display: flex; justify-content: space-between; align-items: center; padding: 24px 32px 0 32px; }
        .card-title { font-size: 1.5rem; font-weight: 700; color: #222; }
        .divider { width: 100%; height: 4px; background: #ffd600; margin: 18px 0 0 0; border-radius: 2px; }
        .filters { display: flex; gap: 18px; align-items: center; margin: 24px 0 0 0; padding: 0 32px; }
        .filters select { padding: 8px 18px; border-radius: 8px; border: 1.5px solid #e0e0e0; font-size: 1.08rem; min-width: 160px; background: #fffbe6; font-weight: 600; }
        .filters select:focus { outline: 2px solid #ffd600; }
        .filters input[type=date] { padding: 8px 18px; border-radius: 8px; border: 1.5px solid #e0e0e0; font-size: 1.08rem; min-width: 160px; background: #fffbe6; font-weight: 600; height: 44px; box-sizing: border-box; }
        .table-container { padding: 24px 32px 32px 32px; }
        table { width: 100%; border-collapse: collapse; background: #fff; border-radius: 10px; overflow: hidden; }
        th, td { padding: 10px 12px; border-bottom: 1px solid #f0f0f0; text-align: left; }
        th { background: #fafafa; font-weight: 600; font-size: 1.08rem; border-bottom: 1.5px solid #e0e0e0; }
        .att-present { color: #137a13; font-weight: bold; }
        .att-absent { color: #f44336; font-weight: bold; }
        .att-leave { color: #bfa600; font-weight: bold; }
        .att-empty { color: #aaa; }

    </style>
</head>
<body>
    @include('header')
    <div class="main-content">
        <div class="card">
            <div class="card-header">
                <div class="card-title">View Child Attendance</div>
            </div>
            <div class="divider"></div>
            <form method="GET" class="filters">
                <select name="childID" onchange="this.form.submit()">
                    @foreach($children as $child)
                        <option value="{{ $child->studentID }}" {{ $selectedChildID == $child->studentID ? 'selected' : '' }}>{{ $child->student_name }}</option>
                    @endforeach
                </select>
                <select name="month" onchange="this.form.submit()">
                    @foreach($months as $month)
                        <option value="{{ $month }}" {{ $selectedMonth == $month ? 'selected' : '' }}>{{ $month }}</option>
                    @endforeach
                </select>
            </form>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th style="width: 60px;">#</th>
                            <th>Student Name</th>
                            <th>Date</th>
                            <th>Subject</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($attendances as $i => $attendance)
                            <tr>
                                <td>{{ $i+1 }}</td>
                                <td>{{ $attendance->student_name }}</td>
                                <td>{{ \Carbon\Carbon::parse($attendance->attendance_date)->format('d M Y') }}</td>
                                <td>{{ $attendance->subject_name }}</td>
                                @php
                                    $status = $attendance->status;
                                    $class = $status === 'Present' ? 'att-present' : ($status === 'Absent' ? 'att-absent' : ($status === 'Leave with permission' ? 'att-leave' : 'att-empty'));
                                @endphp
                                <td><span class="att-status {{ $class }}">{{ $status === 'Leave with permission' ? 'Leave' : ($status ?? '-') }}</span></td>
                            </tr>
                        @empty
                            <tr><td colspan="5" style="text-align:center;">No attendance records for this child and month.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @include('footer')
</body>
</html>
