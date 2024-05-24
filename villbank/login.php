<?php
// Establish database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "villbankdb";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get user input from the form
    $group = $_POST['group'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];

    // Query the database to check if the user exists and the password is correct
    $stmt = $conn->prepare("SELECT * FROM user WHERE group_name = ? AND phoneNumber = ?");
    $stmt->bind_param("ss", $group, $phone);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            // User is authenticated, redirect to home.php
            header("Location: home.php");
            exit();
        }
    }

    // User is not authenticated, display an error message
    $error = "Invalid login credentials. Please try again.";

    // Debugging: Output any MySQL errors
    if ($stmt->error) {
        echo "MySQL error: " . $stmt->error;
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="login.css">
</head>
<body>
<div class="login-container">
    <h2>Login</h2>
    <?php if (isset($error)) echo "<p>$error</p>"; ?>
    <form action="login.php" method="post">
        <label for="group">Group Name</label>
        <input type="text" name="group" required><br>

        <label for="phone">Phone Number</label>
        <input type="text" name="phone" required><br>

        <label for="password">Password</label>
        <input type="password" name="password" required><br>

        <button type="submit" name="submit">Login</button>
    </form>
</div>
</body>
</html>
