<x-layout>
    <link rel="stylesheet" href="{{asset('css/events.css')}}">
    
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="event-form-card">
                    <div class="form-header">
                        <h2><i class="fa fa-plus-circle"></i> Create New Event</h2>
                        <p class="text-muted">Fill in the details below to create a new event for the GDGoC community</p>
                    </div>

                    <form action="/events" method="POST" enctype="multipart/form-data" class="event-form">
                        @csrf
                        
                        <!-- Event Image Upload -->
                        <div class="mb-4">
                            <label for="image" class="form-label">
                                <i class="fa fa-image"></i> Event Image
                            </label>
                            <div class="image-upload-container">
                                <div class="image-preview" id="imagePreview">
                                    <i class="fa fa-cloud-upload fa-3x"></i>
                                    <p>Click to upload or drag and drop</p>
                                    <small class="text-muted">PNG, JPG, GIF up to 10MB</small>
                                </div>
                                <input type="file" class="form-control d-none" id="image" name="image" accept="image/*">
                            </div>
                        </div>

                        <!-- Event Title -->
                        <div class="mb-3">
                            <label for="title" class="form-label">
                                <i class="fa fa-edit"></i> Event Title
                            </label>
                            <input type="text" class="form-control" id="title" name="title" 
                                   placeholder="Enter event title..." required>
                        </div>

                        <!-- Event Description -->
                        <div class="mb-3">
                            <label for="description" class="form-label">
                                <i class="fa fa-align-left"></i> Event Description
                            </label>
                            <textarea class="form-control" id="description" name="description" rows="5" 
                                      placeholder="Describe your event..." required></textarea>
                        </div>

                        <!-- Date and Time Row -->
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="date" class="form-label">
                                    <i class="fa fa-calendar"></i> Event Date
                                </label>
                                <input type="date" class="form-control" id="date" name="date" required>
                            </div>
                            <div class="col-md-4">
                                <label for="start_time" class="form-label">
                                    <i class="fa fa-clock-o"></i> Start Time
                                </label>
                                <input type="time" class="form-control" id="start_time" name="start_time" required>
                            </div>
                            <div class="col-md-4">
                                <label for="end_time" class="form-label">
                                    <i class="fa fa-clock-o"></i> End Time
                                </label>
                                <input type="time" class="form-control" id="end_time" name="end_time">
                            </div>
                        </div>

                        <!-- Location and Event Type Row -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="location" class="form-label">
                                    <i class="fa fa-map-marker"></i> Location
                                </label>
                                <input type="text" class="form-control" id="location" name="location" 
                                       placeholder="Event venue or online link...">
                            </div>
                            <div class="col-md-6">
                                <label for="event_type" class="form-label">
                                    <i class="fa fa-tag"></i> Event Type
                                </label>
                                <select class="form-control" id="event_type" name="event_type">
                                    <option value="">Select event type...</option>
                                    <option value="Workshop">Workshop</option>
                                    <option value="Seminar">Seminar</option>
                                    <option value="Competition">Competition</option>
                                    <option value="Networking">Networking</option>
                                    <option value="Hackathon">Hackathon</option>
                                    <option value="Study Group">Study Group</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                        </div>

                        <!-- Additional Details Row -->
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="organizer" class="form-label">
                                    <i class="fa fa-user"></i> Organizer
                                </label>
                                <input type="text" class="form-control" id="organizer" name="organizer" 
                                       placeholder="Organizer name...">
                            </div>
                            <div class="col-md-4">
                                <label for="max_participants" class="form-label">
                                    <i class="fa fa-users"></i> Max Participants
                                </label>
                                <input type="number" class="form-control" id="max_participants" name="max_participants" 
                                       placeholder="Leave empty for unlimited">
                            </div>
                            <div class="col-md-4">
                                <label for="category" class="form-label">
                                    <i class="fa fa-folder"></i> Category
                                </label>
                                <input type="text" class="form-control" id="category" name="category" 
                                       placeholder="e.g., Technology, AI, Web Dev...">
                            </div>
                        </div>

                        <!-- Registration Settings -->
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="registration_required" name="registration_required" value="1">
                                <label class="form-check-label" for="registration_required">
                                    <i class="fa fa-check-circle"></i> Registration Required
                                </label>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fa fa-save"></i> Create Event
                            </button>
                            <a href="/events" class="btn btn-secondary btn-lg">
                                <i class="fa fa-times"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Image upload preview
        document.getElementById('image').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const preview = document.getElementById('imagePreview');
            
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.innerHTML = `<img src="${e.target.result}" alt="Preview" class="preview-image">`;
                };
                reader.readAsDataURL(file);
            }
        });

        // Click to upload
        document.getElementById('imagePreview').addEventListener('click', function() {
            document.getElementById('image').click();
        });

        // Drag and drop functionality
        const imagePreview = document.getElementById('imagePreview');
        
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            imagePreview.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            imagePreview.addEventListener(eventName, highlight, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            imagePreview.addEventListener(eventName, unhighlight, false);
        });

        function highlight(e) {
            imagePreview.classList.add('drag-over');
        }

        function unhighlight(e) {
            imagePreview.classList.remove('drag-over');
        }

        imagePreview.addEventListener('drop', handleDrop, false);

        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            
            if (files.length > 0) {
                document.getElementById('image').files = files;
                const event = new Event('change', { bubbles: true });
                document.getElementById('image').dispatchEvent(event);
            }
        }
    </script>
</x-layout>
