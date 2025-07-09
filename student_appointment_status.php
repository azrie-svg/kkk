<?php
session_start();
include("db_connect.php");

if (!isset($_SESSION['studentId']) || $_SESSION['stuLevel'] != 'Student') {
    header("Location: login.html");
    exit();
}

$studentId = $_SESSION['studentId'];

$query = "SELECT a.*, i.itemName
          FROM appointment a
          LEFT JOIN items i ON a.itemId = i.itemId
          WHERE a.studentId = ?
          ORDER BY a.appointmentDate DESC, a.appointmentTime DESC";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $studentId);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>My Appointment Status</title>
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

  .badge-secondary {
    background-color: #6c757d;
    color: white;
  }

  .btn-sm {
    margin-right: 5px;
  }
</style>
</head>
<body>

<div class="container">
  <h2>My Appointment Status</h2>
  <a href="student_dashboard.php" class="btn btn-secondary btn-back">← Back to Dashboard</a>

  <?php if (isset($_SESSION['message'])): ?>
    <div class="alert alert-info"><?= htmlspecialchars($_SESSION['message']) ?></div>
    <?php unset($_SESSION['message']); ?>
  <?php endif; ?>

  <?php if ($result && $result->num_rows > 0): ?>
  <table>
    <thead>
      <tr>
        <th>Item</th>
        <th>Date</th>
        <th>Time</th>
        <th>Status</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($row = $result->fetch_assoc()): ?>
      <tr>
        <td><?= htmlspecialchars($row['itemName'] ?? 'N/A') ?></td>
        <td><?= htmlspecialchars($row['appointmentDate']) ?></td>
        <td><?= htmlspecialchars($row['appointmentTime']) ?></td>
        <td>
          <?php
            $status = $row['status'];
            $badgeClass = 'badge-secondary';
            if ($status === 'Approved') $badgeClass = 'badge-success';
            elseif ($status === 'Rejected') $badgeClass = 'badge-danger';
            elseif ($status === 'Pending') $badgeClass = 'badge-warning';
            elseif ($status === 'Canceled') $badgeClass = 'badge-secondary';
          ?>
          <span class="badge <?= $badgeClass ?>"><?= htmlspecialchars($status) ?></span>
        </td>
        <td>
          <?php if (in_array($status, ['Pending', 'Approved'])): ?>
            <form method="POST" action="cancel_appointment.php" onsubmit="return confirm('Are you sure you want to cancel this appointment?');" style="display:inline;">
              <input type="hidden" name="appointmentId" value="<?= $row['appointmentId'] ?>">
              <button type="submit" class="btn btn-danger btn-sm">Cancel</button>
            </form>
          <?php else: ?>
            -
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

<?php
$stmt->close();
$conn->close();
?>


