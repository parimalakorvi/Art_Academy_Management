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
    $title = $_POST['title'];
    $medium = $_POST['medium'];
    $description = $_POST['description'];
    $submission_date = $_POST['submission_date'];

    if ($id) {
        $stmt = $conn->prepare("UPDATE artworks SET student_id=?, title=?, medium=?, description=?, submission_date=? WHERE art_id=?");
        $stmt->bind_param("sssssi", $student_id, $title, $medium, $description, $submission_date, $id);
    } else {
        $stmt = $conn->prepare("INSERT INTO artworks (ID, title, medium, description, submission_date) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $student_id, $title, $medium, $description, $submission_date);
    }
    $stmt->execute();
    header("Location: artworks.php");
    exit();
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM artworks WHERE art_id=$id");
    header("Location: artworks.php");
    exit();
}

// Edit Prefill
$edit_id = '';
$student_id = '';
$title = '';
$medium = '';
$description = '';
$submission_date = '';

if (isset($_GET['edit'])) {
    $edit_id = $_GET['edit'];
    $stmt = $conn->prepare("SELECT * FROM artworks WHERE art_id = ?");
    $stmt->bind_param("i", $edit_id);
    $stmt->execute();
    $result_edit = $stmt->get_result();
    if ($result_edit->num_rows === 1) {
        $row = $result_edit->fetch_assoc();
        $student_id = $row['student_id'];
        $title = $row['title'];
        $medium = $row['medium'];
        $description = $row['description'];
        $submission_date = $row['submission_date'];
    }
}

$result = $conn->query("SELECT * FROM artworks");
?>

<h2>Artworks</h2>
<form method="post" class="mb-4">
    <input type="hidden" name="id" value="<?= $edit_id ?>">
    <div class="row g-2">
        <div class="col-md-2"><input type="text" name="student_id" class="form-control" placeholder="Student ID" value="<?= htmlspecialchars($student_id) ?>" required></div>
        <div class="col-md-2"><input type="text" name="title" class="form-control" placeholder="Title" value="<?= htmlspecialchars($title) ?>" required></div>
        <div class="col-md-2"><input type="text" name="medium" class="form-control" placeholder="Medium" value="<?= htmlspecialchars($medium) ?>"></div>
        <div class="col-md-2"><input type="text" name="description" class="form-control" placeholder="Description" value="<?= htmlspecialchars($description) ?>"></div>
        <div class="col-md-2"><input type="date" name="submission_date" class="form-control" value="<?= htmlspecialchars($submission_date) ?>"></div>
        <div class="col-md-2"><button type="submit" name="save" class="btn btn-success w-100"><?= $edit_id ? 'Update' : 'Save' ?></button></div>
    </div>
</form>

<table class="table table-bordered">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Student ID</th>
            <th>Title</th>
            <th>Medium</th>
            <th>Description</th>
            <th>Date</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['art_id'] ?></td>
            <td><?= $row['ID'] ?></td>
            <td><?= htmlspecialchars($row['title']) ?></td>
            <td><?= htmlspecialchars($row['medium']) ?></td>
            <td><?= htmlspecialchars($row['description']) ?></td>
            <td><?= $row['submission_date'] ?></td>
            <td>
                <a href="artworks.php?edit=<?= $row['art_id'] ?>" class="btn btn-sm btn-primary">Edit</a>
                <a href="artworks.php?delete=<?= $row['art_id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this artwork?')">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<?php include 'includes/footer.php'; ?>
