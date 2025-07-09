<?php
session_start();
if ($_SESSION['stuLevel'] !== 'Admin') {
    header("Location: login.html");
    exit();
}

$conn = new mysqli("localhost", "root", "", "lafsystem");
$itemId = $_GET['itemId'];

$stmt = $conn->prepare("UPDATE items SET status='Found' WHERE itemId = ?");
$stmt->bind_param("i", $itemId);
$stmt->execute();

header("Location: lost_items.php");
exit();
