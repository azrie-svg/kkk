
<?php
session_start();
if (!isset($_SESSION['studentId'])) {
    header("Location: login.html");
    exit();
}

$conn = new mysqli("localhost", "root", "", "lafsystem");

$itemId = $_POST['itemId'];
$studentId = $_SESSION['studentId'];
$appointmentDate = $_POST['appointmentDate'];
$appointmentTime = $_POST['appointmentTime'];
$purpose = $_POST['purpose'];

$stmt = $conn->prepare("INSERT INTO appointment (appointmentDate, appointmentTime, purpose, itemId, studentId)
                        VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssii", $appointmentDate, $appointmentTime, $purpose, $itemId, $studentId);

if ($stmt->execute()) {
    echo "<script>alert('Appointment submitted!'); window.location.href='student_dashboard.php';</script>";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
