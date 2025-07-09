<?php
session_start();
if (!isset($_SESSION['studentId']) || $_SESSION['stuLevel'] != 'Student') {
    header("Location: login.html");
    exit();
}

$conn = new mysqli("localhost", "root", "", "lafsystem");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$result = $conn->query("SELECT * FROM items WHERE itemType='Lost' ORDER BY reportDate DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Lost Items | Student</title>
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
      overflow: hidden;
      margin-bottom: 30px;
      box-shadow: 0 8px 16px rgba(0,0,0,0.2);
    }
    .card-img-top {
      height: 200px;
      object-fit: cover;
    }
    .card-title {
      color: #55311c;
    }
  </style>
</head>
<body>

<header class="header-area header-sticky">
  <div class="container">
    <nav class="main-nav">
      <a href="student_dashboard.php" class="logo">Lost Items</a>
      <ul class="nav">
        <li><a href="student_dashboard.php">Home</a></li>
        <li><a href="logout.php">Logout</a></li>
      </ul>
      <a class="menu-trigger"><span>Menu</span></a>
    </nav>
  </div>
</header>

<div class="container content-section">
  <h2 class="text-white text-center mb-5">Reported Lost Items</h2>
  <div class="row">
    <?php while ($row = $result->fetch_assoc()): ?>
      <div class="col-md-4">
        <div class="card card-custom">
          <img src="uploads/<?= htmlspecialchars($row['itemPhoto']) ?>" class="card-img-top" alt="Item Photo">
          <div class="card-body">
            <h5 class="card-title"><?= htmlspecialchars($row['itemName']) ?></h5>
            <p class="card-text"><?= htmlspecialchars($row['itemDescription']) ?></p>
            <p><strong>Location:</strong> <?= htmlspecialchars($row['location']) ?></p>
            <p><strong>Date:</strong> <?= date('d M Y', strtotime($row['reportDate'])) ?></p>
			<td>
			</td>

          </div>
        </div>
      </div>
    <?php endwhile; ?>
  </div>
</div>

</body>
</html>
