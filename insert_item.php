<?php
session_start();
if (!isset($_SESSION['studentId']) || $_SESSION['stuLevel'] != 'Admin') {
    header("Location: login.html");
    exit();
}

$conn = new mysqli("localhost", "root", "", "lafsystem");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Collect text fields
$itemName = $_POST['itemName'];
$itemDescription = $_POST['itemDescription'];
$itemType = $_POST['itemType'];
$location = $_POST['location'];
$reportedBy = $_SESSION['studentId'];

// Handle image upload
$photoName = $_FILES['itemPhoto']['name'];
$photoTmp  = $_FILES['itemPhoto']['tmp_name'];
$photoExt  = pathinfo($photoName, PATHINFO_EXTENSION);
$photoNew  = uniqid("item_", true) . '.' . strtolower($photoExt);
$uploadDir = 'uploads/';

if (!is_dir($uploadDir)) {
    mkdir($uploadDir);
}

$uploadPath = $uploadDir . $photoNew;

if (move_uploaded_file($photoTmp, $uploadPath)) {
    // Insert to database
    $stmt = $conn->prepare("INSERT INTO items (itemName, itemDescription, itemType, location, reportedBy, itemPhoto) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssis", $itemName, $itemDescription, $itemType, $location, $reportedBy, $photoNew);

    if ($stmt->execute()) {
        echo "<script>alert('Item reported successfully.'); window.location.href='admin_report.php';</script>";
    } else {
        echo "Database error: " . $stmt->error;
    }
    $stmt->close();
} else {
    echo "<script>alert('Failed to upload photo.'); window.location.href='admin_report.php';</script>";
}

$conn->close();
?>

