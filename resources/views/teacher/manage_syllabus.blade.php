<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Syllabus</title>
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
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #f4f7fa; }
        .main-content { margin-top: 80px; margin-left: 220px; padding: 32px 0 32px 0; background: #f4f7fa; min-height: 100vh; }
        .card {
            max-width: 900px;
            margin: 32px auto 0 auto;
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 6px 32px rgba(0,0,0,0.10);
            border: none;
            padding: 0 0 32px 0;
        }
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 32px 48px 0 48px;
        }
        .card-title {
            font-size: 2rem;
            font-weight: 700;
            color: #222;
            margin-right: 32px;
        }
        .header-filters {
            display: flex;
            gap: 24px;
            align-items: center;
        }
        .header-filters select {
            padding: 12px 24px;
            border-radius: 10px;
            border: 2px solid #ffe066;
            font-size: 1.15rem;
            min-width: 180px;
            background: #fffbe6;
            font-weight: 700;
            transition: border 0.2s;
        }
        .header-filters select:focus {
            outline: 2px solid #ffd600;
            border: 2px solid #ffd600;
        }
        .divider {
            width: 100%;
            height: 4px;
            background: #ffd600;
            margin: 18px 0 0 0;
            border-radius: 2px;
        }
        .syllabus-table-container {
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
            margin: 8px 48px 0 48px;
            padding: 32px 32px 24px 32px;
        }
        .syllabus-actions {
            margin-top: 24px;
            margin-left: 32px;
            display: flex;
            gap: 16px;
            justify-content: flex-start;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 1px 4px rgba(0,0,0,0.03);
        }
        th, td {
            padding: 10px 12px;
            border-bottom: 1px solid #f0f0f0;
            text-align: left;
        }
        th {
            background: #fafafa;
            font-weight: 700;
            font-size: 1.08rem;
            border-bottom: 2px solid #e0e0e0;
        }
        tr:last-child td { border-bottom: none; }
        .add-btn-icon {
            background: #3cc13b;
            color: #fff;
            border: none;
            border-radius: 50%;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            cursor: pointer;
            margin: 0 auto;
        }
        .remove-btn {
            background: #f44336;
            color: #fff;
            border: none;
            border-radius: 4px;
            padding: 6px 18px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            display: block;
            margin: 0 auto;
        }
        .remove-input-btn {
            background: #f44336;
            color: #fff;
            border: none;
            border-radius: 4px;
            padding: 6px 18px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            display: inline-block;
            margin-left: 8px;
        }
        .update-btn {
            background: #3cc13b;
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 10px 32px;
            font-weight: 700;
            font-size: 1.08rem;
            cursor: pointer;
            transition: background 0.2s;
        }
        .update-btn:hover {
            background: #2e9e2e;
        }
        .cancel-btn {
            background: #f44336;
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 10px 32px;
            font-weight: 700;
            font-size: 1.08rem;
            cursor: pointer;
            transition: background 0.2s;
            display: inline-block;
            text-align: center;
        }
        .cancel-btn:hover {
            background: #c62828;
        }

                /* Success Message Popup Styles */
        .success-popup {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) scale(0.8);
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.2), 0 8px 32px rgba(0,0,0,0.1);
            padding: 40px;
            text-align: center;
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            transition: all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            min-width: 320px;
            border: 1px solid rgba(255,255,255,0.2);
        }

        .success-popup.show {
            opacity: 1;
            visibility: visible;
            transform: translate(-50%, -50%) scale(1);
        }

        .success-icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: linear-gradient(135deg, #3cc13b 0%, #2e9e2e 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px;
            animation: successPulse 0.8s ease;
            box-shadow: 0 8px 24px rgba(60, 193, 59, 0.3);
            position: relative;
        }

        .success-icon::before {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background: linear-gradient(135deg, #3cc13b 0%, #2e9e2e 100%);
            animation: ripple 1.5s ease-out infinite;
            opacity: 0.3;
        }

        .success-icon i {
            color: white;
            font-size: 32px;
            animation: checkmark 0.6s ease 0.3s both;
            position: relative;
            z-index: 1;
        }

        .success-message {
            font-size: 1.4rem;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 12px;
            animation: slideUp 0.5s ease 0.4s both;
        }

        .success-subtitle {
            font-size: 1rem;
            color: #7f8c8d;
            animation: slideUp 0.5s ease 0.5s both;
        }

                @keyframes successPulse {
            0% { transform: scale(0.6); opacity: 0; }
            50% { transform: scale(1.15); }
            100% { transform: scale(1); opacity: 1; }
        }

        @keyframes checkmark {
            0% { transform: scale(0) rotate(-45deg); opacity: 0; }
            50% { transform: scale(1.3) rotate(-45deg); opacity: 1; }
            100% { transform: scale(1) rotate(0deg); opacity: 1; }
        }

        @keyframes ripple {
            0% { transform: scale(1); opacity: 0.3; }
            100% { transform: scale(1.5); opacity: 0; }
        }

        @keyframes slideUp {
            0% { transform: translateY(20px); opacity: 0; }
            100% { transform: translateY(0); opacity: 1; }
        }

        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.3);
            z-index: 999;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .overlay.show {
            opacity: 1;
            visibility: visible;
        }
    </style>
</head>
<body>
    @include('header')

    <div class="main-content">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Manage Syllabus</div>
                <form method="GET" class="header-filters">
                    <select name="classID" onchange="this.form.submit()">
                        <option value="" disabled {{ !$selectedClassID ? 'selected' : '' }}>Select Class</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->classID }}" {{ $selectedClassID == $class->classID ? 'selected' : '' }}>{{ $class->class_name }}</option>
                        @endforeach
                    </select>
                    <select name="subjectID" onchange="this.form.submit()">
                        <option value="" disabled {{ !$selectedSubjectID ? 'selected' : '' }}>Select Subject</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->subjectID }}" {{ $selectedSubjectID == $subject->subjectID ? 'selected' : '' }}>{{ $subject->subject_name }}</option>
                        @endforeach
                    </select>
                </form>
            </div>
            <div class="divider"></div>
            @if($selectedClassID && $selectedSubjectID)
                <form method="POST" action="{{ url('/teacher/manage_syllabus/update') }}">
                    @csrf
                    <input type="hidden" name="classID" value="{{ $selectedClassID }}">
                    <input type="hidden" name="subjectID" value="{{ $selectedSubjectID }}">
                    <div class="syllabus-table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th style="width: 60px;">No.</th>
                                    <th>Syllabus</th>
                                    <th style="width: 120px;">Action</th>
                                </tr>
                            </thead>
                            <tbody id="syllabus-table-body">
                                <!-- Add row always at the top -->
                                <tr class="add-row">
                                    <td class="row-number">1</td>
                                    <td>
                                        <input type="text" class="add-syllabus-input" placeholder="Add new syllabus..." style="width: 90%; padding: 6px 10px; border-radius: 4px; border: 1px solid #ccc; font-size: 1rem;">
                                    </td>
                                    <td style="text-align:center;">
                                        <button type="button" class="add-btn-icon"><i class="fas fa-plus"></i></button>
                                    </td>
                                </tr>
                                <!-- Existing syllabus items -->
                                @foreach($syllabus as $i => $item)
                                    <tr>
                                        <td class="row-number">{{ $i+2 }}</td>
                                        <td>{{ $item->syllabus_name }}</td>
                                        <td style="text-align:center;">
                                            <button type="button" class="remove-db-btn remove-btn" data-syllabus-id="{{ $item->syllabusID }}">Remove</button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="syllabus-actions">
                        <button type="submit" name="update" value="1" class="update-btn">Update</button>
                        <a href="{{ url()->current() }}?classID={{ $selectedClassID }}&subjectID={{ $selectedSubjectID }}" class="cancel-btn" style="text-decoration:none;">Cancel</a>
                    </div>
                </form>
            @else
                <div style="text-align:center; color:#888; font-size:1.15rem; margin:48px 0 32px 0;">Please select both a class and a subject to manage the syllabus.</div>
            @endif
        </div>
    </div>
    @include('footer')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tableBody = document.getElementById('syllabus-table-body');
            if (!tableBody) return;
            function updateRowNumbers() {
                const rows = tableBody.querySelectorAll('tr');
                rows.forEach((row, idx) => {
                    const numberCell = row.querySelector('.row-number');
                    if (numberCell) numberCell.textContent = idx + 1;
                });
            }
            // Handle form submission with SweetAlert2 success popup
            const form = document.querySelector('form[method="POST"]');
            if (form) {
                form.addEventListener('submit', function(e) {
                    const updateBtn = e.submitter;
                    if (updateBtn && updateBtn.name === 'update') {
                        e.preventDefault();
                        const formData = new FormData(form);
                        console.log([...formData.entries()]); // DEBUG: log all form data
                        // Remove empty add-row input before submit
                        const addRowInput = document.querySelector('.add-row input[name^="new_syllabus"]');
                        if (addRowInput && addRowInput.value.trim() === '') {
                            addRowInput.disabled = true;
                        }
                        fetch('/teacher/manage_syllabus/update', {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                            }
                        })
                        .then(async response => {
                            let text = await response.text();
                            if (!response.ok) {
                                alert(text); // Show real error
                                throw new Error(text);
                            }
                            return text;
                        })
                        .then(data => {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: 'Syllabus updated successfully!',
                                showConfirmButton: false,
                                timer: 1800,
                                customClass: {
                                    icon: 'swal2-success swal2-animate-success-icon',
                                }
                            });
                            setTimeout(() => {
                                window.location.reload();
                            }, 1800);
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            // window.location.reload();
                        });
                    }
                });
            }
            // Add row (Google Sheets style)
            tableBody.addEventListener('click', function(e) {
                if (e.target.closest('.add-btn-icon')) {
                    e.preventDefault();
                    const addRow = document.querySelector('.add-row');
                    const input = addRow.querySelector('.add-syllabus-input');
                    const value = input.value.trim();
                    if (value === '') {
                        input.setCustomValidity('Please fill out this field.');
                        input.reportValidity();
                        input.setCustomValidity('');
                        return;
                    }
                    // Create a static row below the add row
                    const staticRow = document.createElement('tr');
                    staticRow.innerHTML = `
                        <td class="row-number"></td>
                        <td><input type="hidden" name="new_syllabus[]" value="${value}"><span>${value}</span></td>
                        <td style="text-align:center;"><button type="button" class="remove-input-btn remove-btn">Remove</button></td>
                    `;
                    addRow.parentNode.appendChild(staticRow);
                    input.value = '';
                    updateRowNumbers();
                }
                // Remove input/static row
                if (e.target.closest('.remove-input-btn')) {
                    e.preventDefault();
                    const row = e.target.closest('tr');
                    row.remove();
                    updateRowNumbers();
                }
                // Remove syllabus from DB (existing logic)
                if (e.target.classList.contains('remove-db-btn')) {
                    e.preventDefault();
                    const btn = e.target;
                    const syllabusID = btn.getAttribute('data-syllabus-id');
                    Swal.fire({
                        title: 'Are you sure?',
                        text: 'This syllabus item will be permanently deleted.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#43b324',
                        cancelButtonColor: '#f44336',
                        confirmButtonText: 'Yes, remove it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            fetch("/teacher/manage_syllabus/remove", {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                                },
                                body: JSON.stringify({ syllabusID })
                            })
                            .then(res => res.json())
                            .then(data => {
                                if (data.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Deleted!',
                                        text: 'Syllabus item removed.',
                                        showConfirmButton: false,
                                        timer: 1200
                                    });
                                    // Remove the row from the table
                                    btn.closest('tr').remove();
                                    updateRowNumbers();
                                } else {
                                    Swal.fire('Error', 'Failed to remove syllabus item.', 'error');
                                }
                            })
                            .catch(() => {
                                Swal.fire('Error', 'Failed to remove syllabus item.', 'error');
                            });
                        }
                    });
                    return;
                }
            });
            updateRowNumbers();
        });
    </script>
</body>
</html>

