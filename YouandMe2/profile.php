<?php
session_start();
include 'dbconn.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];

// Fetch user's ads instead of their user profile
$stmt = $conn->prepare("SELECT * FROM listing WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Ads - Profile</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h2>Welcome, <?php echo htmlspecialchars($username); ?>!</h2>
    <h3>Your Posted Ads</h3>

    <?php if ($result && $result->num_rows > 0): ?>
        <div class="ad-list">
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="ad-item">
                    <?php if (!empty($row['imagePath'])): ?>
                        <img src="<?php echo htmlspecialchars($row['imagePath']); ?>" alt="Ad Image" width="150">
                    <?php endif; ?>
                    <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                    <p><strong>Category:</strong> <?php echo htmlspecialchars($row['category']); ?></p>
                    <p><strong>Price:</strong> $<?php echo htmlspecialchars($row['price']); ?></p>
                    <p><strong>Description:</strong> <?php echo htmlspecialchars($row['description']); ?></p>
                    <p><strong>Location:</strong> <?php echo htmlspecialchars($row['location']); ?></p>
                    <p><strong>Contact:</strong> <?php echo htmlspecialchars($row['contact']); ?></p>

                    <form method="POST" action="delete_ad.php" onsubmit="return confirm('Are you sure you want to delete this ad?');">
                        <input type="hidden" name="listing_id" value="<?php echo htmlspecialchars($row['listing_id']); ?>">
                        <button type="submit">Delete Ad</button>
                    </form>
                    <form method="GET" action="update_ad.php">
                        <input type="hidden" name="listing_id" value="<?php echo htmlspecialchars($row['listing_id']); ?>">
                        <button type="submit">Update Ad</button>
                    </form>
                </div>
                <hr>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <p>You haven't posted any ads yet.</p>
    <?php endif; ?>
</body>
</html>
<?php
$stmt->close();
$conn->close();   