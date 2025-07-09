<?php
session_start();
if (!isset($_SESSION['studentId'])) {
    header("Location: login.html");
    exit();
}

$conn = new mysqli("localhost", "root", "", "lafsystem");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$id = $_SESSION['studentId'];
$name = $_POST['studentName'];
$phone = $_POST['stuPhoneNum'];
$course = $_POST['stuCourse'];
$username = $_POST['stuUsername'];
$newPassword = $_POST['newPassword'];

// Update with or without password
if (!empty($newPassword)) {
    $hashed = password_hash($newPassword, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("UPDATE student SET studentName=?, stuPhoneNum=?, stuCourse=?, stuUsername=?, stuPassword=? WHERE studentId=?");
    $stmt->bind_param("sssssi", $name, $phone, $course, $username, $hashed, $id);
} else {
    $stmt = $conn->prepare("UPDATE student SET studentName=?, stuPhoneNum=?, stuCourse=?, stuUsername=? WHERE studentId=?");
    $stmt->bind_param("ssssi", $name, $phone, $course, $username, $id);
}

if ($stmt->execute()) {
    echo "<script>alert('Profile updated successfully'); window.location.href='student_dashboard.php';</script>";
} else {
    echo "<script>alert('Error updating profile'); window.location.href='student_profile.php';</script>";
}

$stmt->close();
$conn->close();
?>
