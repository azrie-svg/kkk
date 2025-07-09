<?php
session_start();
if (!isset($_SESSION['studentId']) || $_SESSION['stuLevel'] !== 'Admin') {
    header("Location: login.html");
    exit();
}

$conn = new mysqli("localhost", "root", "", "lafsystem");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$id = $_SESSION['studentId'];
$query = $conn->prepare("SELECT * FROM student WHERE studentId = ?");
$query->bind_param("i", $id);
$query->execute();
$result = $query->get_result();
$row = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Edit Profile</title>
  <link rel="stylesheet" href="assets/css/bootstrap.min.css">
  <style>
    body {
      background: url('assets/images/slide-01.jpg') no-repeat center center fixed;
      background-size: cover;
      font-family: 'Raleway', sans-serif;
    }
    .container {
      background: rgba(255, 255, 255, 0.95);
      padding: 30px;
      margin-top: 80px;
      border-radius: 10px;
      max-width: 600px;
    }
  </style>
</head>
<body>
  <div class="container">
    <h3 class="text-center mb-4">Edit Admin Profile</h3>
    <form method="POST" action="update_profile.php">
      <input type="hidden" name="studentId" value="<?= $row['studentId'] ?>">
      <div class="form-group">
        <label>Name</label>
        <input type="text" name="studentName" class="form-control" value="<?= htmlspecialchars($row['studentName']) ?>" required>
      </div>
      <div class="form-group">
        <label>Phone</label>
        <input type="text" name="stuPhoneNum" class="form-control" value="<?= htmlspecialchars($row['stuPhoneNum']) ?>" required>
      </div>
      <div class="form-group">
        <label>Course</label>
        <input type="text" name="stuCourse" class="form-control" value="<?= htmlspecialchars($row['stuCourse']) ?>" required>
      </div>
      <div class="form-group">
        <label>Username (Email)</label>
        <input type="email" name="stuUsername" class="form-control" value="<?= htmlspecialchars($row['stuUsername']) ?>" required>
      </div>
      <div class="form-group">
        <label>New Password (leave blank to keep current)</label>
        <input type="password" name="newPassword" class="form-control">
      </div>
      <button type="submit" class="btn btn-primary btn-block">Update Profile</button>
    </form>
  </div>
</body>
</html>
