<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BEACON | Unified Campus Hub</title>
    <link rel="icon" type="image/png" href="<?= base_url('assets/images/beacon-logo-v2.png') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/nav.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/home.css') ?>">
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
        <div class="star small color-blue" style="top: 12%; left: 40%; animation-delay: 1s;"></div>
        <div class="star large" style="top: 25%; left: 55%; animation-delay: 1.5s;"></div>
        <div class="star medium color-purple" style="top: 18%; left: 70%; animation-delay: 2s;"></div>
        <div class="star small" style="top: 22%; left: 85%; animation-delay: 2.5s;"></div>
        
        <div class="star medium color-yellow" style="top: 35%; left: 8%; animation-delay: 0.3s;"></div>
        <div class="star small" style="top: 40%; left: 22%; animation-delay: 0.8s;"></div>
        <div class="star large color-blue" style="top: 38%; left: 38%; animation-delay: 1.3s;"></div>
        <div class="star small color-purple" style="top: 42%; left: 52%; animation-delay: 1.8s;"></div>
        <div class="star medium" style="top: 36%; left: 68%; animation-delay: 2.3s;"></div>
        <div class="star small color-yellow" style="top: 39%; left: 82%; animation-delay: 2.8s;"></div>
        
        <div class="star small" style="top: 55%; left: 12%; animation-delay: 0.2s;"></div>
        <div class="star medium color-blue" style="top: 58%; left: 28%; animation-delay: 0.7s;"></div>
        <div class="star large color-purple" style="top: 56%; left: 45%; animation-delay: 1.2s;"></div>
        <div class="star small" style="top: 60%; left: 60%; animation-delay: 1.7s;"></div>
        <div class="star medium color-yellow" style="top: 57%; left: 75%; animation-delay: 2.2s;"></div>
        <div class="star small" style="top: 59%; left: 90%; animation-delay: 2.7s;"></div>
        
        <div class="star medium" style="top: 72%; left: 5%; animation-delay: 0.4s;"></div>
        <div class="star small color-purple" style="top: 75%; left: 18%; animation-delay: 0.9s;"></div>
        <div class="star large color-blue" style="top: 73%; left: 32%; animation-delay: 1.4s;"></div>
        <div class="star small" style="top: 77%; left: 48%; animation-delay: 1.9s;"></div>
        <div class="star medium color-yellow" style="top: 74%; left: 63%; animation-delay: 2.4s;"></div>
        <div class="star small" style="top: 76%; left: 78%; animation-delay: 2.9s;"></div>
        
        <div class="star small color-blue" style="top: 88%; left: 15%; animation-delay: 0.6s;"></div>
        <div class="star medium" style="top: 91%; left: 30%; animation-delay: 1.1s;"></div>
        <div class="star small color-purple" style="top: 89%; left: 50%; animation-delay: 1.6s;"></div>
        <div class="star large color-yellow" style="top: 93%; left: 65%; animation-delay: 2.1s;"></div>
        <div class="star small" style="top: 90%; left: 80%; animation-delay: 2.6s;"></div>
    </div>
    
    <?= view('components/nav', ['active' => 'home']) ?>

    <main class="home-wrapper">
        <section class="hero">
            <div class="hero-text">
                <h1>Bringing Events, Announcements, and Campus Organization Network.</h1>
                <p>
                    BEACON is CSPC's centralized hub for organization management, event visibility,
                    and student engagement. Stay informed, collaborate seamlessly, and keep the pulse
                    of campus life in one place.
                </p>
                <div class="hero-actions">
                    <a href="<?= base_url('auth/register') ?>" class="btn primary">Sign Up</a>
                    <a href="<?= base_url('auth/login') ?>" class="btn secondary">Log In</a>
                    <a href="<?= base_url('organization/launch') ?>" class="btn tertiary">Launch Organization Page</a>
                </div>
                <div class="hero-stats">
                    <div class="stat-card">
                        <span class="stat-value">40+</span>
                        <span class="stat-label">Organizations</span>
                    </div>
                    <div class="stat-card">
                        <span class="stat-value">150+</span>
                        <span class="stat-label">Campus Events</span>
                    </div>
                    <div class="stat-card">
                        <span class="stat-value">24/7</span>
                        <span class="stat-label">Access Anywhere</span>
                    </div>
                </div>
            </div>
            <div class="hero-visual">
                <div class="floating-card schedule schedule-one">
                    <div class="card-header">
                        <span class="card-badge">Upcoming</span>
                        <span class="card-date">Apr 15</span>
                    </div>
                    <h3>Leadership Summit 2025</h3>
                    <p class="card-location">CSPC Auditorium</p>
                    <div class="card-time">2:00 PM - 5:00 PM</div>
                </div>
                <div class="floating-card schedule schedule-two">
                    <div class="card-header">
                        <span class="card-badge">Featured</span>
                        <span class="card-date">Apr 22</span>
                    </div>
                    <h3>Tech Innovation Fair</h3>
                    <p class="card-location">Main Campus Grounds</p>
                    <div class="card-time">9:00 AM - 4:00 PM</div>
                </div>
                <div class="floating-card schedule schedule-three">
                    <div class="card-header">
                        <span class="card-badge">New</span>
                        <span class="card-date">Apr 28</span>
                    </div>
                    <h3>Cultural Festival</h3>
                    <p class="card-location">Student Center</p>
                    <div class="card-time">6:00 PM - 10:00 PM</div>
                </div>
                <div class="glow-ring ring-one"></div>
                <div class="glow-ring ring-two"></div>
                <div class="glow-ring ring-three"></div>
            </div>
        </section>

        <section class="feature-grid">
            <div class="feature-card">
                <h4>Campus-wide Announcements</h4>
                <p>Publish timely circulars and updates that reach the entire CSPC community instantly.</p>
            </div>
            <div class="feature-card">
                <h4>Event Visibility</h4>
                <p>Showcase organization events, track attendance, and keep students engaged with campus life.</p>
            </div>
            <div class="feature-card">
                <h4>Organization Management</h4>
                <p>Empower moderators and officers with tools for collaboration, planning, and documentation.</p>
            </div>
        </section>
    </main>
    <?= view('components/footer') ?>
    <script src="<?= base_url('assets/js/nav.js') ?>"></script>
</body>
</html>