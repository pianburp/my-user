<?= $this->extend(config('Auth')->views['layout']) ?>

<?= $this->section('title') ?>Sign In<?= $this->endSection() ?>

<?= $this->section('main') ?>

<div class="auth-card">
    <div class="auth-logo">
        <div class="auth-logo-icon">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/>
                <polyline points="10 17 15 12 10 7"/>
                <line x1="15" y1="12" x2="3" y2="12"/>
            </svg>
        </div>
        <h1 class="auth-title">Welcome back</h1>
        <p class="auth-subtitle">Sign in to your account to continue</p>
    </div>

    <?php if (session('error') !== null) : ?>
        <div class="alert alert-danger" id="login-error-alert">
            <?= session('error') ?>
        </div>
    <?php elseif (session('errors') !== null) : ?>
        <div class="alert alert-danger" id="login-errors-alert">
            <ul>
                <?php foreach (session('errors') as $error) : ?>
                    <li><?= $error ?></li>
                <?php endforeach ?>
            </ul>
        </div>
    <?php endif ?>

    <?php if (session('message') !== null) : ?>
        <div class="alert alert-success" id="login-success-alert">
            <?= session('message') ?>
        </div>
    <?php endif ?>

    <form action="<?= url_to('login') ?>" method="post" id="login-form">
        <?= csrf_field() ?>

        <!-- Email -->
        <div class="form-group">
            <label for="email" class="form-label">Email address</label>
            <input 
                type="email" 
                id="email" 
                name="email" 
                class="form-input" 
                placeholder="you@example.com"
                autocomplete="email"
                value="<?= old('email') ?>"
                required
            >
        </div>

        <!-- Password -->
        <div class="form-group">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                <label for="password" class="form-label" style="margin-bottom: 0;">Password</label>
                <?php if (setting('Auth.allowMagicLinkLogins')) : ?>
                    <a href="<?= url_to('magic-link') ?>" class="auth-link">Forgot password?</a>
                <?php endif ?>
            </div>
            <div class="input-wrapper">
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    class="form-input" 
                    placeholder="••••••••"
                    autocomplete="current-password"
                    required
                >
            </div>
        </div>

        <!-- Remember Me -->
        <?php if (setting('Auth.sessionConfig')['allowRemembering']) : ?>
            <div class="form-check">
                <input type="checkbox" id="remember" name="remember" value="1" 
                    <?php if (old('remember')) : ?> checked<?php endif ?>>
                <label for="remember">Remember me for 30 days</label>
            </div>
        <?php endif ?>

        <button type="submit" id="login-submit-btn" class="btn btn-primary">
            Sign in
        </button>
    </form>

    <?php if (setting('Auth.allowRegistration')) : ?>
        <div class="auth-footer">
            Don't have an account? <a href="<?= url_to('register') ?>">Create one</a>
        </div>
    <?php endif ?>
</div>

<?= $this->endSection() ?>
