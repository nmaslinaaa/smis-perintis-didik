<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'View Student Information')</title>
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
        /* Main content styles */
        .main-content { margin-top: 80px; margin-left: 220px; padding: 20px; position: relative; background: #f4f7fa; min-height: 100vh; }
        .card { max-width: 1000px; margin: 32px auto 0 auto; background: #fff; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); border: 1px solid #e0e0e0; padding: 0; }
        .card-header { display: flex; justify-content: space-between; align-items: center; padding: 24px 32px 0 32px; }
        .card-title { font-size: 1.5rem; font-weight: 700; color: #222; }
        .lang-btn { background: #ffd600; color: #222; font-weight: 600; font-size: 1.08rem; border: none; border-radius: 18px; padding: 8px 32px; cursor: pointer; box-shadow: 0 2px 6px rgba(0,0,0,0.04); display: flex; align-items: center; gap: 8px; }
        .divider { width: 100%; height: 4px; background: #ffd600; margin: 18px 0 0 0; border-radius: 2px; }
        .filters { display: flex; gap: 18px; align-items: center; margin: 24px 0 0 0; padding: 0 32px; }
        .filters select { padding: 8px 18px; border-radius: 8px; border: 1.5px solid #e0e0e0; font-size: 1.08rem; min-width: 160px; }
        .filters select[disabled] { background: #f7f7f7; color: #aaa; }
        .table-container { padding: 24px 32px 32px 32px; }
        table { width: 100%; border-collapse: collapse; background: #fff; border-radius: 10px; overflow: hidden; }
        th, td { padding: 10px 12px; border-bottom: 1px solid #f0f0f0; text-align: left; }
        th { background: #fafafa; font-weight: 600; font-size: 1.08rem; border-bottom: 1.5px solid #e0e0e0; }
        .perf-level-label { display: inline-block; min-width: 90px; padding: 6px 16px; border-radius: 12px; border: 2px solid #ccc; background: #f9f9f9; text-align: center; font-size: 1rem; font-weight: bold; transition: all 0.2s; }
        .perf-level-label.red { color: #f44336; border-color: #f44336; background: #ffeaea; }
        .perf-level-label.yellow { color: #bfa600; border-color: #ffd600; background: #fffbe6; }
        .perf-level-label.green { color: #137a13; border-color: #3cc13b; background: #e6ffe6; }
        .footer { background-color: #fff; text-align: center; padding: 0px 105px;; position: fixed; width: 100%; bottom: 0; box-shadow: 0 -2px 5px rgba(0, 0, 0, 0.1); }
    </style>
</head>

<body>
    @include('header')
    <div class="main-content">
        <div class="card">
            <div class="card-header">
                <div class="card-title">View Student Performance</div>
            </div>
            <div class="divider"></div>
            <form method="GET" class="filters">
                <select name="month" onchange="this.form.submit()">
                    @foreach($months as $month)
                        <option value="{{ $month }}" {{ $selectedMonth == $month ? 'selected' : '' }}>{{ $month }}</option>
                    @endforeach
                </select>
                <select name="classID" onchange="this.form.submit()" @if(!$selectedMonth) disabled @endif>
                    <option value="">Select Class</option>
                    @foreach($classes as $class)
                        <option value="{{ $class->classID ?? $class['classID'] }}" {{ $selectedClassID == ($class->classID ?? $class['classID']) ? 'selected' : '' }}>{{ $class->class_name ?? $class['class_name'] }}</option>
                    @endforeach
                </select>
                <select name="subjectID" onchange="this.form.submit()" @if(!$selectedClassID) disabled @endif>
                    <option value="">Select Subject</option>
                    @foreach($subjects as $subject)
                        <option value="{{ $subject->subjectID }}" {{ $selectedSubjectID == $subject->subjectID ? 'selected' : '' }}>{{ $subject->subject_name }}</option>
                    @endforeach
                </select>
                <select name="slotID" onchange="this.form.submit()" @if(!$selectedSubjectID) disabled @endif>
                    <option value="">Select Slot</option>
                    @foreach($slots as $slot)
                        <option value="{{ $slot->classsubjectID }}" {{ $selectedSlotID == $slot->classsubjectID ? 'selected' : '' }}>
                            {{ $slot->days }} {{ substr($slot->start_time,0,5) }}-{{ substr($slot->end_time,0,5) }} ({{ $slot->teacher_name }})
                        </option>
                    @endforeach
                </select>
            </form>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th style="width: 60px;">#</th>
                            <th>Student Name</th>
                            <th style="text-align: center;">Test Score</th>
                            <th style="text-align: center;">Performance Level</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($selectedClassID && $selectedSubjectID && $selectedSlotID)
                            @forelse($students as $i => $student)
                                <tr>
                                    <td>{{ $i+1 }}</td>
                                    <td>{{ $student->student_name }}</td>
                                    <td style="text-align: center;">
                                        @if(isset($performanceData[$student->studentID]) && !is_null($performanceData[$student->studentID]->test_score))
                                            {{ rtrim(rtrim(number_format($performanceData[$student->studentID]->test_score, 2), '0'), '.') }}%
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td style="text-align: center;">
                                        @php
                                            $status = $performanceData[$student->studentID]->performance_status ?? null;
                                            $badgeClass = '';
                                            if ($status === 'Need Help') $badgeClass = 'perf-level-label red';
                                            elseif ($status === 'Good') $badgeClass = 'perf-level-label yellow';
                                            elseif ($status === 'Excellent') $badgeClass = 'perf-level-label green';
                                        @endphp
                                        @if($status)
                                            <span class="{{ $badgeClass }}">{{ $status }}</span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" style="text-align:center;">No students found for this slot.</td></tr>
                            @endforelse
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @include('footer')
</body>
</html>
