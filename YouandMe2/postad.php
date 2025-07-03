<?php

session_start();
include 'dbconn.php';
if (!isset($_SESSION['username'])) {
    die("You must be logged in to post an ad.");
}

$username = $_SESSION['username'];


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['adTitle'];
    $category = $_POST['adCategory'];
    $price = $_POST['adPrice'];
    $description = $_POST['adDescription'];
    $location = $_POST['adLocation'];
    $contact = $_POST['adContact'];

    // Handle image upload
    $imagePath = null;
    if (isset($_FILES['imageUpload']) && $_FILES['imageUpload']['error'] == 0) {
        $targetDir = "uploads/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir);
        }
        $imagePath = $targetDir . basename($_FILES["imageUpload"]["name"]);
        move_uploaded_file($_FILES["imageUpload"]["tmp_name"], $imagePath);
    }

    $stmt = $conn->prepare("INSERT INTO listing (title, category, price, description, imagePath, location, contact, username) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssdssssi", $title, $category, $price, $description, $imagePath, $location, $contact, $username);
if ($stmt->execute()) {
    echo "Ad posted successfully!";
    if (isset($stmt) && $stmt->insert_id) {
        $listing_id = $stmt->insert_id;
        echo '<a href="update_ad.php?listing_id=' . $listing_id . '">Update this ad</a>';
    }
} else {
    echo "Error: " . $stmt->error;
}
}
?>
