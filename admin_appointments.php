<?php
session_start();
if ($_SESSION['stuLevel'] !== 'Admin') {
    header("Location: login.html");
    exit();
}

$conn = new mysqli("localhost", "root", "", "lafsystem");
$result = $conn->query("SELECT a.*, s.studentName, i.itemName 
                        FROM appointment a 
                        JOIN student s ON a.studentId = s.studentId 
                        JOIN items i ON a.itemId = i.itemId
                        ORDER BY a.appointmentDate DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>All Appointments</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
</head>
<body style="padding:40px;">
<div class="container">
    <h3>Appointment Requests</h3>
    <table class="table table-bordered table-striped">
        <thead class="thead-dark">
            <tr>
                <th>No</th>
                <th>Student</th>
                <th>Item</th>
                <th>Date</th>
                <th>Time</th>
                <th>Purpose</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php $no = 1; while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= $row['studentName'] ?></td>
                <td><?= $row['itemName'] ?></td>
                <td><?= $row['appointmentDate'] ?></td>
                <td><?= $row['appointmentTime'] ?></td>
                <td><?= $row['purpose'] ?></td>
                <td><?= $row['appointmentStatus'] ?></td>
                <td>
                    <a href="approve_appointment.php?id=<?= $row['appointmentId'] ?>&action=approve" class="btn btn-success btn-sm">Approve</a>
                    <a href="approve_appointment.php?id=<?= $row['appointmentId'] ?>&action=reject" class="btn btn-danger btn-sm">Reject</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>
