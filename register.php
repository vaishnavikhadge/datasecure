<?php
$host = "localhost";
$user = "root";
$password = "";
$db = "admin";

// Connect to DB
$conn = new mysqli($host, $user, $password, $db);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Get user input
$name = trim($_POST['name']);
$username = trim($_POST['username']);
$rawPassword = trim($_POST['password']);
$hashedPassword = password_hash($rawPassword, PASSWORD_DEFAULT);
$phone = trim($_POST['phone']);
$address = trim($_POST['address']);
$dob = $_POST['dob'];
$gender = $_POST['gender'];

// Check if user already exists
$stmt = $conn->prepare("SELECT * FROM login_user WHERE user_name = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
  echo "User already exists. <a href='register.html'>Try again</a>";
} else {
  // Insert new user
  $stmt = $conn->prepare("INSERT INTO login_user (name, user_name, password, phone, address, dob, gender) VALUES (?, ?, ?, ?, ?, ?, ?)");
  $stmt->bind_param("sssssss", $name, $username, $hashedPassword, $phone, $address, $dob, $gender);
  
  if ($stmt->execute()) {
    echo "Registration successful! <a href='login.html'>Login now</a>";
  } else {
    echo "Error: " . $stmt->error;
  }
}

$conn->close();
?>
