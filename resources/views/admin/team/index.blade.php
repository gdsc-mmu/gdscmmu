<x-layout>
    <link rel="stylesheet" href="{{ asset('css/team.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">

    <div class="admin-page-wrapper">
        <div class="admin-container">

            <!-- Sub-Navigation Bar -->
            @include('admin.partials.nav', ['active' => 'team'])

            <!-- Header Card -->
            <div class="admin-header-card" data-aos="fade-down" data-aos-duration="800">
                <div class="admin-header-stripe">
                    <div class="stripe-blue"></div>
                    <div class="stripe-red"></div>
                    <div class="stripe-yellow"></div>
                    <div class="stripe-green"></div>
                </div>

                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                    <div>
                        <div class="admin-badge">
                            <i class="fa fa-users text-success"></i>
                            <span>{{ $divisions->count() ?? 0 }} Divisions &bull; {{ $teamMembersCount ?? 0 }} Total Members</span>
                        </div>
                        <h1 class="admin-title">Manage Team &amp; Divisions</h1>
                        <p class="admin-subtitle">Review, add, and dynamically update club divisions and committee members with complete profiles.</p>
                    </div>
                    <div class="admin-header-actions mt-3 mt-md-0">
                        <button type="button" class="btn-admin-action btn-admin-primary" data-bs-toggle="modal" data-bs-target="#addDivisionModal">
                            <i class="fa fa-plus-circle"></i>
                            <span>Add New Division</span>
                        </button>
                        <a href="/team" target="_blank" class="btn-admin-action btn-admin-secondary">
                            <i class="fa fa-external-link"></i>
                            <span>View Public Showcase</span>
                        </a>
                    </div>
                </div>
            </div>

            @if(session('message'))
                <div class="alert alert-success alert-dismissible fade show rounded-4 shadow-sm mb-4" role="alert">
                    <i class="fa fa-check-circle me-2"></i> {{ session('message') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Stats Overview Strip -->
            <div class="stats-grid mb-4" data-aos="fade-up" data-aos-duration="800">
                <div class="stat-card">
                    <div class="stat-icon-wrapper icon-yellow">
                        <i class="fa fa-sitemap"></i>
                    </div>
                    <div>
                        <div class="stat-number">{{ $divisions->count() ?? 0 }}</div>
                        <p class="stat-label">Active Divisions</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon-wrapper icon-green">
                        <i class="fa fa-id-badge"></i>
                    </div>
                    <div>
                        <div class="stat-number">{{ $teamMembersCount ?? 0 }}</div>
                        <p class="stat-label">Committee Members</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon-wrapper icon-blue">
                        <i class="fa fa-plus"></i>
                    </div>
                    <div>
                        <a href="#" class="text-decoration-none fw-bold" style="color: #0e174a; font-size: 1.1rem;" data-bs-toggle="modal" data-bs-target="#addDivisionModal">
                            + New Division
                        </a>
                        <p class="stat-label">Quick Action</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon-wrapper icon-red">
                        <i class="fa fa-user-plus"></i>
                    </div>
                    <div>
                        <a href="#" class="text-decoration-none fw-bold" style="color: #0e174a; font-size: 1.1rem;" data-bs-toggle="modal" data-bs-target="#addMemberModal">
                            + New Member
                        </a>
                        <p class="stat-label">Add Member</p>
                    </div>
                </div>
            </div>

            <!-- Manage Team & Divisions Section -->
            <div class="team-mgmt-wrapper" data-aos="fade-up" data-aos-duration="900">
                <div class="modal-stripe-bar mb-3" style="border-radius: 4px; overflow: hidden;">
                    <div class="bar-blue"></div>
                    <div class="bar-red"></div>
                    <div class="bar-yellow"></div>
                    <div class="bar-green"></div>
                </div>

                <!-- Filter & Search Bar -->
                <div class="team-filter-bar mb-3">
                    <div class="team-search-box flex-grow-1">
                        <i class="fa fa-search"></i>
                        <input type="text" id="teamSearchInput" class="team-search-input" placeholder="Search division, member name, or role...">
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3" onclick="expandAllDivisions()">
                        <i class="fa fa-angle-double-down me-1"></i> Expand All
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3" onclick="collapseAllDivisions()">
                        <i class="fa fa-angle-double-up me-1"></i> Collapse All
                    </button>
                </div>

                <!-- Divisions Accordion / Cards List -->
                <div class="division-accordion" id="divisionsAccordion">
                    @forelse($divisions as $divIndex => $division)
                        <div class="division-card division-item" id="division-{{ $division->id }}" data-division-name="{{ strtolower($division->name) }}" data-division-desc="{{ strtolower($division->description ?? '') }}">
                            <div class="division-card-header" data-bs-toggle="collapse" data-bs-target="#collapseDivision{{ $division->id }}" aria-expanded="true">
                                <div class="division-title-group">
                                    <div class="division-index-badge">{{ $divIndex + 1 }}</div>
                                    <div>
                                        <h3 class="division-name">{{ $division->name }}</h3>
                                        <p class="division-desc">{{ $division->description ?? 'No tagline specified' }}</p>
                                    </div>
                                </div>
                                <div class="division-header-actions" onclick="event.stopPropagation();">
                                    <span class="badge bg-light text-dark border px-3 py-2 rounded-pill font-monospace">
                                        {{ $division->members->count() }} {{ \Illuminate\Support\Str::plural('member', $division->members->count()) }}
                                    </span>
                                    <button type="button" class="btn-pill-action btn-pill-accent" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#addMemberModal"
                                            data-division-id="{{ $division->id }}"
                                            data-division-name="{{ $division->name }}"
                                            title="Add new member to {{ $division->name }}">
                                        <i class="fa fa-user-plus"></i>
                                        <span>Add Member</span>
                                    </button>
                                    <button type="button" class="btn-pill-action"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editDivisionModal"
                                            data-division-id="{{ $division->id }}"
                                            data-division-name="{{ $division->name }}"
                                            data-division-desc="{{ $division->description }}"
                                            data-division-order="{{ $division->order }}"
                                            title="Edit division details">
                                        <i class="fa fa-pencil"></i>
                                        <span>Edit</span>
                                    </button>
                                    <form action="{{ route('admin.divisions.destroy', $division->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete division \'{{ addslashes($division->name) }}\' and all its members?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-pill-action btn-pill-delete" title="Delete division">
                                            <i class="fa fa-trash-o"></i>
                                        </button>
                                    </form>
                                    <i class="fa fa-chevron-down text-muted ms-1" style="transition: transform 0.2s;"></i>
                                </div>
                            </div>

                            <div id="collapseDivision{{ $division->id }}" class="collapse show">
                                <!-- Division Sub-Nav: Members vs Preview -->
                                <div class="division-card-subnav">
                                    <ul class="nav nav-pills division-tabs" role="tablist">
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link active" id="tab-members-{{ $division->id }}" data-bs-toggle="pill" data-bs-target="#pane-members-{{ $division->id }}" type="button" role="tab">
                                                Members ({{ $division->members->count() }})
                                            </button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="tab-preview-{{ $division->id }}" data-bs-toggle="pill" data-bs-target="#pane-preview-{{ $division->id }}" type="button" role="tab">
                                                Preview
                                            </button>
                                        </li>
                                    </ul>
                                </div>

                                <div class="tab-content">
                                    <!-- Tab 1: Members Roster -->
                                    <div class="tab-pane fade show active" id="pane-members-{{ $division->id }}" role="tabpanel">
                                        <div class="division-body">
                                            @if($division->members->isEmpty())
                                                <div class="empty-division-state">
                                                    <i class="fa fa-users text-muted mb-2" style="font-size: 2rem;"></i>
                                                    <p class="mb-2 fw-semibold">No members currently in this division</p>
                                                    <button type="button" class="btn btn-sm btn-primary rounded-pill px-3"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#addMemberModal"
                                                            data-division-id="{{ $division->id }}"
                                                            data-division-name="{{ $division->name }}">
                                                        <i class="fa fa-plus-circle me-1"></i> Add First Member
                                                    </button>
                                                </div>
                                            @else
                                                <div class="members-grid">
                                                    @foreach($division->members as $member)
                                                        <div class="member-item-card member-item" 
                                                             data-member-name="{{ strtolower($member->name) }}" 
                                                             data-member-role="{{ strtolower($member->role) }}">
                                                            <div class="member-item-top">
                                                                <div class="member-avatar-wrapper">
                                                                    @if($member->avatar && file_exists(public_path($member->avatar)))
                                                                        <img src="{{ asset($member->avatar) }}" alt="{{ $member->name }}">
                                                                    @elseif($member->avatar && str_starts_with($member->avatar, 'http'))
                                                                        <img src="{{ $member->avatar }}" alt="{{ $member->name }}">
                                                                    @else
                                                                        <span>{{ strtoupper(substr($member->name, 0, 2)) }}</span>
                                                                    @endif
                                                                </div>
                                                                <div class="member-info">
                                                                    <h4 class="member-name" title="{{ $member->name }}">{{ $member->name }}</h4>
                                                                    @php
                                                                        $roleLower = strtolower($member->role);
                                                                        $roleClass = '';
                                                                        if (str_contains($roleLower, 'co-lead')) {
                                                                            $roleClass = 'role-co-lead';
                                                                        } elseif (str_contains($roleLower, 'lead')) {
                                                                            $roleClass = 'role-lead';
                                                                        }
                                                                    @endphp
                                                                    <span class="badge-member-role {{ $roleClass }}" title="{{ $member->role }}">
                                                                        {{ $member->role }}
                                                                    </span>
                                                                </div>
                                                            </div>

                                                            <div class="member-actions-row">
                                                                <button type="button" class="btn-icon-action btn-icon-edit"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target="#editMemberModal"
                                                                        data-member-id="{{ $member->id }}"
                                                                        data-division-id="{{ $member->team_division_id }}"
                                                                        data-member-name="{{ $member->name }}"
                                                                        data-member-role="{{ $member->role }}"
                                                                        data-member-avatar="{{ $member->avatar }}"
                                                                        data-member-order="{{ $member->order }}"
                                                                        title="Edit member">
                                                                    <i class="fa fa-pencil"></i>
                                                                </button>
                                                                <form action="{{ route('admin.members.destroy', $member->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Remove member \'{{ addslashes($member->name) }}\'?');">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn-icon-action btn-icon-del" title="Delete member">
                                                                        <i class="fa fa-trash-o"></i>
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Tab 2: Preview -->
                                    <div class="tab-pane fade" id="pane-preview-{{ $division->id }}" role="tabpanel">
                                        <div class="division-body">
                                            <div class="preview-mode-bar mb-3 rounded-3">
                                                <span><i class="fa fa-eye text-primary me-1"></i> Preview</span>
                                                <a href="/team" target="_blank" class="text-decoration-none text-muted small">
                                                    <i class="fa fa-external-link me-1"></i> Open Public Showcase
                                                </a>
                                            </div>
                                            <div class="teams team-{{ ($divIndex % 8) + 1 }} preview-teams-container">
                                                <h2 class="mb-2">{{ $division->name }}</h2>
                                                @if($division->description)
                                                    <p>{{ $division->description }}</p>
                                                @endif
                                                <div id="pic">
                                                    <div class="row mb-3 justify-content-center">
                                                        @forelse($division->members as $member)
                                                            <div class="col-lg-4 col-md-6 col-sm-6 mb-4 d-flex justify-content-center">
                                                                <div class="profile" @if($member->avatar && !str_starts_with($member->avatar, 'http') && !str_contains($member->avatar, '/')) id="{{ $member->avatar }}" @endif>
                                                                    @php
                                                                        $bgStyle = '';
                                                                        if ($member->avatar && (str_starts_with($member->avatar, 'http') || str_contains($member->avatar, '/'))) {
                                                                            $bgStyle = 'style="background-image: url(' . asset($member->avatar) . ') !important;"';
                                                                        }
                                                                    @endphp
                                                                    <span class="profile-span" {!! $bgStyle !!}></span>
                                                                    <span class="profile-span" {!! $bgStyle !!}></span>
                                                                    <span class="profile-span" {!! $bgStyle !!}></span>
                                                                    <span class="profile-span" {!! $bgStyle !!}></span>
                                                                    <div class="profile-text">
                                                                        <h2>Hi, I'm {{ $member->name }}</h2>
                                                                        <p>{{ $member->role }}</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @empty
                                                            <div class="text-center py-4 text-muted">
                                                                <p class="mb-0">No members in this division to preview.</p>
                                                            </div>
                                                        @endforelse
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5">
                            <i class="fa fa-id-badge text-muted" style="font-size: 3rem;"></i>
                            <h4 class="mt-3 text-muted">No Team Divisions Configured</h4>
                            <p class="text-muted">Start creating your club division structure by clicking the button below.</p>
                            <button type="button" class="btn btn-primary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#addDivisionModal">
                                <i class="fa fa-plus-circle me-1"></i> Create First Division
                            </button>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>

    <!-- Modal: Add Division -->
    <div class="modal fade modal-admin-custom" id="addDivisionModal" tabindex="-1" aria-labelledby="addDivisionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-stripe-bar">
                    <div class="bar-blue"></div>
                    <div class="bar-red"></div>
                    <div class="bar-yellow"></div>
                    <div class="bar-green"></div>
                </div>
                <form action="{{ route('admin.divisions.store') }}" method="POST">
                    @csrf
                    <div class="modal-admin-header">
                        <h5 class="modal-admin-title" id="addDivisionModalLabel">
                            <i class="fa fa-folder-open-o text-primary me-2"></i> Add Team Division
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-admin-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold text-dark">Division Name <span class="text-danger">*</span></label>
                            <input type="text" id="addDivInputName" name="name" class="form-control" placeholder="e.g. Mobile &amp; Cloud Division, AI/ML Team" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold text-dark">Tagline / Motto / Description</label>
                            <input type="text" id="addDivInputDesc" name="description" class="form-control" placeholder="e.g. We code, build, and deploy apps for MMU students">
                            <small class="text-muted">Short tagline displayed under the division heading on the public showcase.</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold text-dark">Display Order</label>
                            <input type="number" name="order" class="form-control" value="{{ ($divisions->max('order') ?? 0) + 1 }}" min="1">
                        </div>
                    </div>

                    <div class="modal-admin-footer">
                        <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary rounded-pill px-4">
                            <i class="fa fa-check me-1"></i> Create Division
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal: Edit Division -->
    <div class="modal fade modal-admin-custom" id="editDivisionModal" tabindex="-1" aria-labelledby="editDivisionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-stripe-bar">
                    <div class="bar-blue"></div>
                    <div class="bar-red"></div>
                    <div class="bar-yellow"></div>
                    <div class="bar-green"></div>
                </div>
                <form id="editDivisionForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-admin-header">
                        <h5 class="modal-admin-title" id="editDivisionModalLabel">
                            <i class="fa fa-pencil text-primary me-2"></i> Edit Division Details
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-admin-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold text-dark">Division Name <span class="text-danger">*</span></label>
                            <input type="text" id="editDivisionName" name="name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold text-dark">Tagline / Motto / Description</label>
                            <input type="text" id="editDivisionDesc" name="description" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold text-dark">Display Order</label>
                            <input type="number" id="editDivisionOrder" name="order" class="form-control" min="1">
                        </div>
                    </div>

                    <div class="modal-admin-footer">
                        <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary rounded-pill px-4">
                            <i class="fa fa-check me-1"></i> Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal: Add Team Member -->
    <div class="modal fade modal-admin-custom" id="addMemberModal" tabindex="-1" aria-labelledby="addMemberModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-stripe-bar">
                    <div class="bar-blue"></div>
                    <div class="bar-red"></div>
                    <div class="bar-yellow"></div>
                    <div class="bar-green"></div>
                </div>
                <form action="{{ route('admin.members.store') }}" method="POST" enctype="multipart/form-data" id="addMemberForm">
                    @csrf
                    <div class="modal-admin-header">
                        <h5 class="modal-admin-title" id="addMemberModalLabel">
                            <i class="fa fa-user-plus text-success me-2"></i> Add New Team Member
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-admin-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold text-dark">Team Division <span class="text-danger">*</span></label>
                                <select id="addMemberDivisionSelect" name="team_division_id" class="form-select" required>
                                    @foreach($divisions as $div)
                                        <option value="{{ $div->id }}">{{ $div->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold text-dark">Member Full Name <span class="text-danger">*</span></label>
                                <input type="text" id="addMemberNameInput" name="name" class="form-control" placeholder="e.g. Muhammad Raziq, Issye" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 mb-3">
                                <label class="form-label fw-bold text-dark">Role / Position Title <span class="text-danger">*</span></label>
                                <input type="text" id="addMemberRoleInput" name="role" class="form-control" placeholder="e.g. Department Lead, Technical Co-Lead, Core Member" required>
                                <div class="mt-2 d-flex gap-1 flex-wrap">
                                    <small class="text-muted me-1">Quick presets:</small>
                                    <button type="button" class="badge bg-light text-dark border role-chip" onclick="setAddRole('Lead')">Lead</button>
                                    <button type="button" class="badge bg-light text-dark border role-chip" onclick="setAddRole('Co-Lead')">Co-Lead</button>
                                    <button type="button" class="badge bg-light text-dark border role-chip" onclick="setAddRole('Core Member')">Core Member</button>
                                    <button type="button" class="badge bg-light text-dark border role-chip" onclick="setAddRole('Treasurer')">Treasurer</button>
                                    <button type="button" class="badge bg-light text-dark border role-chip" onclick="setAddRole('Secretary')">Secretary</button>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold text-dark">Display Order in Division</label>
                                <input type="number" name="order" class="form-control" value="1" min="1">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold text-dark">Upload Profile Photo</label>
                                <input type="file" id="addMemberFileInput" name="avatar_file" class="form-control" accept="image/*">
                                <small class="text-muted">JPG, PNG, WebP up to 3MB.</small>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold text-dark">Or CSS Avatar Key / URL</label>
                                <input type="text" id="addMemberAvatarInput" name="avatar" class="form-control" placeholder="e.g. raziq, issye2">
                                <small class="text-muted">Preset key or image link.</small>
                            </div>
                        </div>
                    </div>

                    <div class="modal-admin-footer">
                        <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success rounded-pill px-4 text-white">
                            <i class="fa fa-user-plus me-1"></i> Add Member
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal: Edit Team Member -->
    <div class="modal fade modal-admin-custom" id="editMemberModal" tabindex="-1" aria-labelledby="editMemberModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-stripe-bar">
                    <div class="bar-blue"></div>
                    <div class="bar-red"></div>
                    <div class="bar-yellow"></div>
                    <div class="bar-green"></div>
                </div>
                <form id="editMemberForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-admin-header">
                        <h5 class="modal-admin-title" id="editMemberModalLabel">
                            <i class="fa fa-pencil text-primary me-2"></i> Edit Member Profile
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-admin-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold text-dark">Team Division <span class="text-danger">*</span></label>
                                <select id="editMemberDivisionSelect" name="team_division_id" class="form-select" required>
                                    @foreach($divisions as $div)
                                        <option value="{{ $div->id }}">{{ $div->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold text-dark">Member Full Name <span class="text-danger">*</span></label>
                                <input type="text" id="editMemberName" name="name" class="form-control" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 mb-3">
                                <label class="form-label fw-bold text-dark">Role / Position Title <span class="text-danger">*</span></label>
                                <input type="text" id="editMemberRole" name="role" class="form-control" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold text-dark">Display Order in Division</label>
                                <input type="number" id="editMemberOrder" name="order" class="form-control" min="1">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold text-dark">Replace Profile Photo</label>
                                <input type="file" id="editMemberFileInput" name="avatar_file" class="form-control" accept="image/*">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold text-dark">Or Avatar Key / URL</label>
                                <input type="text" id="editMemberAvatar" name="avatar" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="modal-admin-footer">
                        <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary rounded-pill px-4">
                            <i class="fa fa-check me-1"></i> Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Client Scripts for Manage Team -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Edit Division Modal Handler
            const editDivisionModal = document.getElementById('editDivisionModal');
            if (editDivisionModal) {
                editDivisionModal.addEventListener('show.bs.modal', function (event) {
                    const button = event.relatedTarget;
                    const id = button.getAttribute('data-division-id');
                    const name = button.getAttribute('data-division-name') || '';
                    const desc = button.getAttribute('data-division-desc') || '';
                    const order = button.getAttribute('data-division-order') || '1';

                    const form = document.getElementById('editDivisionForm');
                    form.action = '/admin/divisions/' + id;
                    document.getElementById('editDivisionName').value = name;
                    document.getElementById('editDivisionDesc').value = desc;
                    document.getElementById('editDivisionOrder').value = order;
                });
            }

            // Add Member Modal Handler - sets target division
            const addMemberModal = document.getElementById('addMemberModal');
            if (addMemberModal) {
                addMemberModal.addEventListener('show.bs.modal', function (event) {
                    const button = event.relatedTarget;
                    if (button && button.hasAttribute('data-division-id')) {
                        const divisionId = button.getAttribute('data-division-id');
                        const divisionName = button.getAttribute('data-division-name');
                        const select = document.getElementById('addMemberDivisionSelect');
                        if (select) {
                            select.value = divisionId;
                        }
                        const label = document.getElementById('addMemberModalLabel');
                        if (label && divisionName) {
                            label.innerHTML = '<i class="fa fa-user-plus text-success me-2"></i> Add Member to ' + divisionName;
                        }
                    }
                });
            }

            // Edit Member Modal Handler
            const editMemberModal = document.getElementById('editMemberModal');
            if (editMemberModal) {
                editMemberModal.addEventListener('show.bs.modal', function (event) {
                    const button = event.relatedTarget;
                    const memberId = button.getAttribute('data-member-id');
                    const divId = button.getAttribute('data-division-id');
                    const name = button.getAttribute('data-member-name') || '';
                    const role = button.getAttribute('data-member-role') || '';
                    const avatar = button.getAttribute('data-member-avatar') || '';
                    const order = button.getAttribute('data-member-order') || '1';

                    const form = document.getElementById('editMemberForm');
                    form.action = '/admin/members/' + memberId;
                    document.getElementById('editMemberDivisionSelect').value = divId;
                    document.getElementById('editMemberName').value = name;
                    document.getElementById('editMemberRole').value = role;
                    document.getElementById('editMemberAvatar').value = avatar;
                    document.getElementById('editMemberOrder').value = order;
                });
            }

            // Live Search Filter for Divisions & Members
            const searchInput = document.getElementById('teamSearchInput');
            if (searchInput) {
                searchInput.addEventListener('input', function () {
                    const query = this.value.toLowerCase().trim();
                    const divisionCards = document.querySelectorAll('.division-item');

                    divisionCards.forEach(card => {
                        const divName = card.getAttribute('data-division-name') || '';
                        const divDesc = card.getAttribute('data-division-desc') || '';
                        const memberCards = card.querySelectorAll('.member-item');
                        let matchedMemberCount = 0;

                        memberCards.forEach(m => {
                            const mName = m.getAttribute('data-member-name') || '';
                            const mRole = m.getAttribute('data-member-role') || '';

                            if (query === '' || mName.includes(query) || mRole.includes(query)) {
                                m.style.display = '';
                                matchedMemberCount++;
                            } else {
                                m.style.display = 'none';
                            }
                        });

                        const divisionMatches = divName.includes(query) || divDesc.includes(query);
                        if (query === '' || divisionMatches || matchedMemberCount > 0) {
                            card.style.display = '';
                            if (query !== '' && matchedMemberCount > 0) {
                                const collapseEl = card.querySelector('.collapse');
                                if (collapseEl && !collapseEl.classList.contains('show')) {
                                    new bootstrap.Collapse(collapseEl, { toggle: true });
                                }
                            }
                        } else {
                            card.style.display = 'none';
                        }
                    });
                });
            }

            // Auto open modal from query params if present
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('action') === 'create-division') {
                const addDivModal = new bootstrap.Modal(document.getElementById('addDivisionModal'));
                addDivModal.show();
            } else if (urlParams.get('action') === 'create-member') {
                const addMemModal = new bootstrap.Modal(document.getElementById('addMemberModal'));
                addMemModal.show();
            }
        });

        // Role Preset Buttons
        function setAddRole(role) {
            const input = document.getElementById('addMemberRoleInput');
            if (input) {
                input.value = role;
                input.focus();
            }
        }

        function expandAllDivisions() {
            document.querySelectorAll('#divisionsAccordion .collapse').forEach(el => {
                if (!el.classList.contains('show')) {
                    new bootstrap.Collapse(el, { toggle: true });
                }
            });
        }

        function collapseAllDivisions() {
            document.querySelectorAll('#divisionsAccordion .collapse').forEach(el => {
                if (el.classList.contains('show')) {
                    new bootstrap.Collapse(el, { toggle: true });
                }
            });
        }
    </script>
</x-layout>

