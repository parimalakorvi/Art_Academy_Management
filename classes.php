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
    $course_id = $_POST['course_id'];
    $instructor_id = $_POST['instructor_id'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $schedule = $_POST['schedule'];
    $location = $_POST['location'];

    if ($id) {
        $stmt = $conn->prepare("UPDATE classes SET course_id=?, instructor_id=?, start_date=?, end_date=?, schedule=?, location=? WHERE class_id=?");
        $stmt->bind_param("iissssi", $course_id, $instructor_id, $start_date, $end_date, $schedule, $location, $id);
    } else {
        $stmt = $conn->prepare("INSERT INTO classes (course_id, instructor_id, start_date, end_date, schedule, location) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iissss", $course_id, $instructor_id, $start_date, $end_date, $schedule, $location);
    }

    $stmt->execute();
    header("Location: classes.php");
    exit();
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM classes WHERE class_id=$id");
    header("Location: classes.php");
    exit();
}

// Handle Edit Prefill
$edit_id = '';
$course_id = '';
$instructor_id = '';
$start_date = '';
$end_date = '';
$schedule = '';
$location = '';

if (isset($_GET['edit'])) {
    $edit_id = $_GET['edit'];
    $stmt = $conn->prepare("SELECT * FROM classes WHERE class_id = ?");
    $stmt->bind_param("i", $edit_id);
    $stmt->execute();
    $result_edit = $stmt->get_result();
    if ($result_edit->num_rows === 1) {
        $row = $result_edit->fetch_assoc();
        $course_id = $row['course_id'];
        $instructor_id = $row['instructor_id'];
        $start_date = $row['start_date'];
        $end_date = $row['end_date'];
        $schedule = $row['schedule'];
        $location = $row['location'];
    }
}

// Fetch data
$result = $conn->query("SELECT classes.*, courses.title AS course_title, instructors.name AS instructor_name 
    FROM classes 
    LEFT JOIN courses ON classes.course_id = courses.course_id 
    LEFT JOIN instructors ON classes.instructor_id = instructors.instructor_id");

$courses = $conn->query("SELECT course_id, title FROM courses");
$instructors = $conn->query("SELECT instructor_id, name FROM instructors");
?>

<h2>Classes</h2>
<form method="post" class="mb-4">
    <input type="hidden" name="id" value="<?= $edit_id ?>">
    <div class="row g-2">
        <div class="col-md-2">
            <select name="course_id" class="form-control" required>
                <option value="">Select Course</option>
                <?php while ($row = $courses->fetch_assoc()): ?>
                    <option value="<?= $row['course_id'] ?>" <?= $course_id == $row['course_id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($row['title']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="col-md-2">
            <select name="instructor_id" class="form-control" required>
                <option value="">Select Instructor</option>
                <?php while ($row = $instructors->fetch_assoc()): ?>
                    <option value="<?= $row['instructor_id'] ?>" <?= $instructor_id == $row['instructor_id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($row['name']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="col-md-2">
            <input type="date" name="start_date" class="form-control" value="<?= $start_date ?>" placeholder="Start Date" required>
        </div>
        <div class="col-md-2">
            <input type="date" name="end_date" class="form-control" value="<?= $end_date ?>" placeholder="End Date" required>
        </div>
        <div class="col-md-2">
            <input type="text" name="schedule" class="form-control" placeholder="Schedule" value="<?= htmlspecialchars($schedule) ?>" required>
        </div>
        <div class="col-md-2">
            <input type="text" name="location" class="form-control" placeholder="Location" value="<?= htmlspecialchars($location) ?>" required>
        </div>
        <div class="col-md-2 mt-2">
            <button type="submit" name="save" class="btn btn-success w-100"><?= $edit_id ? 'Update' : 'Save' ?></button>
        </div>
    </div>
</form>

<table class="table table-bordered">
    <thead class="table-dark">
        <tr>
            <th>ID</th><th>Course</th><th>Instructor</th><th>Start</th><th>End</th><th>Schedule</th><th>Location</th><th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['class_id'] ?></td>
            <td><?= htmlspecialchars($row['course_title']) ?></td>
            <td><?= htmlspecialchars($row['instructor_name']) ?></td>
            <td><?= $row['start_date'] ?></td>
            <td><?= $row['end_date'] ?></td>
            <td><?= htmlspecialchars($row['schedule']) ?></td>
            <td><?= htmlspecialchars($row['location']) ?></td>
            <td>
                <a href="classes.php?edit=<?= $row['class_id'] ?>" class="btn btn-sm btn-primary">Edit</a>
                <a href="classes.php?delete=<?= $row['class_id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this class?')">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<?php include 'includes/footer.php'; ?>
