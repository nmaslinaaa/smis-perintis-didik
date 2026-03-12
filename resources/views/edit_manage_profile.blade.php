<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
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
        .big-input {
            width: 100%;
            padding: 16px 14px;
            font-size: 1.18rem;
            border: 1.5px solid #e0e0e0;
            border-radius: 7px;
            background: #f7f7f7;
            color: #222;
            font-family: inherit;
            margin-top: 6px;
            margin-bottom: 2px;
            transition: border 0.2s;
        }
        .big-input:focus {
            border: 1.5px solid #ffd600;
            outline: none;
            background: #fffbe6;
        }
        .password-wrapper {
            position: relative;
            width: 100%;
        }
        .password-toggle {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            font-size: 1.25rem;
            color: #888;
        }
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

<div class="main-content" style="margin-top: 60px; margin-left: 220px; padding: 0; min-height: 100vh; background: #f4f7fa;">
    <div style="max-width: 900px; margin: 40px auto 0 auto; background: #fff; border-radius: 14px; box-shadow: 0 2px 8px rgba(0,0,0,0.04); padding: 40px 40px 32px 40px;">
        <form method="POST" action="{{ url('/update_manage_profile') }}" enctype="multipart/form-data">
            @csrf
            <div style="text-align: center; margin-bottom: 32px;">
                <img src="{{ isset($profile->profile_picture) && $profile->profile_picture ? asset('uploads/profile_pictures/' . $profile->profile_picture) : asset('images/smislogo.png') }}" alt="Profile" style="width: 130px; height: 130px; border-radius: 50%; border: 2px solid #ccc; object-fit: cover; margin-bottom: 10px;">
                <div style="margin-top: 10px;">
                    <input type="file" name="profile_picture" accept="image/*">
                </div>
            </div>
            <div style="display: flex; flex-wrap: wrap; gap: 32px;">
                <div style="flex: 1 1 350px; min-width: 320px; display: flex; flex-direction: column; gap: 18px;">
                    @if($role === 'Administrator' || $role === 'Teacher')
                        <label>First Name<input class="big-input" type="text" name="firstname" value="{{ $profile->firstname }}" required></label>
                        <label>Last Name<input class="big-input" type="text" name="lastname" value="{{ $profile->lastname }}" required></label>
                        <label>IC Number
                            <input class="big-input" type="text" name="ic_number" id="ic_number" value="{{ $profile->ic_number }}" required>
                            <div id="ic_number_error" style="color: #f44336; font-size: 0.9rem; margin-top: 5px; display: none;"></div>
                            <div id="existing_ic_numbers" style="color: #666; font-size: 0.9rem; margin-top: 5px; display: none;">
                                <strong>Existing IC numbers:</strong> <span id="existing_ic_numbers_list"></span>
                            </div>
                        </label>
                        <label>Phone Number<input class="big-input" type="text" name="phonenumber" value="{{ $profile->phonenumber }}" required></label>
                        <label>Address<input class="big-input" type="text" name="address" value="{{ $profile->address }}" required></label>
                        <label>Email<input class="big-input" type="email" name="email" value="{{ $profile->email }}" required></label>
                        <label>Educational Background<input class="big-input" type="text" name="educational_background" value="{{ $profile->educational_background }}"></label>
                        <label>Current Job<input class="big-input" type="text" name="current_job" value="{{ $profile->current_job }}"></label>
                        <label>Username<input class="big-input" type="text" name="user_name" value="{{ $profile->user_name }}" required></label>
                        <label>Password
                            <div class="password-wrapper">
                                <input class="big-input" type="password" name="password" value="{{ $profile->password }}" id="main-password" required>
                                <button type="button" class="password-toggle" onclick="togglePassword('main-password', this)"><i class="fas fa-eye"></i></button>
                            </div>
                        </label>
                    @elseif($role === 'Parent')
                        <label>Name<input class="big-input" type="text" name="name" value="{{ $profile->name }}" required></label>
                        <label>Phone Number<input class="big-input" type="text" name="phonenumber" value="{{ $profile->phonenumber }}" required></label>
                        <label>Email<input class="big-input" type="email" name="email" value="{{ $profile->email }}" required></label>
                        <label>Username<input class="big-input" type="text" name="user_name" value="{{ $profile->user_name }}" required></label>
                        <label>Password
                            <div class="password-wrapper">
                                <input class="big-input" type="password" name="password" value="{{ $profile->password }}" id="main-password" required>
                                <button type="button" class="password-toggle" onclick="togglePassword('main-password', this)"><i class="fas fa-eye"></i></button>
                            </div>
                        </label>
                    @endif
                </div>
            </div>
            @if($role === 'Parent' && isset($children))
            <div style="margin-top: 40px;">
                <div style="font-size: 1.5rem; font-weight: 700; margin-bottom: 12px; color: #222;">Children List</div>
                @foreach($children as $i => $child)
                <div style="background: #fffbe9; border: 2px solid #ffe066; border-radius: 14px; padding: 24px 32px; margin-bottom: 24px; max-width: 700px; font-size: 1.08rem; font-weight: 600; color: #222;">
                    <div style="font-size: 1.18rem; font-weight: 700; margin-bottom: 10px;">{{ $child->student_name }}</div>
                    <input type="hidden" name="children[{{ $i }}][studentID]" value="{{ $child->studentID }}">
                    <label>Name<input class="big-input" type="text" name="children[{{ $i }}][student_name]" value="{{ $child->student_name }}" required></label>
                    <label>School Name<input class="big-input" type="text" name="children[{{ $i }}][school_name]" value="{{ $child->school_name }}"></label>
                    <label>Address<input class="big-input" type="text" name="children[{{ $i }}][address]" value="{{ $child->address }}"></label>
                    <label>Start Date<input class="big-input" type="date" name="children[{{ $i }}][tuition_startdate]" value="{{ $child->tuition_startdate }}"></label>
                </div>
                @endforeach
            </div>
            @endif
            <div style="text-align: right; margin-top: 32px;">
                <button type="submit" style="background: #43b324; color: #fff; font-size: 1.15rem; font-weight: 700; border: none; border-radius: 6px; padding: 12px 48px; cursor: pointer;">Save</button>
                <a href="{{ url('/manage_profile') }}" style="background: #f44336; color: #fff; font-size: 1.15rem; font-weight: 700; border: none; border-radius: 6px; padding: 12px 48px; cursor: pointer; text-decoration: none; margin-left: 16px;">Cancel</a>
            </div>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Data guru untuk JS (id dan nama)
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
    });
</script>
<script>
function togglePassword(id, btn) {
    const input = document.getElementById(id);
    if (input.type === 'password') {
        input.type = 'text';
        btn.innerHTML = '<i class="fas fa-eye-slash"></i>';
    } else {
        input.type = 'password';
        btn.innerHTML = '<i class="fas fa-eye"></i>';
    }
}

// IC Number validation for employees only
@if($role === 'Administrator' || $role === 'Teacher')
// Fetch existing IC numbers for validation (excluding current employee)
let existingICNumbers = [];
const currentICNumber = '{{ $profile->ic_number }}';

// Fetch existing IC numbers when page loads
fetch('/admin/get-existing-ic-numbers')
    .then(response => response.json())
    .then(data => {
        // Filter out the current employee's IC number
        existingICNumbers = data.ic_numbers.filter(ic => ic !== currentICNumber);
    })
    .catch(error => {
        console.error('Error fetching IC numbers:', error);
    });

// Real-time validation for IC number
document.getElementById('ic_number').addEventListener('input', function() {
    const icNumber = this.value.trim();
    const errorDiv = document.getElementById('ic_number_error');
    const existingDiv = document.getElementById('existing_ic_numbers');
    const existingList = document.getElementById('existing_ic_numbers_list');
    const submitBtn = document.querySelector('button[type="submit"]');

    // Clear previous errors
    errorDiv.style.display = 'none';
    existingDiv.style.display = 'none';

    if (icNumber === '') {
        submitBtn.disabled = false;
        return;
    }

    // Check for duplicates (case-insensitive), but allow the current IC number
    const isDuplicate = existingICNumbers.some(existing =>
        existing.toLowerCase() === icNumber.toLowerCase()
    );

    if (isDuplicate) {
        // Show SweetAlert pop-up instead of inline error
        Swal.fire({
            icon: 'error',
            title: 'Duplicate IC Number!',
            text: 'An employee with this IC number already exists. Please check the IC number and try again.',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'OK',
            showClass: {
                popup: 'animate__animated animate__zoomIn'
            },
            hideClass: {
                popup: 'animate__animated animate__zoomOut'
            }
        });

        submitBtn.disabled = true;

        // Show existing IC numbers for reference
        existingList.textContent = existingICNumbers.join(', ');
        existingDiv.style.display = 'block';
    } else {
        errorDiv.style.display = 'none';
        existingDiv.style.display = 'none';
        submitBtn.disabled = false;

        // Show success message for valid IC number (only if it's not empty and different from current)
        if (icNumber.length > 0 && icNumber !== currentICNumber) {
            Swal.fire({
                icon: 'success',
                title: 'Valid IC Number!',
                text: 'This IC number is available and can be used.',
                confirmButtonColor: '#28a745',
                confirmButtonText: 'Great!',
                timer: 2000,
                showConfirmButton: false,
                showClass: {
                    popup: 'animate__animated animate__zoomIn'
                },
                hideClass: {
                    popup: 'animate__animated animate__zoomOut'
                }
            });
        }
    }
});

// Form submit validation
document.querySelector('form').addEventListener('submit', function(e) {
    const icNumber = document.getElementById('ic_number').value.trim();

    if (icNumber === '') {
        e.preventDefault();
        Swal.fire({
            icon: 'warning',
            title: 'Required Field',
            text: 'IC Number is required.',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'OK',
            showClass: {
                popup: 'animate__animated animate__fadeInDown'
            },
            hideClass: {
                popup: 'animate__animated animate__fadeOutUp'
            }
        });
        return false;
    }

    // Check for duplicates before submitting
    const isDuplicate = existingICNumbers.some(existing =>
        existing.toLowerCase() === icNumber.toLowerCase()
    );

    if (isDuplicate) {
        e.preventDefault();
        Swal.fire({
            icon: 'error',
            title: 'Duplicate IC Number!',
            text: 'An employee with this IC number already exists. Please check the IC number and try again.',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'OK',
            showClass: {
                popup: 'animate__animated animate__zoomIn'
            },
            hideClass: {
                popup: 'animate__animated animate__zoomOut'
            }
        });
        return false;
    }
});
@endif
</script>

</body>
 @include('footer') <!-- Include the footer -->
</html>

