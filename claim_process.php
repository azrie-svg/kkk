<?php
session_start();

// ✅ Only Admin can perform claim
if (!isset($_SESSION['studentId']) || $_SESSION['stuLevel'] !== 'Admin') {
    header("Location: login.html");
    exit();
}

$conn = new mysqli("localhost", "root", "", "lafsystem");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $itemId = $_POST['itemId'] ?? null;
    $studentId = $_POST['studentId'] ?? null;
    $date = date('Y-m-d');
    $time = date('H:i:s');

    if (!$itemId || !$studentId) {
        die("Missing item ID or student ID.");
    }

    // Prevent duplicate claim
    $check = $conn->prepare("SELECT * FROM claim WHERE itemId = ?");
    $check->bind_param("i", $itemId);
    $check->execute();
    if ($check->get_result()->num_rows > 0) {
        echo "<script>alert('This item has already been claimed.'); window.location.href='admin_found_items.php';</script>";
        exit();
    }
    $check->close();

    // Insert claim
    $stmt = $conn->prepare("INSERT INTO claim (dateClaim, timeClaim, itemId, studentId) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssii", $date, $time, $itemId, $studentId);
    if ($stmt->execute()) {
        echo "<script>alert('Claim recorded successfully.'); window.location.href='admin_found_items.php';</script>";
    } else {
        echo "Error submitting claim: " . $stmt->error;
    }

    $stmt->close();
}
$conn->close();
?>
