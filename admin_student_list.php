<?php
session_start();
if ($_SESSION['stuLevel'] !== 'Admin') {
    header("Location: login.html");
    exit();
}

$conn = new mysqli("localhost", "root", "", "lafsystem");

// Pagination setup
$limit = 10;
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$total = $conn->query("SELECT COUNT(*) FROM student WHERE stuLevel != 'Admin'")->fetch_row()[0];
$total_pages = ceil($total / $limit);

// Fetch students for current page
$stmt = $conn->prepare("SELECT * FROM student WHERE stuLevel != 'Admin' LIMIT ?, ?");
$stmt->bind_param("ii", $offset, $limit);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Students</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body style="padding: 40px; background: #f8f9fa;">
<div class="container">
    <h3 class="mb-4">Student Management</h3>
    
    <a href="admin_add_student.php" class="btn btn-success mb-3">+ Add Student</a>

    <table class="table table-bordered table-hover bg-white">
        <thead class="thead-dark">
            <tr>
                <th>ID</th><th>Name</th><th>Phone</th><th>Course</th><th>Username</th><th>Action</th>
