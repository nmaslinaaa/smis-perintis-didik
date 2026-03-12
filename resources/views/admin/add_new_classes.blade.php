<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Classes</title>
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
        font-size: 2.1rem;
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
        border-collapse: separate;
        border-spacing: 0;
        background: #fff;
        margin-top: 0;
        font-size: 1.08rem;
    }
    .class-table th {
        font-weight: 700;
        font-size: 1.08rem;
        padding: 12px 0 12px 12px;
        border-bottom: 2px solid #e0e0e0;
        background: none;
        color: #222;
    }
    .class-table th:first-child,
    .class-table td:first-child {
        text-align: left;
        padding-left: 18px;
    }
    .class-table th:nth-child(2),
    .class-table td:nth-child(2) {
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
        border-bottom: 1px solid #e0e0e0;
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
    @if($errors->any())
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: @json($errors->first()),
            showConfirmButton: false,
            timer: 2200,
            customClass: {
                icon: 'swal2-error swal2-animate-error-icon',
            }
        });
    </script>
    @endif
    <div class="class-list-container" style="max-width:700px;">
        <div class="class-list-title" style="font-size:2rem;">Create New Class</div>
        <div class="class-list-divider" style="margin-bottom:30px;"></div>
        <form method="POST" action="{{ url('/admin/add_new_classes') }}" id="create-class-form">
            @csrf
            <div style="margin-bottom: 28px;">
                <label for="class_name" style="font-weight: bold; font-size: 1.2rem;">Class Name</label><br>
                <input type="text" id="class_name" name="class_name" placeholder="Name of Class"
                       style="width: 400px; padding: 10px; font-size: 1.1rem; border: 1px solid #ccc; border-radius: 5px; margin-top: 7px;"
                       value="{{ old('class_name') }}" required>
                <div id="class_name_error" style="color: #f44336; font-size: 0.9rem; margin-top: 5px; display: none;"></div>
                <div id="existing_classes" style="color: #666; font-size: 0.9rem; margin-top: 5px;">
                    <strong>Existing classes:</strong>
                    @php
                        $existingClasses = \App\Models\Classes::pluck('class_name')->toArray();
                        echo implode(', ', $existingClasses);
                    @endphp
                </div>
            </div>
            <div style="margin-bottom: 32px;">
                <label style="font-weight: bold; font-size: 1.2rem;">Assign Teacher</label><br>
                <div style="display: flex; align-items: center; margin-bottom: 10px;">
                    <select id="teacher_select" style="width: 350px; padding: 10px; font-size: 1.1rem; border: 1px solid #ccc; border-radius: 5px;">
                        <option value="">Select</option>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->employeeID }}">{{ $teacher->firstname }} {{ $teacher->lastname }}</option>
                        @endforeach
                    </select>
                    <button type="button" id="add_teacher_btn" style="margin-left: 10px; background: none; border: none; cursor: pointer;">
                        <span style="font-size: 2rem; color: #3cb043; display: flex; align-items: center;"><i class="fas fa-plus-circle"></i></span>
                    </button>
                </div>
                <div id="assigned_teachers_list"></div>
            </div>
            <div style="margin-top: 40px; display: flex; gap: 30px;">
                <button type="submit" style="background: #19d219; color: #fff; font-weight: bold; font-size: 1.1rem; border: none; border-radius: 5px; padding: 12px 40px; cursor: pointer;">Create Class</button>
                <button type="reset" style="background: #f44336; color: #fff; font-weight: bold; font-size: 1.1rem; border: none; border-radius: 5px; padding: 12px 40px; cursor: pointer;">Reset</button>
            </div>
        </form>
    </div>
</div>
<script>
    // Data guru untuk JS (id dan nama)
    const teachers = [
        @foreach($teachers as $teacher)
            {id: '{{ $teacher->employeeID }}', name: '{{ $teacher->firstname }} {{ $teacher->lastname }}'},
        @endforeach
    ];

    // Existing classes for validation
    const existingClasses = [
        @php
            $existingClasses = \App\Models\Classes::pluck('class_name')->toArray();
            foreach($existingClasses as $className) {
                echo "'" . addslashes($className) . "',";
            }
        @endphp
    ];

    // Real-time validation for class name
    document.getElementById('class_name').addEventListener('input', function() {
        const className = this.value.trim();
        const errorDiv = document.getElementById('class_name_error');
        const submitBtn = document.querySelector('button[type="submit"]');

        if (className === '') {
            errorDiv.style.display = 'none';
            submitBtn.disabled = false;
            return;
        }

        // Check for duplicates (case-insensitive)
        const isDuplicate = existingClasses.some(existing =>
            existing.toLowerCase() === className.toLowerCase()
        );

        if (isDuplicate) {
            errorDiv.textContent = 'A class with this name already exists. Please choose a different name.';
            errorDiv.style.display = 'block';
            submitBtn.disabled = true;
        } else {
            errorDiv.style.display = 'none';
            submitBtn.disabled = false;
        }
    });

    // Add teacher
    document.getElementById('add_teacher_btn').onclick = function() {
        const select = document.getElementById('teacher_select');
        const id = select.value;
        const name = select.options[select.selectedIndex].text;
        if(!id) return;
        // Elak duplicate
        if(document.querySelector('input[name="assigned_teachers[]"][value="'+id+'"]')) return;
        // Create row
        const row = document.createElement('div');
        row.className = 'assigned-teacher-row';
        row.style = 'display: flex; align-items: center; margin-bottom: 10px;';
        row.innerHTML = `<select disabled style=\"width: 350px; padding: 10px; font-size: 1.1rem; border: 1px solid #ccc; border-radius: 5px; background: #f7f7f7; color: #333;\"><option>${name}</option></select><button type=\"button\" class=\"remove-teacher-btn\" style=\"margin-left: 10px; background: none; border: none; cursor: pointer;\"><span style=\"font-size: 2rem; color: #f44336; display: flex; align-items: center;\"><i class=\"fas fa-times-circle\"></i></span></button><input type=\"hidden\" name=\"assigned_teachers[]\" value=\"${id}\">`;
        document.getElementById('assigned_teachers_list').appendChild(row);
        row.querySelector('.remove-teacher-btn').onclick = function() {
            row.remove();
        };
        // Remove from dropdown
        select.remove(select.selectedIndex);
        select.selectedIndex = 0;
    };
    // Reset button: clear assigned teachers list and reset dropdown
    document.getElementById('create-class-form').addEventListener('reset', function() {
        document.getElementById('assigned_teachers_list').innerHTML = '';
        const select = document.getElementById('teacher_select');
        select.innerHTML = '<option value="">Select</option>' + teachers.map(t => `<option value="${t.id}">${t.name}</option>`).join('');
        // Clear validation error
        document.getElementById('class_name_error').style.display = 'none';
        document.querySelector('button[type="submit"]').disabled = false;
    });
</script>

</body>
 @include('footer') <!-- Include the footer -->
</html>

