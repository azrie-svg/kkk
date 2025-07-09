<?php
session_start();
if ($_SESSION['stuLevel'] !== 'Admin') {
    header("Location: login.html");
    exit();
}

$conn = new mysqli("localhost", "root", "", "lafsystem");

$id = $_GET['id'];
$action = $_GET['action'];
$status = ($action == 'approve') ? 'Approved' : 'Rejected';

$stmt = $conn->prepare("UPDATE appointment SET appointmentStatus = ? WHERE appointmentId = ?");
$stmt->bind_param("si", $status, $id);
$stmt->execute();

header("Location: admin_appointments.php");
