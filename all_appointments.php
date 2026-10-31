<?php
// ================= DATABASE CONNECTION =================
$servername = "localhost";
$username   = "root";
$password   = "";
$database   = "appointments";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ================= FETCH DATA =================
$sql = "SELECT id, name, email, phone, appointment_date, appointment_time, service, notes, created_at
        FROM bookings
        ORDER BY created_at DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Appointments</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f4f6f9;
            padding: 40px;
        }
        .booking-card {
            border-radius: 14px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            transition: transform 0.2s ease;
        }
        .booking-card:hover {
            transform: translateY(-5px);
        }
        .service-badge {
            background-color: #667eea;
        }
        .meta {
            font-size: 0.85rem;
            color: #6c757d;
        }
    </style>
</head>
<body>

<div class="container">
    <h2 class="mb-4 text-center fw-bold">📅 Appointment Bookings</h2>

    <div class="row g-4">
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card booking-card h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-2">
                                <h5 class="fw-bold mb-0">
                                    <?= htmlspecialchars($row['name']) ?>
                                </h5>
                                <span class="meta">#<?= $row['id'] ?></span>
                            </div>

                            <p class="mb-1"><strong>Email:</strong> <?= htmlspecialchars($row['email']) ?></p>
                            <p class="mb-1"><strong>Phone:</strong> <?= htmlspecialchars($row['phone']) ?></p>

                            <p class="mb-1">
                                <strong>Date:</strong>
                                <?= date("d M Y", strtotime($row['appointment_date'])) ?>
                            </p>

                            <p class="mb-1">
                                <strong>Time:</strong>
                                <?= date("h:i A", strtotime($row['appointment_time'])) ?>
                            </p>

                            <span class="badge service-badge mb-2">
                                <?= htmlspecialchars($row['service']) ?>
                            </span>

                            <?php if (!empty($row['notes'])): ?>
                                <p class="mt-2 text-muted small">
                                    <?= htmlspecialchars($row['notes']) ?>
                                </p>
                            <?php endif; ?>

                            <p class="meta mt-3">
                                Created: <?= date("d M Y, h:i A", strtotime($row['created_at'])) ?>
                            </p>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="text-center text-muted">No bookings found.</p>
        <?php endif; ?>
    </div>
</div>

</body>
</html>

<?php
$conn->close();
?>
