<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Profile — MyUser Application">
    <title>Profile — MyUser</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('css/app.css') ?>">
</head>
<body>
    <!-- Top Navigation -->
    <nav class="topnav" id="profile-nav">
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
                <div class="user-avatar"><?= strtoupper(substr($user->username ?? 'U', 0, 2)) ?></div>
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
        <!-- Page Header -->
        <section class="welcome-section">
            <div class="profile-header-row">
                <div>
                    <h1 class="welcome-title">Your Profile</h1>
                    <p class="welcome-subtitle">Manage your account details and security settings.</p>
                </div>
            </div>
        </section>

        <!-- Profile Grid -->
        <section class="profile-grid">

            <!-- Edit Profile -->
            <div class="content-card">
                <div class="content-card-header">
                    <div class="content-card-title-row">
                        <div class="profile-card-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                <circle cx="12" cy="7" r="4"/>
                            </svg>
                        </div>
                        <h2 class="content-card-title">Edit Profile</h2>
                    </div>
                </div>
                <div class="content-card-body">
                    <?php if (session('profile_success')) : ?>
                        <div class="alert alert-success" id="profile-success-alert">
                            <?= esc(session('profile_success')) ?>
                        </div>
                    <?php endif ?>
                    <?php if (session('profile_errors')) : ?>
                        <div class="alert alert-danger" id="profile-error-alert">
                            <ul style="margin: 0; padding-left: 1.25rem;">
                                <?php foreach (session('profile_errors') as $error) : ?>
                                    <li><?= esc($error) ?></li>
                                <?php endforeach ?>
                            </ul>
                        </div>
                    <?php endif ?>

                    <form action="/user/profile/update" method="post" id="profile-form">
                        <?= csrf_field() ?>

                        <div class="form-group">
                            <label for="email" class="form-label">Email address</label>
                            <input type="email" id="email" class="form-input form-input--readonly"
                                value="<?= esc($user->email) ?>" disabled>
                            <span class="form-hint">Email cannot be changed here.</span>
                        </div>

                        <div class="form-group">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" id="username" name="username" class="form-input"
                                value="<?= esc(old('username', $user->username)) ?>"
                                placeholder="johndoe" autocomplete="username" required>
                        </div>

                        <button type="submit" id="profile-submit-btn" class="btn btn-primary profile-btn">
                            Save Changes
                        </button>
                    </form>
                </div>
            </div>

            <!-- Change Password -->
            <div class="content-card">
                <div class="content-card-header">
                    <div class="content-card-title-row">
                        <div class="profile-card-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                            </svg>
                        </div>
                        <h2 class="content-card-title">Change Password</h2>
                    </div>
                </div>
                <div class="content-card-body">
                    <?php if (session('password_success')) : ?>
                        <div class="alert alert-success" id="password-success-alert">
                            <?= esc(session('password_success')) ?>
                        </div>
                    <?php endif ?>
                    <?php if (session('password_errors')) : ?>
                        <div class="alert alert-danger" id="password-error-alert">
                            <ul style="margin: 0; padding-left: 1.25rem;">
                                <?php foreach (session('password_errors') as $error) : ?>
                                    <li><?= esc($error) ?></li>
                                <?php endforeach ?>
                            </ul>
                        </div>
                    <?php endif ?>

                    <form action="/user/profile/password" method="post" id="password-form">
                        <?= csrf_field() ?>

                        <div class="form-group">
                            <label for="current_password" class="form-label">Current Password</label>
                            <input type="password" id="current_password" name="current_password" class="form-input"
                                placeholder="••••••••" autocomplete="current-password" required>
                        </div>

                        <div class="form-group">
                            <label for="new_password" class="form-label">New Password</label>
                            <input type="password" id="new_password" name="new_password" class="form-input"
                                placeholder="Minimum 8 characters" autocomplete="new-password" required>
                        </div>

                        <div class="form-group">
                            <label for="password_confirm" class="form-label">Confirm New Password</label>
                            <input type="password" id="password_confirm" name="password_confirm" class="form-input"
                                placeholder="Re-enter your new password" autocomplete="new-password" required>
                        </div>

                        <button type="submit" id="password-submit-btn" class="btn btn-primary profile-btn">
                            Update Password
                        </button>
                    </form>
                </div>
            </div>

        </section>
    </main>
</body>
</html>
