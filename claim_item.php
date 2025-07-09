<?php
session_start();
if (!isset($_SESSION['studentId'])) {
    header("Location: login.html");
    exit();
}

$conn = new mysqli("localhost", "root", "", "lafsystem");
$itemId = $_GET['itemId'];
$studentId = $_SESSION['studentId'];

$stmt = $conn->prepare("INSERT INTO claim (itemId, studentId, claimDate) VALUES (?, ?, NOW())");
$stmt->bind_param("ii", $itemId, $studentId);

if ($stmt->execute()) {
    echo "<script>alert('Claim submitted!'); window.location.href='found_items.php';</script>";
} else {
    echo "<script>alert('Already claimed or error.'); window.history.back();</script>";
}
