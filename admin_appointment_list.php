<?php
include("db_connect.php");
session_start();

if (!isset($_SESSION['studentId']) || $_SESSION['stuLevel'] != 'Admin') {
    header("Location: login.html");
    exit();
}

// Handle approve/reject POST action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'], $_POST['appointmentId'])) {
    $appointmentId = intval($_POST['appointmentId']);
    $action = $_POST['action'];

    if ($action === 'approve') {
        $newStatus = 'Approved';
    } elseif ($action === 'reject') {
        $newStatus = 'Rejected';
    } else {
        $newStatus = null;
    }

    if ($newStatus) {
        $stmt = $conn->prepare("UPDATE appointment SET status = ? WHERE appointmentId = ?");
        $stmt->bind_param("si", $newStatus, $appointmentId);
        $stmt->execute();
        $stmt->close();
    }

    header("Location: admin_appointment_list.php");
    exit();
}

// Fetch all appointments
$query = "SELECT a.*, s.studentName, i.itemName
          FROM appointment a
          JOIN student s ON a.studentId = s.studentId
          JOIN items i ON a.itemId = i.itemId
          ORDER BY a.appointmentDate, a.appointmentTime";

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Admin - Appointment List</title>
<link rel="stylesheet" href="assets/css/bootstrap.min.css" />
<style>
  html, body {
    height: 100%;
    margin: 0;
    font-family: 'Raleway', sans-serif;
    background: url('assets/images/slide-01.jpg') no-repeat center center fixed;
    background-size: cover;
  }

  .container {
    background: rgba(255, 255, 255, 0.95);
    border-radius: 10px;
    overflow: hidden;
    margin: 40px auto 60px auto;
    box-shadow: 0 8px 16px rgba(0,0,0,0.2);
    padding: 30px 40px;
    max-width: 1100px;
  }

  h2 {
    color: #55311c;
    font-weight: 700;
    margin-bottom: 20px;
  }

  .btn-back {
    margin-bottom: 20px;
  }

  table {
    border-collapse: separate !important;
    border-spacing: 0 8px;
    width: 100%;
  }

  thead tr {
    background-color: #007bff;
    color: white;
  }

  thead th {
    padding: 12px 15px;
    text-transform: uppercase;
    font-weight: 600;
  }

  tbody tr {
    background-color: #fefefe;
    box-shadow: 0 2px 5px rgb(0 0 0 / 0.05);
  }

  tbody td {
    padding: 15px;
    vertical-align: middle;
  }

  tbody tr:hover {
    background-color: #e9f5ff;
    cursor: default;
  }

  .badge {
    font-size: 0.9em;
    font-weight: 600;
    padding: 6px 12px;
    border-radius: 12px;
  }

  .badge-success {
    background-color: #28a745;
    color: white;
  }

  .badge-danger {
    background-color: #dc3545;
    color: white;
  }

  .badge-warning {
    background-color: #ffc107;
    color: #212529;
  }

  .btn-sm {
    margin-right: 5px;
  }
</style>
</head>
<body>

<div class="container">
  <h2>All Appointments</h2>
  <a href="admin_dashboard.php" class="btn btn-secondary btn-back">← Back to Dashboard</a>

  <?php if ($result && $result->num_rows > 0): ?>
  <table>
    <thead>
      <tr>
        <th>Item</th>
        <th>Student</th>
        <th>Date</th>
        <th>Time</th>
        <th>Status</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($row = $result->fetch_assoc()): ?>
      <tr>
        <td><?= htmlspecialchars($row['itemName']) ?></td>
        <td><?= htmlspecialchars($row['studentName']) ?></td>
        <td><?= htmlspecialchars($row['appointmentDate']) ?></td>
        <td><?= htmlspecialchars($row['appointmentTime']) ?></td>
        <td>
          <?php
            $status = $row['status'];
            $badgeClass = 'badge-secondary';
            if ($status === 'Approved') $badgeClass = 'badge-success';
            elseif ($status === 'Rejected') $badgeClass = 'badge-danger';
            elseif ($status === 'Pending') $badgeClass = 'badge-warning';
          ?>
          <span class="badge <?= $badgeClass ?>"><?= htmlspecialchars($status) ?></span>
        </td>
        <td>
          <?php if ($status === 'Pending'): ?>
            <form method="POST" style="display:inline-block;">
              <input type="hidden" name="appointmentId" value="<?= $row['appointmentId'] ?>">
              <button type="submit" name="action" value="approve" class="btn btn-success btn-sm">Approve</button>
            </form>
            <form method="POST" style="display:inline-block;">
              <input type="hidden" name="appointmentId" value="<?= $row['appointmentId'] ?>">
              <button type="submit" name="action" value="reject" class="btn btn-danger btn-sm">Reject</button>
            </form>
          <?php else: ?>
            <button class="btn btn-success btn-sm" disabled style="<?= $status === 'Approved' ? '' : 'display:none;' ?>">Approved</button>
            <button class="btn btn-danger btn-sm" disabled style="<?= $status === 'Rejected' ? '' : 'display:none;' ?>">Rejected</button>
          <?php endif; ?>
        </td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
  <?php else: ?>
    <p>No appointments found.</p>
  <?php endif; ?>
</div>

<script src="assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php $conn->close(); ?>
