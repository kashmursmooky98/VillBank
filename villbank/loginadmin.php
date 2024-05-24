<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "villbankdb";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Authentication Logic
    $group = $_POST['group_name'];
   $phone = $_POST['phoneNumber'];
    $password = $_POST['password'];

    // Use prepared statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT password FROM admin WHERE group_name = ? AND phoneNumber = ?");
    $stmt->bind_param("ss", $group, $phone);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($hashed_password);
        $stmt->fetch();

        // Verify the password
        if (password_verify($password, $hashed_password)) {
            // Authentication successful, set session variables and redirect to home2.php
            $_SESSION["loggedin"] = true;
            $_SESSION["group"] = $group;
            header("Location: home2.php");
            exit;
        } else {
            // Authentication failed, display an error message
            $login_error = "Invalid credentials. Please try again.";
        }
    } else {
        // Authentication failed, display an error message
        $login_error = "Invalid credentials. Please try again.";
    }

    $stmt->close();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" type="text/css" href="login.css">
</head>
<body>
    <div class="login-container">
        <h2>Admin Login</h2>
        <?php if(isset($login_error)) echo "<p>$login_error</p>"; ?>
        <form action="loginadmin.php" method="post">
            <label for="group">Group Name</label>
            <input type="text" id="group" name="group" required><br>

            <label for="phone">Phone Number</label>
            <input type="text" id="phoneNumber" name="phone" required><br>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" required><br>

            <button type="submit" name="submit">Login</button>
        </form>
    </div>
</body>
</html>
