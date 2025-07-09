<?php
session_start();
if ($_SESSION['stuLevel'] !== 'Admin') {
    header("Location: login.html");
    exit();
}

$conn = new mysqli("localhost", "root", "", "lafsystem");
$id = $_GET['id'] ?? 0;

$stmt = $conn->prepare("SELECT * FROM student WHERE studentId = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
  <title>Edit Student</title>
  <link rel="stylesheet" href="assets/css/bootstrap.min.css">
</head>
<body style="padding: 40px;">
  <div class="container">
    <h3>Edit Student Profile</h3>
    <form action="update_profile.php" method="POST">
      <input type="hidden" name="studentId" value="<?= $row['studentId'] ?>">
      <div class="form-group">
        <label>Name</label>
        <input type="text" name="studentName" class="form-control" value="<?= $row['studentName'] ?>" required>
      </div>
      <div class="form-group">
        <label>Phone</label>
        <input type="text" name="stuPhoneNum" class="form-control" value="<?= $row['stuPhoneNum'] ?>" required>
      </div>
      <div class="form-group">
        <label>Course</label>
        <input type="text" name="stuCourse" class="form-control" value="<?= $row['stuCourse'] ?>" required>
      </div>
      <div class="form-group">
        <label>Username (Email)</label>
        <input type="email" name="stuUsername" class="form-control" value="<?= $row['stuUsername'] ?>" required>
      </div>
      <div class="form-group">
        <label>New Password (leave blank to keep current)</label>
        <input type="password" name="newPassword" class="form-control">
      </div>
      <button type="submit" class="btn btn-success">Update Student</button>
    </form>
  </div>
</body>
</html>
