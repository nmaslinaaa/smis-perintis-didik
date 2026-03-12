<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Evaluate Student Performance')</title>
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
    <div class="class-list-container">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div class="class-list-title" style="font-size:28px; font-weight:700;">
                Evaluate Student Performance ({{ $class ? $class->class_name : '' }} - {{ $subjectName }})
            </div>
            <div style="display: flex; align-items: center; gap: 18px;">
                <form id="slot-filter-form" method="get" action="">
                    <input type="hidden" name="classID" value="{{ $class->classID }}">
                    <input type="hidden" name="subject" value="{{ $subjectName }}">
                    <input type="hidden" name="month" value="{{ $month }}">
                    <!-- Show slot as fixed label, not dropdown -->
                    @php
                        $selectedSlot = null;
                        foreach ($timeSlots as $slot) {
                            if ($selectedClassSubjectID == $slot->classsubjectID) {
                                $selectedSlot = $slot;
                                break;
                            }
                        }
                    @endphp
                    <span style="padding: 7px 18px; border-radius: 8px; font-size: 1.08rem; background: #f7f7f7; border: 1px solid #ccc; display: inline-block; min-width: 180px;">
                        @if($selectedSlot)
                            {{ $selectedSlot->days }} {{ substr($selectedSlot->start_time,0,5) }}-{{ substr($selectedSlot->end_time,0,5) }}
                        @else
                            No slot selected
                        @endif
                    </span>
                </form>
                <button class="save-btn" type="submit" form="performance-form">Save</button>
            </div>
        </div>
        <div class="class-list-divider"></div>
        <div style="font-size:1.35rem; font-weight:600; color:#888; margin: 18px 0 10px 0;">Month - {{ $month }}</div>
        <form id="performance-form" method="POST" action="{{ route('teacher.save_performance') }}">
            @csrf
            <input type="hidden" name="classID" value="{{ $class->classID }}">
            <input type="hidden" name="subjectName" value="{{ $subjectName }}">
            <input type="hidden" name="month" value="{{ $month }}">
            <input type="hidden" name="class_subject_id" value="{{ $selectedClassSubjectID }}">
        <table class="class-table">
            <thead>
                <tr>
                    <th style="width: 50px;">#</th>
                    <th style="width: 220px;">Name</th>
                    <th style="width: 140px; text-align: center;">Test Score</th>
                    <th style="width: 220px; text-align: center;">Performance Level</th>
                    <th style="width: 340px;">Comment</th>
                </tr>
            </thead>
            <tbody>
                @foreach($students as $i => $student)
                @php
                    $perf = $performanceData[$student->studentID] ?? null;
                    $perfStatus = $perf ? $perf->performance_status : '';
                    $perfScore = $perf ? round($perf->test_score) : '';
                    $perfComment = $perf ? $perf->teacher_comment : '';
                    $perfClass = '';
                    if ($perfStatus === 'Need Help') $perfClass = 'red';
                    elseif ($perfStatus === 'Good') $perfClass = 'yellow';
                    elseif ($perfStatus === 'Excellent') $perfClass = 'green';
                @endphp
                <tr>
                    <td>{{ $i+1 }}</td>
                    <td>{{ $student->student_name }}</td>
                    <td style="text-align: center;">
                        <input type="number" min="0" max="100" step="1" class="score-input" name="performances[{{ $student->studentID }}][test_score]" placeholder="0-100" value="{{ $perfScore }}" onchange="roundScore(this)" title="Enter whole numbers only (0-100)">
                    </td>
                    <td style="text-align: center;">
                        <span class="perf-level-label{{ $perfClass ? ' ' . $perfClass : '' }}" style="{{ $perfStatus ? '' : 'display:none;' }}">{{ $perfStatus }}</span>
                        <input type="hidden" name="performances[{{ $student->studentID }}][performance_status]" class="perf-level-hidden" value="{{ $perfStatus }}">
                    </td>
                    <td><input type="text" class="comment-input" name="performances[{{ $student->studentID }}][teacher_comment]"  placeholder="Add comment" value="{{ $perfComment }}"></td>
                </tr>
                @endforeach
                @if(count($students) == 0)
                <tr><td colspan="5" style="text-align:center; color:#b71c1c; font-weight:bold;">No students registered for this slot.</td></tr>
                @endif
            </tbody>
        </table>
    </form>
    </div>
</div>

<style>
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
.score-input {
    width: 70px;
    border: none;
    border-radius: 18px;
    background: #ededed;
    color: #444;
    font-size: 1.08rem;
    font-weight: 600;
    text-align: center;
    padding: 6px 8px; /* Kecilkan padding kanan supaya spin button tidak terlalu ke tepi */
    outline: none;
    box-shadow: none;
}
/* Tunjukkan spin button sentiasa (Chrome/Edge) */
.score-input::-webkit-outer-spin-button,
.score-input::-webkit-inner-spin-button {
    opacity: 1 !important;
    pointer-events: all;
}
/* Untuk Firefox, pastikan spin button ada */
.score-input[type=number] {
    -moz-appearance: number-input;
}
.comment-input {
    width: 90%;
    margin-left: auto;
    margin-right: auto;
    display: block;
    box-sizing: border-box;
    border: 1px solid transparent;
    border-radius: 8px;
    background: #fff;
    color: #222;
    font-size: 1.05rem;
    font-weight: 400;
    padding: 6px 10px;
    outline: none;
    box-shadow: none;
    text-align: center;
}
.comment-input::placeholder {
    text-align: center;
}
.perf-select {
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
    border: none;
    outline: none;
    font-size: 1rem;
    font-weight: 600;
    border-radius: 18px;
    padding: 4px 28px 4px 18px;
    min-width: 120px;
    text-align: center;
    text-align-last: center;
    color: #fff;
    background-color: #bdbdbd;
    background-image: url('data:image/svg+xml;utf8,<svg fill="%23222" height="16" viewBox="0 0 24 24" width="16" xmlns="http://www.w3.org/2000/svg"><path d="M7 10l5 5 5-5z"/></svg>');
    background-repeat: no-repeat;
    background-position: right 10px center;
    background-size: 18px 18px;
    cursor: pointer;
    transition: background 0.2s;
    box-shadow: none;
}
.perf-select.need-help { background-color: #ff6b6b; color: #fff; }
.perf-select.moderate { background-color: #ffd600; color: #222; }
.perf-select.mastered { background-color: #38e13b; color: #fff; }
.perf-select.select-level { background-color: #bdbdbd; color: #222; }
.perf-select option { text-align: center; }
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
    font-size: 2rem;
    font-weight: 700;
    margin: 0;
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
.class-table th:nth-child(4),
.class-table td:nth-child(4) {
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
.perf-level-label {
    display: inline-block;
    min-width: 90px;
    padding: 6px 16px;
    border-radius: 12px;
    border: 2px solid #ccc;
    background: #f9f9f9;
    text-align: center;
    font-size: 1rem;
    font-weight: bold;
    transition: all 0.2s;
}
.perf-level-label.red {
    color: #f44336;
    border-color: #f44336;
    background: #ffeaea;
}
.perf-level-label.yellow {
    color: #bfa600;
    border-color: #ffd600;
    background: #fffbe6;
}
.perf-level-label.green {
    color: #137a13;
    border-color: #3cc13b;
    background: #e6ffe6;
}
</style>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function setPerfLevel(span, score) {
    span.textContent = '';
    span.className = 'perf-level-label';
    var hidden = span.closest('td').querySelector('.perf-level-hidden');
    if (isNaN(score) || score === '') {
        span.style.display = 'none';
        if (hidden) hidden.value = '';
        return;
    }
    span.style.display = 'inline-block';
    if (score < 40) {
        span.textContent = 'Need Help';
        span.classList.add('red');
        if (hidden) hidden.value = 'Need Help';
    } else if (score <= 70) {
        span.textContent = 'Good';
        span.classList.add('yellow');
        if (hidden) hidden.value = 'Good';
    } else {
        span.textContent = 'Excellent';
        span.classList.add('green');
        if (hidden) hidden.value = 'Excellent';
    }
}

function roundScore(input) {
    let score = parseFloat(input.value);
    if (!isNaN(score)) {
        score = Math.round(score);
        input.value = score;
    }
}

document.addEventListener('DOMContentLoaded', function() {
    // Colorize pre-filled performance levels
    document.querySelectorAll('.perf-level-label').forEach(function(span) {
        var status = span.textContent.trim();
        span.classList.remove('red', 'yellow', 'green');
        if (status === 'Need Help') span.classList.add('red');
        else if (status === 'Good') span.classList.add('yellow');
        else if (status === 'Excellent') span.classList.add('green');
    });
    document.querySelectorAll('.score-input').forEach(function(input) {
        input.addEventListener('input', function() {
            // Round the score to whole number
            let score = parseFloat(this.value);
            if (!isNaN(score)) {
                score = Math.round(score);
                this.value = score;
            }

            if (this.value !== '' && (score < 0 || score > 100)) {
                this.value = '';
                var span = this.closest('tr').querySelector('.perf-level-label');
                setPerfLevel(span, '');
                Swal.fire({
                    icon: 'warning',
                    title: 'Invalid Score',
                    text: 'Score must be a whole number between 0 and 100!',
                    confirmButtonColor: '#ffd600',
                });
                return;
            }
            var span = this.closest('tr').querySelector('.perf-level-label');
            setPerfLevel(span, score);
        });
    });
    // Slot filter: reload page on change
    var slotSelect = document.getElementById('slot-select');
    if (slotSelect) {
        slotSelect.addEventListener('change', function() {
            document.getElementById('slot-filter-form').submit();
        });
    }
});
</script>
@if(session('success'))
<script>
Swal.fire({
    icon: 'success',
    title: 'Success!',
    text: @json(session('success')),
    showConfirmButton: false,
    timer: 1800,
    customClass: {
        icon: 'swal2-success swal2-animate-success-icon',
    }
});
</script>
@endif


</body>
 @include('footer') <!-- Include the footer -->
</html>

