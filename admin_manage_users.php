<?php
session_start();

// Ensure only admin can access
if (!isset($_SESSION['studentId']) || $_SESSION['stuLevel'] !== 'Admin') {
    header("Location: login.html");
    exit();
}

$conn = new mysqli("localhost", "root", "", "lafsystem");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle role update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_role'])) {
    $userId = $_POST['studentId'];
    $newRole = $_POST['stuLevel'];

    $stmt = $conn->prepare("UPDATE student SET stuLevel = ? WHERE studentId = ?");
    $stmt->bind_param("si", $newRole, $userId);
    $stmt->execute();
    $stmt->close();

    echo "<script>alert('Role updated successfully.'); window.location.href='admin_manage_users.php';</script>";
    exit();
}

$result = $conn->query("SELECT studentId, studentName, stuUsername, stuPhoneNum, stuLevel FROM student WHERE stuLevel = 'Student' ORDER BY studentName ASC");

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Manage Users | Admin</title>
  <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
  <link rel="stylesheet" href="assets/css/font-awesome.css" />
  <link rel="stylesheet" href="assets/css/templatemo-breezed.css" />
  <style>
    html, body {
      height: 100%;
      margin: 0;
      font-family: 'Raleway', sans-serif;
      background: url('assets/images/slide-01.jpg') no-repeat center center fixed;
      background-size: cover;
    }
    .content-section {
      padding: 80px 20px;
    }
    .card-custom {
      background: rgba(255, 255, 255, 0.95);
      border-radius: 10px;
      padding: 30px;
      box-shadow: 0 10px 25px rgba(0,0,0,0.2);
    }
    .table thead th {
      background-color: #55311c;
      color: white;
    }
    .table td, .table th {
      vertical-align: middle;
    }
  </style>
</head>
<body>

<header class="header-area header-sticky">
  <div class="container">
    <nav class="main-nav">
      <a href="admin_dashboard.php" class="logo">Manage Users</a>
      <ul class="nav">
        <li><a href="admin_dashboard.php">Home</a></li>
        <li><a href="admin_report.php">Report</a></li>
        <li><a href="logout.php">Logout</a></li>
      </ul>
      <a class="menu-trigger"><span>Menu</span></a>
    </nav>
  </div>
</header>

<div class="container content-section">
  <div class="card card-custom">
    <h3 class="text-center mb-4">Manage Student/Admin Roles</h3>
    <div class="table-responsive">
      <table class="table table-bordered table-striped">
        <thead>
          <tr>
            <th>Name</th>
            <th>Email (Username)</th>
            <th>Phone</th>
            <th>Current Role</th>
            <th>Change Role</th>
          </tr>
        </thead>
        <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?= htmlspecialchars($row['studentName']) ?></td>
            <td><?= htmlspecialchars($row['stuUsername']) ?></td>
            <td><?= htmlspecialchars($row['stuPhoneNum']) ?></td>
            <td><strong><?= $row['stuLevel'] ?></strong></td>
            <td>
              <form method="POST" class="form-inline">
                <input type="hidden" name="studentId" value="<?= $row['studentId'] ?>">
                <select name="stuLevel" class="form-control form-control-sm d-inline w-auto">
                  <option value="Student" <?= $row['stuLevel'] === 'Student' ? 'selected' : '' ?>>Student</option>
                  <option value="Admin" <?= $row['stuLevel'] === 'Admin' ? 'selected' : '' ?>>Admin</option>
                </select>
                <button type="submit" name="update_role" class="btn btn-primary btn-sm ml-2">Update</button>
              </form>
            </td>
          </tr>
        <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

</body>
</html>
