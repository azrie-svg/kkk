<?php
session_start();
if ($_SESSION['stuLevel'] !== 'Admin') {
    header("Location: login.html");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Student</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body style="padding: 40px;">
<div class="container">
    <h3>Add New Student</h3>
    <form method="POST" action="admin_add_student_process.php">
        <div class="form-group"><input type="text" name="studentName" class="form-control" placeholder="Full Name" required></div>
        <div class="form-group"><input type="text" name="stuPhoneNum" class="form-control" placeholder="Phone Number" required></div>
        <div class="form-group"><input type="text" name="stuCourse" class="form-control" placeholder="Course" required></div>
        <div class="form-group"><input type="email" name="stuUsername" class="form-control" placeholder="Username (Email)" required></div>
        <div class="form-group"><input type="password" name="stuPassword" class="form-control" placeholder="Password" required></div>
        <div class="form-group">
            <select name="stuLevel" class="form-control">
                <option value="Student">Student</option>
                <option value="Admin">Admin</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Add Student</button>
    </form>
</div>
</body>
</html>
