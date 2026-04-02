<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="MyUser — A modern authentication starter built on CodeIgniter 4 and Shield.">
    <title>MyUser — Welcome</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('css/app.css') ?>">
</head>
<body>
    <!-- Navigation -->
    <nav class="nav" id="main-nav">
        <a href="/" class="nav-brand">
            <div class="nav-logo">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                    <circle cx="9" cy="7" r="4"/>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                </svg>
            </div>
            <span class="nav-brand-name">MyUser</span>
        </a>
        <div class="nav-links">
            <?php if (auth()->loggedIn()) : ?>
                <a href="/user/dashboard" class="nav-link nav-link--primary" id="nav-dashboard">Dashboard</a>
            <?php else : ?>
                <a href="<?= url_to('login') ?>" class="nav-link nav-link--ghost" id="nav-login">Sign in</a>
                <a href="<?= url_to('register') ?>" class="nav-link nav-link--primary" id="nav-register">Get Started</a>
            <?php endif ?>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-inner">
            <div class="hero-badge">
                <span class="hero-badge-dot"></span>
                Built with CodeIgniter 4 + Shield
            </div>
            <h1 class="hero-title">
                Secure auth,<br>ready to go.
            </h1>
            <p class="hero-subtitle">
                A modern authentication starter with login, registration, session management, and a protected dashboard. Based on CI4 Shield.
            </p>
            <div class="hero-actions">
                <?php if (auth()->loggedIn()) : ?>
                    <a href="/user/dashboard" class="btn btn--primary" id="hero-dashboard">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                            <polyline points="9 22 9 12 15 12 15 22"/>
                        </svg>
                        Go to Dashboard
                    </a>
                <?php else : ?>
                    <a href="<?= url_to('register') ?>" class="btn btn--primary" id="hero-register">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                            <circle cx="8.5" cy="7" r="4"/>
                            <line x1="20" y1="8" x2="20" y2="14"/>
                            <line x1="23" y1="11" x2="17" y2="11"/>
                        </svg>
                        Create account
                    </a>
                    <a href="<?= url_to('login') ?>" class="btn btn--outline" id="hero-login">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/>
                            <polyline points="10 17 15 12 10 7"/>
                            <line x1="15" y1="12" x2="3" y2="12"/>
                        </svg>
                        Sign in
                    </a>
                <?php endif ?>
            </div>
        </div>
    </section>

    <!-- Features -->
    <section class="features">
        <div class="feature-card">
            <div class="feature-icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                </svg>
            </div>
            <h3 class="feature-title">Session Authentication</h3>
            <p class="feature-desc">Secure login and registration with password hashing, CSRF protection, and remember-me cookies out of the box.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                </svg>
            </div>
            <h3 class="feature-title">Route Protection</h3>
            <p class="feature-desc">Filter-based guards on your routes. Unauthenticated users are seamlessly redirected to the login page.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                    <circle cx="9" cy="7" r="4"/>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                </svg>
            </div>
            <h3 class="feature-title">Groups & Permissions</h3>
            <p class="feature-desc">Built-in role-based access control. Assign users to groups and manage fine-grained permissions easily.</p>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        MyUser &copy; <?= date('Y') ?> &middot; Powered by <a href="https://codeigniter.com" target="_blank" rel="noopener">CodeIgniter <?= CodeIgniter\CodeIgniter::CI_VERSION ?></a> + <a href="https://codeigniter4.github.io/shield/" target="_blank" rel="noopener">Shield</a>
    </footer>
</body>
</html>
