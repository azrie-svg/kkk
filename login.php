<?php
session_start();

$conn = new mysqli("localhost", "root", "", "lafsystem");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM student WHERE stuUsername = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $student = $result->fetch_assoc();
        if (password_verify($password, $student['stuPassword'])) {
            $_SESSION['studentId'] = $student['studentId'];
            $_SESSION['studentName'] = $student['studentName']; // 
            $_SESSION['stuLevel'] = $student['stuLevel'];

            if ($student['stuLevel'] === 'Admin') {
                header("Location: admin_dashboard.php");
            } else {
                header("Location: student_dashboard.php");
            }
            exit();
        } else {
            echo "<script>alert('Incorrect password.'); window.location='login.html';</script>";
        }
    } else {
        echo "<script>alert('User not found.'); window.location='login.html';</script>";
    }
    $stmt->close();
}
$conn->close();
?>
