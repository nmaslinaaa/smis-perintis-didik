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
                <li><a href="{{ url('/parent/dashboard') }}" class="{{ Request::is('parent/dashboard') ? 'active' : '' }}"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                <li class="has-submenu {{ Request::is('parent/my_child') || Request::is('parent/register_children') || Request::is('parent/child_verification_status') ? 'open' : '' }}">
                    <a href="#" class="dropdown-toggle">
                        <i class="fas fa-user-graduate"></i> My Childs
                        <i class="fas fa-caret-down" style="float: right;"></i>
                    </a>
                    <ul class="submenu" style="{{ Request::is('parent/my_child') || Request::is('parent/register_children') || Request::is('parent/child_verification_status') ? 'display:block;' : '' }}">
                        <li><a href="{{ url('/parent/my_child') }}" class="{{ Request::is('parent/my_child') ? 'active' : '' }}">All Childs</a></li>
                        <li><a href="{{ url('/parent/register_children') }}" class="{{ Request::is('parent/register_children') ? 'active' : '' }}">Register New Child</a></li>
                        <li><a href="{{ url('/parent/child_verification_status') }}" class="{{ Request::is('parent/child_verification_status') ? 'active' : '' }}">New Child Verification Status</a></li>
                    </ul>
                </li>
                <li><a href="{{ url('/parent/view_child_performance') }}" class="{{ Request::is('parent/view_child_performance') ? 'active' : '' }}"><i class="fas fa-chart-line"></i> Performance</a></li>
                <li><a href="{{ url('/parent/view_child_attendance') }}" class="{{ Request::is('parent/view_child_attendance') ? 'active' : '' }}"><i class="fas fa-calendar-check"></i> Attendance</a></li>
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
