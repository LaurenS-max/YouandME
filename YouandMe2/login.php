<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>

<?php
//start the session
session_start();
$error = "";

// Only process if form is submitted via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //get the username and password from the form
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
  
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';

    if (empty($username) || empty($password)) {
        $error = "Username, and password cannot be empty";
    } else {
        //database connection (assuming you have a database connection file)
        $host="localhost";
        $db_username="root";
        $db_password="";
        $db_name="ecom_users";

    //Establish connection to the database
    $conn = new mysqli($host, $db_username, $db_password, $db_name);

    //Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM user WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();

        //verify the password
        if (password_verify($password, $user['password'])) {
            //set session variables
            $_SESSION["loggedin"] = true;
            $_SESSION["username"] = $user['username'];

            //redirect to welcome page
            header("Location: welcome.php");
            exit();
        } else {
            die("Invalid password.");
        }
    } else {
        die("Username not found.");
    }
    //close the connection
    $stmt->close();
    $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login Result</title>
</head>
<body>
    <?php
    if (!empty($error)) {
        echo "<p style='color:red;'>$error</p>";
    }
    ?>
    <p><a href="login.html">Back to Login</a></p>
</body>
</html>
