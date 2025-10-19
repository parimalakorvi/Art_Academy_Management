<?php
include 'includes/header.php';
include 'config/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $role = $_POST['role'];
    $username = trim($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    if ($role === 'admin') {
        $stmt = $conn->prepare("INSERT INTO login (username, password, role) VALUES (?, ?, 'admin')");
        $stmt->bind_param("ss", $username, $password);
    } elseif ($role === 'student') {
        $name = $_POST['student_name'] ?? '';
        $phone = $_POST['student_phone'] ?? '';
        $stmt = $conn->prepare("INSERT INTO students (name, email, password, phone) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $username, $password, $phone);
    } elseif ($role === 'instructor') {
        $name = $_POST['instructor_name'] ?? '';
        $phone = $_POST['instructor_phone'] ?? '';
        $expertise = $_POST['expertise'] ?? '';
        $bio = $_POST['bio'] ?? '';
        $stmt = $conn->prepare("INSERT INTO instructors (name, email, password, phone, expertise, bio) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $name, $username, $password, $phone, $expertise, $bio);
    }

    if ($stmt->execute()) {
        header("Location: login.php");
        exit();
    } else {
        $error = "Registration failed: " . $stmt->error;
    }
}
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<div class="container mt-5 mb-5">
    <div class="card shadow-lg">
        <div class="card-body">
            <h2 class="text-center mb-4">🎨 Register to Art Academy</h2>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>

            <form method="post">
                <div class="mb-3">
                    <label for="role" class="form-label">Select Role</label>
                    <select name="role" id="role" class="form-select" onchange="showFields()" required>
                        <option value="">-- Choose Role --</option>
                        <option value="admin">Admin</option>
                        <option value="student">Student</option>
                        <option value="instructor">Instructor</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label>Email (Username)</label>
                    <input type="email" name="username" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>

                <!-- Student Fields -->
                <div id="student-fields" style="display:none;">
                    <div class="mb-3">
                        <label>Full Name</label>
                        <input type="text" name="student_name" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Phone</label>
                        <input type="text" name="student_phone" class="form-control">
                    </div>
                </div>

                <!-- Instructor Fields -->
                <div id="instructor-fields" style="display:none;">
                    <div class="mb-3">
                        <label>Full Name</label>
                        <input type="text" name="instructor_name" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Phone</label>
                        <input type="text" name="instructor_phone" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Expertise</label>
                        <input type="text" name="expertise" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Bio</label>
                        <textarea name="bio" class="form-control" rows="3"></textarea>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-primary px-5">Register</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function showFields() {
    const role = document.getElementById('role').value;
    document.getElementById('student-fields').style.display = (role === 'student') ? 'block' : 'none';
    document.getElementById('instructor-fields').style.display = (role === 'instructor') ? 'block' : 'none';
}
</script>

<?php include 'includes/footer.php'; ?>
