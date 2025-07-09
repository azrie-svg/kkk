<?php
session_start();
if ($_SESSION['stuLevel'] !== 'Admin') {
    header("Location: login.html");
    exit();
}

$conn = new mysqli("localhost", "root", "", "lafsystem");
$id = $_GET['id'] ?? 0;

$stmt = $conn->prepare("DELETE FROM student WHERE studentId = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: admin_student_list.php");
exit();
