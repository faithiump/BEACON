<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Launch Organization - BEACON | CSPC</title>
    <link rel="icon" type="image/png" href="<?= base_url('assets/images/beacon-logo-v2.png') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/nav.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/launch-org.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/footer.css') ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="stars-container">
        <!-- Flares -->
        <div class="flare blue" style="width: 200px; height: 200px; top: 10%; left: 5%; animation-delay: 0s;"></div>
        <div class="flare purple" style="width: 250px; height: 250px; top: 60%; right: 8%; animation-delay: 3s;"></div>
        <div class="flare yellow" style="width: 180px; height: 180px; bottom: 15%; left: 15%; animation-delay: 6s;"></div>
        <div class="flare blue" style="width: 220px; height: 220px; top: 30%; right: 20%; animation-delay: 9s;"></div>
        
        <!-- Stars -->
        <div class="star small" style="top: 15%; left: 10%; animation-delay: 0s;"></div>
        <div class="star medium" style="top: 20%; left: 25%; animation-delay: 0.5s;"></div>
        <div class="star small" style="top: 12%; left: 40%; animation-delay: 1s;"></div>
        <div class="star large" style="top: 25%; left: 55%; animation-delay: 1.5s;"></div>
        <div class="star medium" style="top: 18%; left: 70%; animation-delay: 2s;"></div>
        <div class="star small" style="top: 22%; left: 85%; animation-delay: 2.5s;"></div>
        
        <div class="star medium" style="top: 35%; left: 8%; animation-delay: 0.3s;"></div>
        <div class="star small" style="top: 40%; left: 22%; animation-delay: 0.8s;"></div>
        <div class="star large" style="top: 38%; left: 38%; animation-delay: 1.3s;"></div>
        <div class="star small" style="top: 42%; left: 52%; animation-delay: 1.8s;"></div>
        <div class="star medium" style="top: 36%; left: 68%; animation-delay: 2.3s;"></div>
        <div class="star small" style="top: 39%; left: 82%; animation-delay: 2.8s;"></div>
        
        <div class="star small" style="top: 55%; left: 12%; animation-delay: 0.2s;"></div>
        <div class="star medium" style="top: 58%; left: 28%; animation-delay: 0.7s;"></div>
        <div class="star large" style="top: 56%; left: 45%; animation-delay: 1.2s;"></div>
        <div class="star small" style="top: 60%; left: 60%; animation-delay: 1.7s;"></div>
        <div class="star medium" style="top: 57%; left: 75%; animation-delay: 2.2s;"></div>
        <div class="star small" style="top: 59%; left: 90%; animation-delay: 2.7s;"></div>
        
        <div class="star medium" style="top: 72%; left: 5%; animation-delay: 0.4s;"></div>
        <div class="star small" style="top: 75%; left: 18%; animation-delay: 0.9s;"></div>
        <div class="star large" style="top: 73%; left: 32%; animation-delay: 1.4s;"></div>
        <div class="star small" style="top: 77%; left: 48%; animation-delay: 1.9s;"></div>
        <div class="star medium" style="top: 74%; left: 63%; animation-delay: 2.4s;"></div>
        <div class="star small" style="top: 76%; left: 78%; animation-delay: 2.9s;"></div>
        
        <div class="star small" style="top: 88%; left: 15%; animation-delay: 0.6s;"></div>
        <div class="star medium" style="top: 91%; left: 30%; animation-delay: 1.1s;"></div>
        <div class="star small" style="top: 89%; left: 50%; animation-delay: 1.6s;"></div>
        <div class="star large" style="top: 93%; left: 65%; animation-delay: 2.1s;"></div>
        <div class="star small" style="top: 90%; left: 80%; animation-delay: 2.6s;"></div>
    </div>
    
    <!-- Navigation Panel -->
    <?= view('components/nav', ['active' => 'launch']) ?>

    <div class="launch-container">
        <div class="launch-form-container">
            <div class="launch-form-card">
                <h1 class="launch-title">Launch Your Organization</h1>
                <p class="launch-subtitle">Submit your organization application for review and approval by the administration.</p>
                
                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success">
                        <?= session()->getFlashdata('success') ?>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('errors')): ?>
                    <div class="alert alert-error">
                        <ul>
                            <?php foreach (session()->getFlashdata('errors') as $error): ?>
                                <li><?= $error ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form action="<?= base_url('organization/launch') ?>" method="POST" enctype="multipart/form-data" class="launch-form">
                    <!-- Organization Information Section -->
                    <div class="form-section">
                        <h3 class="section-title">Organization Information</h3>
                        
                        <div class="form-group">
                            <label for="organization_name">Organization Name <span class="required">*</span></label>
                            <input type="text" name="organization_name" id="organization_name" class="form-control" placeholder="Enter the full name of your organization" required>
                        </div>

                        <div class="form-group">
                            <label for="organization_acronym">Organization Acronym <span class="required">*</span></label>
                            <input type="text" name="organization_acronym" id="organization_acronym" class="form-control" placeholder="e.g., CSS, ITC, etc." required maxlength="20">
                        </div>

                        <div class="form-row">
                            <div class="form-group form-group-half">
                                <label for="organization_type">Organization Type <span class="required">*</span></label>
                                <div class="select-wrapper">
                                    <select name="organization_type" id="organization_type" class="form-control" required>
                                        <option value="">Select type</option>
                                        <option value="academic">Academic</option>
                                        <option value="non_academic">Non-Academic</option>
                                        <option value="service">Service</option>
                                        <option value="religious">Religious</option>
                                        <option value="cultural">Cultural</option>
                                        <option value="sports">Sports</option>
                                        <option value="other">Other</option>
                                    </select>
                                    <svg class="select-arrow" width="12" height="8" viewBox="0 0 12 8" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M1 1L6 6L11 1" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </div>
                            </div>

                            <div class="form-group form-group-half">
                                <label for="organization_category">Organization Category <span class="required">*</span></label>
                                <div class="select-wrapper">
                                    <select name="organization_category" id="organization_category" class="form-control" required>
                                        <option value="">Select category</option>
                                        <option value="departmental">Departmental</option>
                                        <option value="inter_departmental">Inter-Departmental</option>
                                        <option value="university_wide">University-Wide</option>
                                    </select>
                                    <svg class="select-arrow" width="12" height="8" viewBox="0 0 12 8" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M1 1L6 6L11 1" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="founding_date">Founding Date <span class="required">*</span></label>
                            <input type="date" name="founding_date" id="founding_date" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="mission">Mission Statement <span class="required">*</span></label>
                            <textarea name="mission" id="mission" class="form-control" rows="4" placeholder="Describe your organization's mission (50-1000 characters)" required minlength="50" maxlength="1000"></textarea>
                            <small class="form-hint">Minimum 50 characters required</small>
                        </div>

                        <div class="form-group">
                            <label for="vision">Vision Statement <span class="required">*</span></label>
                            <textarea name="vision" id="vision" class="form-control" rows="4" placeholder="Describe your organization's vision (50-1000 characters)" required minlength="50" maxlength="1000"></textarea>
                            <small class="form-hint">Minimum 50 characters required</small>
                        </div>

                        <div class="form-group">
                            <label for="objectives">Objectives <span class="required">*</span></label>
                            <textarea name="objectives" id="objectives" class="form-control" rows="5" placeholder="List your organization's main objectives (50-2000 characters)" required minlength="50" maxlength="2000"></textarea>
                            <small class="form-hint">Minimum 50 characters required</small>
                        </div>

                        <div class="form-row">
                            <div class="form-group form-group-half">
                                <label for="contact_email">Contact Email <span class="required">*</span></label>
                                <input type="email" name="contact_email" id="contact_email" class="form-control" placeholder="organization@cspc.edu.ph" required>
                                <small class="form-hint">This will be your login email</small>
                            </div>

                            <div class="form-group form-group-half">
                                <label for="contact_phone">Contact Phone <span class="required">*</span></label>
                                <input type="tel" name="contact_phone" id="contact_phone" class="form-control" placeholder="+63 XXX XXX XXXX" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group form-group-half">
                                <label for="password">Account Password <span class="required">*</span></label>
                                <input type="password" name="password" id="password" class="form-control" placeholder="Enter your password" required minlength="8">
                                <small class="form-hint">Minimum 8 characters</small>
                            </div>

                            <div class="form-group form-group-half">
                                <label for="password_confirm">Confirm Password <span class="required">*</span></label>
                                <input type="password" name="password_confirm" id="password_confirm" class="form-control" placeholder="Confirm your password" required minlength="8">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="current_members">Current Number of Members <span class="required">*</span></label>
                            <input type="number" name="current_members" id="current_members" class="form-control" placeholder="Enter number of members" required min="5">
                            <small class="form-hint">Minimum of 5 members required</small>
                        </div>
                    </div>

                    <!-- Faculty Advisor Information Section -->
                    <div class="form-section">
                        <h3 class="section-title">Faculty Advisor Information</h3>
                        
                        <div class="form-group">
                            <label for="advisor_name">Advisor Full Name <span class="required">*</span></label>
                            <input type="text" name="advisor_name" id="advisor_name" class="form-control" placeholder="Enter advisor's full name" required>
                        </div>

                        <div class="form-row">
                            <div class="form-group form-group-half">
                                <label for="advisor_email">Advisor Email <span class="required">*</span></label>
                                <input type="email" name="advisor_email" id="advisor_email" class="form-control" placeholder="advisor@cspc.edu.ph" required>
                            </div>

                            <div class="form-group form-group-half">
                                <label for="advisor_phone">Advisor Phone <span class="required">*</span></label>
                                <input type="tel" name="advisor_phone" id="advisor_phone" class="form-control" placeholder="+63 XXX XXX XXXX" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="advisor_department">Advisor Department <span class="required">*</span></label>
                            <div class="select-wrapper">
                                <select name="advisor_department" id="advisor_department" class="form-control" required>
                                    <option value="">Select department</option>
                                    <option value="ccs">College of Computer Studies</option>
                                    <option value="cea">College of Engineering and Architecture</option>
                                    <option value="cthbm">College of Tourism, Hospitality, and Business Management</option>
                                    <option value="chs">College of Health Sciences</option>
                                    <option value="ctde">College of Technological and Developmental Education</option>
                                    <option value="cas">College of Arts and Sciences</option>
                                    <option value="gs">Graduate School</option>
                                </select>
                                <svg class="select-arrow" width="12" height="8" viewBox="0 0 12 8" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M1 1L6 6L11 1" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Primary Officer Section -->
                    <div class="form-section">
                        <h3 class="section-title">Primary Officer Information</h3>
                        <p class="section-description">Please provide information for the person holding the highest position in your organization (e.g., President, Chairperson, Leader).</p>
                        
                        <div class="officer-group">
                            <div class="form-group">
                                <label for="officer_position">Position/Title <span class="required">*</span></label>
                                <input type="text" name="officer_position" id="officer_position" class="form-control" placeholder="e.g., President, Chairperson, Leader" required>
                            </div>

                            <div class="form-group">
                                <label for="primary_officer_name">Full Name <span class="required">*</span></label>
                                <input type="text" name="primary_officer_name" id="primary_officer_name" class="form-control" placeholder="Enter officer's full name" required>
                            </div>

                            <div class="form-row three-columns">
                                <div class="form-group form-group-third">
                                    <label for="primary_officer_email">Email <span class="required">*</span></label>
                                    <input type="email" name="primary_officer_email" id="primary_officer_email" class="form-control" placeholder="email@cspc.edu.ph" required>
                                </div>
                                <div class="form-group form-group-third">
                                    <label for="primary_officer_phone">Phone <span class="required">*</span></label>
                                    <input type="tel" name="primary_officer_phone" id="primary_officer_phone" class="form-control" placeholder="+63 XXX XXX XXXX" required>
                                </div>
                                <div class="form-group form-group-third">
                                    <label for="primary_officer_student_id">Student ID <span class="required">*</span></label>
                                    <input type="text" name="primary_officer_student_id" id="primary_officer_student_id" class="form-control" placeholder="Enter student ID" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Documentation Section -->
                    <div class="form-section">
                        <h3 class="section-title">Documentation</h3>
                        
                        <div class="form-group">
                            <label for="constitution_file">Organization Constitution/By-Laws <span class="required">*</span></label>
                            <input type="file" name="constitution_file" id="constitution_file" class="form-control file-input" accept=".pdf,.doc,.docx" required>
                            <small class="form-hint">Upload your organization's constitution or by-laws document (PDF, DOC, or DOCX, max 5MB)</small>
                        </div>

                        <div class="form-group">
                            <label for="certification_file">Certification of Proof <span class="required">*</span></label>
                            <input type="file" name="certification_file" id="certification_file" class="form-control file-input" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" required>
                            <small class="form-hint">Upload certification or proof that your organization already exists in CSPC campus (PDF, DOC, DOCX, JPG, PNG, max 5MB). This can be an official document, recognition letter, or any valid proof of existence.</small>
                        </div>
                    </div>

                    <div class="form-note">
                        <p><strong>Note:</strong> All submitted applications will be reviewed by the administration. You will receive an email notification once a decision has been made. The review process typically takes 5-7 business days.</p>
                    </div>

                    <button type="submit" class="btn-primary">Submit Application</button>
                </form>

                <p class="auth-footer">
                    <a href="<?= base_url() ?>">← Back to Home</a>
                </p>
            </div>
        </div>
    </div>
    <?= view('components/footer') ?>
</body>
</html>

