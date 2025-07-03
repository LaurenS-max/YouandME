<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

if($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);
    
    // Input validation
    if(empty($username) || empty($email) || empty($password)) {
        die("Error: All fields are required");
    }
    
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Error: Invalid email format");     
    }
    


    // Database Connection
    $host = "localhost"; // Adjust the port if necessary
    $db_user = "root";
    $db_password = "";
    $db_name = "ecom_users";

    // Establishing connection
    $connection = mysqli_connect($host, $db_user, $db_password, $db_name);

    if(!$connection) {
        die("Connection failed: " . mysqli_connect_error());
    } else {
        echo "Connected successfully";
    }

// Check if username already exists (using prepared statements)


    // Check if email already exists
    $sql = "SELECT * FROM user WHERE email = ?";
    $stmt = mysqli_prepare($connection, $sql);
    if (!$stmt) {
        die("Prepare failed (email check): " . mysqli_error($connection));
    }
   

    // Hash the password before storing
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    var_dump($username, $email, $password);

    // Inserting the new user using prepared statement
    $sql = "INSERT INTO user (username, email, password) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($connection, $sql);
    if (!$stmt) {
        die("Prepare failed (insert): " . mysqli_error($connection));
    }
    mysqli_stmt_bind_param($stmt, "sss", $username, $email, $hashed_password);

    if(mysqli_stmt_execute($stmt)) {
        header("Location: login.html");
        exit();
    } else {
        die("Error: " . mysqli_error($connection));
    }

    mysqli_stmt_close($stmt);
    mysqli_close($connection);
} else {
    die("Error: Invalid request method");
}
?>   