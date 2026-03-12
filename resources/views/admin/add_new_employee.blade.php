<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Add New Employee')</title>
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
            width: 240px;
            background-color: #241f1f;
            position: fixed;
            height: 100%;
            top: 0;
            left: 0;
            z-index: 1000;
            overflow-y: auto;
            max-height: 100vh;
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
            margin-top: 80px;
            margin-left: 240px;
            padding: 40px 0 0 0;
            min-height: 100vh;
            background: #f4f7fa;
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

        .add-employee-container {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.08);
            max-width: 900px;
            margin: 0 auto;
            padding: 30px 75px 30px 55px;
        }
        .add-employee-title {
            font-size: 2rem;
            font-weight: 700;
            margin: 0 0 8px 0;
            letter-spacing: 0.5px;
        }
        .add-employee-divider {
            width: 100%;
            height: 3px;
            background: #ffd600;
            margin-bottom: 18px;
        }
        .add-employee-form {
            display: flex;
            flex-wrap: wrap;
            gap: 0 32px;
        }
        .add-employee-form-col {
            flex: 1 1 350px;
            min-width: 320px;
            display: flex;
            flex-direction: column;
            gap: 18px;
        }
        .add-employee-label {
            font-weight: bold;
            font-size: 1.08rem;
            margin-bottom: 4px;
            color: #222;
            letter-spacing: 0.1px;
            text-align: left;
        }
        .add-employee-input, .add-employee-select {
            width: 100%;
            padding: 9px 10px;
            font-size: 1rem;
            border: 1.2px solid #e0e0e0;
            border-radius: 5px;
            background: #f7f7f7;
            color: #222;
            font-family: inherit;
            transition: border 0.2s;
        }
        .add-employee-input:focus, .add-employee-select:focus {
            border: 1.2px solid #ffd600;
            outline: none;
            background: #fffbe6;
        }
        .add-employee-actions {
            width: 100%;
            display: flex;
            gap: 16px;
            margin-top: 18px;
        }
        .add-employee-btn-green {
            background: #19d219;
            color: #fff;
            font-size: 1.08rem;
            font-weight: bold;
            border: none;
            border-radius: 6px;
            padding: 10px 32px;
            cursor: pointer;
            transition: background 0.2s;
            box-shadow: 0 2px 6px rgba(25,210,25,0.08);
        }
        .add-employee-btn-green:hover {
            background: #13b013;
        }
        .add-employee-btn-red {
            background: #f44336;
            color: #fff;
            font-size: 1.08rem;
            font-weight: bold;
            border: none;
            border-radius: 6px;
            padding: 10px 32px;
            cursor: pointer;
            transition: background 0.2s;
            box-shadow: 0 2px 6px rgba(244,67,54,0.08);
        }
        .add-employee-btn-red:hover {
            background: #d32f2f;
        }
    </style>
</head>

<body>

    @include('header') <!-- Include the header -->

    <div class="main-content">
        <div class="add-employee-container">
            <div class="add-employee-title">Add New Employee</div>
            <div class="add-employee-divider"></div>
            <form class="add-employee-form" method="POST" action="/admin/add_new_employee" enctype="multipart/form-data">
                @csrf
                <div class="add-employee-form-col">
                    <div>
                        <label class="add-employee-label">First Name</label>
                        <input class="add-employee-input" type="text" name="firstname" placeholder="Firstname of employee" required>
                    </div>
                    <div>
                        <label class="add-employee-label">IC Number</label>
                        <input class="add-employee-input" type="text" name="ic_number" id="ic_number" placeholder="eg., 020211-03-0102" required>
                        <div id="ic_number_error" style="color: #f44336; font-size: 0.9rem; margin-top: 5px; display: none;"></div>
                        <div id="existing_ic_numbers" style="color: #666; font-size: 0.9rem; margin-top: 5px; display: none;">
                            <strong>Existing IC numbers:</strong> <span id="existing_ic_numbers_list"></span>
                        </div>
                    </div>
                    <div>
                        <label class="add-employee-label">Phone Number</label>
                        <input class="add-employee-input" type="text" name="phonenumber" placeholder="eg., +60 12 345 6789">
                    </div>
                    <div>
                        <label class="add-employee-label">Profile Picture</label>
                        <input class="add-employee-input" type="file" name="profile_picture">
                    </div>
                    <div>
                        <label class="add-employee-label">Employee Role</label>
                        <select class="add-employee-select" name="group_level" required>
                            <option value="" selected>Select</option>
                            <option value="1">Administrator</option>
                            <option value="2">Teacher</option>
                        </select>
                    </div>
                    <div>
                        <label class="add-employee-label">Username</label>
                        <input class="add-employee-input" type="text" name="user_name" placeholder="Username for login" required>
                    </div>
                </div>
                <div class="add-employee-form-col">
                    <div>
                        <label class="add-employee-label">Last Name</label>
                        <input class="add-employee-input" type="text" name="lastname" placeholder="Lastname of employee" required>
                    </div>
                    <div>
                        <label class="add-employee-label">Address</label>
                        <input class="add-employee-input" type="text" name="address" placeholder="eg., Seksyen 7, Shah Alam, Selangor">
                    </div>
                    <div>
                        <label class="add-employee-label">Email</label>
                        <input class="add-employee-input" type="email" name="email" placeholder="eg., abc123@gmail.com">
                    </div>
                    <div>
                        <label class="add-employee-label">Educational Background</label>
                        <input class="add-employee-input" type="text" name="educational_background" placeholder="eg., Degree">
                    </div>
                    <div>
                        <label class="add-employee-label">Current Job</label>
                        <input class="add-employee-input" type="text" name="current_job" placeholder="eg., Teacher">
                    </div>
                    <div>
                        <label class="add-employee-label">Password</label>
                        <input class="add-employee-input" type="password" name="password" placeholder="Password for login" required>
                    </div>
                </div>
                <div class="add-employee-actions">
                    <button type="submit" class="add-employee-btn-green">Add Employee</button>
                    <button type="reset" class="add-employee-btn-red">Reset</button>
                </div>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Fetch existing IC numbers for validation
        let existingICNumbers = [];

        // Fetch existing IC numbers when page loads
        fetch('/admin/get-existing-ic-numbers')
            .then(response => response.json())
            .then(data => {
                existingICNumbers = data.ic_numbers;
            })
            .catch(error => {
                console.error('Error fetching IC numbers:', error);
            });

        // Auto-format as 6-2-4 (e.g., 020211-03-0102) while keeping only digits under the hood
        document.getElementById('ic_number').addEventListener('input', function() {
            const digitsOnly = this.value.replace(/\D/g, '').slice(0, 12);
            const part1 = digitsOnly.slice(0, 6);
            const part2 = digitsOnly.slice(6, 8);
            const part3 = digitsOnly.slice(8, 12);
            let formatted = part1;
            if (part2.length) formatted += '-' + part2;
            if (part3.length) formatted += '-' + part3;
            if (this.value !== formatted) {
                this.value = formatted;
            }
        });

        // Form reset handler
        document.querySelector('button[type="reset"]').addEventListener('click', function() {
            // Clear validation errors
            document.getElementById('ic_number_error').style.display = 'none';
            document.getElementById('existing_ic_numbers').style.display = 'none';
            document.querySelector('button[type="submit"]').disabled = false;
        });

        // Form submit validation
        document.querySelector('.add-employee-form').addEventListener('submit', function(e) {
            const icNumber = document.getElementById('ic_number').value.trim();
            // Remove dashes and spaces for validation
            const icNumberClean = icNumber.replace(/[-\s]/g, '');

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

            // Check if IC number is complete (require 12 digits)
            if (icNumberClean.length < 12) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Incomplete IC Number',
                    text: 'Please enter a complete 12-digit IC number (e.g., 020211-03-0102).',
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
                existing.toLowerCase().replace(/[-\s]/g, '') === icNumberClean.toLowerCase()
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

            // Show success message before submitting
            e.preventDefault();
            Swal.fire({
                icon: 'success',
                title: 'Valid IC Number!',
                text: 'All information is valid. Submitting the form...',
                confirmButtonColor: '#28a745',
                confirmButtonText: 'OK',
                timer: 1500,
                showConfirmButton: false,
                showClass: {
                    popup: 'animate__animated animate__zoomIn'
                },
                hideClass: {
                    popup: 'animate__animated animate__zoomOut'
                }
            }).then(() => {
                // Submit the form after showing success message
                document.querySelector('.add-employee-form').submit();
            });
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

