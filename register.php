<?php
include("db_connect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $studentID = $_POST['studentID'];
    $studentName = $_POST['name'];
    $stuPhoneNum = $_POST['phone'];
    $stuCourse = $_POST['course'];
    $stuUsername = $_POST['username'];
    $password = $_POST['password'];
    $confirm = $_POST['confirm'];

    if ($password !== $confirm) {
        echo "<script>alert('Passwords do not match.'); window.history.back();</script>";
        exit();
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $stuLevel = 'Student';

    $check = $conn->query("SELECT * FROM student WHERE studentId = '$studentID' OR stuUsername = '$stuUsername'");
    if ($check === false) {
        echo "Query Error: " . $conn->error;
        exit();
    }

    if ($check->num_rows > 0) {
        echo "<script>alert('Student ID or Username already exists.'); window.history.back();</script>";
        exit();
    }

    $sql = "INSERT INTO student (studentId, studentName, stuPhoneNum, stuCourse, stuUsername, stuPassword, stuLevel)
            VALUES ('$studentID', '$studentName', '$stuPhoneNum', '$stuCourse', '$stuUsername', '$hashedPassword', '$stuLevel')";

    if ($conn->query($sql) === TRUE) {
        // Choose ONE method of redirect:
        // JavaScript:
        echo "<script>alert('Registration successful!'); window.location.href='login.php';</script>";
        
        // OR plain PHP redirect (comment out one):
        // header("Location: login.php");
        // exit();
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
xmlrpc_decode