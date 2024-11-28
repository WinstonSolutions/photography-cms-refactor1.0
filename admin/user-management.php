<?php

require_once  __DIR__ . '/../includes/functions.php';
require_once  __DIR__ . '/../classes/User.php';



$user = new User();
$users = $user->getAllUsers(); // Get all users

// Handle user deletion
if (isset($_POST['delete_user'])) {
    $user_id = clean_input($_POST['user_id']);
    $user->deleteUser($user_id);
    header('Location: index.php?page=users'); // Refresh page to show updated user list
    exit();
}

?>

<div class="admin-dashboard">
    <h1>User Management</h1>
    
    <div class="admin-section">
        <h2>All Users</h2>
        
        <div class="user-list">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($users as $user): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($user['id']); ?></td>
                        <td><?php echo htmlspecialchars($user['username']); ?></td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        <td><?php echo htmlspecialchars($user['role']); ?></td>
                        <td>
                            <?php if ($_SESSION['user_role'] === 'admin'): ?>
                                <a href="post-management.php?delete_id=<?php echo $img['id']; ?>"
                                    onclick="return confirm('Are you sure you want to delete this image?');"
                                    class="delete-btn delete-active">Delete</a>
                            <?php else: ?>
                                <a href="#" onclick="alert('You do not have permission to delete this User'); return false;"
                                    class="delete-btn delete-inactive">Delete</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
.admin-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

.admin-table th,
.admin-table td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

.admin-table th {
    background-color: #f8f9fa;
    font-weight: bold;
}

.admin-table tr:hover {
    background-color: #f5f5f5;
}

.delete-btn {
        padding: 5px 10px;
        text-decoration: none;
        border-radius: 3px;
        transition: background-color 0.3s ease;
    }

    .delete-active {
        background-color: red;
        color: white;
        cursor: pointer;
    }

    .delete-active:hover {
        background-color: darkred;
    }

    .delete-inactive {
        background-color: #cccccc;
        color: #666666;
        cursor: not-allowed;
    }

</style>

