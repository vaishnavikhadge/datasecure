<?php
// === Step 1: Enable error reporting for debugging ===
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// === Step 2: Set response content type ===
header('Content-Type: application/json');

// === Step 3: Log the request start ===
error_log("=== pay.php called ===");

// === Step 4: Database configuration ===
$host = "localhost";
$user = "root";
$password = "";
$dbname = "admin";

// === Step 5: Connect to database ===
$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    error_log("❌ DB connection error: " . $conn->connect_error);
    http_response_code(500);
    echo json_encode(["error" => "❌ Database connection failed."]);
    exit;
}
error_log("✅ Database connected.");

// === Step 6: Get POST data ===
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$amount = isset($_POST['amount']) ? intval($_POST['amount']) : 0;

error_log("📩 Received Email: $email");
error_log("💰 Received Amount: $amount");

// === Step 7: Validate input ===
if (empty($email) || empty($amount)) {
    error_log("❌ Email or amount is empty.");
    http_response_code(400);
    echo json_encode(["error" => "❌ Email and amount are required."]);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    error_log("❌ Invalid email format: $email");
    http_response_code(400);
    echo json_encode(["error" => "❌ Invalid email format."]);
    exit;
}

if (!is_numeric($amount) || $amount <= 0) {
    error_log("❌ Invalid amount value: $amount");
    http_response_code(400);
    echo json_encode(["error" => "❌ Amount must be a positive number."]);
    exit;
}

// === Step 8: Insert into database ===
$sql = "INSERT INTO payments (email, amount, payment_status) VALUES (?, ?, 'pending')";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    error_log("❌ SQL prepare error: " . $conn->error);
    http_response_code(500);
    echo json_encode(["error" => "❌ Failed to prepare query."]);
    exit;
}

$stmt->bind_param("si", $email, $amount);

if ($stmt->execute()) {
    error_log("✅ Payment inserted: Email = $email, Amount = ₹$amount");

    // === Step 9: Create dummy payment link (replace with Razorpay later) ===
    $baseURL = "http://" . $_SERVER['HTTP_HOST'] . "/payment.html";
    $paymentLink = $baseURL . "?email=" . urlencode($email) . "&amount=$amount";

    // === Step 10: Return JSON success response ===
    echo json_encode([
        "success" => true,
        "message" => "✅ Payment saved.",
        "short_url" => $paymentLink
    ]);
} else {
    error_log("❌ SQL execution error: " . $stmt->error);
    http_response_code(500);
    echo json_encode(["error" => "❌ Failed to save payment."]);
}

// === Step 11: Cleanup ===
$stmt->close();
$conn->close();
error_log("🔚 pay.php script ended.");
?>
