<?php
session_start();

$conn = new mysqli("localhost", "root", "", "lafsystem");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get data from form
$studentNumber = $_POST['studentNumber'];
$repDetails = $_POST['repDetails'];
$repApproval = $_POST['repApproval'] ?? 'Pending';

// Optional: lookup student ID using student number
$stmt = $conn->prepare("SELECT studentId FROM student WHERE stuUsername = ?");
$stmt->bind_param("s", $studentNumber);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    $stuId = $row['studentId'];

    // Insert report
    $insert = $conn->prepare("INSERT INTO report (repApproval, repDetails, stuId, studentNumber) VALUES (?, ?, ?, ?)");
    $insert->bind_param("ssis", $repApproval, $repDetails, $stuId, $studentNumber);

    if ($insert->execute()) {
        echo "<script>alert('Report submitted successfully.'); window.location.href='admin_dashboard.php';</script>";
    } else {
        echo "<script>alert('Insert failed: " . $insert->error . "'); window.history.back();</script>";
    }
    $insert->close();

} else {
    echo "<script>alert('Student number not found.'); window.history.back();</script>";
}

$conn->close();
?>
