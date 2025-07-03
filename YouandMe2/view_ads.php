<?php
include 'dbconn.php';

$category = isset($_GET['category']) ? $_GET['category'] : '';

if (!$category) {
    echo "No category selected.";
    exit;
}

$stmt = $conn->prepare("SELECT * FROM listing WHERE category = ?");
$stmt->bind_param("s", $category);
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html>
<head>
    <title>Ads in <?php echo htmlspecialchars($category); ?></title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h2>Ads in Category: <?php echo htmlspecialchars($category); ?></h2>

    <?php if ($result->num_rows > 0) { ?>
        <div class="ad-list" style="max-width: 800px; margin: 40px auto; padding: 20px; background: #fafafa; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.07);">
            <?php while ($row = $result->fetch_assoc()) { ?>
                <div class="ad-item" style="display: flex; align-items: flex-start; gap: 20px; margin-bottom: 32px; padding: 16px; background: #fff; border-radius: 6px; box-shadow: 0 1px 4px rgba(0,0,0,0.04);">
                    <?php if ($row['imagePath']) { ?>
                        <img src="<?php echo htmlspecialchars($row['imagePath']); ?>" alt="Ad Image" width="120" style="border-radius: 4px; object-fit: cover;">
                    <?php } ?>
                    <div>
                        <h3 style="margin: 0 0 8px;"><?php echo htmlspecialchars($row['title']); ?></h3>
                        <p style="margin: 0 0 4px;"><strong>Price:</strong> $<?php echo htmlspecialchars($row['price']); ?></p>
                        <p style="margin: 0 0 4px;"><strong>Description:</strong> <?php echo htmlspecialchars($row['description']); ?></p>
                        <p style="margin: 0 0 4px;"><strong>Location:</strong> <?php echo htmlspecialchars($row['location']); ?></p>
                        <p style="margin: 0;"><strong>Contact:</strong> <?php echo htmlspecialchars($row['contact']); ?></p>
                    </div>
                </div>
            <?php } ?>
        </div>
    <?php } else { ?>
        <p>No ads found in this category.</p>
    <?php } ?>
</body>
</html>