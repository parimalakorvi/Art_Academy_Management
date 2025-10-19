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
    $amount = $_POST['amount'];
    $payment_date = $_POST['payment_date'];
    $method = $_POST['method'];
    $status = $_POST['status'];

    if ($id) {
        $stmt = $conn->prepare("UPDATE payments SET student_id=?, amount=?, payment_date=?, method=?, status=? WHERE payment_id=?");
        $stmt->bind_param("sdsssi", $student_id, $amount, $payment_date, $method, $status, $id);
    } else {
        $stmt = $conn->prepare("INSERT INTO payments (ID, amount, payment_date, method, status) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sdsss", $student_id, $amount, $payment_date, $method, $status);
    }
    $stmt->execute();
    header("Location: payments.php");
    exit();
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM payments WHERE payment_id=$id");
    header("Location: payments.php");
    exit();
}

// Edit Prefill
$edit_id = '';
$student_id = '';
$amount = '';
$payment_date = '';
$method = '';
$status = '';

if (isset($_GET['edit'])) {
    $edit_id = $_GET['edit'];
    $stmt = $conn->prepare("SELECT * FROM payments WHERE payment_id = ?");
    $stmt->bind_param("i", $edit_id);
    $stmt->execute();
    $result_edit = $stmt->get_result();
    if ($result_edit->num_rows === 1) {
        $row = $result_edit->fetch_assoc();
        $student_id = $row['student_id'];
        $amount = $row['amount'];
        $payment_date = $row['payment_date'];
        $method = $row['method'];
        $status = $row['status'];
    }
}

$result = $conn->query("SELECT * FROM payments");
?>

<h2>Payments</h2>
<form method="post" class="mb-4">
    <input type="hidden" name="id" value="<?= $edit_id ?>">
    <div class="row g-2">
        <div class="col-md-2"><input type="text" name="student_id" class="form-control" placeholder="Student ID" value="<?= htmlspecialchars($student_id) ?>" required></div>
        <div class="col-md-2"><input type="number" step="0.01" name="amount" class="form-control" placeholder="Amount" value="<?= htmlspecialchars($amount) ?>" required></div>
        <div class="col-md-2"><input type="date" name="payment_date" class="form-control" value="<?= htmlspecialchars($payment_date) ?>"></div>
        <div class="col-md-2"><input type="text" name="method" class="form-control" placeholder="Method" value="<?= htmlspecialchars($method) ?>"></div>
        <div class="col-md-2"><input type="text" name="status" class="form-control" placeholder="Status" value="<?= htmlspecialchars($status) ?>"></div>
        <div class="col-md-2">
            <button type="submit" name="save" class="btn btn-success w-100"><?= $edit_id ? 'Update' : 'Save' ?></button>
        </div>
    </div>
</form>

<table class="table table-bordered">
    <thead class="table-dark"><tr><th>ID</th><th>Student ID</th><th>Amount</th><th>Date</th><th>Method</th><th>Status</th><th>Actions</th></tr></thead>
    <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['payment_id'] ?></td>
            <td><?= $row['ID'] ?></td>
            <td><?= $row['amount'] ?></td>
            <td><?= $row['payment_date'] ?></td>
            <td><?= $row['method'] ?></td>
            <td><?= $row['status'] ?></td>
            <td>
                <a href="payments.php?edit=<?= $row['payment_id'] ?>" class="btn btn-sm btn-primary">Edit</a>
                <a href="payments.php?delete=<?= $row['payment_id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this payment?')">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<?php include 'includes/footer.php'; ?>
