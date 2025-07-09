<?php
session_start();

// Redirect if not logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.html");
    exit();
}

$name = $_SESSION['name'] ?? 'User';

// Connect to MySQL
$conn = new mysqli("localhost", "root", "", "lafsystem");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $item_name = $_POST['item_name'];
    $description = $_POST['description'];
    $date_lost = $_POST['date_lost'];
    $location = $_POST['location'];

    $photo_path = '';
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {
        $uploads_dir = 'uploads';
        if (!is_dir($uploads_dir)) mkdir($uploads_dir);

        $tmp_name = $_FILES['photo']['tmp_name'];
        $filename = basename($_FILES['photo']['name']);
        $photo_path = "$uploads_dir/" . time() . "_" . $filename;

        move_uploaded_file($tmp_name, $photo_path);
    }

    // Insert into database
    $stmt = $conn->prepare("INSERT INTO lost_items (item_name, description, date_lost, location, photo) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $item_name, $description, $date_lost, $location, $photo_path);
    $stmt->execute();
    $stmt->close();
}

// Fetch items from database
$items = [];
$result = $conn->query("SELECT * FROM lostItems ORDER BY id DESC");
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $items[] = $row;
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Lost Items</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 40px;
            background-color: #f5f5f5;
        }

        h1 {
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 25px;
        }

        table, th, td {
            border: 1px solid #ccc;
        }

        th, td {
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #8c7569;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .top-bar {
            margin-bottom: 20px;
        }

        .top-bar a {
            float: right;
            text-decoration: none;
            background-color: #8c7569;
            color: white;
            padding: 8px 14px;
            border-radius: 4px;
        }

        .form-section {
            margin-top: 40px;
        }

        input, textarea {
            width: 100%;
            margin-bottom: 12px;
            padding: 10px;
        }

        input[type="submit"] {
            background-color: #8c7569;
            color: white;
            border: none;
            cursor: pointer;
        }

        img {
            max-width: 100px;
            max-height: 100px;
        }
    </style>
</head>
<body>

<div class="top-bar">
    <span>Hi <?php echo htmlspecialchars($name); ?>!</span>
    <a href="logout.php">Logout</a>
</div>

<h1>Lost Items</h1>

<table>
    <thead>
        <tr>
            <th>Item Name</th>
            <th>Description</th>
            <th>Date Lost</th>
            <th>Location</th>
            <th>Photo</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($items as $item): ?>
            <tr>
                <td><?= htmlspecialchars($item['item_name']) ?></td>
                <td><?= htmlspecialchars($item['description']) ?></td>
                <td><?= htmlspecialchars($item['date_lost']) ?></td>
                <td><?= htmlspecialchars($item['location']) ?></td>
                <td>
                    <?php if ($item['photo']): ?>
                        <img src="<?= htmlspecialchars($item['photo']) ?>" alt="Item Photo">
                    <?php else: ?>
                        No photo
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<div class="form-section">
    <h2>Report New Lost Item</h2>
    <form action="lostitems.php" method="POST" enctype="multipart/form-data">
        <input type="text" name="item_name" placeholder="Item Name" required>
        <textarea name="description" placeholder="Description" required></textarea>
        <input type="date" name="date_lost" required>
        <input type="text" name="location" placeholder="Location" required>
        <input type="file" name="photo" accept="image/*">
        <input type="submit" value="Add Item">
    </form>
</div>

</body>
</html>
