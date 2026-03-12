@php
    use App\Models\Employee;
    use App\Models\ParentModel;

    $groupLevel = Session::get('group_level');
    $userId = Session::get('user_id') ?? Session::get('use_id');
    $user = Employee::find($userId);
    $name = 'Guest';
    $role = 'User';
    $profileImage = asset('images/user-profile.png');

    if ($groupLevel == 1 || $groupLevel == 2) {
        $user = Employee::find($userId);
        if ($user) {
            $name = $user->firstname ?? $user->user_name ?? 'Employee';
            $role = $user->group_level == 1 ? 'Administrator' : 'Teacher';
            if (!empty($user->profile_picture)) {
                $profileImage = url('uploads/profile_pictures/' . $user->profile_picture);
            }
        }
    } elseif ($groupLevel == 3) {
        $user = ParentModel::find($userId);
        if ($user) {
            $name = $user->firstname ?? $user->user_name ?? 'Parent';
            $role = 'Parent';
            if (!empty($user->profile_picture)) {
                $profileImage = url('uploads/profile_pictures/' . $user->profile_picture);
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
