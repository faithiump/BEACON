<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Event - BEACON</title>
    <link rel="icon" type="image/png" href="<?= base_url('assets/images/beacon-logo-v4.png') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/admin/dashboard.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/organization/sidebar.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/organization/topbar.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/organization/organization.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/organization/overview.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/organization/edit_event.css') ?>">
</head>
<body>
    <div class="dashboard-container">
        <?= view('organization/partials/sidebar') ?>
        <div class="dashboard-wrapper">
            <?= view('organization/partials/topbar') ?>
            <main class="dashboard-main">
                <div class="edit-event-container">
                    <div class="edit-event-header">
                        <h1 class="edit-event-title">Edit Event</h1>
                        <a href="<?= base_url('organization/events') ?>" class="edit-event-back-btn">
                            <i class="fas fa-arrow-left"></i>
                            Back to Events
                        </a>
                    </div>

                    <form action="<?= base_url('organization/events/update/' . $event['event_id']) ?>" method="post" enctype="multipart/form-data" class="edit-event-form-card">
                        <?= csrf_field() ?>

                        <div class="edit-event-form-body">
                            <div class="form-sections">
                                <!-- Basic Information Section -->
                                <div class="form-section">
                                    <h3 class="form-section-title">Basic Information</h3>
                                    <div class="form-grid-enhanced">
                                        <div class="form-field full-width">
                                            <label class="form-label form-required">Event Title</label>
                                            <input type="text" name="title" class="form-input"
                                                   value="<?= esc($event['event_name'] ?? $event['title'] ?? '') ?>" required>
                                        </div>

                                        <div class="form-field half-width">
                                            <label class="form-label form-required">Date</label>
                                            <input type="date" name="date" class="form-input"
                                                   value="<?= esc($event['date'] ?? '') ?>" required>
                                        </div>

                                        <div class="form-field half-width">
                                            <label class="form-label form-required">Time</label>
                                            <input type="time" name="time" class="form-input"
                                                   value="<?= esc($event['time'] ?? '') ?>" required>
                                        </div>

                                        <div class="form-field half-width">
                                            <label class="form-label">End Date</label>
                                            <input type="date" name="end_date" class="form-input"
                                                   value="<?= esc($event['end_date'] ?? '') ?>">
                                            <span class="form-help-text">Optional - leave empty for single-day events</span>
                                        </div>

                                        <div class="form-field half-width">
                                            <label class="form-label">End Time</label>
                                            <input type="time" name="end_time" class="form-input"
                                                   value="<?= esc($event['end_time'] ?? '') ?>">
                                        </div>

                                        <div class="form-field full-width">
                                            <label class="form-label form-required">Location/Venue</label>
                                            <input type="text" name="location" class="form-input"
                                                   value="<?= esc($event['venue'] ?? $event['location'] ?? '') ?>" required
                                                   placeholder="e.g., Main Auditorium, Online, etc.">
                                        </div>

                                        <div class="form-field full-width">
                                            <label class="form-label form-required">Description</label>
                                            <textarea name="description" class="form-textarea" rows="4" required
                                                      placeholder="Describe your event..."><?= esc($event['description'] ?? '') ?></textarea>
                                        </div>
                                    </div>
                                </div>

                                <!-- Audience Settings Section -->
                                <div class="form-section">
                                    <h3 class="form-section-title">Audience Settings</h3>
                                    <div class="form-grid-enhanced">
                                        <div class="form-field half-width">
                                            <label class="form-label">Target Audience</label>
                                            <select name="audience_type" class="form-select">
                                                <option value="all" <?= ($event['audience_type'] ?? 'all') === 'all' ? 'selected' : '' ?>>All Students</option>
                                                <option value="department" <?= ($event['audience_type'] ?? '') === 'department' ? 'selected' : '' ?>>Specific Department</option>
                                                <option value="specific_students" <?= ($event['audience_type'] ?? '') === 'specific_students' ? 'selected' : '' ?>>Specific Students</option>
                                            </select>
                                        </div>

                                        <div class="form-field half-width">
                                            <label class="form-label">Department Access</label>
                                            <select name="department_access" class="form-select">
                                                <option value="">All Departments</option>
                                                <option value="ccs" <?= ($event['department_access'] ?? '') === 'ccs' ? 'selected' : '' ?>>College of Computer Studies (CCS)</option>
                                                <option value="cea" <?= ($event['department_access'] ?? '') === 'cea' ? 'selected' : '' ?>>College of Engineering and Architecture (CEA)</option>
                                                <option value="cthbm" <?= ($event['department_access'] ?? '') === 'cthbm' ? 'selected' : '' ?>>College of Tourism and Hospitality Management (CTHBM)</option>
                                                <option value="chs" <?= ($event['department_access'] ?? '') === 'chs' ? 'selected' : '' ?>>College of Health Sciences (CHS)</option>
                                                <option value="ctde" <?= ($event['department_access'] ?? '') === 'ctde' ? 'selected' : '' ?>>College of Teacher Education (CTE)</option>
                                                <option value="cas" <?= ($event['department_access'] ?? '') === 'cas' ? 'selected' : '' ?>>College of Arts and Sciences (CAS)</option>
                                                <option value="gs" <?= ($event['department_access'] ?? '') === 'gs' ? 'selected' : '' ?>>Graduate School (GS)</option>
                                            </select>
                                            <span class="form-help-text">Only applies when "Specific Department" is selected above</span>
                                        </div>

                                        <div class="form-field half-width">
                                            <label class="form-label">Max Attendees</label>
                                            <input type="number" name="max_attendees" class="form-input"
                                                   value="<?= esc($event['max_attendees'] ?? '') ?>" min="0"
                                                   placeholder="Unlimited">
                                            <span class="form-help-text">Leave empty for unlimited attendees</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Image Section -->
                                <div class="form-section">
                                    <h3 class="form-section-title">Event Image</h3>
                                    <div class="image-preview-section">
                                        <?php if (!empty($event['image'])): ?>
                                            <?php
                                            $imageUrl = (stripos($event['image'], 'http') === 0)
                                                ? $event['image']
                                                : base_url($event['image']);
                                            ?>
                                            <div class="current-image-preview">
                                                <h4 style="margin-bottom: 12px; color: #374151;">Current Image</h4>
                                                <img src="<?= esc($imageUrl) ?>" alt="Current event image">
                                            </div>
                                            <p style="color: #6b7280; margin: 16px 0;">
                                                Leave the upload field empty below to keep the current image, or upload a new one to replace it.
                                            </p>
                                        <?php else: ?>
                                            <p style="color: #6b7280; margin-bottom: 16px;">
                                                No image uploaded yet. Upload one below to add visual appeal to your event.
                                            </p>
                                        <?php endif; ?>

                                        <div class="image-upload-area">
                                            <input type="file" name="image" accept="image/*" class="image-upload-input">
                                            <span class="form-help-text">Supported formats: JPG, PNG, GIF. Max size: 5MB</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Form Actions -->
                            <div class="form-actions">
                                <a href="<?= base_url('organization/events') ?>" class="form-cancel-btn">Cancel</a>
                                <button type="submit" class="form-submit-btn">
                                    <i class="fas fa-save" style="margin-right: 8px;"></i>
                                    Update Event
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </main>
        </div>
    </div>

    <script>
    // Enhanced form functionality
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');
        const audienceType = document.querySelector('select[name="audience_type"]');
        const departmentAccess = document.querySelector('select[name="department_access"]');
        const departmentField = departmentAccess.closest('.form-field');

        // Handle audience type changes with smooth transitions
        function toggleDepartmentAccess() {
            const selectedValue = audienceType.value;

            if (selectedValue === 'department' || selectedValue === 'specific_students') {
                departmentField.style.display = 'block';
                departmentField.style.opacity = '0';
                setTimeout(() => {
                    departmentField.style.opacity = '1';
                    departmentField.style.transform = 'translateY(0)';
                }, 10);
            } else {
                departmentField.style.opacity = '0';
                departmentField.style.transform = 'translateY(-10px)';
                setTimeout(() => {
                    departmentField.style.display = 'none';
                    departmentAccess.value = '';
                }, 200);
            }
        }

        // Initialize department access visibility
        departmentField.style.transition = 'all 0.2s ease';
        departmentField.style.transform = 'translateY(-10px)';
        toggleDepartmentAccess();

        audienceType.addEventListener('change', toggleDepartmentAccess);

        // Enhanced form validation with better UX
        form.addEventListener('submit', function(e) {
            // Clear previous errors
            document.querySelectorAll('.form-field.error').forEach(field => {
                field.classList.remove('error');
            });

            const title = form.querySelector('input[name="title"]').value.trim();
            const date = form.querySelector('input[name="date"]').value;
            const time = form.querySelector('input[name="time"]').value;
            const location = form.querySelector('input[name="location"]').value.trim();
            const description = form.querySelector('textarea[name="description"]').value.trim();

            let hasErrors = false;
            const errors = [];

            if (!title) {
                errors.push('Event title is required');
                form.querySelector('input[name="title"]').closest('.form-field').classList.add('error');
                hasErrors = true;
            }

            if (!date) {
                errors.push('Event date is required');
                form.querySelector('input[name="date"]').closest('.form-field').classList.add('error');
                hasErrors = true;
            }

            if (!time) {
                errors.push('Event time is required');
                form.querySelector('input[name="time"]').closest('.form-field').classList.add('error');
                hasErrors = true;
            }

            if (!location) {
                errors.push('Event location is required');
                form.querySelector('input[name="location"]').closest('.form-field').classList.add('error');
                hasErrors = true;
            }

            if (!description) {
                errors.push('Event description is required');
                form.querySelector('textarea[name="description"]').closest('.form-field').classList.add('error');
                hasErrors = true;
            }

            if (hasErrors) {
                e.preventDefault();
                alert('Please fix the following errors:\n\n' + errors.join('\n'));
                return false;
            }

            // Show loading state
            const submitBtn = form.querySelector('.form-submit-btn');
            const originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<div class="loading-spinner" style="display: inline-block; margin-right: 8px;"></div> Updating Event...';

            // Re-enable button after 10 seconds as fallback
            setTimeout(() => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }, 10000);
        });

        // Image preview functionality
        const imageInput = document.querySelector('input[name="image"]');
        const currentImage = document.querySelector('.current-image-preview img');

        imageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                // Validate file type
                if (!file.type.match('image.*')) {
                    alert('Please select a valid image file.');
                    this.value = '';
                    return;
                }

                // Validate file size (5MB limit)
                if (file.size > 5 * 1024 * 1024) {
                    alert('File size must be less than 5MB.');
                    this.value = '';
                    return;
                }

                // Preview new image
                const reader = new FileReader();
                reader.onload = function(e) {
                    if (currentImage) {
                        currentImage.src = e.target.result;
                    }
                };
                reader.readAsDataURL(file);
            }
        });

        // Add visual feedback for required fields
        document.querySelectorAll('input[required], textarea[required], select[required]').forEach(field => {
            field.addEventListener('blur', function() {
                if (this.value.trim() === '') {
                    this.closest('.form-field').classList.add('error');
                } else {
                    this.closest('.form-field').classList.remove('error');
                }
            });

            field.addEventListener('input', function() {
                if (this.value.trim() !== '') {
                    this.closest('.form-field').classList.remove('error');
                }
            });
        });
    });
    </script>
</body>
</html>
