<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include("db/config.php");

// Check if user is admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    header("Location: index.html");
    exit();
}

// Update complaint status and remarks if form submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $complaint_id = $_POST['complaint_id'];
    $status = $_POST['status'];
    $remarks = $_POST['remarks'];

    $update = "UPDATE complaints SET status='$status', admin_remarks='$remarks' WHERE id='$complaint_id'";
    mysqli_query($conn, $update);
}

$result = mysqli_query($conn, "SELECT c.*, u.name, u.email FROM complaints c JOIN users u ON c.user_id = u.id ORDER BY c.created_at DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        body { font-family: Arial; padding: 20px; background: #f0f0f0; }
        table { background: white; width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 10px; border: 1px solid #ccc; text-align: left; }
        th { background: #333; color: white; }
        form { display: inline; }
        h2 { text-align: center; }
    </style>
</head>
<body>
<h2>Admin Dashboard</h2>
<table>
    <tr>
        <th>Student Name</th>
        <th>Email</th>
        <th>Category</th>
        <th>Description</th>
        <th>Status</th>
        <th>Admin Remarks</th>
        <th>Action</th>
    </tr>
    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
    <tr>
        <td><?= $row['name'] ?></td>
        <td><?= $row['email'] ?></td>
        <td><?= $row['category'] ?></td>
        <td><?= $row['description'] ?></td>
        <td><?= $row['status'] ?></td>
        <td><?= $row['admin_remarks'] ?></td>
        <td>
            <form method="POST">
                <input type="hidden" name="complaint_id" value="<?= $row['id'] ?>">
                <select name="status" required>
                    <option value="Pending" <?= $row['status'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
                    <option value="In Progress" <?= $row['status'] == 'In Progress' ? 'selected' : '' ?>>In Progress</option>
                    <option value="Resolved" <?= $row['status'] == 'Resolved' ? 'selected' : '' ?>>Resolved</option>
                </select><br>
                <input type="text" name="remarks" placeholder="Add remarks" value="<?= $row['admin_remarks'] ?>"><br>
                <input type="submit" value="Update">
            </form>
        </td>
    </tr>
    <?php } ?>
</table>
</body>
</html>
