<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include("db/config.php");

// Check if user is logged in and is a student
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'student') {
    header("Location: index.html");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];

// Handle new complaint submission
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['category']) && isset($_POST['description'])) {
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    $sql = "INSERT INTO complaints (user_id, category, description) VALUES ('$user_id', '$category', '$description')";
    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Complaint submitted successfully!');</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        h2 {
            text-align: center;
        }
        .container {
            width: 90%;
            max-width: 700px;
            margin: auto;
        }
        table {
            width: 100%;
            margin-top: 30px;
            border-collapse: collapse;
            background-color: #fff;
        }
        table, th, td {
            border: 1px solid #ccc;
            padding: 10px;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        .logout {
            text-align: right;
            margin-bottom: 10px;
        }
        .logout a {
            color: red;
            text-decoration: none;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="logout">
        <a href="logout.php">Logout</a>
    </div>

    <h2>Welcome, <?php echo $user_name; ?> 👋</h2>

    <!-- Complaint Form -->
    <form method="POST" action="">
        <label>Category:</label><br>
        <select name="category" required>
            <option value="">-- Select Category --</option>
            <option value="Electricity">Electricity</option>
            <option value="Water">Water</option>
            <option value="Cleaning">Cleaning</option>
            <option value="Food">Food</option>
            <option value="Others">Others</option>
        </select><br><br>

        <label>Description:</label><br>
        <textarea name="description" rows="4" style="width:100%;" required></textarea><br><br>

        <input type="submit" value="Submit Complaint">
    </form>

    <!-- Show previous complaints -->
    <h3>Your Complaints:</h3>
    <table>
        <tr>
            <th>Category</th>
            <th>Description</th>
            <th>Status</th>
            <th>Admin Remarks</th>
            <th>Date</th>
        </tr>

        <?php
        $sql = "SELECT * FROM complaints WHERE user_id = '$user_id' ORDER BY created_at DESC";
        $result = mysqli_query($conn, $sql);

        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>".$row['category']."</td>";
            echo "<td>".$row['description']."</td>";
            echo "<td>".$row['status']."</td>";
            echo "<td>".$row['admin_remarks']."</td>";
            echo "<td>".$row['created_at']."</td>";
            echo "</tr>";
        }
        ?>
    </table>
</div>
</body>
</html>
