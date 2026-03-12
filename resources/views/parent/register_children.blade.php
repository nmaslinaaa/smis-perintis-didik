<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Register New Child')</title>
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
        <div class="add-employee-container">
            <div class="add-employee-title">Register New Child</div>
            <div class="add-employee-divider"></div>
            <form class="add-employee-form" method="POST" action="{{ url('/parent/register_children') }}">
                @csrf
                <div class="add-employee-form-col">
                    <div>
                        <label class="add-employee-label">Name</label>
                        <input class="add-employee-input" type="text" name="child_name" placeholder="Name of Child" required>
                    </div>
                    <div>
                        <label class="add-employee-label">School Name</label>
                        <input class="add-employee-input" type="text" name="school_name" placeholder="eg., SK Permatang Damai Laut" required>
                    </div>
                    <div>
                        <label class="add-employee-label">Address</label>
                        <input class="add-employee-input" type="text" name="address" placeholder="eg., Seksyen 7, Shah Alam, Selangor" required>
                    </div>
                    <div>
                        <label class="add-employee-label">Class</label>
                        <select class="add-employee-select" name="class" id="class-select" required>
                            <option value="" selected>Select</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->classID }}">{{ $class->class_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="add-employee-form-col">
                    <div>
                        <label class="add-employee-label">Start Date</label>
                        <input class="add-employee-input" type="date" name="start_date" placeholder="eg., 01-01-2025" required>
                    </div>
                    <div>
                        <label class="add-employee-label">Subject Taken</label>
                        <div id="subject-dropdowns">
                            <div class="subject-dropdown-row" style="display:flex;align-items:center;gap:8px;">
                                <select class="add-employee-select subject-select" name="subject_taken[]" required>
                                    <option value="" selected>Select Subject</option>
                                </select>
                                <select class="add-employee-select timing-select" name="subject_timing[]" style="display:none;" required>
                                    <option value="" selected>Select Timing</option>
                                </select>
                            </div>
                        </div>
                        <button type="button" id="add-more-subject" style="margin-top:8px; background:#5a8ecb; color:#fff; border:none; border-radius:5px; padding:6px 16px; font-size:1rem; cursor:pointer;">add more</button>
                    </div>
                </div>
                <div class="add-employee-actions">
                    <button type="submit" class="add-employee-btn-green">Add Child</button>
                    <button type="reset" class="add-employee-btn-red">Reset</button>
                </div>
            </form>
        </div>
    </div>

<script>
window.addEventListener('DOMContentLoaded', function() {
    const addMoreBtn = document.getElementById('add-more-subject');
    const subjectDropdownsDiv = document.getElementById('subject-dropdowns');
    const classSelect = document.getElementById('class-select');
    let subjectsData = [];

    // Function to update all subject dropdowns
    function updateSubjectDropdowns(subjects) {
        subjectsData = subjects;
        const subjectRows = subjectDropdownsDiv.querySelectorAll('.subject-dropdown-row');
        subjectRows.forEach(row => {
            const subjectSelect = row.querySelector('.subject-select');
            const timingSelect = row.querySelector('.timing-select');
            subjectSelect.options.length = 1;
            if (subjects.length === 0) {
                const option = document.createElement('option');
                option.value = '';
                option.textContent = 'No subjects available';
                option.disabled = true;
                subjectSelect.appendChild(option);
                subjectSelect.selectedIndex = 1;
                timingSelect.style.display = 'none';
            } else {
                subjects.forEach(subject => {
                    const option = document.createElement('option');
                    option.value = subject.subjectID;
                    option.textContent = subject.subject_name;
                    subjectSelect.appendChild(option);
                });
                timingSelect.style.display = 'none';
                timingSelect.options.length = 1;
            }
        });
    }

    // Prevent duplicate subject selection
    function updateSubjectDropdownOptions() {
        const subjectSelects = subjectDropdownsDiv.querySelectorAll('.subject-select');
        const selectedValues = Array.from(subjectSelects)
            .map(select => select.value)
            .filter(val => val !== "");
        subjectSelects.forEach(select => {
            const currentValue = select.value;
            Array.from(select.options).forEach(option => {
                if (option.value === "" || option.value === currentValue) {
                    option.disabled = false;
                } else if (selectedValues.includes(option.value)) {
                    option.disabled = true;
                } else {
                    option.disabled = false;
                }
            });
        });
    }

    // Helper to check if two time ranges overlap
    function timeRangesOverlap(a, b) {
        if (!a || !b) return false;
        // a and b: { day, start, end }
        if (a.day !== b.day) return false;
        // Compare as HH:MM:SS
        return (a.start < b.end && b.start < a.end);
    }

    // Parse timing option value and text to extract day, start, end
    function parseTimingOption(option) {
        // Example: 'Isnin 08:00:00-10:00:00'
        const match = option.textContent.match(/^(\S+)\s(\d{2}:\d{2}:\d{2})-(\d{2}:\d{2}:\d{2})/);
        if (!match) return null;
        return { day: match[1], start: match[2], end: match[3] };
    }

    // Prevent overlapping timing selection for the same child
    function updateTimingDropdownOptions() {
        const timingSelects = subjectDropdownsDiv.querySelectorAll('.timing-select');
        // Collect all selected timing values and their parsed info
        const selectedTimings = Array.from(timingSelects)
            .map(select => {
                const selectedOption = select.options[select.selectedIndex];
                return selectedOption && selectedOption.value ? { value: selectedOption.value, ...parseTimingOption(selectedOption) } : null;
            })
            .filter(val => val && val.value);
        timingSelects.forEach(select => {
            const currentValue = select.value;
            Array.from(select.options).forEach(option => {
                if (option.value === "" || option.value === currentValue) {
                    option.disabled = false;
                } else {
                    // Parse this option's timing
                    const thisTiming = parseTimingOption(option);
                    // Disable if overlaps with any other selected timing
                    const clash = selectedTimings.some(sel => sel.value !== option.value && timeRangesOverlap(thisTiming, sel));
                    option.disabled = clash;
                }
            });
        });
    }

    function attachSubjectSelectListeners() {
        const subjectSelects = subjectDropdownsDiv.querySelectorAll('.subject-select');
        subjectSelects.forEach(select => {
            select.removeEventListener('change', handleSubjectChange);
            select.addEventListener('change', handleSubjectChange);
            select.removeEventListener('change', updateSubjectDropdownOptions);
            select.addEventListener('change', updateSubjectDropdownOptions);
        });
    }

    function attachTimingSelectListeners() {
        const timingSelects = subjectDropdownsDiv.querySelectorAll('.timing-select');
        timingSelects.forEach(select => {
            select.removeEventListener('change', updateTimingDropdownOptions);
            select.addEventListener('change', updateTimingDropdownOptions);
        });
    }

    // Update timing dropdowns whenever subject or timing changes
    function attachAllListeners() {
        attachSubjectSelectListeners();
        attachTimingSelectListeners();
    }

    // Modify handleSubjectChange to call updateTimingDropdownOptions after showing timing
    function handleSubjectChange(e) {
        const subjectSelect = e.target;
        const row = subjectSelect.closest('.subject-dropdown-row');
        const timingSelect = row.querySelector('.timing-select');
        const subjectID = subjectSelect.value;
        timingSelect.options.length = 1;
        if (!subjectID) {
            timingSelect.style.display = 'none';
            timingSelect.disabled = true;
            updateTimingDropdownOptions();
            return;
        }
        const subject = subjectsData.find(s => s.subjectID == subjectID);
        if (subject && subject.options.length > 0) {
            subject.options.forEach(opt => {
                const option = document.createElement('option');
                option.value = opt.classsubjectID;
                option.textContent = `${opt.days} ${opt.start_time}-${opt.end_time} (${opt.teacher_name || ''})`;
                timingSelect.appendChild(option);
            });
            timingSelect.style.display = '';
            timingSelect.disabled = false;
        } else {
            timingSelect.style.display = 'none';
            timingSelect.disabled = true;
        }
        updateTimingDropdownOptions();
    }

    // Update listeners after adding/removing rows
    addMoreBtn.addEventListener('click', function() {
        const firstRow = subjectDropdownsDiv.querySelector('.subject-dropdown-row');
        const select = firstRow.querySelector('.subject-select');
        const timingSelect = firstRow.querySelector('.timing-select');
        const newRow = document.createElement('div');
        newRow.className = 'subject-dropdown-row';
        newRow.style.display = 'flex';
        newRow.style.alignItems = 'center';
        newRow.style.gap = '8px';
        const newSelect = select.cloneNode(true);
        newSelect.value = '';
        const newTimingSelect = timingSelect.cloneNode(true);
        newTimingSelect.value = '';
        newTimingSelect.style.display = 'none';
        // Add remove button
        const removeBtn = document.createElement('button');
        removeBtn.type = 'button';
        removeBtn.innerHTML = '✖';
        removeBtn.title = 'Remove';
        removeBtn.style.background = '#f44336';
        removeBtn.style.color = '#fff';
        removeBtn.style.border = 'none';
        removeBtn.style.borderRadius = '50%';
        removeBtn.style.width = '28px';
        removeBtn.style.height = '28px';
        removeBtn.style.fontSize = '1rem';
        removeBtn.style.cursor = 'pointer';
        removeBtn.style.display = 'flex';
        removeBtn.style.alignItems = 'center';
        removeBtn.style.justifyContent = 'center';
        removeBtn.style.marginTop = '2px';
        removeBtn.addEventListener('click', function() {
            newRow.remove();
            updateSubjectDropdownOptions();
            updateTimingDropdownOptions();
        });
        newRow.appendChild(newSelect);
        newRow.appendChild(newTimingSelect);
        newRow.appendChild(removeBtn);
        subjectDropdownsDiv.appendChild(newRow);
        attachAllListeners();
        updateSubjectDropdownOptions();
        updateTimingDropdownOptions();
    });

    // Fix: Always refresh and attach listeners after fetching subjects
    function refreshSubjectDropdowns(subjects) {
        updateSubjectDropdowns(subjects);
        updateSubjectDropdownOptions();
        attachAllListeners();
        updateTimingDropdownOptions();
    }

    // On page load, clear all subject dropdowns except the first option
    refreshSubjectDropdowns([]);

    // Fetch subjects for the selected class
    classSelect.addEventListener('change', function() {
        const classID = this.value;
        if (!classID) {
            refreshSubjectDropdowns([]); // Clear if no class selected
            return;
        }
        fetch(`/parent/class/${classID}/subjects`)
            .then(response => response.json())
            .then(subjects => {
                refreshSubjectDropdowns(subjects);
            });
    });

    // Initial attach
    attachAllListeners();
    updateTimingDropdownOptions();

    // Client-side validation for required fields
    const form = document.querySelector('.add-employee-form');
    form.addEventListener('submit', function(e) {
        // Always enable all timing selects before submit (so classsubjectID is sent)
        document.querySelectorAll('.timing-select').forEach(function(sel) { sel.disabled = false; });
        // Remove any previous error message
        let errorDiv = document.getElementById('clientErrorMsg');
        if (errorDiv) errorDiv.remove();

        let errorMsg = '';
        const name = form.child_name.value.trim();
        const schoolName = form.school_name.value.trim();
        const address = form.address.value.trim();
        const classID = form.class.value;
        const startDate = form.start_date.value;
        const subjectSelects = document.querySelectorAll('.subject-select');
        const timingSelects = document.querySelectorAll('.timing-select');
        let hasSubject = false;
        for (let i = 0; i < subjectSelects.length; i++) {
            if (subjectSelects[i].value && timingSelects[i].value) {
                hasSubject = true;
            }
        }
        if (!name) errorMsg = 'Name is required.';
        else if (!schoolName) errorMsg = 'School Name is required.';
        else if (!address) errorMsg = 'Address is required.';
        else if (!classID) errorMsg = 'Class is required.';
        else if (!startDate) errorMsg = 'Start Date is required.';
        else if (!hasSubject) errorMsg = 'At least one subject and timing must be selected.';
        if (errorMsg) {
            e.preventDefault();
            const mainContent = document.querySelector('.main-content');
            const errorDiv = document.createElement('div');
            errorDiv.id = 'clientErrorMsg';
            errorDiv.style.background = '#ffeaea';
            errorDiv.style.border = '1.5px solid #f44336';
            errorDiv.style.color = '#b71c1c';
            errorDiv.style.fontWeight = 'bold';
            errorDiv.style.padding = '12px 30px';
            errorDiv.style.borderRadius = '8px';
            errorDiv.style.margin = '0 auto 18px auto';
            errorDiv.style.maxWidth = '500px';
            errorDiv.style.textAlign = 'center';
            errorDiv.innerText = errorMsg;
            mainContent.insertBefore(errorDiv, mainContent.firstChild);
            return false;
        }
    });
});
</script>
</body>
 @include('footer') <!-- Include the footer -->
</html>

