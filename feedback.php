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
    $rating = $_POST['rating'];
    $comments = $_POST['comments'];
    $date_submitted = $_POST['date_submitted'];

    if ($id) {
        $stmt = $conn->prepare("UPDATE feedback SET student_id=?, course_id=?, rating=?, comments=?, date_submitted=? WHERE feedback_id=?");
        $stmt->bind_param("ssissi", $student_id, $course_id, $rating, $comments, $date_submitted, $id);
    } else {
        $stmt = $conn->prepare("INSERT INTO feedback (ID, course_id, rating, comments, date_submitted) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssiss", $student_id, $course_id, $rating, $comments, $date_submitted);
    }
    $stmt->execute();
    header("Location: feedback.php");
    exit();
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM feedback WHERE feedback_id=$id");
    header("Location: feedback.php");
    exit();
}

// Handle Edit Prefill
$edit_id = '';
$student_id = '';
$course_id = '';
$rating = '';
$comments = '';
$date_submitted = '';

if (isset($_GET['edit'])) {
    $edit_id = $_GET['edit'];
    $stmt = $conn->prepare("SELECT * FROM feedback WHERE feedback_id = ?");
    $stmt->bind_param("i", $edit_id);
    $stmt->execute();
    $result_edit = $stmt->get_result();
    if ($result_edit->num_rows === 1) {
        $row = $result_edit->fetch_assoc();
        $student_id = $row['student_id'];
        $course_id = $row['course_id'];
        $rating = $row['rating'];
        $comments = $row['comments'];
        $date_submitted = $row['date_submitted'];
    }
}

$result = $conn->query("SELECT * FROM feedback");
?>

<h2>Feedback</h2>
<form method="post" class="mb-4">
    <input type="hidden" name="id" value="<?= $edit_id ?>">
    <div class="row g-2">
        <div class="col-md-2"><input type="text" name="student_id" class="form-control" placeholder="Student ID" value="<?= htmlspecialchars($student_id) ?>" required></div>
        <div class="col-md-2"><input type="text" name="course_id" class="form-control" placeholder="Course ID" value="<?= htmlspecialchars($course_id) ?>" required></div>
        <div class="col-md-1"><input type="number" name="rating" min="1" max="5" class="form-control" placeholder="Rating" value="<?= htmlspecialchars($rating) ?>"></div>
        <div class="col-md-3"><input type="text" name="comments" class="form-control" placeholder="Comments" value="<?= htmlspecialchars($comments) ?>"></div>
        <div class="col-md-2"><input type="date" name="date_submitted" class="form-control" value="<?= htmlspecialchars($date_submitted) ?>"></div>
        <div class="col-md-2">
            <button type="submit" name="save" class="btn btn-success w-100"><?= $edit_id ? 'Update' : 'Save' ?></button>
        </div>
    </div>
</form>

<table class="table table-bordered">
    <thead class="table-dark">
        <tr>
            <th>ID</th><th>Student ID</th><th>Course ID</th><th>Rating</th><th>Comments</th><th>Date</th><th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['feedback_id'] ?></td>
            <td><?= $row['ID'] ?></td>
            <td><?= $row['course_id'] ?></td>
            <td><?= $row['rating'] ?></td>
            <td><?= htmlspecialchars($row['comments']) ?></td>
            <td><?= $row['date_submitted'] ?></td>
            <td>
                <a href="feedback.php?edit=<?= $row['feedback_id'] ?>" class="btn btn-sm btn-primary">Edit</a>
                <a href="feedback.php?delete=<?= $row['feedback_id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this feedback?')">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<?php include 'includes/footer.php'; ?>
