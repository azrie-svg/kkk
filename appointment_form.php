<?php
session_start();
include("db_connect.php");

if (!isset($_SESSION['studentId']) || $_SESSION['stuLevel'] != 'Student') {
    header("Location: login.html");
    exit();
}

$studentId = $_SESSION['studentId'];

// Fetch found items for dropdown
$foundItems = [];
$sql = "SELECT itemId, itemName FROM items WHERE itemType = 'Found'";
$result = $conn->query($sql);
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $foundItems[] = $row;
    }
}

// Handle form submission
$message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $appointmentType = $_POST['appointmentType'] ?? '';
    $itemId = $_POST['itemId'] ?? null;  // Might be null if Lost
    $appointmentDate = $_POST['appointmentDate'] ?? '';
    $appointmentTime = $_POST['appointmentTime'] ?? '';

    // Basic validation
    if (!$appointmentType || !$appointmentDate || !$appointmentTime) {
        $message = "Please fill in all required fields.";
    } else {
        // For Lost items, itemId can be NULL or 0
        if ($appointmentType === 'Lost') {
            $itemId = null;
        } elseif ($appointmentType === 'Found' && !$itemId) {
            $message = "Please select an item for Found appointment.";
        }

        if (!$message) {
            $stmt = $conn->prepare("INSERT INTO appointment (studentId, itemId, appointmentType, appointmentDate, appointmentTime, status) VALUES (?, ?, ?, ?, ?, 'Pending')");
            // If $itemId is null, bind_param needs "i" with null replaced by NULL
            if ($itemId === null) {
                $stmt->bind_param("issss", $studentId, $nullItem, $appointmentType, $appointmentDate, $appointmentTime);
                $nullItem = null;
            } else {
                $stmt->bind_param("iisss", $studentId, $itemId, $appointmentType, $appointmentDate, $appointmentTime);
            }
            if ($stmt->execute()) {
                $message = "Appointment request submitted successfully.";
            } else {
                $message = "Error submitting appointment: " . $conn->error;
            }
            $stmt->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Make Appointment</title>
<link rel="stylesheet" href="assets/css/bootstrap.min.css" />
<style>
  html, body {
    height: 100%;
    margin: 0;
    font-family: 'Raleway', sans-serif;
    background: url('assets/images/slide-01.jpg') no-repeat center center fixed;
    background-size: cover;
  }

  .container {
    background: rgba(255, 255, 255, 0.95);
    border-radius: 10px;
    overflow: hidden;
    margin: 40px auto 60px auto;
    box-shadow: 0 8px 16px rgba(0,0,0,0.2);
    padding: 30px 40px;
    max-width: 600px;
  }

  h2 {
    color: #55311c;
    font-weight: 700;
    margin-bottom: 20px;
  }

  .btn-back {
    margin-bottom: 20px;
  }
</style>

<script>
function toggleItemDropdown() {
  const typeSelect = document.getElementById('appointmentType');
  const itemSelectDiv = document.getElementById('foundItemDiv');
  if (typeSelect.value === 'Found') {
    itemSelectDiv.style.display = 'block';
  } else {
    itemSelectDiv.style.display = 'none';
  }
}

window.addEventListener('DOMContentLoaded', () => {
  toggleItemDropdown();
  document.getElementById('appointmentType').addEventListener('change', toggleItemDropdown);
});
</script>

</head>
<body>

<div class="container">
  <h2>Make Appointment</h2>
  <a href="student_dashboard.php" class="btn btn-secondary btn-back">← Back to Dashboard</a>

  <?php if ($message): ?>
    <div class="alert alert-info"><?= htmlspecialchars($message) ?></div>
  <?php endif; ?>

  <form method="POST" action="">

    <div class="mb-3">
      <label for="appointmentType" class="form-label">Appointment Type</label>
      <select name="appointmentType" id="appointmentType" class="form-select" required>
        <option value="">-- Select Type --</option>
        <option value="Lost" <?= (isset($_POST['appointmentType']) && $_POST['appointmentType'] === 'Lost') ? 'selected' : '' ?>>Lost</option>
        <option value="Found" <?= (isset($_POST['appointmentType']) && $_POST['appointmentType'] === 'Found') ? 'selected' : '' ?>>Found</option>
      </select>
    </div>

    <div class="mb-3" id="foundItemDiv" style="display:none;">
      <label for="itemId" class="form-label">Select Found Item</label>
      <select name="itemId" id="itemId" class="form-select">
        <option value="">-- Select Item --</option>
        <?php foreach ($foundItems as $item): ?>
          <option value="<?= $item['itemId'] ?>" <?= (isset($_POST['itemId']) && $_POST['itemId'] == $item['itemId']) ? 'selected' : '' ?>>
            <?= htmlspecialchars($item['itemName']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="mb-3">
      <label for="appointmentDate" class="form-label">Date</label>
      <input type="date" id="appointmentDate" name="appointmentDate" class="form-control" required
        value="<?= isset($_POST['appointmentDate']) ? htmlspecialchars($_POST['appointmentDate']) : '' ?>">
    </div>

    <div class="mb-3">
      <label for="appointmentTime" class="form-label">Time</label>
      <input type="time" id="appointmentTime" name="appointmentTime" class="form-control" required
        value="<?= isset($_POST['appointmentTime']) ? htmlspecialchars($_POST['appointmentTime']) : '' ?>">
    </div>

    <button type="submit" class="btn btn-primary">Submit Appointment</button>
  </form>
</div>

<script src="assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
