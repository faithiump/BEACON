<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BEACON | Unified Campus Hub</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/home.css') ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <nav class="home-nav">
        <div class="nav-container">
            <a href="<?= base_url() ?>" class="nav-brand">
                <span class="nav-logo">BEACON</span>
                <span class="nav-subtitle">CSPC</span>
            </a>
            <div class="nav-links">
                <a href="<?= base_url() ?>" class="nav-link active">Home</a>
                <a href="<?= base_url('auth/login') ?>" class="nav-link">Login</a>
                <a href="<?= base_url('auth/register') ?>" class="nav-link">Register</a>
            </div>
        </div>
    </nav>

    <main class="home-wrapper">
        <section class="hero">
            <div class="hero-text">
                <div class="status-pill">Unified Platform • Student Organizations</div>
                <h1>Bringing Events, Announcements, and Campus Organizations Together.</h1>
                <p>
                    BEACON is CSPC's centralized hub for organization management, event visibility,
                    and student engagement. Stay informed, collaborate seamlessly, and keep the pulse
                    of campus life in one place.
                </p>
                <div class="hero-actions">
                    <a href="<?= base_url('auth/register') ?>" class="btn primary">Get Started</a>
                    <a href="<?= base_url('auth/login') ?>" class="btn secondary">Sign In</a>
                    <a href="<?= base_url('organization/launch') ?>" class="btn tertiary">Launch Organization</a>
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
                <div class="floating-card schedule">
                    <h3>Upcoming Event</h3>
                    <p>Leadership Summit 2025</p>
                    <span>April 15 • CSPC Auditorium</span>
                </div>
                <div class="floating-card announcement">
                    <h3>Latest Announcement</h3>
                    <p>IGP Bazaar applications now open for all organizations.</p>
                </div>
                <div class="glow-ring ring-one"></div>
                <div class="glow-ring ring-two"></div>
                <div class="glow-ring ring-three"></div>
            </div>
        </section>

        <section class="feature-grid">
            <div class="feature-card">
                <span class="feature-icon">📣</span>
                <h4>Campus-wide Announcements</h4>
                <p>Publish timely circulars and updates that reach the entire CSPC community instantly.</p>
            </div>
            <div class="feature-card">
                <span class="feature-icon">🗓️</span>
                <h4>Event Visibility</h4>
                <p>Showcase organization events, track attendance, and keep students engaged with campus life.</p>
            </div>
            <div class="feature-card">
                <span class="feature-icon">🤝</span>
                <h4>Organization Management</h4>
                <p>Empower moderators and officers with tools for collaboration, planning, and documentation.</p>
            </div>
        </section>
    </main>
</body>
</html>