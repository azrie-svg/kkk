<?php
session_start();

// ✅ Allow only Admins
if (!isset($_SESSION['studentId']) || $_SESSION['stuLevel'] !== 'Admin') {
    header("Location: login.html");
    exit();
}

// ✅ Validate itemId from GET
if (!isset($_GET['itemId']) || empty($_GET['itemId'])) {
    die("Invalid item ID.");
}
$itemId = intval($_GET['itemId']);

// ✅ Database connection
$conn = new mysqli("localhost", "root", "", "lafsystem");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ✅ Fetch students with name, email, phone number
$students = $conn->query("SELECT studentId, studentName, stuUsername, stuPhoneNum FROM student WHERE stuLevel = 'Student'");
if (!$students || $students->num_rows === 0) {
    die("No students found.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Claim Item Form</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <style>
        body {
            background: url('https://images.unsplash.com/photo-1538137524007-21e48fa42f3f') no-repeat center center fixed;
            background-size: cover;
            font-family: 'Nunito', sans-serif;
        }
        .form-container {
            margin: 80px auto;
            max-width: 550px;
            background: rgba(255, 255, 255, 0.95);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.3);
        }
        h3 {
            text-align: center;
            color: #55311c;
            margin-bottom: 25px;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="form-container">
        <h3>Assign Found Item to Student</h3>
        <form action="claim_process.php" method="POST">
            <input type="hidden" name="itemId" value="<?= htmlspecialchars($itemId) ?>">

            <div class="form-group">
                <label for="studentId">Select Student</label>
                <select class="form-control" id="studentId" name="studentId" required>
                    <option value="">-- Choose Student --</option>
                    <?php while ($row = $students->fetch_assoc()): ?>
                        <option value="<?= $row['studentId'] ?>">
                            <?= htmlspecialchars($row['studentName']) ?> | <?= htmlspecialchars($row['stuUsername']) ?> | <?= htmlspecialchars($row['stuPhoneNum']) ?> (ID: <?= $row['studentId'] ?>)
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <button type="submit" class="btn btn-primary btn-block">Confirm Claim</button>
            <a href="admin_found_items.php" class="btn btn-secondary btn-block mt-2">Cancel</a>
        </form>
    </div>
</div>
</body>
</html>
