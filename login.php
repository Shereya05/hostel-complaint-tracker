<?php
session_start();
include("../db/config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Check if user exists
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);

        // Verify password
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_role'] = $user['role'];

            // Redirect based on role
            if ($user['role'] == 'student') {
                header("Location: ../dashboard.php");
            } else {
                header("Location: ../admin.php");
            }
            exit();
        } else {
            echo "<script>alert('Incorrect password'); window.location.href='../index.html';</script>";
        }
    } else {
        echo "<script>alert('User not found'); window.location.href='../index.html';</script>";
    }
}
?>

