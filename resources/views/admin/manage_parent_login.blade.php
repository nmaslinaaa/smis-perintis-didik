
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Manage Parent Login')</title>
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

<div class="main-content">
    <div style="background: #fff; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.04); padding: 36px 36px 24px 36px; max-width: 1200px; margin: 0 auto;">
        <h2 style="font-size: 32px; font-weight: 700; margin: 0 0 8px 0;">Manage Parent Login</h2>
        <div style="width: 100%; height: 3px; background: #ffd600; margin-bottom: 24px;"></div>
        <table style="width: 100%; border-collapse: collapse; background: #fff;">
            <thead>
                <tr>
                    <th style="width: 40px; border: 1px solid #e0e0e0; padding: 12px 8px; background: #fafafa; font-weight: 700; font-size: 16px; text-align: left;">#</th>
                    <th style="border: 1px solid #e0e0e0; padding: 12px 8px; background: #fafafa; font-weight: 700; font-size: 16px; text-align: left;">Name</th>
                    <th style="border: 1px solid #e0e0e0; padding: 12px 8px; background: #fafafa; font-weight: 700; font-size: 16px; text-align: left;">Role</th>
                    <th style="border: 1px solid #e0e0e0; padding: 12px 8px; background: #fafafa; font-weight: 700; font-size: 16px; text-align: left;">Username</th>
                    <th style="border: 1px solid #e0e0e0; padding: 12px 8px; background: #fafafa; font-weight: 700; font-size: 16px; text-align: left;">Password</th>
                    <th style="border: 1px solid #e0e0e0; padding: 12px 8px; background: #fafafa; font-weight: 700; font-size: 16px; text-align: left;">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($parents as $i => $parent)
                <tr>
                    <td style="border: 1px solid #e0e0e0; padding: 12px 8px; font-size: 16px;">{{ $i+1 }}</td>
                    <td style="border: 1px solid #e0e0e0; padding: 12px 8px; font-size: 16px;">{{ $parent->name }}</td>
                    <td style="border: 1px solid #e0e0e0; padding: 12px 8px; font-size: 16px;">Parent</td>
                    <td style="border: 1px solid #e0e0e0; padding: 12px 8px; font-size: 16px;">
                        <input type="text" value="{{ $parent->user_name }}" class="form-control" style="width: 100px; background: #eaeaea; border: none; border-radius: 6px; padding: 6px 10px; font-size: 15px; text-align: center;">
                    </td>
                    <td style="border: 1px solid #e0e0e0; padding: 12px 8px; font-size: 16px;">
                        <div style="display: flex; align-items: center;">
                            <input type="password" value="{{ $parent->password }}" class="form-control parent-password" style="width: 80px; background: #eaeaea; border: none; border-radius: 6px; padding: 6px 10px; font-size: 15px; text-align: center;">
                            <button type="button" class="toggle-password" style="background: none; border: none; margin-left: 8px; cursor: pointer;">
                                <i class="fas fa-eye-slash"></i>
                            </button>
                        </div>
                    </td>
                    <td style="border: 1px solid #e0e0e0; padding: 12px 8px; font-size: 16px;">
                        <button type="button" class="action-btn btn-edit" style="background:#2196f3; color:#fff; padding: 6px 18px; border-radius: 6px; font-size: 15px; font-weight: 600; margin-right: 8px;" data-parent-id="{{ $parent->parentID }}">Save</button>
                        <button type="button" class="btn-whatsapp" style="background: #25d366; color: #fff; border: none; border-radius: 6px; padding: 8px 12px; font-size: 16px; cursor: pointer; margin-left: 4px;" data-parent-id="{{ $parent->parentID }}" data-parent-name="{{ $parent->name }}">
                            <i class="fab fa-whatsapp"></i>
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align:center; padding: 30px;">No parent data.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// Toggle password visibility
    document.querySelectorAll('.toggle-password').forEach(function(btn){
        btn.onclick = function(){
            const input = btn.parentNode.parentNode.querySelector('.parent-password');
            if(input.type === 'password'){
                input.type = 'text';
                btn.innerHTML = '<i class=\'fas fa-eye\'></i>';
            }else{
                input.type = 'password';
                btn.innerHTML = '<i class=\'fas fa-eye-slash\'></i>';
            }
        };
    });

// Save only the current row (AJAX, green on success, blue on edit, no error color, only if changed)
    document.querySelectorAll('.btn-edit').forEach(function(btn){
        const row = btn.closest('tr');
        const usernameInput = row.querySelector('input[type="text"]');
        const passwordInput = row.querySelector('.parent-password');
        // Store original value
        btn._original = {
            user_name: usernameInput.value,
            password: passwordInput.value
        };
        btn.onclick = function(){
            const parentId = btn.getAttribute('data-parent-id');
            const user_name = usernameInput.value;
            const password = passwordInput.value;
            // Check if changed
            if (user_name === btn._original.user_name && password === btn._original.password) {
                Swal.fire({
                    icon: 'info',
                    title: 'No Changes',
                    text: 'No changes detected. Please edit before saving.',
                    timer: 1500,
                    showConfirmButton: false
                });
                return;
            }
            fetch(`/admin/update_parent_login/${parentId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ user_name, password })
            })
            .then(res => res.json())
            .then(data => {
                if(data.success){
                    btn.classList.add('saved');
                    btn.style.background = '#43b324';
                    btn.style.color = '#fff';
                    btn.innerText = 'Saved';
                    // Update original value
                    btn._original.user_name = user_name;
                    btn._original.password = password;
                    Swal.fire({ icon: 'success', title: 'Success', text: data.message, timer: 1200, showConfirmButton: false });
                }else{
                    Swal.fire({ icon: 'error', title: 'Error', text: data.message || 'Update failed.' });
                }
            })
            .catch(() => {
                Swal.fire({ icon: 'error', title: 'Error', text: 'Update failed.' });
            });
        };
        // Listen for input changes to revert button color
        [usernameInput, passwordInput].forEach(input => {
            input.addEventListener('input', function(){
                btn.classList.remove('saved');
                btn.style.background = '#2196f3';
                btn.style.color = '#fff';
                btn.innerText = 'Save';
            });
        });
    });

    // WhatsApp button functionality
    document.querySelectorAll('.btn-whatsapp').forEach(function(btn) {
        btn.onclick = function() {
            const parentId = btn.getAttribute('data-parent-id');
            const parentName = btn.getAttribute('data-parent-name');

            // Show loading state
            const originalContent = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            btn.disabled = true;

            fetch(`/admin/send_parent_login_credentials/${parentId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    // Show success state
                    btn.innerHTML = '<i class="fas fa-check"></i>';
                    btn.style.background = '#43b324';

                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: `Login credentials sent to ${parentName} via WhatsApp`,
                        timer: 2000,
                        showConfirmButton: false
                    });

                    // Reset button after 3 seconds
                    setTimeout(() => {
                        btn.innerHTML = originalContent;
                        btn.style.background = '#25d366';
                        btn.disabled = false;
                    }, 3000);
                } else {
                    // Show error state
                    btn.innerHTML = originalContent;
                    btn.disabled = false;

                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'Failed to send WhatsApp notification'
                    });
                }
            })
            .catch(error => {
                // Show error state
                btn.innerHTML = originalContent;
                btn.disabled = false;

                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to send WhatsApp notification. Please try again.'
                });
            });
        };
    });
</script>
</body>
 @include('footer') <!-- Include the footer -->
</html>

