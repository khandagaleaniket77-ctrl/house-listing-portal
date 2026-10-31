<?php
require_once 'config.php';

$error = '';
$success = '';

// Process registration form
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role = isset($_POST['role']) ? $_POST['role'] : 'user';
    
    // Validate role
    if (!in_array($role, ['user', 'admin'])) {
        $role = 'user';
    }
    
    if (empty($email) || empty($password) || empty($confirm_password)) {
        $error = 'Please fill in all fields';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters';
    } elseif ($password !== $confirm_password) {
        $error = 'Passwords do not match';
    } else {
        // Check if email already exists
        $sql = "SELECT id FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $error = 'Email already exists';
        } else {
            // Insert new user
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO users (email, password, role) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sss", $email, $hashed_password, $role);
            
            if ($stmt->execute()) {
                $success = 'Account created successfully! You can now sign in.';
            } else {
                $error = 'Registration failed. Please try again.';
            }
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/app.css">
</head>
<body class="auth-bg">
    <nav class="navbar navbar-expand-lg bg-white border-bottom">
        <div class="container">
            <a class="navbar-brand fw-semibold brand-red" href="index.php">Property Finder</a>
            <div class="ms-auto d-flex gap-2">
                <a class="btn btn-outline-secondary btn-sm" href="login.php">Sign in</a>
            </div>
        </div>
    </nav>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-6">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <h1 class="h4 mb-1">Create account</h1>
                        <p class="text-secondary mb-4">Sign up to get started.</p>

                        <?php if ($error): ?>
                            <div class="alert alert-danger" role="alert"><?php echo htmlspecialchars($error); ?></div>
                        <?php endif; ?>

                        <?php if ($success): ?>
                            <div class="alert alert-success" role="alert"><?php echo htmlspecialchars($success); ?></div>
                        <?php endif; ?>

                        <form method="POST" action="" class="vstack gap-3">
                            <div>
                                <label class="form-label" for="email">Email</label>
                                <input class="form-control" type="email" id="email" name="email" placeholder="name@example.com" required>
                            </div>

                            <div>
                                <label class="form-label" for="password">Password</label>
                                <input class="form-control" type="password" id="password" name="password" placeholder="At least 6 characters" required>
                            </div>

                            <div>
                                <label class="form-label" for="confirm_password">Confirm password</label>
                                <input class="form-control" type="password" id="confirm_password" name="confirm_password" placeholder="Re-enter password" required>
                            </div>

                            <div>
                                <label class="form-label d-block">Account type</label>
                                <div class="row g-2">
                                    <div class="col-12 col-sm-6">
                                        <div class="form-check border rounded-3 p-3">
                                            <input class="form-check-input" type="radio" id="user" name="role" value="user" checked>
                                            <label class="form-check-label fw-semibold" for="user">User</label>
                                            <div class="text-secondary small">Browse and book properties.</div>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-brand-red w-100">Create account</button>
                        </form>

                        <div class="text-center mt-3 small text-secondary">
                            Already have an account? <a href="login.php" class="text-decoration-none">Sign in</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>