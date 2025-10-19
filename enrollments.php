<?php
session_start();
include 'includes/header.php';
include 'config/db_connect.php';
?>

<div style="margin: 20px;">
    <a href="dashboard.php" class="btn btn-secondary">⬅ Back to Dashboard</a>
</div>

<?php
// Handle Insert/Update
if (isset($_POST['save'])) {
    $id = $_POST['id'] ?? '';
    $student_id = $_POST['student_id'];
    $course_id = $_POST['course_id'];
    $enrollment_date = $_POST['enrollment_date'];
    $status = $_POST['status'];

    if ($id) {
        $stmt = $conn->prepare("UPDATE enrollments SET student_id=?, course_id=?, enrollment_date=?, status=? WHERE enrollment_id=?");
        $stmt->bind_param("sssss", $student_id, $course_id, $enrollment_date, $status, $id);
    } else {
        $id = 'E' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
        $stmt = $conn->prepare("INSERT INTO enrollments (enrollment_id, ID, course_id, enrollment_date, status) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $id, $ID, $course_id, $enrollment_date, $status);
    }
    $stmt->execute();
    header("Location: enrollments.php");
    exit();
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM enrollments WHERE enrollment_id='$id'");
    header("Location: enrollments.php");
    exit();
}

// Handle Edit Prefill
$edit_id = '';
$student_id = '';
$course_id = '';
$enrollment_date = '';
$status = '';

if (isset($_GET['edit'])) {
    $edit_id = $_GET['edit'];
    $stmt = $conn->prepare("SELECT * FROM enrollments WHERE enrollment_id = ?");
    $stmt->bind_param("s", $edit_id);
    $stmt->execute();
    $result_edit = $stmt->get_result();
    if ($result_edit->num_rows === 1) {
        $row = $result_edit->fetch_assoc();
        $student_id = $row['student_id'];
        $course_id = $row['course_id'];
        $enrollment_date = $row['enrollment_date'];
        $status = $row['status'];
    }
}

// Fetch enrollment data with student and course names
$query = "
    SELECT 
        e.enrollment_id,
        e.ID,
        s.name AS student_name,
        e.course_id,
        c.title AS course_title,
        e.enrollment_date,
        e.status
    FROM enrollments e
    JOIN students s ON e.ID = s.ID
    JOIN courses c ON e.course_id = c.course_id
    ORDER BY e.enrollment_id
";
$result = $conn->query($query);
?>

<h2>Enrollments</h2>
<form method="post" class="mb-4">
    <input type="hidden" name="id" value="<?= $edit_id ?>">
    <div class="row g-2">
        <div class="col-md-2">
            <input type="text" name="student_id" class="form-control" placeholder="Student ID" value="<?= htmlspecialchars($student_id) ?>" required>
        </div>
        <div class="col-md-2">
            <input type="text" name="course_id" class="form-control" placeholder="Course ID" value="<?= htmlspecialchars($course_id) ?>" required>
        </div>
        <div class="col-md-2">
            <input type="date" name="enrollment_date" class="form-control" value="<?= htmlspecialchars($enrollment_date) ?>" required>
        </div>
        <div class="col-md-2">
            <input type="text" name="status" class="form-control" placeholder="Status" value="<?= htmlspecialchars($status) ?>" required>
        </div>
        <div class="col-md-2">
            <button type="submit" name="save" class="btn btn-success w-100"><?= $edit_id ? 'Update' : 'Save' ?></button>
        </div>
    </div>
</form>

<table class="table table-bordered">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Student</th>
            <th>Course</th>
            <th>Date</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['enrollment_id'] ?></td>
            <td><?= htmlspecialchars($row['student_name']) ?> (<?= $row['ID'] ?>)</td>
            <td><?= htmlspecialchars($row['course_title']) ?> (<?= $row['course_id'] ?>)</td>
            <td><?= $row['enrollment_date'] ?></td>
            <td><?= $row['status'] ?></td>
            <td>
                <a href="enrollments.php?edit=<?= $row['enrollment_id'] ?>" class="btn btn-sm btn-primary">Edit</a>
                <a href="enrollments.php?delete=<?= $row['enrollment_id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this enrollment?')">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<?php include 'includes/footer.php'; ?>
