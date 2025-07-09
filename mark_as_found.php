<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['studentId']) || $_SESSION['stuLevel'] !== 'Admin') {
    header("Location: login.html");
    exit();
}

$conn = new mysqli("localhost", "root", "", "lafsystem");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$itemId = $_POST['itemId'] ?? null;

if ($itemId) {
    $stmt = $conn->prepare("UPDATE items SET itemType = 'found' WHERE itemId = ?");
    $stmt->bind_param("i", $itemId);
    if ($stmt->execute()) {
        echo "<script>alert('Item marked as found successfully'); window.location.href='admin_lost_items.php';</script>";
    } else {
        echo "<script>alert('Failed to update item'); window.history.back();</script>";
    }
    $stmt->close();
} else {
    echo "<script>alert('Item ID missing'); window.history.back();</script>";
}

$conn->close();
?>
