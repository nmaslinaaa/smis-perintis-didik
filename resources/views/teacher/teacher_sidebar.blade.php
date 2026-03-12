<style>
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
                <li><a href="{{ url('/teacher/dashboard') }}" class="{{ Request::is('teacher/dashboard') ? 'active' : '' }}"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                <li class="has-submenu {{ Request::is('admin/all_student*') || Request::is('teacher/evaluate_performance') || Request::is('teacher/view_performance') ? 'open' : '' }}">
                    <a href="#" class="dropdown-toggle">
                        <i class="fas fa-user-graduate"></i> Student
                        <i class="fas fa-caret-down" style="float: right;"></i>
                    </a>
                    <ul class="submenu" style="{{ Request::is('admin/all_student*') || Request::is('teacher/evaluate_performance') || Request::is('teacher/view_performance') ? 'display:block;' : '' }}">
                        <li><a href="{{ url('/admin/all_student') }}" class="{{ Request::is('admin/all_student') ? 'active' : '' }}">All Student</a></li>
                        <li><a href="{{ url('/teacher/evaluate_performance') }}" class="{{ Request::is('teacher/evaluate_performance') ? 'active' : '' }}">Evaluate Performance</a></li>
                        {{-- <li><a href="{{ url('/teacher/view_performance') }}" class="{{ Request::is('teacher/view_performance') ? 'active' : '' }}">View Performance</a></li> --}}
                    </ul>
                </li>
                <li class="has-submenu {{ Request::is('teacher/record_attendance') || Request::is('teacher/view_attendance') ? 'open' : '' }}">
                    <a href="#" class="dropdown-toggle">
                        <i class="fas fa-calendar-check"></i> Attendance
                        <i class="fas fa-caret-down" style="float: right;"></i>
                    </a>
                    <ul class="submenu" style="{{ Request::is('teacher/record_attendance') || Request::is('teacher/view_attendance') ? 'display:block;' : '' }}">
                        <li><a href="{{ url('/teacher/record_attendance') }}" class="{{ Request::is('teacher/record_attendance') ? 'active' : '' }}">Record Attendance</a></li>
                        {{-- <li><a href="{{ url('/teacher/view_attendance') }}" class="{{ Request::is('teacher/view_attendance') ? 'active' : '' }}">View Attendance</a></li> --}}
                    </ul>
                </li>
                <li class="has-submenu {{ Request::is('teacher/syllabus_coverage') || Request::is('teacher/manage_syllabus') ? 'open' : '' }}">
                    <a href="#" class="dropdown-toggle">
                        <i class="fas fa-book-open"></i> Syllabus Coverage
                        <i class="fas fa-caret-down" style="float: right;"></i>
                    </a>
                    <ul class="submenu" style="{{ Request::is('teacher/syllabus_coverage') || Request::is('teacher/manage_syllabus') ? 'display:block;' : '' }}">
                        <li><a href="{{ url('/teacher/syllabus_coverage') }}" class="{{ Request::is('teacher/syllabus_coverage') ? 'active' : '' }}">Coverage</a></li>
                        <li><a href="{{ url('/teacher/manage_syllabus') }}" class="{{ Request::is('teacher/manage_syllabus') ? 'active' : '' }}">Manage Syllabus</a></li>
                    </ul>
                </li>
                <li class="has-submenu {{ Request::is('manage_profile') || Request::is('logout') ? 'open' : '' }}">
                    <a href="#" class="dropdown-toggle">
                        <i class="fas fa-cogs"></i> Settings
                        <i class="fas fa-caret-down" style="float: right;"></i>
                    </a>
                    <ul class="submenu" style="{{ Request::is('manage_profile') || Request::is('logout') ? 'display:block;' : '' }}">
                        <li><a href="{{ url('/manage_profile') }}" class="{{ Request::is('manage_profile') ? 'active' : '' }}">Profile</a></li>
                        <li><a href="{{ url('/logout') }}" class="{{ Request::is('logout') ? 'active' : '' }}">Log Out</a></li>
                    </ul>
                </li>
            </ul>
        </nav>
    </div>
</aside>
