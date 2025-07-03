<?php
session_start();
include 'dbconn.php';


// Redirect if user is not logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];

// Step 1: Get the listing_id from the URL
if (!isset($_GET['listing_id'])) {
    die("No listing ID provided.");
}

$listing_id = intval($_GET['listing_id']);

// Step 2: Fetch the listing to make sure the user owns it
$stmt = $conn->prepare("SELECT * FROM listing WHERE listing_id = ? AND username = ?");
$stmt->bind_param("is", $listing_id, $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    die("Listing not found or you do not have permission to edit it.");
}

$listing = $result->fetch_assoc();

// Step 3: If form is submitted, update the listing
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $price = floatval($_POST['price']);
    $description = trim($_POST['description']);
    $category = trim($_POST['category']);
    $location = trim($_POST['location']);
    $contact = trim($_POST['contact']);

    $stmt = $conn->prepare("UPDATE listing 
    SET title = ?, category = ?, price = ?, description = ?, location = ?, contact = ? 
    WHERE listing_id = ? AND username = ?");

$stmt->bind_param("ssdsssis", 
    $title, $category, $price, $description, $location, $contact, $listing_id, $username);

    echo "<pre>";
    print_r($_POST);
    echo "</pre>";

    if (empty($title) || empty($price) || empty($description)) {
    die("Fields cannot be empty");
}

    if ($stmt->execute()) {
        echo "<p>Ad updated successfully! <a href='profile.php'>Back to Profile</a></p>";
        exit();
    } else {
        echo "<p>Error updating ad: " . $conn->error . "</p>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Listing</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h2>Update Your Listing</h2>
    <form method="POST" action="">
        <label>Title:</label>
        <input type="text" name="title" value="<?php echo htmlspecialchars($listing['title']); ?>" required><br>

        <label>Price:</label>
        <input type="number" name="price" step="0.01" value="<?php echo htmlspecialchars($listing['price']); ?>" required><br>

        <label>Description:</label>
        <textarea name="description" required><?php echo htmlspecialchars($listing['description']); ?></textarea><br>

        <label>Category:</label>
        <input type="text" name="category" value="<?php echo htmlspecialchars($listing['category']); ?>" required><br>

        <label>Location:</label>
        <input type="text" name="location" value="<?php echo htmlspecialchars($listing['location']); ?>" required><br>

        <label>Contact Info:</label>
        <input type="text" name="contact" value="<?php echo htmlspecialchars($listing['contact']); ?>" required><br>

        <button type="submit">Update Listing</button>
    </form>
</body>
</html>
