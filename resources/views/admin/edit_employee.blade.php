<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Edit Employee')</title>
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
            padding: 23px;
            background-color: #fff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            position: fixed; /* Fixed header */
            top: 0;
            padding-left: 0px;
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
            width: 230px; /* Fixed width for the sidebar */
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

   <div class="main-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div style="background: #fff; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.04); padding: 28px 20px 18px 20px; max-width: 800px; margin: 0 auto;">
                        <h2 style="font-size: 24px; font-weight: 700; margin: 0 0 8px 0;">Edit {{ $employee->firstname }} {{ $employee->lastname }}</h2>
                        <div style="width: 100%; height: 3px; background: #ffd600; margin-bottom: 18px;"></div>
                        <form style="display: flex; flex-wrap: wrap; gap: 20px 24px;" method="POST" action="/admin/update_employee/{{ $employee->employeeID }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div style="flex: 1 1 240px; min-width: 200px;">
                                <div style="margin-bottom: 12px;">
                                    <label style="font-weight: bold; font-size: 16px; display: block; margin-bottom: 4px;">First Name</label>
                                    <input type="text" name="firstname" value="{{ $employee->firstname }}" style="width: 100%; padding: 7px; font-size: 15px; border: 1px solid #ccc; border-radius: 4px;">
                                </div>
                                <div style="margin-bottom: 12px;">
                                    <label style="font-weight: bold; font-size: 16px; display: block; margin-bottom: 4px;">Last Name</label>
                                    <input type="text" name="lastname" value="{{ $employee->lastname }}" style="width: 100%; padding: 7px; font-size: 15px; border: 1px solid #ccc; border-radius: 4px;">
                                </div>
                                <div style="margin-bottom: 12px;">
                                    <label style="font-weight: bold; font-size: 16px; display: block; margin-bottom: 4px;">IC Number</label>
                                    <input type="text" name="ic_number" id="ic_number" value="{{ $employee->ic_number }}" required style="width: 100%; padding: 7px; font-size: 15px; border: 1px solid #ccc; border-radius: 4px;">
                                    <div id="ic_number_error" style="color: #f44336; font-size: 0.9rem; margin-top: 5px; display: none;"></div>
                                    <div id="existing_ic_numbers" style="color: #666; font-size: 0.9rem; margin-top: 5px; display: none;">
                                        <strong>Existing IC numbers:</strong> <span id="existing_ic_numbers_list"></span>
                                    </div>
                                </div>
                                <div style="margin-bottom: 12px;">
                                    <label style="font-weight: bold; font-size: 16px; display: block; margin-bottom: 4px;">Phone Number</label>
                                    <input type="text" name="phonenumber" value="{{ $employee->phonenumber }}" style="width: 100%; padding: 7px; font-size: 15px; border: 1px solid #ccc; border-radius: 4px;">
                                </div>
                                <div style="margin-bottom: 12px;">
                                    <label style="font-weight: bold; font-size: 16px; display: block; margin-bottom: 4px;">Profile Picture</label>
                                    <input type="file" name="profile_picture" style="width: 100%; font-size: 14px;">
                                </div>
                                <div style="margin-bottom: 12px;">
                                    <label style="font-weight: bold; font-size: 16px; display: block; margin-bottom: 4px;">Employee Role</label>
                                    <div style="padding: 9px 10px; background: #f7f7f7; border-radius: 5px; color: #222; font-size: 1rem;">
                                        {{ $employee->group_level == 1 ? 'Administrator' : ($employee->group_level == 2 ? 'Teacher' : 'Parent') }}
                                    </div>
                                    <input type="hidden" name="group_level" value="{{ $employee->group_level }}">
                                </div>
                            </div>
                            <div style="flex: 1 1 240px; min-width: 200px;">
                                <div style="margin-bottom: 12px;">
                                    <label style="font-weight: bold; font-size: 16px; display: block; margin-bottom: 4px;">Address</label>
                                    <input type="text" name="address" value="{{ $employee->address }}" style="width: 100%; padding: 7px; font-size: 15px; border: 1px solid #ccc; border-radius: 4px;">
                                </div>
                                <div style="margin-bottom: 12px;">
                                    <label style="font-weight: bold; font-size: 16px; display: block; margin-bottom: 4px;">Email</label>
                                    <input type="email" name="email" value="{{ $employee->email }}" style="width: 100%; padding: 7px; font-size: 15px; border: 1px solid #ccc; border-radius: 4px;">
                                </div>
                            </div>
                            <div style="width: 100%; display: flex; gap: 14px; justify-content: flex-start; margin-top: 6px;">
                                <button type="submit" style="background: #19d219; color: #fff; font-size: 16px; font-weight: bold; border: none; border-radius: 6px; padding: 8px 28px; cursor: pointer;">Update</button>
                                <a href="/admin/all_employee"><button type="button" style="background: #f44336; color: #fff; font-size: 16px; font-weight: bold; border: none; border-radius: 6px; padding: 8px 28px; cursor: pointer;">Cancel</button></a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Fetch existing IC numbers for validation (excluding current employee)
        let existingICNumbers = [];
        const currentEmployeeID = {{ $employee->employeeID }};
        const currentICNumber = '{{ $employee->ic_number }}';

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
                        popup: 'animate__animated animate__zoomIn'
                    },
                    hideClass: {
                        popup: 'animate__animated animate__zoomOut'
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

        // Display validation errors from server
        @if($errors->any())
            @foreach($errors->all() as $error)
                Swal.fire({
                    icon: 'error',
                    title: 'Validation Error',
                    text: '{{ $error }}',
                    confirmButtonColor: '#3085d6'
                });
            @endforeach
        @endif
    </script>
</body>
 @include('footer') <!-- Include the footer -->
</html>

