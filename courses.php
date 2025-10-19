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
    $title = $_POST['title'];
    $description = $_POST['description'];
    $duration = $_POST['duration'];
    $level = $_POST['level'];
    $instructor_id = $_POST['instructor_id'];

    if ($id) {
        $stmt = $conn->prepare("UPDATE courses SET title=?, description=?, duration=?, level=?, instructor_id=? WHERE course_id=?");
        $stmt->bind_param("ssissi", $title, $description, $duration, $level, $instructor_id, $id);
    } else {
        $stmt = $conn->prepare("INSERT INTO courses (title, description, duration, level, instructor_id) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssisi", $title, $description, $duration, $level, $instructor_id);
    }
    $stmt->execute();
    header("Location: courses.php");
    exit();
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM courses WHERE course_id=$id");
    header("Location: courses.php");
    exit();
}

// Handle Edit Prefill
$edit_id = '';
$title = '';
$description = '';
$duration = '';
$level = '';
$instructor_id = '';

if (isset($_GET['edit'])) {
    $edit_id = $_GET['edit'];
    $stmt = $conn->prepare("SELECT * FROM courses WHERE course_id = ?");
    $stmt->bind_param("i", $edit_id);
    $stmt->execute();
    $result_edit = $stmt->get_result();
    if ($result_edit->num_rows === 1) {
        $row = $result_edit->fetch_assoc();
        $title = $row['title'];
        $description = $row['description'];
        $duration = $row['duration'];
        $level = $row['level'];
        $instructor_id = $row['instructor_id'];
    }
}

// Fetch courses and instructors
$result = $conn->query("SELECT courses.*, instructors.name AS instructor FROM courses LEFT JOIN instructors ON courses.instructor_id = instructors.instructor_id");
$instructors = $conn->query("SELECT instructor_id, name FROM instructors");
?>

<h2>Courses</h2>
<form method="post" class="mb-4">
    <input type="hidden" name="id" value="<?= $edit_id ?>">
    <div class="row g-2">
        <div class="col-md-2">
            <input type="text" name="title" class="form-control" placeholder="Title" value="<?= htmlspecialchars($title) ?>" required>
        </div>
        <div class="col-md-2">
            <input type="text" name="description" class="form-control" placeholder="Description" value="<?= htmlspecialchars($description) ?>">
        </div>
        <div class="col-md-2">
            <input type="number" name="duration" class="form-control" placeholder="Duration (hrs)" value="<?= htmlspecialchars($duration) ?>">
        </div>
        <div class="col-md-2">
            <input type="text" name="level" class="form-control" placeholder="Level" value="<?= htmlspecialchars($level) ?>">
        </div>
        <div class="col-md-2">
            <select name="instructor_id" class="form-control">
                <option value="">Select Instructor</option>
                <?php while ($row = $instructors->fetch_assoc()): ?>
                    <option value="<?= $row['instructor_id'] ?>" <?= $instructor_id == $row['instructor_id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($row['name']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" name="save" class="btn btn-success w-100"><?= $edit_id ? 'Update' : 'Save' ?></button>
        </div>
    </div>
</form>

<table class="table table-bordered">
    <thead class="table-dark">
        <tr>
            <th>ID</th><th>Title</th><th>Description</th><th>Duration</th><th>Level</th><th>Instructor</th><th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['course_id'] ?></td>
            <td><?= htmlspecialchars($row['title']) ?></td>
            <td><?= htmlspecialchars($row['description']) ?></td>
            <td><?= $row['duration'] ?> hrs</td>
            <td><?= htmlspecialchars($row['level']) ?></td>
            <td><?= htmlspecialchars($row['instructor']) ?></td>
            <td>
                <a href="courses.php?edit=<?= $row['course_id'] ?>" class="btn btn-sm btn-primary">Edit</a>
                <a href="courses.php?delete=<?= $row['course_id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this course?')">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<?php include 'includes/footer.php'; ?>
