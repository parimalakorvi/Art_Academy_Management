<?php
session_start();
if (!isset($_SESSION['role'])) {
    header("Location: login.php");
    exit();
}
$role = $_SESSION['role'];
$user = $_SESSION['user'];
include 'includes/header.php';
?>

<style>
.dashboard-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 20px;
    padding: 30px;
}
.tab-card {
    background: linear-gradient(145deg, #e0e0e0, #ffffff);
    border-radius: 12px;
    padding: 20px;
    text-align: center;
    font-size: 1.1rem;
    font-weight: bold;
    transition: transform 0.3s, box-shadow 0.3s;
    color: #333;
    text-decoration: none;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}
.tab-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 15px rgba(0,0,0,0.2);
}
.tab-icon {
    font-size: 2rem;
    margin-bottom: 10px;
    display: block;
}
</style>

<div class="header">
    <h2>Welcome, <?= htmlspecialchars($user['name'] ?? $user['username']) ?> (<?= ucfirst($role) ?>)</h2>
    <a href="logout.php" class="btn btn-danger">Logout</a>
</div>

<div class="dashboard-grid">
    <?php if ($role == 'admin'): ?>
        <a href="students.php" class="tab-card"><span class="tab-icon">👥</span>Students</a>
        <a href="instructors.php" class="tab-card"><span class="tab-icon">👨‍🏫</span>Instructors</a>
        <a href="courses.php" class="tab-card"><span class="tab-icon">📚</span>Courses</a>
        <a href="classes.php" class="tab-card"><span class="tab-icon">🕐</span>Classes</a>
        <a href="enrollments.php" class="tab-card"><span class="tab-icon">📝</span>Enrollments</a>
        <a href="artworks.php" class="tab-card"><span class="tab-icon">🎨</span>Artworks</a>
        <a href="payments.php" class="tab-card"><span class="tab-icon">💳</span>Payments</a>
        <a href="feedback.php" class="tab-card"><span class="tab-icon">🗣</span>Feedback</a>
    <?php elseif ($role == 'instructor'): ?>
        <a href="courses.php?filter=mine" class="tab-card"><span class="tab-icon">📚</span>My Courses</a>
        <a href="classes.php?filter=mine" class="tab-card"><span class="tab-icon">🕐</span>My Classes</a>
        <a href="students.php?filter=instructor" class="tab-card"><span class="tab-icon">👥</span>My Students</a>
    <?php elseif ($role == 'student'): ?>
        <a href="enrollments.php?filter=my" class="tab-card"><span class="tab-icon">📝</span>My Enrollments</a>
        <a href="payments.php?filter=my" class="tab-card"><span class="tab-icon">💳</span>My Payments</a>
        <a href="feedback.php?filter=my" class="tab-card"><span class="tab-icon">🗣</span>My Feedback</a>
        <a href="artworks.php?filter=my" class="tab-card"><span class="tab-icon">🎨</span>My Artworks</a>
    <?php endif; ?>

    <a href="profile.php" class="tab-card"><span class="tab-icon">👤</span>Profile</a>
</div>

<?php include 'includes/footer.php'; ?>
