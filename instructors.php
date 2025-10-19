<?php
session_start();
include 'includes/header.php';
include 'config/db_connect.php';
?>

<div style="margin: 20px;">
    <a href="dashboard.php" class="btn btn-secondary">⬅ Back to Dashboard</a>
</div>
<?php
// Initialize variables
$edit_id = '';
$name = '';
$email = '';
$phone = '';
$expertise = '';
$bio = '';

// Handle Insert/Update
if (isset($_POST['save'])) {
    $id = $_POST['id'] ?? '';
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $expertise = $_POST['expertise'];
    $bio = $_POST['bio'];

    if ($id) {
        // UPDATE
        $stmt = $conn->prepare("UPDATE instructors SET name=?, email=?, phone=?, expertise=?, bio=? WHERE instructor_id=?");
        $stmt->bind_param("sssssi", $name, $email, $phone, $expertise, $bio, $id);
    } else {
        // INSERT
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO instructors (name, email, password, phone, expertise, bio) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $name, $email, $password, $phone, $expertise, $bio);
    }

    $stmt->execute();
    header("Location: instructors.php");
    exit();
}

// Handle Edit Prefill
if (isset($_GET['edit'])) {
    $edit_id = $_GET['edit'];
    $stmt = $conn->prepare("SELECT * FROM instructors WHERE instructor_id = ?");
    $stmt->bind_param("i", $edit_id);
    $stmt->execute();
    $result_edit = $stmt->get_result();
    if ($result_edit->num_rows === 1) {
        $row = $result_edit->fetch_assoc();
        $name = $row['name'];
        $email = $row['email'];
        $phone = $row['phone'];
        $expertise = $row['expertise'];
        $bio = $row['bio'];
    }
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM instructors WHERE instructor_id=$id");
    header("Location: instructors.php");
    exit();
}

// Fetch all instructors
$result = $conn->query("SELECT * FROM instructors");
?>

<h2>Instructors</h2>
<form method="post" class="mb-4">
    <input type="hidden" name="id" value="<?= $edit_id ?>">
    <div class="row g-2">
        <div class="col-md-2">
            <input type="text" name="name" class="form-control" placeholder="Name" value="<?= htmlspecialchars($name) ?>" required>
        </div>
        <div class="col-md-2">
            <input type="email" name="email" class="form-control" placeholder="Email" value="<?= htmlspecialchars($email) ?>" required>
        </div>
        <div class="col-md-2">
            <input type="text" name="phone" class="form-control" placeholder="Phone" value="<?= htmlspecialchars($phone) ?>">
        </div>
        <div class="col-md-2">
            <input type="text" name="expertise" class="form-control" placeholder="Expertise" value="<?= htmlspecialchars($expertise) ?>">
        </div>
        <div class="col-md-2">
            <input type="text" name="bio" class="form-control" placeholder="Bio" value="<?= htmlspecialchars($bio) ?>">
        </div>
        <div class="col-md-2">
            <?php if (!$edit_id): ?>
                <input type="password" name="password" class="form-control" placeholder="Password" required>
            <?php endif; ?>
            <button type="submit" name="save" class="btn btn-success mt-2 w-100"><?= $edit_id ? 'Update' : 'Save' ?></button>
        </div>
    </div>
</form>

<table class="table table-bordered">
    <thead class="table-dark">
        <tr>
            <th>ID</th><th>Name</th><th>Email</th><th>Phone</th><th>Expertise</th><th>Bio</th><th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['instructor_id'] ?></td>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= htmlspecialchars($row['email']) ?></td>
            <td><?= htmlspecialchars($row['phone']) ?></td>
            <td><?= htmlspecialchars($row['expertise']) ?></td>
            <td><?= htmlspecialchars($row['bio']) ?></td>
            <td>
                <a href="instructors.php?edit=<?= $row['instructor_id'] ?>" class="btn btn-sm btn-primary">Edit</a>
                <a href="instructors.php?delete=<?= $row['instructor_id'] ?>" onclick="return confirm('Delete this instructor?')" class="btn btn-sm btn-danger">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<?php include 'includes/footer.php'; ?>
