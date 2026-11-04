<x-layout>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">

    <div class="admin-page-wrapper">
        <div class="admin-container">

            <!-- Sub-Navigation Bar -->
            @include('admin.partials.nav', ['active' => 'news'])

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
                            <i class="fa fa-newspaper-o text-danger"></i>
                            <span id="newsArticlesCountBadge">{{ $allNews->count() }} News Stories Live</span>
                        </div>
                        <h1 class="admin-title">Manage News Feed</h1>
                        <p class="admin-subtitle">Publish tech updates, AI breakthroughs, cybersecurity trends, and software engineering insights for the MMU community.</p>
                    </div>
                    <div class="admin-header-actions mt-3 mt-md-0">
                        <button type="button" class="btn-admin-action btn-admin-primary" data-bs-toggle="modal" data-bs-target="#addNewsModal">
                            <i class="fa fa-plus-circle"></i>
                            <span>Publish New Article</span>
                        </button>
                        <a href="/news" target="_blank" class="btn-admin-action btn-admin-secondary">
                            <i class="fa fa-external-link"></i>
                            <span>View Public Feed</span>
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

            <!-- Manage News Feed Section -->
            <div class="news-mgmt-wrapper" data-aos="fade-up" data-aos-duration="900">
                <div class="modal-stripe-bar mb-3" style="border-radius: 4px; overflow: hidden;">
                    <div class="bar-blue"></div>
                    <div class="bar-red"></div>
                    <div class="bar-yellow"></div>
                    <div class="bar-green"></div>
                </div>

                <!-- Filter & Search Bar -->
                <div class="news-filter-bar mb-4">
                    <div class="news-search-box flex-grow-1">
                        <i class="fa fa-search text-muted"></i>
                        <input type="text" id="newsSearchInput" placeholder="Search news by title, category, or author...">
                    </div>
                    <div class="news-filter-pills" id="newsFilterPills">
                        <button type="button" class="news-filter-pill-btn active" data-news-filter="all">All</button>
                        <button type="button" class="news-filter-pill-btn" data-news-filter="ai">AI / ML</button>
                        <button type="button" class="news-filter-pill-btn" data-news-filter="software-engineering">Software Engineering</button>
                        <button type="button" class="news-filter-pill-btn" data-news-filter="cybersecurity">Cybersecurity</button>
                        <button type="button" class="news-filter-pill-btn" data-news-filter="cloud">Cloud Computing</button>
                        <button type="button" class="news-filter-pill-btn" data-news-filter="other">Other</button>
                    </div>
                </div>

                <!-- News Grid -->
                <div class="news-admin-grid" id="newsAdminGrid">
                    @forelse($allNews as $nw)
                        @php
                            $catLower = strtolower(trim((string)($nw->category ?? '')));
                            $catSlug = preg_replace('/[^a-z0-9]+/', '-', $catLower);
                            $catSlug = trim($catSlug, '-');
                            if (in_array($catSlug, ['ai-ml', 'artificial-intelligence'], true)) {
                                $catSlug = 'ai';
                            }
                            $catBadgeClass = 'badge-cat-other';
                            if (str_contains($catSlug, 'ai')) { $catBadgeClass = 'badge-cat-ai'; }
                            elseif (str_contains($catSlug, 'software') || str_contains($catSlug, 'engineering') || str_contains($catSlug, 'web')) { $catBadgeClass = 'badge-cat-se'; }
                            elseif (str_contains($catSlug, 'cyber') || str_contains($catSlug, 'security')) { $catBadgeClass = 'badge-cat-cyber'; }
                            elseif (str_contains($catSlug, 'cloud')) { $catBadgeClass = 'badge-cat-cloud'; }

                            $coverImg = !empty($nw->news_img[0]) ? (str_starts_with($nw->news_img[0], 'http') ? $nw->news_img[0] : asset($nw->news_img[0])) : null;
                            $paragraphsCount = is_array($nw->paragraph) ? count($nw->paragraph) : 1;
                        @endphp
                        <div class="news-admin-card news-card-item"
                             id="news-item-{{ $nw->id }}"
                             data-news-id="{{ $nw->id }}"
                             data-news-category="{{ $catSlug }}"
                             data-news-title="{{ strtolower($nw->title) }}"
                             data-news-author="{{ strtolower($nw->author ?? '') }}"
                             data-news-desc="{{ strtolower($nw->description ?? '') }}">
                            
                            <div class="news-card-thumb-wrap">
                                @if($coverImg)
                                    <img src="{{ $coverImg }}" alt="{{ $nw->title }}" class="news-card-thumb" onerror="this.onerror=null; this.src='{{ asset('assets/img/developer-logo.png') }}';">
                                @else
                                    <div class="news-thumb-placeholder">
                                        <i class="fa fa-newspaper-o fa-2x mb-2"></i>
                                        <span class="small fw-bold">GDGoC MMU News</span>
                                    </div>
                                @endif
                                <span class="news-card-category-badge {{ $catBadgeClass }}">
                                    {{ $nw->category ?? 'Tech' }}
                                </span>
                            </div>

                            <div class="news-card-body-content">
                                <div class="news-card-meta">
                                    <span><i class="fa fa-calendar-o"></i> {{ $nw->date ? $nw->date->format('M j, Y') : 'Recent' }}</span>
                                    <span>&bull;</span>
                                    <span><i class="fa fa-user-circle-o"></i> {{ $nw->author ?? 'GDGoC MMU' }}</span>
                                </div>

                                <h3 class="news-card-title" title="{{ $nw->title }}">
                                    {{ $nw->title }}
                                </h3>

                                <p class="news-card-desc">
                                    {{ $nw->description }}
                                </p>

                                <div class="news-card-footer-info">
                                    <span><i class="fa fa-align-left me-1"></i> {{ $paragraphsCount }} {{ \Illuminate\Support\Str::plural('paragraph', $paragraphsCount) }}</span>
                                    @if(!empty($nw->news_source))
                                        <a href="{{ $nw->news_source }}" target="_blank" class="text-primary text-decoration-none" title="Visit External Source Link">
                                            <i class="fa fa-external-link me-1"></i> Source
                                        </a>
                                    @endif
                                </div>
                            </div>

                            <div class="news-card-actions-bar">
                                <button type="button" class="btn-pill-action btn-pill-accent btn-trigger-preview-news"
                                        data-news-id="{{ $nw->id }}"
                                        title="Instant Preview in Reader Mode">
                                    <i class="fa fa-eye"></i>
                                    <span>Preview</span>
                                </button>
                                
                                <button type="button" class="btn-pill-action btn-trigger-edit-news"
                                        data-news-id="{{ $nw->id }}"
                                        title="Edit Article Details &amp; Paragraphs">
                                    <i class="fa fa-pencil"></i>
                                    <span>Edit</span>
                                </button>

                                <form action="{{ route('admin.news.destroy', $nw->id) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Are you sure you want to delete \'{{ addslashes($nw->title) }}\'? This cannot be undone.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-pill-action btn-pill-delete" title="Delete article">
                                        <i class="fa fa-trash-o"></i>
                                    </button>
                                </form>

                                <a href="/news/{{ $nw->id }}" target="_blank" class="btn-pill-action" title="Open Public Page">
                                    <i class="fa fa-external-link"></i>
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="news-empty-state">
                            <i class="fa fa-newspaper-o text-muted mb-3" style="font-size: 3rem;"></i>
                            <h4 class="fw-bold text-dark mb-1">No News Articles Found</h4>
                            <p class="text-muted mb-3">Publish your first article or tech news update to share with the community!</p>
                            <button type="button" class="btn btn-primary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#addNewsModal">
                                <i class="fa fa-plus-circle me-1"></i> Publish First Article
                            </button>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>

    <!-- Modal: Add News Article -->
    <div class="modal fade modal-admin-custom" id="addNewsModal" tabindex="-1" aria-labelledby="addNewsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-stripe-bar">
                    <div class="bar-blue"></div>
                    <div class="bar-red"></div>
                    <div class="bar-yellow"></div>
                    <div class="bar-green"></div>
                </div>

                <div class="modal-admin-header">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fa fa-newspaper-o text-danger fs-5"></i>
                        <h5 class="modal-admin-title" id="addNewsModalLabel">Publish News Article</h5>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-preview-tabs px-3 pt-2">
                    <ul class="nav nav-pills" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="addNewsTabFormBtn" data-bs-toggle="pill" data-bs-target="#addNewsTabFormPane" type="button" role="tab" onclick="updateNewsFooterToggleBtn('add', 'form')">
                                <i class="fa fa-pencil me-1"></i> Write Article
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="addNewsTabPreviewBtn" data-bs-toggle="pill" data-bs-target="#addNewsTabPreviewPane" type="button" role="tab" onclick="updateAddNewsLivePreview(); updateNewsFooterToggleBtn('add', 'preview')">
                                <i class="fa fa-eye me-1"></i> Live Preview
                            </button>
                        </li>
                    </ul>
                </div>

                <form action="{{ route('admin.news.store') }}" method="POST" enctype="multipart/form-data" id="addNewsForm">
                    @csrf
                    <div class="modal-body modal-admin-body">
                        <div class="tab-content">
                            <!-- Tab 1: Form Fields (Multi-Step Wizard) -->
                            <div class="tab-pane fade show active" id="addNewsTabFormPane" role="tabpanel">

                                <!-- Multi-Step Wizard Stepper -->
                                <div class="news-wizard-stepper">
                                    <button type="button" class="wizard-step-item active" id="addNewsStepIndicator1" onclick="goToNewsStep('add', 1)">
                                        <span class="wizard-step-circle">1</span>
                                        <div class="wizard-step-labels">
                                            <span class="wizard-step-title">Article Details</span>
                                            <span class="wizard-step-desc">Title &amp; Info</span>
                                        </div>
                                    </button>
                                    <div class="wizard-step-divider" id="addNewsStepDivider1"></div>
                                    <button type="button" class="wizard-step-item" id="addNewsStepIndicator2" onclick="goToNewsStep('add', 2)">
                                        <span class="wizard-step-circle">2</span>
                                        <div class="wizard-step-labels">
                                            <span class="wizard-step-title">Summary &amp; Media</span>
                                            <span class="wizard-step-desc">Lead &amp; Cover</span>
                                        </div>
                                    </button>
                                    <div class="wizard-step-divider" id="addNewsStepDivider2"></div>
                                    <button type="button" class="wizard-step-item" id="addNewsStepIndicator3" onclick="goToNewsStep('add', 3)">
                                        <span class="wizard-step-circle">3</span>
                                        <div class="wizard-step-labels">
                                            <span class="wizard-step-title">Paragraphs</span>
                                            <span class="wizard-step-desc">Story Content</span>
                                        </div>
                                    </button>
                                </div>

                                <!-- STEP 1: Article Details -->
                                <div class="news-step-pane active" id="addNewsStepPane1">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold text-dark">Article Title <span class="text-danger">*</span></label>
                                        <input type="text" id="addNewsTitle" name="title" class="form-control" placeholder="e.g. Anthropic Unveils Next-Gen AI System..." required>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label fw-bold text-dark">Category <span class="text-danger">*</span></label>
                                            <select id="addNewsCategorySelect" class="form-select" onchange="handleAddNewsCategoryChange(this)">
                                                <option value="AI">AI / Machine Learning</option>
                                                <option value="Software Engineering">Software Engineering</option>
                                                <option value="Cybersecurity">Cybersecurity</option>
                                                <option value="Cloud Computing">Cloud Computing</option>
                                                <option value="Web Development">Web Development</option>
                                                <option value="Mobile Development">Mobile Development</option>
                                                <option value="__custom__">+ Custom Category...</option>
                                            </select>
                                            <input type="text" id="addNewsCategoryInput" name="category" class="form-control mt-2" value="AI" style="display: none;" placeholder="Type custom category name...">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label fw-bold text-dark">Author</label>
                                            <input type="text" id="addNewsAuthor" name="author" class="form-control" value="{{ Auth::user()->name ?? 'GDGoC MMU Team' }}" placeholder="Author name">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label fw-bold text-dark">Publication Date <span class="text-danger">*</span></label>
                                            <input type="date" id="addNewsDate" name="date" class="form-control" value="{{ date('Y-m-d') }}" required>
                                        </div>
                                    </div>

                                    <div class="d-flex align-items-center justify-content-between mt-3 pt-2 border-top">
                                        <span class="text-muted small"><i class="fa fa-info-circle text-primary me-1"></i> Step 1 of 3: Details &amp; Metadata</span>
                                        <button type="button" class="btn btn-sm btn-primary rounded-pill px-3" onclick="goToNewsStep('add', 2)">
                                            Next: Summary &amp; Media <i class="fa fa-arrow-right ms-1"></i>
                                        </button>
                                    </div>
                                </div>

                                <!-- STEP 2: Summary & Cover Image -->
                                <div class="news-step-pane" id="addNewsStepPane2">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold text-dark">Lead Summary / Excerpt <span class="text-danger">*</span></label>
                                        <textarea id="addNewsDescription" name="description" class="form-control" rows="2" placeholder="Brief 1-2 sentence lead summary displayed on cards and hero..." required></textarea>
                                        <small class="text-muted">Displays on the news feed card grid and underneath the headline on the public story.</small>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-bold text-dark">Cover Image Upload</label>
                                            <input type="file" id="addNewsImageFile" name="news_image_file" class="form-control" accept="image/*">
                                            <small class="text-muted">Upload an image file (JPEG, PNG, WEBP, up to 5MB).</small>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-bold text-dark">Or Cover Image URL</label>
                                            <input type="text" id="addNewsImageUrl" name="news_img_url" class="form-control" placeholder="https://... or images/example.png">
                                            <small class="text-muted">Paste an external image link or asset path if not uploading a file.</small>
                                        </div>
                                    </div>

                                    <!-- Cover Preview Thumbnail -->
                                    <div class="mb-3 p-2 bg-light rounded-3" id="addNewsCoverPreviewWrap" style="display: none;">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="d-flex align-items-center gap-3">
                                                <img id="addNewsCoverPreviewThumb" src="" alt="Cover Preview" style="width: 80px; height: 50px; object-fit: cover; border-radius: 6px; border: 1px solid #e2e8f0;">
                                                <div>
                                                    <div class="fw-semibold text-dark small"><i class="fa fa-image text-primary me-1"></i> Cover Image Preview</div>
                                                    <small class="text-muted" id="addNewsCoverPreviewText">Selected cover image</small>
                                                </div>
                                            </div>
                                            <button type="button" class="btn btn-sm btn-outline-danger rounded-pill" onclick="clearAddNewsCoverImage()">
                                                <i class="fa fa-times me-1"></i> Clear
                                            </button>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-bold text-dark">External Source Link (Optional)</label>
                                        <input type="url" id="addNewsSource" name="news_source" class="form-control" placeholder="https://example.com/article-source">
                                    </div>

                                    <div class="d-flex align-items-center justify-content-between mt-3 pt-2 border-top">
                                        <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3" onclick="goToNewsStep('add', 1)">
                                            <i class="fa fa-arrow-left me-1"></i> Back to Details
                                        </button>
                                        <button type="button" class="btn btn-sm btn-primary rounded-pill px-3" onclick="goToNewsStep('add', 3)">
                                            Next: Story Paragraphs <i class="fa fa-arrow-right ms-1"></i>
                                        </button>
                                    </div>
                                </div>

                                <!-- STEP 3: Paragraphs Builder -->
                                <div class="news-step-pane" id="addNewsStepPane3">
                                    <div class="card p-3 bg-light border-0 rounded-3 mb-2">
                                        <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap gap-2">
                                            <div>
                                                <h6 class="fw-bold text-dark mb-0"><i class="fa fa-align-left text-primary me-1"></i> Article Paragraphs</h6>
                                                <small class="text-muted">Add paragraphs in sequence. Attach optional images per paragraph.</small>
                                            </div>
                                            <div class="d-flex gap-2">
                                                <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill" onclick="toggleQuickPasteBox('addQuickPasteContainer')">
                                                    <i class="fa fa-paste me-1"></i> Quick Paste Text
                                                </button>
                                                <button type="button" class="btn btn-sm btn-primary rounded-pill px-3" data-action="add-news-paragraph" data-target-container="addNewsParagraphsContainer" onclick="addNewsParagraphRow('addNewsParagraphsContainer')">
                                                    <i class="fa fa-plus me-1"></i> Add Paragraph
                                                </button>
                                            </div>
                                        </div>

                                        <!-- Quick Paste Box -->
                                        <div id="addQuickPasteContainer" class="p-3 bg-white border rounded-3 mb-3" style="display: none;">
                                            <label class="form-label fw-semibold small text-dark">Paste Full Story Text (Double-line breaks will create separate paragraphs):</label>
                                            <textarea id="addQuickPasteText" class="form-control mb-2" rows="4" placeholder="Paste full article text here..."></textarea>
                                            <div class="d-flex justify-content-end gap-2">
                                                <button type="button" class="btn btn-sm btn-secondary rounded-pill" onclick="toggleQuickPasteBox('addQuickPasteContainer')">Cancel</button>
                                                <button type="button" class="btn btn-sm btn-success rounded-pill px-3" onclick="convertQuickPasteToParagraphs('addQuickPasteText', 'addNewsParagraphsContainer')">
                                                    <i class="fa fa-magic me-1"></i> Convert to Paragraphs
                                                </button>
                                            </div>
                                        </div>

                                        <div id="addNewsParagraphsContainer" class="paragraphs-scroll-wrapper">
                                            <!-- Populated dynamically via JS -->
                                        </div>

                                        <div class="text-center mt-2">
                                            <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3" data-action="add-news-paragraph" data-target-container="addNewsParagraphsContainer" onclick="addNewsParagraphRow('addNewsParagraphsContainer')">
                                                <i class="fa fa-plus me-1"></i> Add Another Paragraph
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Tab 2: Live Preview -->
                            <div class="tab-pane fade" id="addNewsTabPreviewPane" role="tabpanel">
                                <div class="news-live-preview-container">
                                    <div class="preview-mode-bar">
                                        <span><i class="fa fa-desktop me-1"></i> Live Reader View Mode</span>
                                        <span class="badge bg-light text-dark border rounded-pill px-2 py-1"><i class="fa fa-arrows-v text-muted me-1"></i> Scroll down to read full story</span>
                                    </div>
                                    <div id="addNewsLivePreviewContent" class="news-preview-scroll-wrapper">
                                        <!-- Rendered by JavaScript -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-admin-footer d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <button type="button" class="btn btn-secondary rounded-pill px-3" data-bs-dismiss="modal">Cancel</button>

                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <!-- Wizard Prev Button -->
                            <button type="button" class="btn btn-outline-secondary rounded-pill px-3" id="addNewsStepPrevBtn" onclick="prevNewsStep('add')" style="display: none;">
                                <i class="fa fa-arrow-left me-1"></i> <span id="addNewsStepPrevText">Previous</span>
                            </button>

                            <!-- Wizard Next Button -->
                            <button type="button" class="btn btn-primary rounded-pill px-3" id="addNewsStepNextBtn" onclick="nextNewsStep('add')">
                                <span id="addNewsStepNextText">Next: Summary &amp; Media</span> <i class="fa fa-arrow-right ms-1"></i>
                            </button>

                            <!-- Reader Mode Toggle -->
                            <button type="button" class="btn btn-outline-primary rounded-pill px-3" id="addNewsFooterToggleBtn" onclick="toggleNewsPreviewTab('add')">
                                <i class="fa fa-eye me-1"></i> Preview
                            </button>

                            <!-- Publish Submit -->
                            <button type="submit" class="btn btn-success rounded-pill px-4" id="addNewsPublishSubmitBtn">
                                <i class="fa fa-paper-plane me-1"></i> Publish Article
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal: Edit News Article -->
    <div class="modal fade modal-admin-custom" id="editNewsModal" tabindex="-1" aria-labelledby="editNewsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-stripe-bar">
                    <div class="bar-blue"></div>
                    <div class="bar-red"></div>
                    <div class="bar-yellow"></div>
                    <div class="bar-green"></div>
                </div>

                <div class="modal-admin-header">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fa fa-pencil text-primary fs-5"></i>
                        <h5 class="modal-admin-title" id="editNewsModalLabel">Edit News Article</h5>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-preview-tabs px-3 pt-2">
                    <ul class="nav nav-pills" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="editNewsTabFormBtn" data-bs-toggle="pill" data-bs-target="#editNewsTabFormPane" type="button" role="tab" onclick="updateNewsFooterToggleBtn('edit', 'form')">
                                <i class="fa fa-pencil me-1"></i> Edit Details
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="editNewsTabPreviewBtn" data-bs-toggle="pill" data-bs-target="#editNewsTabPreviewPane" type="button" role="tab" onclick="updateEditNewsLivePreview(); updateNewsFooterToggleBtn('edit', 'preview')">
                                <i class="fa fa-eye me-1"></i> Live Preview
                            </button>
                        </li>
                    </ul>
                </div>

                <form id="editNewsForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-body modal-admin-body">
                        <div class="tab-content">
                            <!-- Tab 1: Form Fields (Multi-Step Wizard for Edit) -->
                            <div class="tab-pane fade show active" id="editNewsTabFormPane" role="tabpanel">
                                <!-- Multi-Step Wizard Stepper -->
                                <div class="news-wizard-stepper">
                                    <button type="button" class="wizard-step-item active" id="editNewsStepIndicator1" onclick="goToNewsStep('edit', 1)">
                                        <span class="wizard-step-circle">1</span>
                                        <div class="wizard-step-labels">
                                            <span class="wizard-step-title">Article Details</span>
                                            <span class="wizard-step-desc">Title &amp; Info</span>
                                        </div>
                                    </button>
                                    <div class="wizard-step-divider" id="editNewsStepDivider1"></div>
                                    <button type="button" class="wizard-step-item" id="editNewsStepIndicator2" onclick="goToNewsStep('edit', 2)">
                                        <span class="wizard-step-circle">2</span>
                                        <div class="wizard-step-labels">
                                            <span class="wizard-step-title">Summary &amp; Media</span>
                                            <span class="wizard-step-desc">Lead &amp; Cover</span>
                                        </div>
                                    </button>
                                    <div class="wizard-step-divider" id="editNewsStepDivider2"></div>
                                    <button type="button" class="wizard-step-item" id="editNewsStepIndicator3" onclick="goToNewsStep('edit', 3)">
                                        <span class="wizard-step-circle">3</span>
                                        <div class="wizard-step-labels">
                                            <span class="wizard-step-title">Paragraphs</span>
                                            <span class="wizard-step-desc">Story Content</span>
                                        </div>
                                    </button>
                                </div>

                                <!-- STEP 1: Details -->
                                <div class="news-step-pane active" id="editNewsStepPane1">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold text-dark">Article Title <span class="text-danger">*</span></label>
                                        <input type="text" id="editNewsTitle" name="title" class="form-control" required>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label fw-bold text-dark">Category <span class="text-danger">*</span></label>
                                            <select id="editNewsCategorySelect" class="form-select" onchange="handleEditNewsCategoryChange(this)">
                                                <option value="AI">AI / Machine Learning</option>
                                                <option value="Software Engineering">Software Engineering</option>
                                                <option value="Cybersecurity">Cybersecurity</option>
                                                <option value="Cloud Computing">Cloud Computing</option>
                                                <option value="Web Development">Web Development</option>
                                                <option value="Mobile Development">Mobile Development</option>
                                                <option value="__custom__">+ Custom Category...</option>
                                            </select>
                                            <input type="text" id="editNewsCategoryInput" name="category" class="form-control mt-2" style="display: none;" placeholder="Type custom category name...">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label fw-bold text-dark">Author</label>
                                            <input type="text" id="editNewsAuthor" name="author" class="form-control" placeholder="Author name">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label fw-bold text-dark">Publication Date <span class="text-danger">*</span></label>
                                            <input type="date" id="editNewsDate" name="date" class="form-control" required>
                                        </div>
                                    </div>

                                    <div class="d-flex align-items-center justify-content-between mt-3 pt-2 border-top">
                                        <span class="text-muted small"><i class="fa fa-info-circle text-primary me-1"></i> Step 1 of 3: Details &amp; Metadata</span>
                                        <button type="button" class="btn btn-sm btn-primary rounded-pill px-3" onclick="goToNewsStep('edit', 2)">
                                            Next: Summary &amp; Media <i class="fa fa-arrow-right ms-1"></i>
                                        </button>
                                    </div>
                                </div>

                                <!-- STEP 2: Summary & Cover Image -->
                                <div class="news-step-pane" id="editNewsStepPane2">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold text-dark">Lead Summary / Excerpt <span class="text-danger">*</span></label>
                                        <textarea id="editNewsDescription" name="description" class="form-control" rows="2" required></textarea>
                                    </div>

                                    <!-- Current Cover Thumbnail Preview -->
                                    <div class="mb-3 p-3 bg-light rounded-3" id="editNewsCurrentImgContainer" style="display: none;">
                                        <div class="d-flex align-items-center gap-3">
                                            <img id="editNewsCurrentImgPreview" src="" alt="Cover Image" style="width: 100px; height: 65px; object-fit: cover; border-radius: 8px;">
                                            <div>
                                                <div class="fw-semibold text-dark small">Current Cover Image</div>
                                                <small class="text-muted" id="editNewsCurrentImgText"></small>
                                                <input type="hidden" name="keep_current_image" id="editNewsKeepCurrentImage" value="1">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-bold text-dark">Replace Cover Image (File)</label>
                                            <input type="file" id="editNewsImageFile" name="news_image_file" class="form-control" accept="image/*">
                                            <small class="text-muted">Leave empty to keep existing cover image.</small>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-bold text-dark">Or Update Cover Image URL</label>
                                            <input type="text" id="editNewsImageUrl" name="news_img_url" class="form-control" placeholder="https://... or images/example.png">
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-bold text-dark">External Source Link (Optional)</label>
                                        <input type="url" id="editNewsSource" name="news_source" class="form-control" placeholder="https://example.com/article-source">
                                    </div>

                                    <div class="d-flex align-items-center justify-content-between mt-3 pt-2 border-top">
                                        <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3" onclick="goToNewsStep('edit', 1)">
                                            <i class="fa fa-arrow-left me-1"></i> Back to Details
                                        </button>
                                        <button type="button" class="btn btn-sm btn-primary rounded-pill px-3" onclick="goToNewsStep('edit', 3)">
                                            Next: Story Paragraphs <i class="fa fa-arrow-right ms-1"></i>
                                        </button>
                                    </div>
                                </div>

                                <!-- STEP 3: Paragraphs Builder -->
                                <div class="news-step-pane" id="editNewsStepPane3">
                                    <div class="card p-3 bg-light border-0 rounded-3 mb-2">
                                        <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap gap-2">
                                            <div>
                                                <h6 class="fw-bold text-dark mb-0"><i class="fa fa-align-left text-primary me-1"></i> Article Paragraphs</h6>
                                                <small class="text-muted">Edit, reorder, or add paragraphs.</small>
                                            </div>
                                            <div class="d-flex gap-2">
                                                <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill" onclick="toggleQuickPasteBox('editQuickPasteContainer')">
                                                    <i class="fa fa-paste me-1"></i> Quick Paste Text
                                                </button>
                                                <button type="button" class="btn btn-sm btn-primary rounded-pill px-3" data-action="add-news-paragraph" data-target-container="editNewsParagraphsContainer" onclick="addNewsParagraphRow('editNewsParagraphsContainer')">
                                                    <i class="fa fa-plus me-1"></i> Add Paragraph
                                                </button>
                                            </div>
                                        </div>

                                        <!-- Quick Paste Box for Edit -->
                                        <div id="editQuickPasteContainer" class="p-3 bg-white border rounded-3 mb-3" style="display: none;">
                                            <label class="form-label fw-semibold small text-dark">Paste New Content (Will append as paragraphs):</label>
                                            <textarea id="editQuickPasteText" class="form-control mb-2" rows="4" placeholder="Paste full article text here..."></textarea>
                                            <div class="d-flex justify-content-end gap-2">
                                                <button type="button" class="btn btn-sm btn-secondary rounded-pill" onclick="toggleQuickPasteBox('editQuickPasteContainer')">Cancel</button>
                                                <button type="button" class="btn btn-sm btn-success rounded-pill px-3" onclick="convertQuickPasteToParagraphs('editQuickPasteText', 'editNewsParagraphsContainer')">
                                                    <i class="fa fa-magic me-1"></i> Convert to Paragraphs
                                                </button>
                                            </div>
                                        </div>

                                        <div id="editNewsParagraphsContainer" class="paragraphs-scroll-wrapper">
                                            <!-- Populated dynamically via JS -->
                                        </div>

                                        <div class="text-center mt-2">
                                            <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3" data-action="add-news-paragraph" data-target-container="editNewsParagraphsContainer" onclick="addNewsParagraphRow('editNewsParagraphsContainer')">
                                                <i class="fa fa-plus me-1"></i> Add Another Paragraph
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Tab 2: Live Preview -->
                            <div class="tab-pane fade" id="editNewsTabPreviewPane" role="tabpanel">
                                <div class="news-live-preview-container">
                                    <div class="preview-mode-bar">
                                        <span><i class="fa fa-desktop me-1"></i> Live Reader View Mode</span>
                                        <span class="badge bg-light text-dark border rounded-pill px-2 py-1"><i class="fa fa-arrows-v text-muted me-1"></i> Scroll down to read full story</span>
                                    </div>
                                    <div id="editNewsLivePreviewContent" class="news-preview-scroll-wrapper">
                                        <!-- Rendered by JavaScript -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-admin-footer d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <button type="button" class="btn btn-secondary rounded-pill px-3" data-bs-dismiss="modal">Cancel</button>

                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <!-- Wizard Prev Button -->
                            <button type="button" class="btn btn-outline-secondary rounded-pill px-3" id="editNewsStepPrevBtn" onclick="prevNewsStep('edit')" style="display: none;">
                                <i class="fa fa-arrow-left me-1"></i> <span id="editNewsStepPrevText">Previous</span>
                            </button>

                            <!-- Wizard Next Button -->
                            <button type="button" class="btn btn-primary rounded-pill px-3" id="editNewsStepNextBtn" onclick="nextNewsStep('edit')">
                                <span id="editNewsStepNextText">Next: Summary &amp; Media</span> <i class="fa fa-arrow-right ms-1"></i>
                            </button>

                            <!-- Reader Mode Toggle -->
                            <button type="button" class="btn btn-outline-primary rounded-pill px-3" id="editNewsFooterToggleBtn" onclick="toggleNewsPreviewTab('edit')">
                                <i class="fa fa-eye me-1"></i> Preview
                            </button>

                            <!-- Save Changes Submit -->
                            <button type="submit" class="btn btn-primary rounded-pill px-4" id="editNewsPublishSubmitBtn">
                                <i class="fa fa-check me-1"></i> Save Changes
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal: Dedicated Article Reader Preview -->
    <div class="modal fade modal-admin-custom" id="previewNewsModal" tabindex="-1" aria-labelledby="previewNewsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-stripe-bar">
                    <div class="bar-blue"></div>
                    <div class="bar-red"></div>
                    <div class="bar-yellow"></div>
                    <div class="bar-green"></div>
                </div>

                <div class="modal-admin-header">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fa fa-eye text-info fs-5"></i>
                        <h5 class="modal-admin-title" id="previewNewsModalLabel">Article Reader Preview</h5>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <a href="#" id="previewNewsOpenPublicLink" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                            <i class="fa fa-external-link me-1"></i> Open Public Story
                        </a>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                </div>

                <div class="modal-body p-0 news-preview-scroll-wrapper" id="previewNewsModalBody">
                    <!-- Populated dynamically via JS -->
                </div>

                <div class="modal-admin-footer">
                    <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary rounded-pill px-4" id="previewNewsEditButton">
                        <i class="fa fa-pencil me-1"></i> Edit This Article
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Interactive Client Scripts for Manage News -->
    <script>
        // Completely purge the unwanted draft key from localStorage
        try {
            localStorage.removeItem('gdsc_news_modal_draft');
        } catch(e) {}

        function handleAddNewsCategoryChange(select) {
            const input = document.getElementById('addNewsCategoryInput');
            if (select.value === '__custom__') {
                input.style.display = 'block';
                input.value = '';
                input.focus();
            } else {
                input.style.display = 'none';
                input.value = select.value;
            }
        }

        function handleEditNewsCategoryChange(select) {
            const input = document.getElementById('editNewsCategoryInput');
            if (select.value === '__custom__') {
                input.style.display = 'block';
                input.value = '';
                input.focus();
            } else {
                input.style.display = 'none';
                input.value = select.value;
            }
        }

        // Paragraph Builder Helper
        function addNewsParagraphRow(containerId, text = '', imgUrl = '') {
            const container = document.getElementById(containerId);
            if (!container) return;
            const currentCards = container.querySelectorAll('.paragraph-card');
            const nextIndex = currentCards.length;
            const hasImg = !!imgUrl;

            const card = document.createElement('div');
            card.className = 'paragraph-card';
            card.setAttribute('data-paragraph-index', nextIndex);
            card.innerHTML = `
                <div class="paragraph-card-header">
                    <span class="paragraph-card-label">Paragraph ${nextIndex + 1}</span>
                    <button type="button" class="btn btn-sm btn-outline-danger border-0 py-0 px-2 btn-remove-para" title="Remove this paragraph">
                        <i class="fa fa-trash-o"></i> Remove
                    </button>
                </div>
                <textarea name="paragraphs[${nextIndex}]" class="paragraph-card-textarea mb-2" rows="3" placeholder="Enter paragraph content here...">${escapeHtml(text)}</textarea>
                
                <!-- Paragraph Image Option Section -->
                <div class="paragraph-img-section p-2 rounded-3 bg-white border">
                    <div class="d-flex align-items-center justify-content-between mb-1 flex-wrap gap-1">
                        <span class="small fw-semibold text-secondary">
                            <i class="fa fa-picture-o text-primary me-1"></i> Paragraph Image (Optional)
                        </span>
                        <button type="button" class="btn btn-xs btn-outline-danger border-0 py-0 px-1 btn-clear-para-img" style="${hasImg ? '' : 'display: none;'}" title="Remove image from this paragraph">
                            <i class="fa fa-times me-1"></i> Clear Image
                        </button>
                    </div>

                    <div class="para-img-preview-wrap mb-2" style="${hasImg ? '' : 'display: none;'}">
                        <img src="${escapeHtml(imgUrl)}" alt="Paragraph Image" class="para-img-thumb" style="max-height: 80px; max-width: 140px; border-radius: 6px; object-fit: cover; border: 1px solid #e2e8f0;">
                    </div>

                    <input type="hidden" name="paragraph_existing_images[${nextIndex}]" class="para-existing-img-input" value="${escapeHtml(imgUrl)}">

                    <div class="row g-2">
                        <div class="col-sm-6">
                            <input type="text" name="paragraph_image_urls[${nextIndex}]" class="form-control form-control-sm para-img-url-input" placeholder="Paste image URL (https://...)" value="${imgUrl && imgUrl.startsWith('http') ? escapeHtml(imgUrl) : ''}">
                        </div>
                        <div class="col-sm-6">
                            <input type="file" name="paragraph_image_files[${nextIndex}]" class="form-control form-control-sm para-img-file-input" accept="image/*">
                        </div>
                    </div>
                </div>
            `;

            // Remove button handler
            const removeBtn = card.querySelector('.btn-remove-para');
            if (removeBtn) {
                removeBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    card.remove();
                    renumberParagraphCards(containerId);
                    if (containerId.indexOf('add') !== -1) {
                        updateAddNewsLivePreview();
                    } else {
                        updateEditNewsLivePreview();
                    }
                });
            }

            // Paragraph text change listener
            const ta = card.querySelector('textarea');
            if (ta) {
                ta.addEventListener('input', function() {
                    if (containerId.indexOf('add') !== -1) {
                        updateAddNewsLivePreview();
                    } else {
                        updateEditNewsLivePreview();
                    }
                });
            }

            // Image URL, File, and Clear controls
            const urlInput = card.querySelector('.para-img-url-input');
            const fileInput = card.querySelector('.para-img-file-input');
            const previewWrap = card.querySelector('.para-img-preview-wrap');
            const previewImg = card.querySelector('.para-img-thumb');
            const clearBtn = card.querySelector('.btn-clear-para-img');
            const existingInput = card.querySelector('.para-existing-img-input');

            if (urlInput) {
                urlInput.addEventListener('input', function() {
                    const val = this.value.trim();
                    if (val) {
                        previewImg.src = val;
                        previewWrap.style.display = 'block';
                        clearBtn.style.display = 'inline-block';
                        existingInput.value = val;
                    } else if (!fileInput.files.length) {
                        previewWrap.style.display = 'none';
                        clearBtn.style.display = 'none';
                        existingInput.value = '';
                    }
                    if (containerId.indexOf('add') !== -1) updateAddNewsLivePreview();
                    else updateEditNewsLivePreview();
                });
            }

            if (fileInput) {
                fileInput.addEventListener('change', function() {
                    if (this.files && this.files[0]) {
                        previewImg.src = URL.createObjectURL(this.files[0]);
                        previewWrap.style.display = 'block';
                        clearBtn.style.display = 'inline-block';
                    }
                    if (containerId.indexOf('add') !== -1) updateAddNewsLivePreview();
                    else updateEditNewsLivePreview();
                });
            }

            if (clearBtn) {
                clearBtn.addEventListener('click', function() {
                    if (urlInput) urlInput.value = '';
                    if (fileInput) fileInput.value = '';
                    if (existingInput) existingInput.value = '';
                    previewWrap.style.display = 'none';
                    clearBtn.style.display = 'none';
                    if (containerId.indexOf('add') !== -1) updateAddNewsLivePreview();
                    else updateEditNewsLivePreview();
                });
            }

            container.appendChild(card);
            container.scrollTop = container.scrollHeight;

            if (ta && !text) {
                setTimeout(() => {
                    ta.focus();
                }, 50);
            }
        }

        function removeNewsParagraphRow(btn, containerId) {
            const card = btn.closest('.paragraph-card');
            if (card) {
                card.remove();
                renumberParagraphCards(containerId);
                if (containerId.indexOf('add') !== -1) {
                    updateAddNewsLivePreview();
                } else {
                    updateEditNewsLivePreview();
                }
            }
        }

        function renumberParagraphCards(containerId) {
            const container = document.getElementById(containerId);
            if (!container) return;
            const cards = container.querySelectorAll('.paragraph-card');
            cards.forEach((card, index) => {
                card.setAttribute('data-paragraph-index', index);
                const label = card.querySelector('.paragraph-card-label');
                if (label) {
                    label.textContent = `Paragraph ${index + 1}`;
                }
                const ta = card.querySelector('textarea');
                if (ta) ta.name = `paragraphs[${index}]`;

                const existingInput = card.querySelector('.para-existing-img-input');
                if (existingInput) existingInput.name = `paragraph_existing_images[${index}]`;

                const urlInput = card.querySelector('.para-img-url-input');
                if (urlInput) urlInput.name = `paragraph_image_urls[${index}]`;

                const fileInput = card.querySelector('.para-img-file-input');
                if (fileInput) fileInput.name = `paragraph_image_files[${index}]`;
            });
        }

        function toggleQuickPasteBox(id) {
            const box = document.getElementById(id);
            if (!box) return;
            box.style.display = box.style.display === 'none' ? 'block' : 'none';
        }

        function convertQuickPasteToParagraphs(textareaId, containerId) {
            const textarea = document.getElementById(textareaId);
            const container = document.getElementById(containerId);
            if (!textarea || !container) return;
            const rawText = textarea.value.trim();
            if (!rawText) return;

            const splitParagraphs = rawText.split(/\r?\n\s*\r?\n+/).map(p => p.trim()).filter(p => p.length > 0);
            if (splitParagraphs.length === 0) return;

            const existingCards = container.querySelectorAll('.paragraph-card');
            if (existingCards.length === 1) {
                const firstTa = existingCards[0].querySelector('textarea');
                if (firstTa && firstTa.value.trim() === '') {
                    container.innerHTML = '';
                }
            }

            splitParagraphs.forEach(p => {
                addNewsParagraphRow(containerId, p);
            });

            textarea.value = '';
            toggleQuickPasteBox(textareaId.replace('Text', 'Container'));
        }

        function escapeHtml(text) {
            if (!text) return '';
            return String(text)
                .replace(/&/g, "&amp;")
                .replace(/</g, "&lt;")
                .replace(/>/g, "&gt;")
                .replace(/"/g, "&quot;")
                .replace(/'/g, "&#039;");
        }

        window.addNewsParagraphRow = addNewsParagraphRow;
        window.removeNewsParagraphRow = removeNewsParagraphRow;
        window.toggleQuickPasteBox = toggleQuickPasteBox;
        window.convertQuickPasteToParagraphs = convertQuickPasteToParagraphs;
        window.updateAddNewsLivePreview = updateAddNewsLivePreview;
        window.updateEditNewsLivePreview = updateEditNewsLivePreview;
        window.handleAddNewsCategoryChange = handleAddNewsCategoryChange;
        window.handleEditNewsCategoryChange = handleEditNewsCategoryChange;

        // Multi-Step Wizard
        window.addNewsCurrentStep = 1;
        window.editNewsCurrentStep = 1;

        function goToNewsStep(mode, targetStep) {
            targetStep = parseInt(targetStep, 10);
            if (targetStep < 1) targetStep = 1;
            if (targetStep > 3) targetStep = 3;

            const prefix = mode === 'add' ? 'addNews' : 'editNews';
            const currentStep = mode === 'add' ? (window.addNewsCurrentStep || 1) : (window.editNewsCurrentStep || 1);

            if (targetStep > currentStep) {
                if (currentStep === 1) {
                    const titleEl = document.getElementById(prefix + 'Title');
                    const dateEl = document.getElementById(prefix + 'Date');
                    if (titleEl && !titleEl.checkValidity()) {
                        titleEl.reportValidity();
                        return false;
                    }
                    if (dateEl && !dateEl.checkValidity()) {
                        dateEl.reportValidity();
                        return false;
                    }
                } else if (currentStep === 2) {
                    const descEl = document.getElementById(prefix + 'Description');
                    if (descEl && !descEl.checkValidity()) {
                        descEl.reportValidity();
                        return false;
                    }
                }
            }

            if (mode === 'add') window.addNewsCurrentStep = targetStep;
            else window.editNewsCurrentStep = targetStep;

            for (let i = 1; i <= 3; i++) {
                const pane = document.getElementById(`${prefix}StepPane${i}`);
                if (pane) {
                    if (i === targetStep) pane.classList.add('active');
                    else pane.classList.remove('active');
                }

                const stepIndicator = document.getElementById(`${prefix}StepIndicator${i}`);
                if (stepIndicator) {
                    if (i === targetStep) stepIndicator.className = 'wizard-step-item active';
                    else if (i < targetStep) stepIndicator.className = 'wizard-step-item completed';
                    else stepIndicator.className = 'wizard-step-item';
                }
            }

            const div1 = document.getElementById(`${prefix}StepDivider1`);
            if (div1) {
                if (targetStep >= 2) div1.classList.add('active');
                else div1.classList.remove('active');
            }
            const div2 = document.getElementById(`${prefix}StepDivider2`);
            if (div2) {
                if (targetStep >= 3) div2.classList.add('active');
                else div2.classList.remove('active');
            }

            const prevBtn = document.getElementById(`${prefix}StepPrevBtn`);
            const prevText = document.getElementById(`${prefix}StepPrevText`);
            const nextBtn = document.getElementById(`${prefix}StepNextBtn`);
            const nextText = document.getElementById(`${prefix}StepNextText`);

            if (prevBtn) {
                if (targetStep === 1) {
                    prevBtn.style.display = 'none';
                } else {
                    prevBtn.style.display = 'inline-block';
                    if (prevText) prevText.textContent = targetStep === 2 ? 'Back to Details' : 'Back to Summary';
                }
            }

            if (nextBtn) {
                if (targetStep === 1) {
                    nextBtn.style.display = 'inline-block';
                    if (nextText) nextText.textContent = 'Next: Summary & Media';
                } else if (targetStep === 2) {
                    nextBtn.style.display = 'inline-block';
                    if (nextText) nextText.textContent = 'Next: Story Paragraphs';
                } else {
                    nextBtn.style.display = 'none';
                }
            }

            return true;
        }

        function nextNewsStep(mode) {
            const current = mode === 'add' ? (window.addNewsCurrentStep || 1) : (window.editNewsCurrentStep || 1);
            goToNewsStep(mode, current + 1);
        }

        function prevNewsStep(mode) {
            const current = mode === 'add' ? (window.addNewsCurrentStep || 1) : (window.editNewsCurrentStep || 1);
            goToNewsStep(mode, current - 1);
        }

        function clearAddNewsCoverImage() {
            const file = document.getElementById('addNewsImageFile');
            if (file) file.value = '';
            const url = document.getElementById('addNewsImageUrl');
            if (url) url.value = '';
            const wrap = document.getElementById('addNewsCoverPreviewWrap');
            if (wrap) wrap.style.display = 'none';
            const thumb = document.getElementById('addNewsCoverPreviewThumb');
            if (thumb) thumb.src = '';
            updateAddNewsLivePreview();
        }

        window.goToNewsStep = goToNewsStep;
        window.nextNewsStep = nextNewsStep;
        window.prevNewsStep = prevNewsStep;
        window.clearAddNewsCoverImage = clearAddNewsCoverImage;

        function updateNewsFooterToggleBtn(mode, state) {
            const prefix = mode === 'add' ? 'addNews' : 'editNews';
            const footerBtn = document.getElementById(prefix + 'FooterToggleBtn');
            if (!footerBtn) return;

            if (state === 'preview') {
                footerBtn.innerHTML = '<i class="fa fa-pencil me-1"></i> Back to Edit';
                footerBtn.classList.remove('btn-outline-primary');
                footerBtn.classList.add('btn-outline-secondary');
            } else {
                footerBtn.innerHTML = '<i class="fa fa-eye me-1"></i> Preview';
                footerBtn.classList.remove('btn-outline-secondary');
                footerBtn.classList.add('btn-outline-primary');
            }
        }

        window.updateNewsFooterToggleBtn = updateNewsFooterToggleBtn;

        function toggleNewsPreviewTab(mode) {
            const prefix = mode === 'add' ? 'addNews' : 'editNews';
            const previewPane = document.getElementById(prefix + 'TabPreviewPane');
            const previewTabBtn = document.getElementById(prefix + 'TabPreviewBtn');
            const formTabBtn = document.getElementById(prefix + 'TabFormBtn');

            const isCurrentlyPreview = (previewPane && (previewPane.classList.contains('active') || previewPane.classList.contains('show'))) ||
                                       (previewTabBtn && previewTabBtn.classList.contains('active'));

            if (isCurrentlyPreview) {
                if (formTabBtn) {
                    try {
                        bootstrap.Tab.getOrCreateInstance(formTabBtn).show();
                    } catch (e) {
                        formTabBtn.click();
                    }
                }
                updateNewsFooterToggleBtn(mode, 'form');
            } else {
                if (mode === 'add') updateAddNewsLivePreview();
                else updateEditNewsLivePreview();

                if (previewTabBtn) {
                    try {
                        bootstrap.Tab.getOrCreateInstance(previewTabBtn).show();
                    } catch (e) {
                        previewTabBtn.click();
                    }
                }
                updateNewsFooterToggleBtn(mode, 'preview');
            }
        }

        window.toggleNewsPreviewTab = toggleNewsPreviewTab;

        function buildNewsPreviewHtml(title, category, author, date, description, coverImg, paragraphs, source, paragraphImages = []) {
            let catBadgeColor = 'badge-cat-other';
            const catLower = (category || '').toLowerCase();
            if (catLower.includes('ai')) catBadgeColor = 'badge-cat-ai';
            else if (catLower.includes('software') || catLower.includes('engineering') || catLower.includes('web')) catBadgeColor = 'badge-cat-se';
            else if (catLower.includes('cyber') || catLower.includes('security')) catBadgeColor = 'badge-cat-cyber';
            else if (catLower.includes('cloud')) catBadgeColor = 'badge-cat-cloud';

            let paragraphsHtml = '';
            if (paragraphs && paragraphs.length > 0) {
                paragraphs.forEach((p, i) => {
                    if (p.trim()) {
                        const paraImg = (paragraphImages && paragraphImages[i]) ? paragraphImages[i] : null;
                        if (paraImg && paraImg !== coverImg) {
                            paragraphsHtml += `
                                <div class="text-center my-3">
                                    <img src="${paraImg}" alt="Paragraph Image" class="news-preview-cover-img" style="max-height: 320px; width: auto; max-width: 100%; border-radius: 10px;">
                                </div>
                            `;
                        }
                        paragraphsHtml += `<p class="news-preview-p">${escapeHtml(p)}</p>`;
                    }
                });
            } else {
                paragraphsHtml = `<p class="news-preview-p text-muted fst-italic">No paragraph content added yet.</p>`;
            }

            let coverImgHtml = '';
            if (coverImg) {
                coverImgHtml = `<img src="${coverImg}" alt="${escapeHtml(title)}" class="news-preview-cover-img" onerror="this.style.display='none';">`;
            }

            let sourceHtml = '';
            if (source) {
                sourceHtml = `
                    <div class="text-center mt-4 pt-3 border-top">
                        <span class="text-muted small">Original Source:</span>
                        <a href="${source}" target="_blank" class="fw-semibold text-primary ms-1">
                            <i class="fa fa-external-link me-1"></i> Read external reference
                        </a>
                    </div>
                `;
            }

            return `
                <div class="news-preview-hero">
                    <span class="news-card-category-badge ${catBadgeColor} mb-2 position-static d-inline-block">
                        ${escapeHtml(category || 'General')}
                    </span>
                    <h2 class="news-preview-title mt-2">${escapeHtml(title || 'Untitled News Article')}</h2>
                    <p class="news-preview-subtitle">${escapeHtml(description || 'No summary entered yet.')}</p>
                    <div class="news-preview-meta">
                        <span><i class="fa fa-user-circle-o me-1"></i> By ${escapeHtml(author || 'GDGoC MMU Team')}</span>
                        <span>&bull;</span>
                        <span><i class="fa fa-calendar-o me-1"></i> ${date || 'Today'}</span>
                    </div>
                </div>

                <div class="news-preview-body-content">
                    ${coverImgHtml}
                    ${paragraphsHtml}
                    ${sourceHtml}
                </div>
            `;
        }

        function updateAddNewsLivePreview() {
            const title = document.getElementById('addNewsTitle')?.value || '';
            const catSelect = document.getElementById('addNewsCategorySelect');
            const catCustom = document.getElementById('addNewsCategoryInput');
            const category = (catSelect?.value === '__custom__' ? catCustom?.value : catSelect?.value) || 'AI';
            const author = document.getElementById('addNewsAuthor')?.value || '';
            const date = document.getElementById('addNewsDate')?.value || '';
            const description = document.getElementById('addNewsDescription')?.value || '';
            const source = document.getElementById('addNewsSource')?.value || '';

            let coverImg = document.getElementById('addNewsImageUrl')?.value || '';
            const fileInput = document.getElementById('addNewsImageFile');
            if (fileInput && fileInput.files && fileInput.files[0]) {
                coverImg = URL.createObjectURL(fileInput.files[0]);
            }

            const paragraphs = [];
            const paragraphImages = [];
            const container = document.getElementById('addNewsParagraphsContainer');
            if (container) {
                container.querySelectorAll('.paragraph-card').forEach(card => {
                    const ta = card.querySelector('textarea');
                    if (ta && ta.value.trim()) {
                        paragraphs.push(ta.value.trim());

                        let pImg = '';
                        const pFile = card.querySelector('.para-img-file-input');
                        const pUrl = card.querySelector('.para-img-url-input');
                        const pExisting = card.querySelector('.para-existing-img-input');

                        if (pFile && pFile.files && pFile.files[0]) {
                            pImg = URL.createObjectURL(pFile.files[0]);
                        } else if (pUrl && pUrl.value.trim()) {
                            pImg = pUrl.value.trim();
                        } else if (pExisting && pExisting.value.trim()) {
                            pImg = pExisting.value.trim();
                        }
                        paragraphImages.push(pImg);
                    }
                });
            }

            if (!coverImg && paragraphImages.length > 0 && paragraphImages[0]) {
                coverImg = paragraphImages[0];
            }

            const target = document.getElementById('addNewsLivePreviewContent');
            if (target) {
                target.innerHTML = buildNewsPreviewHtml(title, category, author, date, description, coverImg, paragraphs, source, paragraphImages);
            }
        }

        function updateEditNewsLivePreview() {
            const title = document.getElementById('editNewsTitle')?.value || '';
            const catSelect = document.getElementById('editNewsCategorySelect');
            const catCustom = document.getElementById('editNewsCategoryInput');
            const category = (catSelect?.value === '__custom__' ? catCustom?.value : catSelect?.value) || 'AI';
            const author = document.getElementById('editNewsAuthor')?.value || '';
            const date = document.getElementById('editNewsDate')?.value || '';
            const description = document.getElementById('editNewsDescription')?.value || '';
            const source = document.getElementById('editNewsSource')?.value || '';

            let coverImg = document.getElementById('editNewsImageUrl')?.value || '';
            const fileInput = document.getElementById('editNewsImageFile');
            if (fileInput && fileInput.files && fileInput.files[0]) {
                coverImg = URL.createObjectURL(fileInput.files[0]);
            } else if (!coverImg) {
                const currentImg = document.getElementById('editNewsCurrentImgPreview');
                if (currentImg && currentImg.src) {
                    coverImg = currentImg.src;
                }
            }

            const paragraphs = [];
            const paragraphImages = [];
            const container = document.getElementById('editNewsParagraphsContainer');
            if (container) {
                container.querySelectorAll('.paragraph-card').forEach(card => {
                    const ta = card.querySelector('textarea');
                    if (ta && ta.value.trim()) {
                        paragraphs.push(ta.value.trim());

                        let pImg = '';
                        const pFile = card.querySelector('.para-img-file-input');
                        const pUrl = card.querySelector('.para-img-url-input');
                        const pExisting = card.querySelector('.para-existing-img-input');

                        if (pFile && pFile.files && pFile.files[0]) {
                            pImg = URL.createObjectURL(pFile.files[0]);
                        } else if (pUrl && pUrl.value.trim()) {
                            pImg = pUrl.value.trim();
                        } else if (pExisting && pExisting.value.trim()) {
                            pImg = pExisting.value.trim();
                        }
                        paragraphImages.push(pImg);
                    }
                });
            }

            if (!coverImg && paragraphImages.length > 0 && paragraphImages[0]) {
                coverImg = paragraphImages[0];
            }

            const target = document.getElementById('editNewsLivePreviewContent');
            if (target) {
                target.innerHTML = buildNewsPreviewHtml(title, category, author, date, description, coverImg, paragraphs, source, paragraphImages);
            }
        }

        // Global Event Handlers for Preview & Edit Buttons
        document.addEventListener('DOMContentLoaded', function () {
            // Delegated click handler for add paragraph buttons
            document.addEventListener('click', function(e) {
                const addParaBtn = e.target.closest('[data-action="add-news-paragraph"]');
                if (addParaBtn) {
                    e.preventDefault();
                    e.stopPropagation();
                    const targetContainer = addParaBtn.getAttribute('data-target-container') || 'addNewsParagraphsContainer';
                    addNewsParagraphRow(targetContainer);
                }
            });

            // Tab change listeners to keep footer toggle button synchronized
            ['addNews', 'editNews'].forEach(prefix => {
                const mode = prefix === 'addNews' ? 'add' : 'edit';
                const previewBtn = document.getElementById(prefix + 'TabPreviewBtn');
                const formBtn = document.getElementById(prefix + 'TabFormBtn');

                if (previewBtn) {
                    previewBtn.addEventListener('shown.bs.tab', function () {
                        updateNewsFooterToggleBtn(mode, 'preview');
                    });
                }

                if (formBtn) {
                    formBtn.addEventListener('shown.bs.tab', function () {
                        updateNewsFooterToggleBtn(mode, 'form');
                    });
                }
            });

            // Cover Image Preview Listeners for Add News
            const addCoverUrlInput = document.getElementById('addNewsImageUrl');
            if (addCoverUrlInput) {
                addCoverUrlInput.addEventListener('input', function() {
                    const val = this.value.trim();
                    const wrap = document.getElementById('addNewsCoverPreviewWrap');
                    const thumb = document.getElementById('addNewsCoverPreviewThumb');
                    const text = document.getElementById('addNewsCoverPreviewText');
                    if (val) {
                        if (thumb) thumb.src = val;
                        if (wrap) wrap.style.display = 'block';
                        if (text) text.textContent = 'Linked external image URL';
                    } else {
                        const fileIn = document.getElementById('addNewsImageFile');
                        if (!fileIn || !fileIn.files || !fileIn.files.length) {
                            if (wrap) wrap.style.display = 'none';
                        }
                    }
                    updateAddNewsLivePreview();
                });
            }

            const addCoverFileInput = document.getElementById('addNewsImageFile');
            if (addCoverFileInput) {
                addCoverFileInput.addEventListener('change', function() {
                    const wrap = document.getElementById('addNewsCoverPreviewWrap');
                    const thumb = document.getElementById('addNewsCoverPreviewThumb');
                    const text = document.getElementById('addNewsCoverPreviewText');
                    if (this.files && this.files[0]) {
                        if (thumb) thumb.src = URL.createObjectURL(this.files[0]);
                        if (wrap) wrap.style.display = 'block';
                        if (text) text.textContent = 'Selected file: ' + this.files[0].name;
                    }
                    updateAddNewsLivePreview();
                });
            }

            // Ensure Add News Modal starts on Form tab and step 1
            const addNewsModalEl = document.getElementById('addNewsModal');
            if (addNewsModalEl) {
                addNewsModalEl.addEventListener('show.bs.modal', function() {
                    updateNewsFooterToggleBtn('add', 'form');
                    const formBtn = document.getElementById('addNewsTabFormBtn');
                    if (formBtn) {
                        try {
                            bootstrap.Tab.getOrCreateInstance(formBtn).show();
                        } catch (e) {
                            formBtn.click();
                        }
                    }

                    goToNewsStep('add', 1);
                    const container = document.getElementById('addNewsParagraphsContainer');
                    if (container && container.querySelectorAll('.paragraph-card').length === 0) {
                        addNewsParagraphRow('addNewsParagraphsContainer');
                    }
                });
            }

            const editNewsModalEl = document.getElementById('editNewsModal');
            if (editNewsModalEl) {
                editNewsModalEl.addEventListener('show.bs.modal', function() {
                    updateNewsFooterToggleBtn('edit', 'form');
                    goToNewsStep('edit', 1);
                    const formBtn = document.getElementById('editNewsTabFormBtn');
                    if (formBtn) {
                        try {
                            bootstrap.Tab.getOrCreateInstance(formBtn).show();
                        } catch (e) {
                            formBtn.click();
                        }
                    }
                });
            }

            // Preview News Buttons Click
            document.querySelectorAll('.btn-trigger-preview-news').forEach(btn => {
                btn.addEventListener('click', function () {
                    const newsId = this.getAttribute('data-news-id');
                    if (!newsId) return;

                    const modalBody = document.getElementById('previewNewsModalBody');
                    if (modalBody) {
                        modalBody.innerHTML = `
                            <div class="text-center py-5">
                                <div class="spinner-border text-primary" role="status"></div>
                                <p class="mt-2 text-muted">Loading article preview...</p>
                            </div>
                        `;
                    }

                    const previewModal = new bootstrap.Modal(document.getElementById('previewNewsModal'));
                    previewModal.show();

                    fetch(`/admin/news/${newsId}/json`)
                        .then(res => res.json())
                        .then(data => {
                            if (data.success && data.news) {
                                const n = data.news;
                                const publicLink = document.getElementById('previewNewsOpenPublicLink');
                                if (publicLink) publicLink.href = `/news/${n.id}`;

                                const editBtn = document.getElementById('previewNewsEditButton');
                                if (editBtn) {
                                    editBtn.onclick = function () {
                                        previewModal.hide();
                                        setTimeout(() => {
                                            const targetEditBtn = document.querySelector(`.btn-trigger-edit-news[data-news-id="${n.id}"]`);
                                            if (targetEditBtn) targetEditBtn.click();
                                        }, 350);
                                    };
                                }

                                if (modalBody) {
                                    modalBody.innerHTML = buildNewsPreviewHtml(
                                        n.title,
                                        n.category,
                                        n.author,
                                        n.date_formatted,
                                        n.description,
                                        n.cover_img,
                                        n.paragraph,
                                        n.news_source,
                                        n.news_img
                                    );
                                }
                            }
                        })
                        .catch(err => {
                            if (modalBody) {
                                modalBody.innerHTML = `<div class="alert alert-danger m-4">Failed to load article preview.</div>`;
                            }
                        });
                });
            });

            // Edit News Buttons Click
            document.querySelectorAll('.btn-trigger-edit-news').forEach(btn => {
                btn.addEventListener('click', function () {
                    const newsId = this.getAttribute('data-news-id');
                    if (!newsId) return;

                    const form = document.getElementById('editNewsForm');
                    if (form) form.action = `/admin/news/${newsId}`;

                    fetch(`/admin/news/${newsId}/json`)
                        .then(res => res.json())
                        .then(data => {
                            if (data.success && data.news) {
                                const n = data.news;
                                document.getElementById('editNewsTitle').value = n.title || '';
                                document.getElementById('editNewsAuthor').value = n.author || '';
                                document.getElementById('editNewsDate').value = n.date || '';
                                document.getElementById('editNewsDescription').value = n.description || '';
                                document.getElementById('editNewsSource').value = n.news_source || '';

                                // Category select vs custom
                                const catSelect = document.getElementById('editNewsCategorySelect');
                                const catInput = document.getElementById('editNewsCategoryInput');
                                let matched = false;
                                for (let i = 0; i < catSelect.options.length; i++) {
                                    if (catSelect.options[i].value.toLowerCase() === (n.category || '').toLowerCase()) {
                                        catSelect.selectedIndex = i;
                                        matched = true;
                                        break;
                                    }
                                }
                                if (!matched) {
                                    catSelect.value = '__custom__';
                                    catInput.style.display = 'block';
                                    catInput.value = n.category || '';
                                } else {
                                    catInput.style.display = 'none';
                                    catInput.value = catSelect.value;
                                }

                                // Cover Image preview
                                const currentImgBox = document.getElementById('editNewsCurrentImgContainer');
                                const currentImgEl = document.getElementById('editNewsCurrentImgPreview');
                                const currentImgText = document.getElementById('editNewsCurrentImgText');
                                if (n.cover_img) {
                                    currentImgBox.style.display = 'block';
                                    currentImgEl.src = n.cover_img;
                                    currentImgText.textContent = n.news_img && n.news_img[0] ? n.news_img[0] : '';
                                } else {
                                    currentImgBox.style.display = 'none';
                                }

                                document.getElementById('editNewsImageUrl').value = '';
                                const editFileInput = document.getElementById('editNewsImageFile');
                                if (editFileInput) editFileInput.value = '';

                                // Build paragraphs container
                                const container = document.getElementById('editNewsParagraphsContainer');
                                container.innerHTML = '';
                                if (n.paragraph && n.paragraph.length > 0) {
                                    n.paragraph.forEach((p, idx) => {
                                        const pImg = (n.news_img && n.news_img[idx]) ? n.news_img[idx] : '';
                                        addNewsParagraphRow('editNewsParagraphsContainer', p, pImg);
                                    });
                                } else {
                                    addNewsParagraphRow('editNewsParagraphsContainer', n.description || '', n.cover_img || '');
                                }

                                goToNewsStep('edit', 1);
                                const formTabBtn = document.getElementById('editNewsTabFormBtn');
                                if (formTabBtn) new bootstrap.Tab(formTabBtn).show();

                                const editModal = new bootstrap.Modal(document.getElementById('editNewsModal'));
                                editModal.show();
                            }
                        })
                        .catch(err => {
                            alert('Could not fetch article details for editing.');
                        });
                });
            });

            // Live Search Filter for News Cards
            const newsSearch = document.getElementById('newsSearchInput');
            if (newsSearch) {
                newsSearch.addEventListener('input', function () {
                    filterNewsArticles();
                });
            }

            // Category Filter Pills
            const filterPills = document.querySelectorAll('#newsFilterPills .news-filter-pill-btn');
            filterPills.forEach(pill => {
                pill.addEventListener('click', function () {
                    filterPills.forEach(p => p.classList.remove('active'));
                    this.classList.add('active');
                    filterNewsArticles();
                });
            });

            function filterNewsArticles() {
                const query = (document.getElementById('newsSearchInput')?.value || '').toLowerCase().trim();
                const activePill = document.querySelector('#newsFilterPills .news-filter-pill-btn.active');
                const categoryFilter = activePill ? activePill.getAttribute('data-news-filter') : 'all';

                const cards = document.querySelectorAll('.news-card-item');
                let visibleCount = 0;

                cards.forEach(card => {
                    const title = card.getAttribute('data-news-title') || '';
                    const author = card.getAttribute('data-news-author') || '';
                    const desc = card.getAttribute('data-news-desc') || '';
                    const category = card.getAttribute('data-news-category') || '';

                    const matchesSearch = query === '' || title.includes(query) || author.includes(query) || desc.includes(query);
                    let matchesCategory = false;
                    if (categoryFilter === 'all') {
                        matchesCategory = true;
                    } else if (categoryFilter === 'other') {
                        matchesCategory = !category.includes('ai') && !category.includes('software') && !category.includes('cyber') && !category.includes('cloud');
                    } else {
                        matchesCategory = category.includes(categoryFilter);
                    }

                    if (matchesSearch && matchesCategory) {
                        card.style.display = '';
                        visibleCount++;
                    } else {
                        card.style.display = 'none';
                    }
                });

                const badge = document.getElementById('newsArticlesCountBadge');
                if (badge) {
                    badge.textContent = `${visibleCount} News ${visibleCount === 1 ? 'Story' : 'Stories'} Displayed`;
                }
            }

            // Auto-open Add News modal if ?action=create in URL
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('action') === 'create') {
                const addModal = new bootstrap.Modal(document.getElementById('addNewsModal'));
                addModal.show();
            }
        });
    </script>
</x-layout>

