<?php
session_start();
include 'includes/header.php';
include 'config/db_connect.php';
?>

<div style="margin: 20px;">
    <a href="dashboard.php" class="btn btn-secondary">⬅ Back to Dashboard</a>
</div>
<?php
// Variables
$edit_id = '';
$name = '';
$email = '';
$phone = '';

// Handle Edit Prefill
if (isset($_GET['edit'])) {
    $edit_id = $_GET['edit'];
    $stmt = $conn->prepare("SELECT * FROM students WHERE ID = ?");
    $stmt->bind_param("i", $edit_id);
    $stmt->execute();
    $result_edit = $stmt->get_result();
    if ($result_edit->num_rows === 1) {
        $row = $result_edit->fetch_assoc();
        $name = $row['name'];
        $email = $row['email'];
        $phone = $row['phone'];
    }
}

// Handle Add/Update
if (isset($_POST['save'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    if ($id) {
        // Update
        $stmt = $conn->prepare("UPDATE students SET name=?, email=?, phone=? WHERE ID=?");
        $stmt->bind_param("sssi", $name, $email, $phone, $id);
    } else {
        // Insert
        $stmt = $conn->prepare("INSERT INTO students (name, email, phone) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $phone);
    }
    $stmt->execute();
    header("Location: students.php");
    exit();
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM students WHERE ID = $id");
    header("Location: students.php");
    exit();
}

// Fetch All Students
$result = $conn->query("SELECT * FROM students ORDER BY ID ASC");
?>

<div class="container mt-4">
    <h2>Students</h2>

    <form method="post" class="mb-4">
        <input type="hidden" name="id" value="<?= htmlspecialchars($edit_id) ?>">
        <div class="row g-2 mb-2">
            <div class="col-md-3">
                <input type="text" name="name" class="form-control" placeholder="Name" required value="<?= htmlspecialchars($name) ?>">
            </div>
            <div class="col-md-3">
                <input type="email" name="email" class="form-control" placeholder="Email" required value="<?= htmlspecialchars($email) ?>">
            </div>
            <div class="col-md-3">
                <input type="text" name="phone" class="form-control" placeholder="Phone" value="<?= htmlspecialchars($phone) ?>">
            </div>
            <div class="col-md-3">
                <button type="submit" name="save" class="btn btn-success w-100"><?= $edit_id ? 'Update' : 'Save' ?></button>
            </div>
        </div>
    </form>

    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>ID</th><th>Name</th><th>Email</th><th>Phone</th><th>Registered</th><th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['ID'] ?></td>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['email']) ?></td>
                    <td><?= htmlspecialchars($row['phone']) ?></td>
                    <td><?= $row['registration_date'] ?></td>
                    <td>
                        <a href="students.php?edit=<?= $row['ID'] ?>" class="btn btn-sm btn-primary">Edit</a>
                        <a href="students.php?delete=<?= $row['ID'] ?>" onclick="return confirm('Are you sure?')" class="btn btn-sm btn-danger">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include 'includes/footer.php'; ?>
