<?php
session_start();
if ($_SESSION['stuLevel'] !== 'Admin') {
    header("Location: login.html");
    exit();
}

$conn = new mysqli("localhost", "root", "", "lafsystem");

$name = $_POST['studentName'];
$phone = $_POST['stuPhoneNum'];
$course = $_POST['stuCourse'];
$username = $_POST['stuUsername'];
$password = $_POST['stuPassword'];
$level = $_POST['stuLevel'];

$hashed = password_hash($password, PASSWORD_DEFAULT);

$stmt = $conn->prepare("INSERT INTO student (studentName, stuPhoneNum, stuCourse, stuUsername, stuPassword, stuLevel) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssss", $name, $phone, $course, $username, $hashed, $level);
$stmt->execute();

header("Location: admin_student_list.php");
exit();
