<?php
session_start();
include("db_connect.php");

// Only Admin can perform this action
if (!isset($_SESSION['studentId']) || $_SESSION['stuLevel'] !== 'Admin') {
    header("Location: login.html");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['claimId'])) {
    $claimId = intval($_POST['claimId']);

    // First, get the itemId from the claim to unclaim the item later
    $stmt = $conn->prepare("SELECT itemId FROM claim WHERE claimId = ?");
    $stmt->bind_param("i", $claimId);
    $stmt->execute();
    $stmt->bind_result($itemId);
    if ($stmt->fetch()) {
        $stmt->close();

        // Delete the claim record
        $delete = $conn->prepare("DELETE FROM claim WHERE claimId = ?");
        $delete->bind_param("i", $claimId);
        if ($delete->execute()) {
            $delete->close();

            // Set the item back to unclaimed
            $update = $conn->prepare("UPDATE items SET claimed = FALSE WHERE itemId = ?");
            $update->bind_param("i", $itemId);
            $update->execute();
            $update->close();

            $_SESSION['message'] = "Claim removed and item marked as unclaimed.";
        } else {
            $_SESSION['message'] = "Failed to delete claim.";
        }
    } else {
        $_SESSION['message'] = "Claim not found.";
    }

    header("Location: claim_page.php");
    exit();
} else {
    $_SESSION['message'] = "Invalid request.";
    header("Location: claim_page.php");
    exit();
}
