
<?php
session_start ();

// check if the user is logged in
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("Location: login.html");
    exit;
}
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>You and Me</title>
</head>
<body>
    <div class = "container">
        <h2> Welcome, <?php echo $_SESSION["username"]; ?>!</h2>
        <p>Thank you for logging in. You can now access the members-only content.</p>
        <p><a href="profile.php">View Profile</a></p>
        <p><a href="postadd.html">Post an Ad</a></p>
        <p><a href="logout.php">Logout</a></p>



    </div>


</body>
</html>