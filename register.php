<?php
session_start();

// Database connection
$host = "localhost";
$user = "root";
$password = "";
$dbname = "admin"; // Ensure this database exists

$conn = new mysqli($host, $user, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Collect and sanitize input
  $name     = trim($_POST['name']);
  $email    = trim($_POST['username']); // 'username' is actually the email field
  $rawPass  = trim($_POST['password']);
  $phone    = trim($_POST['phone']);
  $address  = trim($_POST['address']);
  $dob      = trim($_POST['dob']);
  $gender   = trim($_POST['gender']);

  // Basic validation
  if (
    empty($name) || empty($email) || empty($rawPass) ||
    empty($phone) || empty($address) || empty($dob) || empty($gender)
  ) {
    die("All fields are required.");
  }

  // Check if email already exists
  $check = $conn->prepare("SELECT id FROM user WHERE email = ?");
  $check->bind_param("s", $email);
  $check->execute();
  $check->store_result();

  if ($check->num_rows > 0) {
    die("This email is already registered.");
  }
  $check->close();

  // Hash password
  $hashedPass = password_hash($rawPass, PASSWORD_DEFAULT);

  // Insert into user table
  $stmt = $conn->prepare("INSERT INTO user (name, email, password, phone, address, dob, gender) VALUES (?, ?, ?, ?, ?, ?, ?)");
  $stmt->bind_param("sssssss", $name, $email, $hashedPass, $phone, $address, $dob, $gender);

  if ($stmt->execute()) {
    echo "✅ Registration successful!";
    // Optional: header("Location: login.php");
  } else {
    echo "❌ Error: " . $stmt->error;
  }

  $stmt->close();
}

$conn->close();
?>
