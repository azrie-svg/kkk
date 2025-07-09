<?php
session_start();
include("db_connect.php");

if (!isset($_SESSION['studentId']) || $_SESSION['stuLevel'] != 'Student') {
    header("Location: login.html");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['appointmentId'])) {
    $studentId = $_SESSION['studentId'];
    $appointmentId = intval($_POST['appointmentId']);

    // Check appointment belongs to student and get current status
    $stmt = $conn->prepare("SELECT status FROM appointment WHERE appointmentId = ? AND studentId = ?");
    $stmt->bind_param("ii", $appointmentId, $studentId);
    $stmt->execute();
    $stmt->bind_result($status);
    if ($stmt->fetch()) {
        if (in_array($status, ['Pending', 'Approved'])) {
            $stmt->close(); // close before update

            // Update status to Canceled
            $update = $conn->prepare("UPDATE appointment SET status = 'Canceled' WHERE appointmentId = ? AND studentId = ?");
            $update->bind_param("ii", $appointmentId, $studentId);
            if ($update->execute()) {
                $_SESSION['message'] = "Appointment canceled successfully.";
            } else {
                $_SESSION['message'] = "Failed to cancel appointment. Please try again.";
            }
            $update->close();
        } else {
            $stmt->close(); // close here as well
            $_SESSION['message'] = "Appointment cannot be canceled (status: $status).";
        }
    } else {
        $stmt->close();
        $_SESSION['message'] = "Appointment not found.";
    }
} else {
    $_SESSION['message'] = "Invalid request.";
}

$conn->close();
header("Location: student_appointment_status.php");
exit();
