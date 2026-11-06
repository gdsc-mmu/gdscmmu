<x-layout>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">

    <div class="admin-page-wrapper">
        <div class="admin-container">

            <!-- Sub-Navigation Bar -->
            @include('admin.partials.nav', ['active' => 'members'])

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
                            <i class="fa fa-id-card-o text-primary"></i>
                            <span>{{ number_format($totalMembers) }} Registered Club Members</span>
                        </div>
                        <h1 class="admin-title">Manage Members</h1>
                        <p class="admin-subtitle">Import Excel rosters with auto-sync by Name, Student ID, and Term, and perform bulk management.</p>
                    </div>
                    <div class="admin-header-actions mt-3 mt-md-0">
                        <button type="button" class="btn-admin-action btn-admin-primary" data-bs-toggle="modal" data-bs-target="#importExcelModal">
                            <i class="fa fa-file-excel-o"></i>
                            <span>Import Excel Sheet</span>
                        </button>
                        <button type="button" class="btn-admin-action btn-admin-secondary" data-bs-toggle="modal" data-bs-target="#manualAddMemberModal">
                            <i class="fa fa-user-plus"></i>
                            <span>Add Member</span>
                        </button>
                        <button type="button" class="btn-admin-action" data-bs-toggle="modal" data-bs-target="#manageTermModal" style="background: #ffffff; color: #d97706; border: 1px solid #fde68a;">
                            <i class="fa fa-sliders text-warning"></i>
                            <span>Manage Terms (Archive / Delete)</span>
                        </button>
                        <button type="button" class="btn-admin-action btn-admin-secondary" data-bs-toggle="modal" data-bs-target="#exportRangeModal" title="Export members by custom range to Excel">
                            <i class="fa fa-download"></i>
                            <span>Export (.xlsx)</span>
                        </button>
                    </div>
                </div>
            </div>

            @if(session('message'))
                <div class="alert alert-success alert-dismissible fade show rounded-4 shadow-sm mb-4" role="alert">
                    <i class="fa fa-check-circle me-2"></i> {{ session('message') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show rounded-4 shadow-sm mb-4" role="alert">
                    <i class="fa fa-exclamation-triangle me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Stats Overview Strip -->
            <div class="stats-grid mb-4" data-aos="fade-up" data-aos-duration="800">
                <div class="stat-card">
                    <div class="stat-icon-wrapper icon-blue">
                        <i class="fa fa-users"></i>
                    </div>
                    <div>
                        <div class="stat-number">{{ number_format($totalMembers) }}</div>
                        <p class="stat-label">Total Club Members</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon-wrapper icon-green">
                        <i class="fa fa-check-circle"></i>
                    </div>
                    <div>
                        <div class="stat-number">{{ number_format($activeMembers) }}</div>
                        <p class="stat-label">Active Memberships</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon-wrapper icon-yellow">
                        <i class="fa fa-archive"></i>
                    </div>
                    <div>
                        <div class="stat-number">{{ number_format($inactiveMembers) }}</div>
                        <p class="stat-label">Inactive / Archived</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon-wrapper icon-red">
                        <i class="fa fa-graduation-cap"></i>
                    </div>
                    <div>
                        <div class="stat-number">{{ count($distinctTerms) }}</div>
                        <p class="stat-label">Terms Recorded</p>
                    </div>
                </div>
            </div>

            <!-- Filter & Search Card -->
            <div class="admin-section-card mb-4" data-aos="fade-up" data-aos-duration="850">
                <form action="{{ route('admin.club_members.index') }}" method="GET" class="row g-3 align-items-end">
                    <div class="col-md-5">
                        <label class="form-label fw-bold text-dark small mb-1">Search by Name, Student ID, or Email</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0"><i class="fa fa-search text-muted"></i></span>
                            <input type="text" name="search" class="form-control border-start-0" placeholder="e.g. 1211103984, Muhammad Raziq..." value="{{ request('search') }}">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-bold text-dark small mb-1">Filter by Term</label>
                        <select name="term" class="form-select">
                            <option value="all">All Terms ({{ count($distinctTerms) }})</option>
                            @foreach($termsWithCounts as $tc)
                                <option value="{{ $tc->term }}" {{ request('term') === (string)$tc->term ? 'selected' : '' }}>
                                    Term {{ $tc->term }} ({{ number_format($tc->count) }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label fw-bold text-dark small mb-1">Status</label>
                        <select name="status" class="form-select">
                            <option value="all">All Statuses</option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive / Archived</option>
                            <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>Expired</option>
                        </select>
                    </div>

                    <div class="col-md-2 d-flex gap-2">
                        <button type="submit" class="btn btn-primary rounded-pill px-3 flex-grow-1">
                            <i class="fa fa-filter me-1"></i> Filter
                        </button>
                        @if(request()->hasAny(['search', 'term', 'status']))
                            <a href="{{ route('admin.club_members.index') }}" class="btn btn-outline-secondary rounded-pill px-3" title="Clear Filters">
                                <i class="fa fa-times"></i>
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Members Data Table Card -->
            <div class="admin-section-card" data-aos="fade-up" data-aos-duration="900">
                <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
                    <div>
                        <h4 class="section-title mb-0">
                            <i class="fa fa-address-book-o text-primary"></i>
                            Membership Roster
                        </h4>
                        <small class="text-muted">Showing {{ $members->firstItem() ?? 0 }} to {{ $members->lastItem() ?? 0 }} of {{ number_format($members->total()) }} members</small>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <button type="button" class="btn btn-sm btn-outline-warning text-dark fw-semibold rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#manageTermModal">
                            <i class="fa fa-sliders me-1 text-primary"></i> Archive / Delete Term
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-success rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#importExcelModal">
                            <i class="fa fa-upload me-1"></i> Sync Excel Sheet
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#exportRangeModal" title="Export with range filters">
                            <i class="fa fa-download me-1"></i> Export Range
                        </button>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table admin-table align-middle">
                        <thead>
                            <tr>
                                <th style="width: 50px;">#</th>
                                <th>Student ID</th>
                                <th>Member Full Name</th>
                                <th>Term</th>
                                <th>Email</th>
                                <th>Status</th>
                                <th>Last Updated</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($members as $index => $member)
                                <tr>
                                    <td class="text-muted small">
                                        {{ $members->firstItem() + $index }}
                                    </td>
                                    <td>
                                        @if($member->student_id)
                                            <span class="badge bg-light text-dark border px-2 py-1 font-monospace fw-bold">
                                                {{ $member->student_id }}
                                            </span>
                                        @else
                                            <span class="text-muted fst-italic small">N/A</span>
                                        @endif
                                    </td>
                                    <td class="fw-semibold text-dark">
                                        {{ $member->name }}
                                    </td>
                                    <td>
                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1 rounded-pill">
                                            Term {{ $member->term }}
                                        </span>
                                    </td>
                                    <td class="small text-muted">
                                        {{ $member->email ?? '—' }}
                                    </td>
                                    <td>
                                        @if($member->status === 'active')
                                            <span class="badge-status badge-event" style="background: #ecfdf5; color: #047857; border-color: #a7f3d0;">
                                                <i class="fa fa-check-circle me-1"></i> Active
                                            </span>
                                        @elseif($member->status === 'inactive')
                                            <span class="badge-status" style="background: #fef3c7; color: #92400e; border-color: #fde68a;">
                                                <i class="fa fa-archive me-1"></i> Inactive (Archived)
                                            </span>
                                        @else
                                            <span class="badge-status" style="background: #fef2f2; color: #b91c1c; border-color: #fecaca;">
                                                <i class="fa fa-clock-o me-1"></i> Expired
                                            </span>
                                        @endif
                                    </td>
                                    <td class="small text-muted">
                                        {{ $member->updated_at ? $member->updated_at->format('M j, Y') : '—' }}
                                    </td>
                                    <td class="text-end">
                                        <button type="button" 
                                                class="btn btn-sm btn-outline-secondary rounded-circle me-1 btn-edit-member"
                                                data-id="{{ $member->id }}"
                                                data-name="{{ $member->name }}"
                                                data-student-id="{{ $member->student_id }}"
                                                data-term="{{ $member->term }}"
                                                data-email="{{ $member->email }}"
                                                data-status="{{ $member->status }}"
                                                title="Edit Member">
                                            <i class="fa fa-pencil"></i>
                                        </button>
                                        <button type="button" 
                                                class="btn btn-sm btn-outline-danger rounded-circle btn-single-delete" 
                                                data-id="{{ $member->id }}" 
                                                data-name="{{ $member->name }}" 
                                                title="Delete Member">
                                            <i class="fa fa-trash-o"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <div class="py-4">
                                            <i class="fa fa-users text-muted mb-3" style="font-size: 2.8rem;"></i>
                                            <h5 class="text-dark fw-bold">No Club Members Found</h5>
                                            <p class="text-muted small">Import an Excel spreadsheet or add members manually to populate the roster.</p>
                                            <button type="button" class="btn btn-primary rounded-pill px-4 mt-2" data-bs-toggle="modal" data-bs-target="#importExcelModal">
                                                <i class="fa fa-file-excel-o me-1"></i> Import Excel Spreadsheet
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Hidden form for single deletes -->
                <form id="singleDeleteForm" method="POST" style="display: none;">
                    @csrf
                    @method('DELETE')
                </form>

                <!-- Pagination -->
                @if($members->hasPages())
                    <div class="admin-pagination-wrapper">
                        {{ $members->onEachSide(1)->links('pagination::bootstrap-5') }}
                    </div>
                @endif
            </div>

        </div>
    </div>

    <!-- Modal: Manage Term Cohort (Archive or Delete) -->
    <div class="modal fade modal-admin-custom" id="manageTermModal" tabindex="-1" aria-labelledby="manageTermModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-stripe-bar">
                    <div class="bar-yellow"></div>
                    <div class="bar-red"></div>
                    <div class="bar-blue"></div>
                    <div class="bar-green"></div>
                </div>

                <div class="modal-admin-header">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fa fa-sliders text-warning fs-5"></i>
                        <div>
                            <h5 class="modal-admin-title mb-0" id="manageTermModalLabel">Manage Term Cohort</h5>
                            <span class="small text-muted">Archive (set inactive &amp; hide from tracker search) or permanently delete a term</span>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-admin-body">
                    <!-- Term Selector -->
                    <div class="mb-4">
                        <label class="form-label fw-bold text-dark">Step 1: Select Term Cohort <span class="text-danger">*</span></label>
                        <select id="modalTermSelector" class="form-select form-select-lg">
                            <option value="" disabled selected>— Choose Term Cohort —</option>
                            @foreach($termsWithCounts as $tc)
                                <option value="{{ $tc->term }}" 
                                        data-count="{{ $tc->count }}" 
                                        data-active="{{ $tc->active_count }}" 
                                        data-inactive="{{ $tc->inactive_count }}">
                                    Term {{ $tc->term }} &bull; {{ number_format($tc->count) }} total members ({{ $tc->active_count }} active, {{ $tc->inactive_count }} inactive)
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Selected Term Status Callout -->
                    <div id="termSummaryCallout" class="alert alert-light border rounded-3 mb-4 p-3" style="display: none;">
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                            <div>
                                <span class="small text-muted d-block">Selected Term</span>
                                <h5 class="fw-bold text-dark mb-0" id="displayTermName">Term —</h5>
                            </div>
                            <div class="d-flex gap-3">
                                <div>
                                    <span class="small text-muted d-block">Total Members</span>
                                    <span class="badge bg-primary fs-6 px-3 py-1" id="displayTermCount">0</span>
                                </div>
                                <div>
                                    <span class="small text-muted d-block">Active / Inactive</span>
                                    <span class="small fw-semibold text-dark"><span id="displayTermActive" class="text-success">0</span> active / <span id="displayTermInactive" class="text-secondary">0</span> inactive</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 2: Two Choices Navigation -->
                    <label class="form-label fw-bold text-dark mb-2">Step 2: Choose Action</label>
                    
                    <ul class="nav nav-pills nav-fill mb-3 gap-2" id="termActionPills" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active rounded-3 py-2 fw-semibold" id="pill-archive-tab" data-bs-toggle="pill" data-bs-target="#pill-archive" type="button" role="tab">
                                <i class="fa fa-archive me-1 text-warning"></i> Choice 1: Archive Term (Set Inactive)
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link rounded-3 py-2 fw-semibold text-danger" id="pill-delete-tab" data-bs-toggle="pill" data-bs-target="#pill-delete" type="button" role="tab">
                                <i class="fa fa-trash-o me-1"></i> Choice 2: Delete Term (Permanent)
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content border rounded-3 p-3 bg-light">
                        <!-- CHOICE 1: ARCHIVE / SET INACTIVE -->
                        <div class="tab-pane fade show active" id="pill-archive" role="tabpanel">
                            <div class="d-flex align-items-start gap-2 mb-3">
                                <i class="fa fa-info-circle text-warning fs-4 mt-1"></i>
                                <div>
                                    <h6 class="fw-bold text-dark mb-1">Archive Members (Safe &amp; Reversible)</h6>
                                    <p class="small text-muted mb-2">
                                        Archiving sets all members belonging to this term to <strong>Inactive</strong> status.
                                    </p>
                                    <ul class="small text-secondary ps-3 mb-0">
                                        <li><strong>Member Tracker Search:</strong> Archived members will <span class="text-danger fw-bold">NOT be displayed</span> in the public membership search on the homepage.</li>
                                        <li><strong>Club Records:</strong> All member data, student IDs, and history remain safely preserved in the database roster.</li>
                                        <li>You can reactivate an archived term at any time with a single click.</li>
                                    </ul>
                                </div>
                            </div>

                            <div class="d-flex gap-2 flex-wrap justify-content-end pt-2 border-top">
                                <!-- Form: Activate / Reactivate -->
                                <form action="{{ route('admin.club_members.activate_term') }}" method="POST" id="formActivateTerm" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="term" class="hidden-selected-term" value="">
                                    <button type="submit" class="btn btn-outline-success rounded-pill px-4" id="btnSubmitActivateTerm" disabled>
                                        <i class="fa fa-check-circle me-1"></i> Set Term to Active
                                    </button>
                                </form>

                                <!-- Form: Archive / Set Inactive -->
                                <form action="{{ route('admin.club_members.archive_term') }}" method="POST" id="formArchiveTerm" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="term" class="hidden-selected-term" value="">
                                    <button type="submit" class="btn btn-warning rounded-pill px-4 text-dark fw-bold" id="btnSubmitArchiveTerm" disabled>
                                        <i class="fa fa-archive me-1"></i> Archive Term (Set Inactive)
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- CHOICE 2: DELETE PERMANENTLY -->
                        <div class="tab-pane fade" id="pill-delete" role="tabpanel">
                            <div class="alert alert-danger border-0 rounded-3 mb-3 p-3">
                                <div class="d-flex align-items-start gap-2">
                                    <i class="fa fa-exclamation-triangle fs-4 text-danger mt-1"></i>
                                    <div>
                                        <strong class="text-dark">Permanent Deletion Warning:</strong>
                                        <p class="mb-0 small text-danger">
                                            This action will permanently purge <strong>ALL</strong> club members in this term from the database. This action is <strong>irreversible</strong>.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <form action="{{ route('admin.club_members.delete_by_term') }}" method="POST" id="formDeleteTerm">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="term" class="hidden-selected-term" value="">

                                <div class="form-check p-3 bg-white border border-danger-subtle rounded-3 mb-3">
                                    <input class="form-check-input ms-0 me-2" type="checkbox" id="confirmTermDeleteCheckbox" required>
                                    <label class="form-check-label text-dark small fw-semibold" for="confirmTermDeleteCheckbox">
                                        I confirm that I want to permanently delete all members from this term.
                                    </label>
                                </div>

                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-danger rounded-pill px-4 fw-bold" id="btnSubmitDeleteTerm" disabled>
                                        <i class="fa fa-trash me-1"></i> Permanently Delete Term
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="modal-admin-footer">
                    <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal: Export Members by Range -->
    <div class="modal fade modal-admin-custom" id="exportRangeModal" tabindex="-1" aria-labelledby="exportRangeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-stripe-bar">
                    <div class="bar-blue"></div>
                    <div class="bar-green"></div>
                    <div class="bar-yellow"></div>
                    <div class="bar-red"></div>
                </div>

                <form action="{{ route('admin.club_members.export') }}" method="GET" id="exportRangeForm">
                    <div class="modal-admin-header">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fa fa-file-excel-o text-success fs-5"></i>
                            <div>
                                <h5 class="modal-admin-title mb-0" id="exportRangeModalLabel">Export Member Data to Excel (.xlsx)</h5>
                                <span class="small text-muted">Customize the range criteria to download exactly the cohort you need</span>
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-admin-body">
                        <!-- Instructions Callout -->
                        <div class="alert alert-light border rounded-3 mb-4 p-3">
                            <div class="d-flex align-items-start gap-2">
                                <i class="fa fa-sliders text-primary fs-5 mt-1"></i>
                                <div>
                                    <strong class="text-dark">Flexible Export Range Filters:</strong>
                                    <p class="mb-0 small text-muted">
                                        Choose a specific term cohort, membership status, registration date range, or volume limit. Leave fields at default ("All") to download the complete membership roster.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <!-- 1. Term Range -->
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-dark">
                                    <i class="fa fa-graduation-cap text-primary me-1"></i> Term Cohort Range
                                </label>
                                <select name="term" class="form-select form-select-lg">
                                    <option value="all" selected>All Terms (All Cohorts)</option>
                                    @foreach($termsWithCounts as $tc)
                                        <option value="{{ $tc->term }}">
                                            Term {{ $tc->term }} &bull; {{ number_format($tc->count) }} members
                                        </option>
                                    @endforeach
                                </select>
                                <small class="text-muted">Export members belonging to a specific registration term.</small>
                            </div>

                            <!-- 2. Status Range -->
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-dark">
                                    <i class="fa fa-check-circle text-success me-1"></i> Membership Status Range
                                </label>
                                <select name="status" class="form-select form-select-lg">
                                    <option value="all" selected>All Statuses (Active, Inactive, Expired)</option>
                                    <option value="active">Active Members Only</option>
                                    <option value="inactive">Inactive / Archived Only</option>
                                    <option value="expired">Expired Members Only</option>
                                </select>
                                <small class="text-muted">Filter by member activity status.</small>
                            </div>
                        </div>

                        <!-- 3. Date Added Range -->
                        <div class="card p-3 bg-light border-0 rounded-3 mb-3">
                            <label class="form-label fw-bold text-dark mb-2">
                                <i class="fa fa-calendar text-info me-1"></i> Registration Date Range (Optional)
                            </label>
                            <div class="row g-2 align-items-center">
                                <div class="col-md-6">
                                    <label class="small text-muted mb-1">From Date (Start):</label>
                                    <input type="date" name="date_from" class="form-control" placeholder="YYYY-MM-DD">
                                </div>
                                <div class="col-md-6">
                                    <label class="small text-muted mb-1">To Date (End):</label>
                                    <input type="date" name="date_to" class="form-control" placeholder="YYYY-MM-DD">
                                </div>
                            </div>
                            <small class="text-muted mt-2 d-block">Filter members by when their records were added or imported into the database.</small>
                        </div>

                        <!-- 4. Limit / Row Count Range & Search Keyword -->
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-dark">
                                    <i class="fa fa-list-ol text-warning me-1"></i> Export Volume / Limit Range
                                </label>
                                <select name="limit" class="form-select">
                                    <option value="0" selected>All Matching Records (No Limit)</option>
                                    <option value="50">First 50 records</option>
                                    <option value="100">First 100 records</option>
                                    <option value="250">First 250 records</option>
                                    <option value="500">First 500 records</option>
                                    <option value="1000">First 1,000 records</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-dark">
                                    <i class="fa fa-search text-secondary me-1"></i> Keyword Filter (Optional)
                                </label>
                                <input type="text" name="search" class="form-control" placeholder="Filter by Name, ID, or Email..." value="{{ request('search') }}">
                            </div>
                        </div>
                    </div>

                    <div class="modal-admin-footer d-flex justify-content-between align-items-center">
                        <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success rounded-pill px-4 text-white fw-bold">
                            <i class="fa fa-download me-1"></i> Export Range to Excel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal: Import Excel Spreadsheet -->
    <div class="modal fade modal-admin-custom" id="importExcelModal" tabindex="-1" aria-labelledby="importExcelModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-stripe-bar">
                    <div class="bar-blue"></div>
                    <div class="bar-red"></div>
                    <div class="bar-yellow"></div>
                    <div class="bar-green"></div>
                </div>

                <form action="{{ route('admin.club_members.import') }}" method="POST" enctype="multipart/form-data" id="importExcelForm">
                    @csrf
                    <div class="modal-admin-header">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fa fa-file-excel-o text-success fs-5"></i>
                            <h5 class="modal-admin-title" id="importExcelModalLabel">Import &amp; Auto-Sync Members From Excel</h5>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-admin-body">
                        <!-- Instructions Callout -->
                        <div class="alert alert-info border-0 rounded-3 mb-3 p-3">
                            <div class="d-flex align-items-start gap-2">
                                <i class="fa fa-magic fs-5 text-primary mt-1"></i>
                                <div>
                                    <strong class="text-dark">Smart Auto-Update Matching:</strong>
                                    <p class="mb-0 small text-secondary">
                                        Rows are matched based on <strong>Student ID</strong> and <strong>Term</strong> (or <strong>Name</strong>). If a student already exists in the database, their record will automatically update with new terms or details instead of creating duplicates.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold text-dark">Select Excel File (.xlsx, .xls, .csv) <span class="text-danger">*</span></label>
                            <input type="file" name="excel_file" class="form-control" accept=".xlsx,.xls,.csv" required id="excelFileInput">
                            <small class="text-muted">Maximum file size: 10MB.</small>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-dark">Default Term Fallback</label>
                                <input type="text" name="default_term" class="form-control" placeholder="e.g. 2530" value="2530">
                                <small class="text-muted">Applied if any row in the spreadsheet doesn't specify a term.</small>
                            </div>
                            <div class="col-md-6 d-flex align-items-end">
                                <a href="{{ route('admin.club_members.template') }}" class="btn btn-outline-secondary w-100 rounded-3 py-2">
                                    <i class="fa fa-download me-1"></i> Download Sample Template
                                </a>
                            </div>
                        </div>

                        <!-- Header Guidance -->
                        <div class="card p-3 bg-light border-0 rounded-3">
                            <span class="small fw-bold text-dark mb-2 d-block"><i class="fa fa-table text-primary me-1"></i> Expected Spreadsheet Columns &amp; Structure:</span>
                            <div class="row g-2 small text-muted mb-2">
                                <div class="col-md-4">
                                    <strong>Column A (Term):</strong><br>
                                    <code>Registration Term</code> / <code>Term</code><br>
                                    <span class="text-secondary small fst-italic">Put term (e.g. 2510) on the 1st row of each cohort. Blank rows carry down the same term automatically.</span>
                                </div>
                                <div class="col-md-4">
                                    <strong>Column B (Name):</strong><br>
                                    <code>Name</code> / <code>Student Name</code><br>
                                    <span class="text-secondary small fst-italic">Full name of the student member.</span>
                                </div>
                                <div class="col-md-4">
                                    <strong>Column C (Student ID):</strong><br>
                                    <code>Student ID</code> / <code>ID</code><br>
                                    <span class="text-secondary small fst-italic">Matric ID (e.g. 243UC247DF or ? for pending).</span>
                                </div>
                            </div>
                            <div class="p-2 bg-white rounded border small text-secondary">
                                <i class="fa fa-info-circle text-primary me-1"></i>
                                <strong>Tip:</strong> You can download the sample template to see the exact grouped cohort format.
                            </div>
                        </div>
                    </div>

                    <div class="modal-admin-footer d-flex justify-content-between align-items-center">
                        <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success rounded-pill px-4 text-white" id="btnSubmitImport">
                            <i class="fa fa-upload me-1"></i> Upload &amp; Auto-Sync to Database
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal: Manual Add Member -->
    <div class="modal fade modal-admin-custom" id="manualAddMemberModal" tabindex="-1" aria-labelledby="manualAddMemberModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-stripe-bar">
                    <div class="bar-blue"></div>
                    <div class="bar-red"></div>
                    <div class="bar-yellow"></div>
                    <div class="bar-green"></div>
                </div>

                <form action="{{ route('admin.club_members.store') }}" method="POST">
                    @csrf
                    <div class="modal-admin-header">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fa fa-user-plus text-primary fs-5"></i>
                            <h5 class="modal-admin-title" id="manualAddMemberModalLabel">Add Club Member Manually</h5>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-admin-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold text-dark">Member Full Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" placeholder="e.g. Muhammad Raziq" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold text-dark">Student ID</label>
                                <input type="text" name="student_id" class="form-control" placeholder="e.g. 1211103984">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold text-dark">Registration Term <span class="text-danger">*</span></label>
                                <input type="text" name="term" class="form-control" placeholder="e.g. 2530" value="2530" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold text-dark">Student Email</label>
                            <input type="email" name="email" class="form-control" placeholder="e.g. student@mmu.edu.my">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold text-dark">Membership Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-select" required>
                                <option value="active" selected>Active</option>
                                <option value="inactive">Inactive (Archived)</option>
                                <option value="expired">Expired</option>
                            </select>
                        </div>
                    </div>

                    <div class="modal-admin-footer">
                        <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary rounded-pill px-4">
                            <i class="fa fa-check me-1"></i> Add Member
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal: Edit Member -->
    <div class="modal fade modal-admin-custom" id="editMemberModal" tabindex="-1" aria-labelledby="editMemberModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-stripe-bar">
                    <div class="bar-blue"></div>
                    <div class="bar-red"></div>
                    <div class="bar-yellow"></div>
                    <div class="bar-green"></div>
                </div>

                <form id="editMemberForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-admin-header">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fa fa-pencil text-primary fs-5"></i>
                            <h5 class="modal-admin-title" id="editMemberModalLabel">Edit Member Details</h5>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-admin-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold text-dark">Member Full Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="editMemberName" class="form-control" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold text-dark">Student ID</label>
                                <input type="text" name="student_id" id="editMemberStudentId" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold text-dark">Registration Term <span class="text-danger">*</span></label>
                                <input type="text" name="term" id="editMemberTerm" class="form-control" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold text-dark">Student Email</label>
                            <input type="email" name="email" id="editMemberEmail" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold text-dark">Membership Status <span class="text-danger">*</span></label>
                            <select name="status" id="editMemberStatus" class="form-select" required>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive (Archived)</option>
                                <option value="expired">Expired</option>
                            </select>
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

    <!-- Client Scripts for Manage Members -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Edit Member Modal Handler
            const editModalEl = document.getElementById('editMemberModal');
            const editForm = document.getElementById('editMemberForm');

            document.querySelectorAll('.btn-edit-member').forEach(btn => {
                btn.addEventListener('click', function () {
                    const id = this.getAttribute('data-id');
                    const name = this.getAttribute('data-name') || '';
                    const studentId = this.getAttribute('data-student-id') || '';
                    const term = this.getAttribute('data-term') || '';
                    const email = this.getAttribute('data-email') || '';
                    const status = this.getAttribute('data-status') || 'active';

                    if (editForm) editForm.action = `/admin/club-members/${id}`;
                    document.getElementById('editMemberName').value = name;
                    document.getElementById('editMemberStudentId').value = studentId;
                    document.getElementById('editMemberTerm').value = term;
                    document.getElementById('editMemberEmail').value = email;
                    document.getElementById('editMemberStatus').value = status;

                    const modal = new bootstrap.Modal(editModalEl);
                    modal.show();
                });
            });

            // Single Delete Handler
            const singleDeleteForm = document.getElementById('singleDeleteForm');
            document.querySelectorAll('.btn-single-delete').forEach(btn => {
                btn.addEventListener('click', function () {
                    const id = this.getAttribute('data-id');
                    const name = this.getAttribute('data-name');
                    if (confirm(`Are you sure you want to delete member "${name}"?`)) {
                        singleDeleteForm.action = `/admin/club-members/${id}`;
                        singleDeleteForm.submit();
                    }
                });
            });

            // Manage Term Modal Handlers (Archive or Delete)
            const modalTermSelector = document.getElementById('modalTermSelector');
            const termSummaryCallout = document.getElementById('termSummaryCallout');
            const displayTermName = document.getElementById('displayTermName');
            const displayTermCount = document.getElementById('displayTermCount');
            const displayTermActive = document.getElementById('displayTermActive');
            const displayTermInactive = document.getElementById('displayTermInactive');
            const hiddenTermInputs = document.querySelectorAll('.hidden-selected-term');
            const btnSubmitArchiveTerm = document.getElementById('btnSubmitArchiveTerm');
            const btnSubmitActivateTerm = document.getElementById('btnSubmitActivateTerm');
            const btnSubmitDeleteTerm = document.getElementById('btnSubmitDeleteTerm');
            const confirmTermDeleteCheckbox = document.getElementById('confirmTermDeleteCheckbox');

            function syncModalTermState() {
                const selectedVal = modalTermSelector ? modalTermSelector.value : '';
                const selectedOption = modalTermSelector && modalTermSelector.selectedIndex >= 0 ? modalTermSelector.options[modalTermSelector.selectedIndex] : null;
                const hasTerm = !!selectedVal;

                hiddenTermInputs.forEach(input => {
                    input.value = selectedVal;
                });

                if (hasTerm && selectedOption) {
                    const count = selectedOption.getAttribute('data-count') || '0';
                    const active = selectedOption.getAttribute('data-active') || '0';
                    const inactive = selectedOption.getAttribute('data-inactive') || '0';

                    displayTermName.textContent = `Term ${selectedVal}`;
                    displayTermCount.textContent = `${count} members`;
                    displayTermActive.textContent = active;
                    displayTermInactive.textContent = inactive;
                    termSummaryCallout.style.display = 'block';

                    if (btnSubmitArchiveTerm) btnSubmitArchiveTerm.disabled = false;
                    if (btnSubmitActivateTerm) btnSubmitActivateTerm.disabled = false;
                } else {
                    termSummaryCallout.style.display = 'none';
                    if (btnSubmitArchiveTerm) btnSubmitArchiveTerm.disabled = true;
                    if (btnSubmitActivateTerm) btnSubmitActivateTerm.disabled = true;
                }

                const isDeleteConfirmed = confirmTermDeleteCheckbox && confirmTermDeleteCheckbox.checked;
                if (btnSubmitDeleteTerm) {
                    btnSubmitDeleteTerm.disabled = !(hasTerm && isDeleteConfirmed);
                }
            }

            if (modalTermSelector) modalTermSelector.addEventListener('change', syncModalTermState);
            if (confirmTermDeleteCheckbox) confirmTermDeleteCheckbox.addEventListener('change', syncModalTermState);

            // Loading state on Excel Import submission
            const importForm = document.getElementById('importExcelForm');
            const btnSubmitImport = document.getElementById('btnSubmitImport');
            if (importForm && btnSubmitImport) {
                importForm.addEventListener('submit', function() {
                    btnSubmitImport.disabled = true;
                    btnSubmitImport.innerHTML = '<i class="fa fa-spinner fa-spin me-2"></i> Syncing to Database...';
                });
            }

            // Auto open Import Modal if ?action=import is in URL
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('action') === 'import') {
                const importModal = new bootstrap.Modal(document.getElementById('importExcelModal'));
                importModal.show();
            }
        });
    </script>
</x-layout>
