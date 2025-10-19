<?php
session_start();
include 'includes/header.php';
include 'config/db_connect.php';
?>

<div style="margin: 20px;">
    <a href="dashboard.php" class="btn btn-secondary">⬅ Back to Dashboard</a>
</div>
<?php
if (!isset($_SESSION['user']) || !isset($_SESSION['role'])) {
    header("Location: login.php");
    exit();
}

$user = $_SESSION['user'];
$role = $_SESSION['role'];
?>

<div class="container mt-4">
    <h2>👤 Profile</h2>
    <div class="card p-4 shadow-sm">
        <?php if ($role === 'admin'): ?>
            <p><strong>Role:</strong> Admin</p>
            <p><strong>Username:</strong> <?= htmlspecialchars($user['username']) ?></p>

        <?php elseif ($role === 'student'): ?>
            <p><strong>Role:</strong> Student</p>
            <p><strong>ID:</strong> <?= $user['ID'] ?></p>
            <p><strong>Name:</strong> <?= htmlspecialchars($user['name']) ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
            <p><strong>Phone:</strong> <?= htmlspecialchars($user['phone']) ?></p>
            <p><strong>Registered on:</strong> <?= $user['registration_date'] ?></p>

        <?php elseif ($role === 'instructor'): ?>
            <p><strong>Role:</strong> Instructor</p>
            <p><strong>ID:</strong> <?= $user['instructor_id'] ?></p>
            <p><strong>Name:</strong> <?= htmlspecialchars($user['name']) ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
            <p><strong>Phone:</strong> <?= htmlspecialchars($user['phone']) ?></p>
            <p><strong>Expertise:</strong> <?= htmlspecialchars($user['expertise']) ?></p>
            <p><strong>Bio:</strong> <?= htmlspecialchars($user['bio']) ?></p>
        <?php endif; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
