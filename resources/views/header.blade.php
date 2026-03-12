@php
use App\Models\Employee;
use App\Models\ParentModel;

$user = session('user');

$name = 'Guest';
$role = 'User';
$profileImage = asset('images/user-profile.png');

if ($user) {

    if ($user->group_level == 1 || $user->group_level == 2) {

        $employee = Employee::find($user->employeeID);

        if ($employee) {
            $name = $employee->firstname ?? $employee->user_name ?? 'Employee';
            $role = $employee->group_level == 1 ? 'Administrator' : 'Teacher';

           if (!empty($employee->profile_picture)) {

                $filePath = public_path('uploads/profile_pictures/'.$employee->profile_picture);
            
                if (file_exists($filePath)) {
                    $profileImage = asset('uploads/profile_pictures/'.$employee->profile_picture);
                } else {
                    $profileImage = asset('images/user-profile.png');
                }            
            }
        }

    } elseif ($user->group_level == 3) {

        $parent = ParentModel::find($user->parentID);

        if ($parent) {
            $name = $parent->name ?? $parent->user_name ?? 'Parent';
            $role = 'Parent';

            if (!empty($parent->profile_picture)) {

                $filePath = public_path('uploads/profile_pictures/'.$parent->profile_picture);
            
                if (file_exists($filePath)) {
                    $profileImage = asset('uploads/profile_pictures/'.$parent->profile_picture);
                } else {
                    $profileImage = asset('images/user-profile.png');
                }
            
            }
        }
    }
}
@endphp
<header class="header">
    <div class="header-date">
        <span class="date-left" id="realtime-date"></span>
    </div>
    <div class="header-right">
        <div class="profile-box" style="display: flex; align-items: center; gap: 0px;">
            <div class="profile-info" style="display: flex; flex-direction: column; align-items: flex-start; margin-right: 0; line-height: 1.05;">
                <span style="font-weight: bold; font-size: 1.08rem; color: #222; margin-bottom: 0px; line-height: 1.1;">
                    {{ ucwords(strtolower($name)) }}
                </span>
                <span style="font-size: 0.92rem; color: #444; margin-top: 0px;">
                    {{ $role }}
                </span>
            </div>
            <img class="profile-img" src="{{ $profileImage }}" alt="Profile Image" style="border-radius: 50%; object-fit: cover; border: none; vertical-align: middle; width: 48px; height: 48px; padding-right: 10px;">
        </div>
    </div>
</header>
<script>
    function updateDate() {
        const now = new Date();
        const options = { month: 'long', day: 'numeric', year: 'numeric', hour: 'numeric', minute: '2-digit', second: '2-digit', hour12: true };
        document.getElementById('realtime-date').textContent = now.toLocaleString('en-US', options);
    }
    updateDate();
    setInterval(updateDate, 1000);
</script>
<!-- Sidebar -->
<div class="sidebar">
    @php $groupLevel = Session::get('group_level'); @endphp
    @if($groupLevel == 1)
        @include('admin.admin_sidebar')
    @elseif($groupLevel == 2)
        @include('teacher.teacher_sidebar')
    @elseif($groupLevel == 3)
        @include('parent.parent_sidebar')
    @endif
</div>
<style>
    .sidebar {
        width: 220px;
        background-color: #241f1f;
        position: fixed;
        height: 100%;
        top: 0;
        left: 0;
        z-index: 1000;
        overflow-y: auto;
        max-height: 100vh;
    }
</style>
