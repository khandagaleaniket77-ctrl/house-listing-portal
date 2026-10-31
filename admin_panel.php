<?php
require_once 'config.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Handle user deletion
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $delete_id = intval($_GET['delete']);
    
    // Prevent admin from deleting themselves
    if ($delete_id != $_SESSION['user_id']) {
        $sql = "DELETE FROM users WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $delete_id);
        $stmt->execute();
        $stmt->close();
        header("Location: admin_panel.php?success=User deleted successfully");
        exit();
    }
}

// Handle role update
if (isset($_POST['update_role']) && isset($_POST['user_id']) && isset($_POST['new_role'])) {
    $user_id = intval($_POST['user_id']);
    $new_role = $_POST['new_role'];
    
    // Validate role
    if (in_array($new_role, ['user', 'admin']) && $user_id != $_SESSION['user_id']) {
        $sql = "UPDATE users SET role = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $new_role, $user_id);
        $stmt->execute();
        $stmt->close();
        header("Location: admin_panel.php?success=User role updated successfully");
        exit();
    }
}

// Get all users
$sql = "SELECT id, email, role, created_at FROM users ORDER BY created_at DESC";
$result = $conn->query($sql);
$users = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
}

// Get statistics
$total_users = count($users);
$admin_count = count(array_filter($users, function($user) { return $user['role'] == 'admin'; }));
$user_count = count(array_filter($users, function($user) { return $user['role'] == 'user'; }));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Property Finder</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-color: #f5f5f5;
        }

        .header {
            background-color: #D8232A;
            color: white;
            padding: 15px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .logo {
            font-size: 22px;
            font-weight: 600;
            font-family: 'Montserrat', Arial, sans-serif;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .user-email {
            font-size: 14px;
        }

        .logout-btn {
            background: white;
            color: #D8232A;
            border: none;
            padding: 8px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 500;
            text-decoration: none;
            display: inline-block;
        }

        .logout-btn:hover {
            background-color: #f0f0f0;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 30px 20px;
        }

        .page-title {
            font-size: 28px;
            margin-bottom: 10px;
            color: #1a1a1a;
        }

        .page-subtitle {
            color: #666;
            margin-bottom: 30px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .stat-label {
            color: #666;
            font-size: 14px;
            margin-bottom: 8px;
        }

        .stat-value {
            font-size: 32px;
            font-weight: 600;
            color: #D8232A;
        }

        .users-table-container {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .table-header {
            padding: 20px;
            border-bottom: 1px solid #e0e0e0;
        }

        .table-header h2 {
            font-size: 20px;
            color: #1a1a1a;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background-color: #f8f8f8;
        }

        th {
            text-align: left;
            padding: 15px 20px;
            font-weight: 600;
            color: #333;
            font-size: 14px;
        }

        td {
            padding: 15px 20px;
            border-top: 1px solid #e0e0e0;
            font-size: 14px;
        }

        tbody tr:hover {
            background-color: #f9f9f9;
        }

        .role-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 500;
        }

        .role-admin {
            background-color: #ffeaea;
            color: #D8232A;
        }

        .role-user {
            background-color: #e8f4fd;
            color: #1976d2;
        }

        .action-btn {
            padding: 6px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 13px;
            margin-right: 8px;
            text-decoration: none;
            display: inline-block;
        }

        .btn-edit {
            background-color: #5b7cee;
            color: white;
        }

        .btn-edit:hover {
            background-color: #4a6dd8;
        }

        .btn-delete {
            background-color: #f44336;
            color: white;
        }

        .btn-delete:hover {
            background-color: #d32f2f;
        }

        .btn-delete:disabled {
            background-color: #ccc;
            cursor: not-allowed;
        }

        .success-message {
            background: #efe;
            color: #2d7a2d;
            padding: 12px 20px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            background-color: white;
            margin: 15% auto;
            padding: 30px;
            border-radius: 8px;
            width: 90%;
            max-width: 400px;
        }

        .modal-header {
            font-size: 20px;
            margin-bottom: 20px;
            color: #1a1a1a;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
            color: #333;
        }

        .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }

        .modal-buttons {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
        }

        .btn-cancel {
            background-color: #f5f5f5;
            color: #333;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .btn-submit {
            background-color: #D8232A;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .btn-submit:hover {
            background-color: #c8040a;
        }

        .header a{
            background-color: white;
              padding: 6px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 13px;
            margin-right: 8px;
            text-decoration: none;
            display: inline-block;
            color: #D8232A;
            font-weight: 500;
            
        }

        .header a:hover{
            background-color: #f8f4f4;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">FindMySpace - Admin Panel</div>
        <a href="all_appointments.php">All Appointments</a>
        <div class="user-info">
            <span class="user-email"><?php echo htmlspecialchars($_SESSION['email']); ?></span>
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>
    </div>

    <div class="container">
        <h1 class="page-title">Dashboard</h1>
        <p class="page-subtitle">Manage users and system settings</p>

        <?php if (isset($_GET['success'])): ?>
            <div class="success-message"><?php echo htmlspecialchars($_GET['success']); ?></div>
        <?php endif; ?>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-label">Total Users</div>
                <div class="stat-value"><?php echo $total_users; ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Administrators</div>
                <div class="stat-value"><?php echo $admin_count; ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Regular Users</div>
                <div class="stat-value"><?php echo $user_count; ?></div>
            </div>
        </div>

        <div class="users-table-container">
            <div class="table-header">
                <h2>User Management</h2>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Registered</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?php echo $user['id']; ?></td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        <td>
                            <span class="role-badge role-<?php echo $user['role']; ?>">
                                <?php echo ucfirst($user['role']); ?>
                            </span>
                        </td>
                        <td><?php echo date('M d, Y', strtotime($user['created_at'])); ?></td>
                        <td>
                            <?php if ($user['id'] != $_SESSION['user_id']): ?>
                                <button class="action-btn btn-edit" onclick="openEditModal(<?php echo $user['id']; ?>, '<?php echo $user['role']; ?>')">
                                    Change Role
                                </button>
                                <a href="?delete=<?php echo $user['id']; ?>" 
                                   class="action-btn btn-delete" 
                                   onclick="return confirm('Are you sure you want to delete this user?')">
                                    Delete
                                </a>
                            <?php else: ?>
                                <span style="color: #999; font-size: 13px;">Current User</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Edit Role Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <h2 class="modal-header">Change User Role</h2>
            <form method="POST" action="">
                <input type="hidden" name="user_id" id="edit_user_id">
                <div class="form-group">
                    <label for="new_role">Select New Role</label>
                    <select name="new_role" id="new_role" required>
                        <option value="user">User</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <div class="modal-buttons">
                    <button type="button" class="btn-cancel" onclick="closeEditModal()">Cancel</button>
                    <button type="submit" name="update_role" class="btn-submit">Update Role</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openEditModal(userId, currentRole) {
            document.getElementById('edit_user_id').value = userId;
            document.getElementById('new_role').value = currentRole;
            document.getElementById('editModal').style.display = 'block';
        }

        function closeEditModal() {
            document.getElementById('editModal').style.display = 'none';
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('editModal');
            if (event.target == modal) {
                closeEditModal();
            }
        }
    </script>
</body>
</html>