<?= $this->extend(config('Auth')->views['layout']) ?>

<?= $this->section('title') ?>Create Account<?= $this->endSection() ?>

<?= $this->section('main') ?>

<div class="auth-card">
    <div class="auth-logo">
        <div class="auth-logo-icon">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                <circle cx="8.5" cy="7" r="4"/>
                <line x1="20" y1="8" x2="20" y2="14"/>
                <line x1="23" y1="11" x2="17" y2="11"/>
            </svg>
        </div>
        <h1 class="auth-title">Create account</h1>
        <p class="auth-subtitle">Get started with your free account</p>
    </div>

    <?php if (session('error') !== null) : ?>
        <div class="alert alert-danger" id="register-error-alert">
            <?= session('error') ?>
        </div>
    <?php elseif (session('errors') !== null) : ?>
        <div class="alert alert-danger" id="register-errors-alert">
            <ul>
                <?php foreach (session('errors') as $error) : ?>
                    <li><?= $error ?></li>
                <?php endforeach ?>
            </ul>
        </div>
    <?php endif ?>

    <?php if (session('message') !== null) : ?>
        <div class="alert alert-success" id="register-success-alert">
            <?= session('message') ?>
        </div>
    <?php endif ?>

    <form action="<?= url_to('register') ?>" method="post" id="register-form">
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

        <!-- Username -->
        <div class="form-group">
            <label for="username" class="form-label">Username</label>
            <input 
                type="text" 
                id="username" 
                name="username" 
                class="form-input" 
                placeholder="johndoe"
                autocomplete="username"
                value="<?= old('username') ?>"
                required
            >
        </div>

        <!-- Password -->
        <div class="form-group">
            <label for="password" class="form-label">Password</label>
            <input 
                type="password" 
                id="password" 
                name="password" 
                class="form-input"
                placeholder="Minimum 8 characters"
                autocomplete="new-password"
                required
            >
        </div>

        <!-- Confirm Password -->
        <div class="form-group">
            <label for="password_confirm" class="form-label">Confirm password</label>
            <input 
                type="password" 
                id="password_confirm" 
                name="password_confirm" 
                class="form-input"
                placeholder="Re-enter your password"
                autocomplete="new-password"
                required
            >
        </div>

        <button type="submit" id="register-submit-btn" class="btn btn-primary" style="margin-top: 8px;">
            Create account
        </button>
    </form>

    <div class="auth-footer">
        Already have an account? <a href="<?= url_to('login') ?>">Sign in</a>
    </div>
</div>

<?= $this->endSection() ?>
