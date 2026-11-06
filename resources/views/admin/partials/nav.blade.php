@props(['active' => 'dashboard'])

<div class="admin-subnav-bar" data-aos="fade-down" data-aos-duration="600">
    <div class="admin-subnav-links">
        <a href="{{ route('admin.dashboard') }}" class="admin-subnav-link {{ $active === 'dashboard' ? 'active' : '' }}">
            <i class="fa fa-tachometer"></i>
            <span>Dashboard</span>
        </a>
        <a href="{{ route('admin.events.index') }}" class="admin-subnav-link {{ $active === 'events' ? 'active' : '' }}">
            <i class="fa fa-calendar"></i>
            <span>Manage Events</span>
        </a>
        <a href="{{ route('admin.news.index') }}" class="admin-subnav-link {{ $active === 'news' ? 'active' : '' }}">
            <i class="fa fa-newspaper-o"></i>
            <span>Manage News</span>
        </a>
        <a href="{{ route('admin.team.index') }}" class="admin-subnav-link {{ $active === 'team' ? 'active' : '' }}">
            <i class="fa fa-users"></i>
            <span>Manage Team</span>
        </a>
        <a href="{{ route('admin.club_members.index') }}" class="admin-subnav-link {{ $active === 'members' ? 'active' : '' }}">
            <i class="fa fa-id-card-o"></i>
            <span>Manage Members</span>
        </a>
    </div>

    <div class="admin-subnav-actions">
        <a href="/" target="_blank" class="admin-back-btn">
            <i class="fa fa-external-link"></i>
            <span>Live Site</span>
        </a>
    </div>
</div>

