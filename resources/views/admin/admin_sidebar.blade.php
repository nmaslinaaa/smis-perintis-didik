<style>
.sidebar {
    width: 270px;
    background-color: #241f1f;
    position: fixed;
    height: 100%;
    top: 0;
    left: 0;
    z-index: 1000;
    overflow-y: auto;
    max-height: 100vh;
}

.sidebar nav ul li a.active {
    background-color: #ffd600 !important;
    color: #222 !important;
    font-weight: bold;
}
</style>

<aside class="sidebar">
    <!-- Logo Section -->
    <div class="logo">
        <img src="{{ asset('images/smislogo.png') }}" alt="Logo"> <!-- Logo Image -->
        <div class="logotext">
            <div class="logotext1">
                <h1>Pusat Tuisyen</h1>
            </div>
            <div class="logotext2">
                <h1>Perintis Didik</h1>
            </div>
        </div>
    </div>
    <div>
        <nav>
            <ul>
                <li><a href="{{ url('/admin/dashboard') }}" class="{{ Request::is('admin/dashboard') ? 'active' : '' }}"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>

                <!-- Dropdown for Employee -->
                <li class="has-submenu {{ Request::is('admin/all_employee*') || Request::is('admin/add_new_employee') || Request::is('admin/manage_employee_login') ? 'open' : '' }}">
                    <a href="#" class="dropdown-toggle">
                        <i class="fas fa-users"></i> Employee
                        <i class="fas fa-caret-down" style="float: right;"></i>
                    </a>
                    <ul class="submenu" style="{{ Request::is('admin/all_employee*') || Request::is('admin/add_new_employee') || Request::is('admin/manage_employee_login') ? 'display:block;' : '' }}">
                        <li><a href="{{ url('/admin/all_employee') }}" class="{{ Request::is('admin/all_employee') ? 'active' : '' }}">All Employees</a></li>
                        <li><a href="{{ url('/admin/add_new_employee') }}" class="{{ Request::is('admin/add_new_employee') ? 'active' : '' }}">Add New Employee</a></li>
                        <li><a href="{{ url('/admin/manage_employee_login') }}" class="{{ Request::is('admin/manage_employee_login') ? 'active' : '' }}">Manage Login</a></li>
                    </ul>
                </li>
                <li class="has-submenu {{ Request::is('admin/all_student*') || Request::is('admin/all_new_student') || Request::is('admin/manage_parent_login') ? 'open' : '' }}">
                    <a href="#" class="dropdown-toggle">
                        <i class="fas fa-user-graduate"></i> Student
                        @if(isset($user) && isset($user->group_level) && $user->group_level == 1 && isset($newStudentCount) && $newStudentCount > 0)
                            <span id="student-main-badge" style="background:#e02a2a;color:#fff;border-radius:50%;padding:2px 9px;font-size:0.95em;margin-left:7px;vertical-align:middle;">{{ $newStudentCount }}</span>
                        @endif
                        <i class="fas fa-caret-down" style="float: right;"></i>
                    </a>
                    <ul class="submenu" style="{{ Request::is('admin/all_student*') || Request::is('admin/all_new_student') || Request::is('admin/manage_parent_login') ? 'display:block;' : '' }}">
                        <li><a href="{{ url('/admin/all_student') }}" class="{{ Request::is('admin/all_student') ? 'active' : '' }}">All Student</a></li>
                        <li>
                            <a href="{{ url('/admin/all_new_student') }}" class="{{ Request::is('admin/all_new_student') ? 'active' : '' }}">
                                New Student
                                @if(isset($user) && isset($user->group_level) && $user->group_level == 1 && isset($newStudentCount) && $newStudentCount > 0)
                                    <span style="background:#e02a2a;color:#fff;border-radius:50%;padding:2px 9px;font-size:0.95em;margin-left:7px;vertical-align:middle;">{{ $newStudentCount }}</span>
                                @endif
                            </a>
                        </li>
                        <li><a href="{{ url('/admin/manage_parent_login') }}" class="{{ Request::is('admin/manage_parent_login') ? 'active' : '' }}">Manage Parent Login</a></li>
                    </ul>
                </li>
                <li class="has-submenu {{ Request::is('admin/manage_classes') || Request::is('admin/add_new_classes') || Request::is('admin/edit_class*') ? 'open' : '' }}">
                    <a href="#" class="dropdown-toggle">
                        <i class="fas fa-chalkboard-teacher"></i> Classes
                        <i class="fas fa-caret-down" style="float: right;"></i>
                    </a>
                    <ul class="submenu" style="{{ Request::is('admin/manage_classes') || Request::is('admin/add_new_classes') || Request::is('admin/edit_class*') ? 'display:block;' : '' }}">
                        <li><a href="{{ url('/admin/manage_classes') }}" class="{{ Request::is('admin/manage_classes') ? 'active' : '' }}">Manage Classes</a></li>
                        <li><a href="{{ url('/admin/add_new_classes') }}" class="{{ Request::is('admin/add_new_classes') ? 'active' : '' }}">New Classes</a></li>
                    </ul>
                </li>
                <li class="has-submenu {{ Request::is('admin/manage_subjects') || Request::is('admin/add_new_subjects') || Request::is('admin/edit_subject*') ? 'open' : '' }}">
                    <a href="#" class="dropdown-toggle">
                        <i class="fas fa-book-open"></i> Subjects
                        <i class="fas fa-caret-down" style="float: right;"></i>
                    </a>
                    <ul class="submenu" style="{{ Request::is('admin/manage_subjects') || Request::is('admin/add_new_subjects') || Request::is('admin/edit_subject*') ? 'display:block;' : '' }}">
                        <li><a href="{{ url('/admin/manage_subjects') }}" class="{{ Request::is('admin/manage_subjects') ? 'active' : '' }}">Manage Subjects</a></li>
                        <li><a href="{{ url('/admin/add_new_subjects') }}" class="{{ Request::is('admin/add_new_subjects') ? 'active' : '' }}">Add New Subjects</a></li>
                    </ul>
                </li>
                 <li class="has-submenu">
                    <a href="#" class="dropdown-toggle">
                        <i class="fas fa-dollar-sign"></i> Fees
                        <i class="fas fa-caret-down" style="float: right;"></i>
                    </a>
                    <ul class="submenu">
                        <li><a href="{{ url('/admin/manage_fee_status') }}" class="{{ Request::is('admin/manage_fee_status') ? 'active' : '' }}">Manage Fee Status</a></li>
                    </ul>
                </li>
                <li class="has-submenu">
                    <a href="#" class="dropdown-toggle">
                        <i class="fas fa-calendar-check"></i> Attendance
                        <i class="fas fa-caret-down" style="float: right;"></i>
                    </a>
                    <ul class="submenu">
                        <li><a href="{{ url('/admin/view_attendance') }}" class="{{ Request::is('admin/view_attendance') ? 'active' : '' }}">View Attendance</a></li>
                    </ul>
                </li>
                <li class="has-submenu">
                    <a href="#" class="dropdown-toggle">
                        <i class="fas fa-chart-line"></i> Performance
                        <i class="fas fa-caret-down" style="float: right;"></i>
                    </a>
                    <ul class="submenu">
                        <li><a href="{{ url('/admin/view_performance') }}" class="{{ Request::is('admin/view_performance') ? 'active' : '' }}">View Performance</a></li>
                    </ul>
                </li>
                <li class="has-submenu">
                    <a href="#" class="dropdown-toggle">
                        <i class="fas fa-file-alt"></i> Report Card
                        <i class="fas fa-caret-down" style="float: right;"></i>
                    </a>
                    <ul class="submenu">
                        <li><a href="{{ url('/admin/generate_report') }}" class="{{ Request::is('admin/generate_report') ? 'active' : '' }}">Generate Report</a></li>
                    </ul>
                </li>
                <li class="has-submenu">
                    <a href="#" class="dropdown-toggle">
                        <i class="fas fa-cogs"></i> Settings
                        <i class="fas fa-caret-down" style="float: right;"></i>
                    </a>
                    <ul class="submenu">
                        <li><a href="{{ url('/manage_profile') }}" class="{{ Request::is('admin/manage_profile') ? 'active' : '' }}">Profile</a></li>
                        <li><a href="{{ url('/logout') }}" class="{{ Request::is('admin/logout') ? 'active' : '' }}">Log Out</a></li>
                    </ul>
                </li>
            </ul>
        </nav>
    </div>
</aside>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Cari semua .has-submenu
    var allMenus = document.querySelectorAll('.has-submenu');
    var studentLi = null;
    allMenus.forEach(function(li) {
        var a = li.querySelector('.dropdown-toggle');
        if (a && a.textContent && a.textContent.trim().startsWith('Student')) {
            studentLi = li;
        }
    });
    var studentBadge = document.getElementById('student-main-badge');
    if (studentLi && studentBadge) {
        // Observer untuk perubahan class
        var observer = new MutationObserver(function() {
            var isOpen = studentLi.classList.contains('open');
            studentBadge.style.display = isOpen ? 'none' : '';
        });
        observer.observe(studentLi, { attributes: true, attributeFilter: ['class'] });
        // Initial check
        studentBadge.style.display = studentLi.classList.contains('open') ? 'none' : '';
    }
});
</script>
