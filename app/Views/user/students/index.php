<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Students - MyUser Application">
    <title>Students - MyUser</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('css/app.css') ?>">
</head>
<body>
<nav class="topnav" id="students-nav">
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
        <a href="<?= url_to('logout') ?>" class="btn-logout">
            Logout
        </a>
    </div>
</nav>

<main class="main-content">
    <section class="welcome-section">
        <div class="profile-header-row">
            <div>
                <h1 class="welcome-title">Student Enrollment</h1>
                <p class="welcome-subtitle">Manage student records, search, and enrollment lifecycle.</p>
            </div>
            <a href="/user/students/create" class="btn btn-primary">Add Student</a>
        </div>
    </section>

    <section class="content-card" style="margin-bottom: 1rem;">
        <div class="content-card-body">
            <form method="get" action="/user/students" class="form-group" style="margin: 0; display: flex; gap: 0.75rem; align-items: end; flex-wrap: wrap;">
                <div style="flex: 1; min-width: 220px;">
                    <label for="q" class="form-label">Search</label>
                    <input type="text" id="q" name="q" class="form-input" value="<?= esc($search ?? '') ?>" placeholder="Name, student ID, or email">
                </div>
                <button type="submit" class="btn btn-primary">Search</button>
                <a href="/user/students" class="btn">Reset</a>
            </form>
        </div>
    </section>

    <?php if (session('student_success')) : ?>
        <div class="alert alert-success" style="margin-bottom: 1rem;"><?= esc(session('student_success')) ?></div>
    <?php endif ?>

    <?php if (session('student_errors')) : ?>
        <div class="alert alert-danger" style="margin-bottom: 1rem;">
            <ul style="margin: 0; padding-left: 1.25rem;">
                <?php foreach ((array) session('student_errors') as $error) : ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach ?>
            </ul>
        </div>
    <?php endif ?>

    <section class="content-card">
        <div class="content-card-body" style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; min-width: 850px;">
                <thead>
                    <tr>
                        <th style="text-align: left; padding: 0.75rem; border-bottom: 1px solid #e5e7eb;">Student ID</th>
                        <th style="text-align: left; padding: 0.75rem; border-bottom: 1px solid #e5e7eb;">Name</th>
                        <th style="text-align: left; padding: 0.75rem; border-bottom: 1px solid #e5e7eb;">Email</th>
                        <th style="text-align: left; padding: 0.75rem; border-bottom: 1px solid #e5e7eb;">Program</th>
                        <th style="text-align: left; padding: 0.75rem; border-bottom: 1px solid #e5e7eb;">Status</th>
                        <th style="text-align: left; padding: 0.75rem; border-bottom: 1px solid #e5e7eb;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (! empty($students)) : ?>
                    <?php foreach ($students as $student) : ?>
                        <tr>
                            <td style="padding: 0.75rem; border-bottom: 1px solid #f3f4f6;"><?= esc($student['student_id']) ?></td>
                            <td style="padding: 0.75rem; border-bottom: 1px solid #f3f4f6;"><?= esc($student['first_name'] . ' ' . $student['last_name']) ?></td>
                            <td style="padding: 0.75rem; border-bottom: 1px solid #f3f4f6;"><?= esc($student['email']) ?></td>
                            <td style="padding: 0.75rem; border-bottom: 1px solid #f3f4f6;"><?= esc($student['program_name'] ?? '-') ?></td>
                            <td style="padding: 0.75rem; border-bottom: 1px solid #f3f4f6;"><?= esc(ucfirst($student['status'])) ?></td>
                            <td style="padding: 0.75rem; border-bottom: 1px solid #f3f4f6; display: flex; gap: 0.5rem; align-items: center;">
                                <a href="/user/students/<?= (int) $student['id'] ?>/edit" class="btn">Edit</a>
                                <form action="/user/students/<?= (int) $student['id'] ?>/delete" method="post" onsubmit="return confirm('Archive this student?');" style="margin: 0;">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn" style="color: #b91c1c;">Archive</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach ?>
                <?php else : ?>
                    <tr>
                        <td colspan="6" style="padding: 1rem; text-align: center; color: #6b7280;">No students found.</td>
                    </tr>
                <?php endif ?>
                </tbody>
            </table>
        </div>
    </section>

    <?php if (isset($pager)) : ?>
        <div style="margin-top: 1rem;">
            <?= $pager->only(['q'])->links() ?>
        </div>
    <?php endif ?>
</main>
</body>
</html>
