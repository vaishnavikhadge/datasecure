<?php
session_start();

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database credentials
$host = "localhost";
$dbUser = "root";
$dbPass = "";
$dbName = "admin";

// Connect to database
$conn = new mysqli($host, $dbUser, $dbPass, $dbName);
if ($conn->connect_error) {
  die("❌ DB connection failed: " . $conn->connect_error);
} else {
  echo "✅ DB connected successfully<br>";
}

// Debug: Show form POST values
echo "<pre>Received POST:\n";
print_r($_POST);
echo "</pre>";

// Process login
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = isset($_POST['email']) ? trim($_POST['email']) : '';
  $password = isset($_POST['password']) ? trim($_POST['password']) : '';

  if (empty($email)) {
    die("⚠️ Email missing.");
  }
  if(empty($password)){
    die("⚠️ Password empty.");
  }

  echo "📧 Email: $email<br>";
  echo "🔑 Password (entered): $password<br>";

  // Prepare and execute query
  $stmt = $conn->prepare("SELECT id, name, password FROM user WHERE email = ?");
  if (!$stmt) {
    die("❌ Prepare failed: " . $conn->error);
  }

  $stmt->bind_param("s", $email);
  $stmt->execute();
  $stmt->store_result();
  echo "🔍 Rows found: " . $stmt->num_rows . "<br>";

  if ($stmt->num_rows === 1) {
    $stmt->bind_result($id, $name, $hashedPassword);
    $stmt->fetch();

    echo "✅ User found: $name<br>";
    echo "🔐 Hashed password from DB: $hashedPassword<br>";

    if (password_verify($password, $hashedPassword)) {
      $_SESSION["user_id"] = $id;
      $_SESSION["name"] = $name;
      echo "🎉 Login successful! Welcome, $name.<br>";
    } else {
      echo "❌ Incorrect password.<br>";
    }
  } else {
    echo "❌ No user found with email: $email<br>";
  }

  $stmt->close();
}

$conn->close();
?>
