<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Dashboard - MyUser Application">
    <title>Dashboard — MyUser</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('css/app.css') ?>">
</head>
<body>
    <!-- Top Navigation -->
    <nav class="topnav" id="dashboard-nav">
        <a href="/user/dashboard" class="topnav-brand">
            <div class="topnav-logo">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                    <polyline points="9 22 9 12 15 12 15 22"/>
                </svg>
            </div>
            <span class="topnav-name">MyUser</span>
        </a>
        <div class="topnav-actions">
            <div class="user-pill" id="user-pill">
                <div class="user-avatar"><?= substr($user->username ?? 'U', 0, 2) ?></div>
                <span><?= esc($user->username ?? 'User') ?></span>
            </div>
            <a href="<?= url_to('logout') ?>" class="btn-logout" id="logout-btn">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                    <polyline points="16 17 21 12 16 7"/>
                    <line x1="21" y1="12" x2="9" y2="12"/>
                </svg>
                Logout
            </a>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Welcome Section -->
        <section class="welcome-section">
            <h1 class="welcome-title">Hello, <?= esc($user->username ?? 'User') ?></h1>
            <p class="welcome-subtitle">Here's what's happening with your account today.</p>
        </section>

        <!-- Stats Grid -->
        <section class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                        <circle cx="9" cy="7" r="4"/>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                    </svg>
                </div>
                <p class="stat-label">Total Users</p>
                <p class="stat-value">1</p>
                <p class="stat-change">
                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/></svg>
                    That's you!
                </p>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                        <polyline points="22 4 12 14.01 9 11.01"/>
                    </svg>
                </div>
                <p class="stat-label">Account Status</p>
                <p class="stat-value">Active</p>
                <p class="stat-change">
                    Authenticated
                </p>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                    </svg>
                </div>
                <p class="stat-label">Security</p>
                <p class="stat-value">Strong</p>
                <p class="stat-change">
                    Password hashed
                </p>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"/>
                        <polyline points="12 6 12 12 16 14"/>
                    </svg>
                </div>
                <p class="stat-label">Last Active</p>
                <p class="stat-value" style="font-size: 1.125rem;"><?php if(isset($user->last_active)) { echo $user->last_active->humanize(); } else { echo 'Just now'; } ?></p>
                <p class="stat-change">
                    Session active
                </p>
            </div>
        </section>

        <!-- Content Grid -->
        <section class="content-grid">
            <!-- Activity Feed -->
            <div class="content-card">
                <div class="content-card-header">
                    <h2 class="content-card-title">Recent Activity</h2>
                </div>
                <div class="content-card-body">
                    <ul class="activity-list">
                        <li class="activity-item">
                            <div class="activity-dot">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
                            </div>
                            <div>
                                <p class="activity-text"><strong>Login successful</strong></p>
                                <p class="activity-time">Just now</p>
                            </div>
                        </li>
                        <li class="activity-item">
                            <div class="activity-dot">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/></svg>
                            </div>
                            <div>
                                <p class="activity-text"><strong>Account created</strong></p>
                                <p class="activity-time"><?php if(isset($user->created_at)) { echo $user->created_at->humanize(); } else { echo 'Recently'; } ?></p>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="content-card">
                <div class="content-card-header">
                    <h2 class="content-card-title">Quick Actions</h2>
                </div>
                <div class="content-card-body">
                    <a href="/user/profile" class="quick-action" id="qa-edit-profile">
                        <div class="quick-action-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                <circle cx="12" cy="7" r="4"/>
                            </svg>
                        </div>
                        <div class="quick-action-content">
                            <span>Edit Profile</span>
                            <span class="quick-action-text">Update your information</span>
                        </div>
                    </a>
                    <a href="/user/students" class="quick-action" id="qa-students-management">
                        <div class="quick-action-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                                <circle cx="9" cy="7" r="4"/>
                                <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                            </svg>
                        </div>
                        <div class="quick-action-content">
                            <span>Students Management</span>
                            <span class="quick-action-text">Create and manage enrollments</span>
                        </div>
                    </a>
                </div>
            </div>
        </section>
    </main>
</body>
</html>
