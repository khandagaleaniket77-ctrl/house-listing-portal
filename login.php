<?php
require_once 'config.php';

$error = '';
$success = '';

// Process login form
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];
    
    if (empty($email) || empty($password)) {
        $error = 'Please fill in all fields';
    } else {
        // Check user credentials
        $sql = "SELECT id, email, password, role FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();
            
            if (password_verify($password, $user['password'])) {
                // Login successful
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['role'] = $user['role'];
                
                // Handle "Remember me"
                if (isset($_POST['remember'])) {
                    setcookie('user_email', $email, time() + (86400 * 30), "/");
                }
                
                // Redirect based on role
                if ($user['role'] == 'admin') {
                    header("Location: admin_panel.php");
                } else {
                    header("Location: index123.php");
                }
                exit();
            } else {
                $error = 'Invalid email or password';
            }
        } else {
            $error = 'Invalid email or password';
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
    <title>Sign In</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/app.css">
</head>
<body class="auth-bg">
    <nav class="navbar navbar-expand-lg bg-white border-bottom">
        <div class="container">
            <a class="navbar-brand fw-semibold brand-red" href="index.php">Property Finder</a>
            <div class="ms-auto d-flex gap-2">
                <a class="btn btn-outline-secondary btn-sm" href="index.php">Home</a>
                <a class="btn btn-brand-red btn-sm" href="register.php">Create account</a>
            </div>
        </div>
    </nav>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-5">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <h1 class="h4 mb-1">Sign in</h1>
                        <p class="text-secondary mb-4">Enter your credentials to continue.</p>

                        <?php if ($error): ?>
                            <div class="alert alert-danger" role="alert"><?php echo htmlspecialchars($error); ?></div>
                        <?php endif; ?>

                        <?php if ($success): ?>
                            <div class="alert alert-success" role="alert"><?php echo htmlspecialchars($success); ?></div>
                        <?php endif; ?>

                        <form method="POST" action="" class="vstack gap-3">
                            <div>
                                <label for="email" class="form-label">Email</label>
                                <input
                                    class="form-control"
                                    type="email"
                                    id="email"
                                    name="email"
                                    placeholder="name@example.com"
                                    value="<?php echo isset($_COOKIE['user_email']) ? htmlspecialchars($_COOKIE['user_email']) : ''; ?>"
                                    required
                                >
                            </div>

                            <div>
                                <label for="password" class="form-label">Password</label>
                                <div class="input-group">
                                    <input
                                        class="form-control"
                                        type="password"
                                        id="password"
                                        name="password"
                                        placeholder="Your password"
                                        required
                                    >
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword()" aria-label="Show or hide password">
                                        Show
                                    </button>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                                    <label class="form-check-label" for="remember">Remember me</label>
                                </div>
                                <a href="#" class="link-secondary small text-decoration-none">Forgot password?</a>
                            </div>

                            <button type="submit" class="btn btn-brand-red w-100">Sign in</button>
                        </form>

                        <div class="text-center mt-3 small text-secondary">
                            Don't have an account? <a href="register.php" class="text-decoration-none">Create one</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>