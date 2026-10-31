<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quick Appointment Booking</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/app.css">
</head>
<body class="bg-body-tertiary">
    <nav class="navbar navbar-expand-lg bg-white border-bottom">
        <div class="container">
            <a class="navbar-brand fw-semibold brand-red" href="index123.php">FindMySpace</a>
            <div class="ms-auto">
                <a href="index.php" class="btn btn-outline-secondary btn-sm">Back to Home</a>
            </div>
        </div>
    </nav>

    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-8 col-xl-7">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-brand-red text-white text-center" style="background-color: red;">
                        <h1 class="h4 mb-1">📅 Quick Appointment Booking</h1>
                        <p class="mb-0 small opacity-75">Schedule your appointment in seconds</p>
                    </div>
                    <div class="card-body p-4">
            <?php
            // Database configuration
            $host = 'localhost';
            $username = 'root'; // Change this to your MySQL username
            $password = ''; // Change this to your MySQL password
            $database = 'appointments';
            
            // Initialize variables
            $message = '';
            $messageType = '';
            
            // Create database connection
            $conn = new mysqli($host, $username, $password);
            
            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }
            
            // Create database if it doesn't exist
            $sql = "CREATE DATABASE IF NOT EXISTS $database";
            $conn->query($sql);
            
            // Select database
            $conn->select_db($database);
            
            // Create table if it doesn't exist
            $createTable = "CREATE TABLE IF NOT EXISTS bookings (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                email VARCHAR(255) NOT NULL,
                phone VARCHAR(20) NOT NULL,
                appointment_date DATE NOT NULL,
                appointment_time TIME NOT NULL,
                service VARCHAR(100) NOT NULL,
                notes TEXT,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )";
            $conn->query($createTable);
            
            // Handle form submission
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if (isset($_POST['action']) && $_POST['action'] === 'delete') {
                    // Handle delete
                    $deleteId = intval($_POST['appointment_id']);
                    $stmt = $conn->prepare("DELETE FROM bookings WHERE id = ?");
                    $stmt->bind_param("i", $deleteId);
                    
                    if ($stmt->execute()) {
                        $message = 'Appointment deleted successfully!';
                        $messageType = 'success';
                    } else {
                        $message = 'Error deleting appointment.';
                        $messageType = 'error';
                    }
                    $stmt->close();
                } else {
                    // Handle new appointment
                    $name = trim($_POST['name'] ?? '');
                    $email = trim($_POST['email'] ?? '');
                    $phone = trim($_POST['phone'] ?? '');
                    $date = $_POST['date'] ?? '';
                    $time = $_POST['time'] ?? '';
                    $service = $_POST['service'] ?? '';
                    $notes = trim($_POST['notes'] ?? '');
                    
                    // Validation
                    if (empty($name) || empty($email) || empty($phone) || empty($date) || empty($time) || empty($service)) {
                        $message = 'Please fill in all required fields.';
                        $messageType = 'error';
                    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        $message = 'Please enter a valid email address.';
                        $messageType = 'error';
                    } else {
                        // Insert appointment into database
                        $stmt = $conn->prepare("INSERT INTO bookings (name, email, phone, appointment_date, appointment_time, service, notes) VALUES (?, ?, ?, ?, ?, ?, ?)");
                        $stmt->bind_param("sssssss", $name, $email, $phone, $date, $time, $service, $notes);
                        
                        if ($stmt->execute()) {
                            $message = 'Appointment booked successfully! We will contact you soon.';
                            $messageType = 'success';
                            // Clear form
                            $_POST = [];
                        } else {
                            $message = 'Error booking appointment. Please try again.';
                            $messageType = 'error';
                        }
                        $stmt->close();
                    }
                }
            }
            
            // Display message
            if ($message) {
                $alertClass = $messageType === 'success' ? 'alert-success' : 'alert-danger';
                echo "<div class='alert $alertClass mb-3'>$message</div>";
            }
            ?>

            <form method="POST" action="">
                <div class="mb-3">
                    <label for="name" class="form-label">Full Name *</label>
                    <input type="text" id="name" name="name" class="form-control" required 
                           value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>">
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email Address *</label>
                    <input type="email" id="email" name="email" class="form-control" required 
                           value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                </div>

                <div class="mb-3">
                    <label for="phone" class="form-label">Phone Number *</label>
                    <input type="tel" id="phone" name="phone" class="form-control" required 
                           value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>">
                </div>

                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <label for="date" class="form-label">Preferred Date *</label>
                        <input type="date" id="date" name="date" class="form-control" required 
                           min="<?php echo date('Y-m-d'); ?>"
                           value="<?php echo htmlspecialchars($_POST['date'] ?? ''); ?>">
                    </div>
                    <div class="col-12 col-md-6">
                        <label for="time" class="form-label">Preferred Time *</label>
                        <input type="time" id="time" name="time" class="form-control" required 
                           value="<?php echo htmlspecialchars($_POST['time'] ?? ''); ?>">
                </div>
                </div>

                <div class="mt-3 mb-3">
                    <label for="service" class="form-label">Service Type *</label>
                    <select id="service" name="service" class="form-select" required>
                        <option value="">Select a service</option>
                        <option value="consultation" <?php echo (($_POST['service'] ?? '') === 'consultation') ? 'selected' : ''; ?>>Consultation</option>
                        <option value="checkup" <?php echo (($_POST['service'] ?? '') === 'checkup') ? 'selected' : ''; ?>>General Checkup</option>
                        <option value="followup" <?php echo (($_POST['service'] ?? '') === 'followup') ? 'selected' : ''; ?>>Follow-up</option>
                        <option value="treatment" <?php echo (($_POST['service'] ?? '') === 'treatment') ? 'selected' : ''; ?>>Treatment</option>
                        <option value="other" <?php echo (($_POST['service'] ?? '') === 'other') ? 'selected' : ''; ?>>Other</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="notes" class="form-label">Additional Notes</label>
                    <textarea id="notes" name="notes" class="form-control"
                              placeholder="Any specific requirements or information..."><?php echo htmlspecialchars($_POST['notes'] ?? ''); ?></textarea>
                </div>

                <button type="submit" class="btn btn-brand-red w-100" style="background-color: red; color: white;">Book Appointment</button>
            </form>
                    </div>
                </div>

                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white">
                        <h2 class="h5 mb-0">Recent Appointments</h2>
                    </div>
                    <div class="card-body">
            <?php
            // Fetch recent appointments
            $result = $conn->query("SELECT * FROM bookings ORDER BY created_at DESC LIMIT 5");
            
            if ($result->num_rows === 0) {
                echo "<p class='text-secondary mb-0'>No appointments yet.</p>";
            } else {
                while ($apt = $result->fetch_assoc()) {
                    echo "<div class='card mb-3 border-0 shadow-sm'>";
                    echo "<div class='card-body'>";
                    echo "<h3 class='h6 mb-2'>" . htmlspecialchars($apt['name']) . "</h3>";
                    echo "<p class='mb-1'><strong>Email:</strong> " . htmlspecialchars($apt['email']) . "</p>";
                    echo "<p class='mb-1'><strong>Phone:</strong> " . htmlspecialchars($apt['phone']) . "</p>";
                    echo "<p class='mb-1'><strong>Date:</strong> " . date('F j, Y', strtotime($apt['appointment_date'])) . " at " . date('g:i A', strtotime($apt['appointment_time'])) . "</p>";
                    echo "<p class='mb-1'><strong>Service:</strong> " . ucfirst(htmlspecialchars($apt['service'])) . "</p>";
                    if (!empty($apt['notes'])) {
                        echo "<p class='mb-1'><strong>Notes:</strong> " . htmlspecialchars($apt['notes']) . "</p>";
                    }
                    echo "<form method='POST' class='mt-2'>";
                    echo "<input type='hidden' name='action' value='delete'>";
                    echo "<input type='hidden' name='appointment_id' value='" . $apt['id'] . "'>";
                    echo "<button type='submit' class='btn btn-sm btn-outline-danger' onclick='return confirm(\"Are you sure you want to delete this appointment?\")'>Delete</button>";
                    echo "</form></div></div>";
                }
            }
            
            // Close connection
            $conn->close();
            ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>