<?php
session_start();
if (!isset($_SESSION['studentId']) || $_SESSION['stuLevel'] != 'Admin') {
    header("Location: login.html");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Admin Report | Lost and Found UiTM Kedah</title>

  <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
  <link rel="stylesheet" href="assets/css/font-awesome.css" />
  <link rel="stylesheet" href="assets/css/templatemo-breezed.css" />
  <style>
    html, body {
      height: 100%;
      margin: 0;
      font-family: 'Raleway', sans-serif;
    }

    body {
      background: url('assets/images/slide-01.jpg') no-repeat center center fixed;
      background-size: cover;
      display: flex;
      flex-direction: column;
    }

    .report-container {
      flex: 1;
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 50px 20px;
    }

    .report-form-wrapper {
      width: 100%;
      max-width: 600px;
      color: white;
    }

    .report-form-wrapper h2 {
      font-weight: 700;
      margin-bottom: 25px;
      text-align: center;
      color: white;
      text-shadow: 1px 1px 2px #000;
    }

    .form-control,
    select,
    textarea {
      background-color: rgba(255, 255, 255, 0.2);
      border: 1px solid #ddd;
      color: white;
    }

    .form-control::placeholder,
    textarea::placeholder {
      color: #eee;
    }

    .form-control:focus {
      background-color: rgba(255, 255, 255, 0.3);
      color: white;
      box-shadow: none;
    }

    .btn-submit {
      background-color: #8c7569;
      color: #fff;
      border: none;
    }

    .btn-submit:hover {
      background-color: #55311c;
    }

    .header-area {
      z-index: 1000;
    }
	.report-container {
	margin-top: 100px;  /* Already added top space */
	padding-left: 30px;
	padding-right: 30px;
	padding-bottom: 80px; /* ✅ Add this line for bottom space */
	}

	@media (min-width: 768px) {
	.report-container {
    padding-left: 100px;
    padding-right: 100px;
    padding-bottom: 100px; /* ✅ Responsive bottom spacing */
	}
	

  </style>
</head>
<body>

  <!-- Header -->
  <header class="header-area header-sticky">
    <div class="container">
      <div class="row">
        <div class="col-12">
          <nav class="main-nav">
            <a href="admin_dashboard.php" class="logo">Hi, <?php echo htmlspecialchars($_SESSION['studentName']); ?></a>
            <ul class="nav">
              <li><a href="admin_dashboard.php">Home</a></li>
              <li><a href="admin_report.php" class="active">Report</a></li>
              <li><a href="logout.php">Logout</a></li>
            </ul>
            <a class="menu-trigger"><span>Menu</span></a>
          </nav>
        </div>
      </div>
    </div>
  </header>

  <!-- Full Height Section -->
  <div class="report-container mt-5 px-4 px-md-5">
  <div class="report-form-wrapper p-4 p-md-5" style="background: rgba(0, 0, 0, 0.65); border-radius: 15px;">
    <h2>Submit Lost or Found Item</h2>
    <form action="insert_item.php" method="POST" enctype="multipart/form-data">
      
      <div class="form-row">
        <div class="form-group col-md-6 pr-md-3">
          <label style="color: white;">Student Number</label>
          <input type="text" name="studentNumber" class="form-control" placeholder="e.g. 2023123456" required>
        </div>
        <div class="form-group col-md-6">
          <label style="color: white;">Student Name</label>
          <input type="text" name="studentName" class="form-control" placeholder="e.g. Ahmad Zikri" required>
        </div>
      </div>

      <div class="form-group">
        <label style="color: white;">Item Name</label>
        <input type="text" name="itemName" class="form-control" placeholder="e.g. Wallet" required>
      </div>

      <div class="form-group">
        <label style="color: white;">Description</label>
        <textarea name="itemDescription" class="form-control" rows="3" placeholder="Describe the item..." required></textarea>
      </div>

      <div class="form-row">
        <div class="form-group col-md-6 pr-md-3">
          <label style="color: white;">Type</label>
          <select name="itemType" class="form-control" required>
            <option value="">-- Select Type --</option>
            <option value="Lost">Lost</option>
            <option value="Found">Found</option>
          </select>
        </div>
        <div class="form-group col-md-6">
          <label style="color: white;">Location</label>
          <input type="text" name="location" class="form-control" placeholder="Last seen or found at..." required>
        </div>
      </div>

      <div class="form-group">
        <label style="color: white;">Item Photo</label>
        <input type="file" name="itemPhoto" class="form-control" accept="image/*" required>
      </div>

      <button type="submit" class="btn btn-submit btn-block mt-3">Submit Report</button>
	  
    </form>
  </div>
</div>



  <!-- JS -->
  <script src="assets/js/jquery-2.1.0.min.js"></script>
  <script src="assets/js/popper.js"></script>
  <script src="assets/js/bootstrap.min.js"></script>
</body>
</html>
