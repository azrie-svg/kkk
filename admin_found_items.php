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

// ✅ Only show unclaimed found items
$result = $conn->query("SELECT * FROM items 
    WHERE itemType = 'found' 
    AND (claimed = FALSE OR claimed IS NULL)
    ORDER BY reportDate DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Found Items | Admin</title>
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
      height: 100%;
    }

    .card-img-top {
      height: 200px;
      object-fit: cover;
    }

    .card-title {
      color: #55311c;
      font-weight: bold;
    }

    .card-body {
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      height: 100%;
    }

    .btn-sm {
      margin-top: 10px;
    }
  </style>
</head>
<body>

<header class="header-area header-sticky">
  <div class="container">
    <nav class="main-nav">
      <a href="admin_dashboard.php" class="logo">Found Items</a>
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
  <h2 class="text-white text-center mb-5">Reported Found Items</h2>
  <div class="row">
    <?php if ($result->num_rows > 0): ?>
      <?php while ($row = $result->fetch_assoc()): ?>
        <div class="col-md-4 d-flex">
          <div class="card card-custom w-100">
            <img src="uploads/<?= htmlspecialchars($row['itemPhoto']) ?>" class="card-img-top" alt="Item Photo">
            <div class="card-body">
              <h5 class="card-title"><?= htmlspecialchars($row['itemName']) ?></h5>
              <p class="card-text"><?= htmlspecialchars($row['itemDescription']) ?></p>
              <p><strong>Location:</strong> <?= htmlspecialchars($row['location']) ?></p>
              <p><strong>Date:</strong> <?= date('d M Y', strtotime($row['reportDate'])) ?></p>
              <a href="claim_form.php?itemId=<?= $row['itemId'] ?>" class="btn btn-success btn-sm">Claim</a>
            </div>
          </div>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <div class="col-12">
        <div class="alert alert-info text-center">No unclaimed found items available at the moment.</div>
      </div>
    <?php endif; ?>
  </div>
</div>

</body>
</html>