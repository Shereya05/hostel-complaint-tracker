<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include("../db/config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $name = $_POST["name"];
  $email = $_POST["email"];
  $password = password_hash($_POST["password"], PASSWORD_DEFAULT); // encrypted password

  $sql = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$password')";

  if (mysqli_query($conn, $sql)) {
    header("Location: ../index.html");  // send to login page
    exit();
  } else {
    echo "Error: " . mysqli_error($conn);
  }
}
?>
