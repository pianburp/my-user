<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Create Student - MyUser Application">
    <title>Create Student - MyUser</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('css/app.css') ?>">
</head>
<body>
<main class="main-content" style="max-width: 1100px; margin: 2rem auto;">
    <section class="welcome-section">
        <div class="profile-header-row">
            <div>
                <h1 class="welcome-title">Create Student</h1>
                <p class="welcome-subtitle">Add a new enrollment record and optional document.</p>
            </div>
            <a href="/user/students" class="btn">Back to List</a>
        </div>
    </section>

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
        <div class="content-card-body">
            <form action="/user/students/store" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <?php include __DIR__ . '/form.php'; ?>
                <button type="submit" class="btn btn-primary">Create Student</button>
            </form>
        </div>
    </section>
</main>
</body>
</html>
