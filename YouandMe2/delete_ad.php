<?php
session_start();
include 'dbconn.php';

if (!isset($_SESSION['username'])) {
    die("Unauthorized access.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['listing_id'])) {
    $listing_id = $_POST['listing_id'];
    $username = $_SESSION['username'];

    // Only delete if this user owns the ad
    $stmt = $conn->prepare("DELETE FROM listing WHERE listing_id = ? AND username = ?");
    $stmt->bind_param("is", $listing_id, $username);

    if ($stmt->execute()) {
        header("Location: profile.php");
    } else {
        echo "Error deleting ad.";
    }
}
?>