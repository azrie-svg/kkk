<?php
session_start();

// Only Admin can access
if (!isset($_SESSION['studentId']) || $_SESSION['stuLevel'] !== 'Admin') {
    header("Location: login.html");
    exit();
}

$conn = new mysqli("localhost", "root", "", "lafsystem");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch claimed items with student info
$sql = "SELECT 
            c.claimId,
            i.itemName,
            i.itemPhoto,
            i.itemDescription,
            i.location,
            i.reportDate,
            s.studentName,
            s.stuPhoneNum,
            s.stuUsername,
            c.dateClaim,
            c.timeClaim
        FROM claim c
        JOIN items i ON c.itemId = i.itemId
        JOIN student s ON c.studentId = s.studentId
        ORDER BY c.dateClaim DESC, c.timeClaim DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Claimed Items | Admin</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
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

    .section-title {
      color: white;
      text-align: center;
      margin-bottom: 40px;
    }
  </style>
</head>
<body>

<header class="header-area header-sticky">
  <div class="container">
    <nav class="main-nav">
      <a href="admin_dashboard.php" class="logo">Claimed Items</a>
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
  <h2 class="section-title">List of Claimed Items</h2>
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
              <p><strong>Reported Date:</strong> <?= date('d M Y', strtotime($row['reportDate'])) ?></p>
              <hr>
              <p><strong>Claimed By:</strong> <?= htmlspecialchars($row['studentName']) ?></p>
              <p><strong>Email:</strong> <?= htmlspecialchars($row['stuUsername']) ?></p>
              <p><strong>Phone:</strong> <?= htmlspecialchars($row['stuPhoneNum']) ?></p>
              <p><strong>Claimed At:</strong> <?= $row['dateClaim'] ?> <?= $row['timeClaim'] ?></p>

              <!-- Remove Claim Button -->
              <form method="POST" action="delete_claim.php" onsubmit="return confirm('Are you sure you want to remove this claim?');">
                <input type="hidden" name="claimId" value="<?= $row['claimId'] ?>">
                <button type="submit" class="btn btn-danger btn-sm">Remove Claim</button>
              </form>
            </div>
          </div>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <div class="col-12">
        <div class="alert alert-info text-center">No claimed items available at the moment.</div>
      </div>
    <?php endif; ?>
  </div>
</div>

</body>
</html>
