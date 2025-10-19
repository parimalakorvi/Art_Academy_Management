<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Art Academy Management System</title>
    <link rel="stylesheet" href="css/style.css"> <!-- optional if using separate CSS -->
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-container {
            background: white;
            padding: 40px;
            border-radius: 12px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            text-align: center;
        }

        .logo {
            font-size: 2.5rem;
            margin-bottom: 10px;
            color: black;
        }

        .login-title {
            font-size: 1.8rem;
            font-weight: bold;
            color: #333;
            margin-bottom: 30px;
        }

        .role-selector {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-bottom: 20px;
        }

        .role-btn {
            flex: 1;
            padding: 10px;
            border: 2px solid #667eea;
            border-radius: 25px;
            background-color: #fff;
            color: #667eea;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .role-btn.active,
        .role-btn:hover {
            background-color: #667eea;
            color: #fff;
        }

        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 16px;
        }

        .login-btn {
            width: 100%;
            padding: 12px;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: bold;
            font-size: 16px;
            cursor: pointer;
        }

        .error {
            color: red;
            margin-top: 10px;
            font-size: 14px;
        }
    </style>

    <script>
        let currentRole = 'admin';

        function selectRole(role) {
            currentRole = role;
            document.getElementById('role').value = role;

            const buttons = document.querySelectorAll('.role-btn');
            buttons.forEach(btn => btn.classList.remove('active'));
            document.getElementById(role + '-btn').classList.add('active');
        }

        window.onload = () => {
            selectRole('admin');
        };
    </script>
</head>
<body>
    <div class="login-container">
        <div class="logo">🎨</div>
        <h2 class="login-title">Art Academy Management</h2>

        <div class="role-selector">
            <button id="admin-btn" class="role-btn" onclick="selectRole('admin')">Admin</button>
            <button id="student-btn" class="role-btn" onclick="selectRole('student')">Student</button>
            <button id="instructor-btn" class="role-btn" onclick="selectRole('instructor')">Instructor</button>
        </div>

        <form method="POST" action="auth.php">
            <input type="hidden" name="role" id="role" value="admin">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" name="username" required id="username">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" required id="password">
            </div>
            <button class="login-btn" type="submit">Login</button>
        </form>

        <?php if (isset($_SESSION['login_error'])): ?>
            <div class="error"><?= $_SESSION['login_error'] ?></div>
            <?php unset($_SESSION['login_error']); ?>
        <?php endif; ?>
    </div>
</body>
</html>
