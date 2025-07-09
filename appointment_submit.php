<?php
session_start();

if (!isset($_SESSION['studentId']) || $_SESSION['stuLevel'] != 'Student') {
    header("Location: login.html");
    exit();
}

// DB Connection
$conn = new mysqli("localhost", "root", "", "lafsystem");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Form Data
$studentId = $_SESSION['studentId'];
$type = $_POST['appointment_type'] ?? '';
$date = $_POST['date'] ?? '';
$time = $_POST['time'] ?? '';
$itemId = $_POST['item_id'] ?? null;

// Validate required fields
if (empty($type) || empty($date) || empty($time)) {
    echo "<script>alert('Please complete all required fields.'); window.history.back();</script>";
    exit();
}

// For Found type, itemId must be selected
if ($type === 'Found' && empty($itemId)) {
    echo "<script>alert('Please select a found item.'); window.history.back();</script>";
    exit();
}

// Convert empty or missing itemId to null for Lost type or empty input
if ($type === 'Lost' || empty($itemId)) {
    $itemId = null;
}

if ($itemId === null) {
    // Prepare SQL without itemId, inserting NULL explicitly
    $sql = "INSERT INTO appointment (appointmentType, appointmentDate, appointmentTime, itemId, studentId)
            VALUES (?, ?, ?, NULL, ?)";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
    }
    // Bind params without itemId
    $stmt->bind_param("sssi", $type, $date, $time, $studentId);
} else {
    // Prepare SQL with itemId
    $sql = "INSERT INTO appointment (appointmentType, appointmentDate, appointmentTime, itemId, studentId)
            VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
    }
    // Bind params including itemId
    $stmt->bind_param("sssii", $type, $date, $time, $itemId, $studentId);
}

// Execute and check for success or failure
if ($stmt->execute()) {
    echo "<script>alert('Appointment created successfully.'); window.location='student_dashboard.php';</script>";
} else {
    echo "<script>alert('Failed to create appointment: " . $stmt->error . "'); window.history.back();</script>";
}

$stmt->close();
$conn->close();
?>
	