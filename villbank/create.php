<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "villbankdb";

$conn = new mysqli($servername, $username, $password ,$dbname);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Validate form data (optional but recommended)
  $full_name = $_POST['full_name'];
  $NRC_NO = $_POST['NRC_NO'];
  $phone_number = trim($_POST['phone_number']); // Sanitize username input
  $sex = $_POST['sex'];
  $password = $_POST['password']; // Securely hash password (see note below)
  $confirm_password = $_POST['confirm_password'];
  $group_name = $_POST['group_name']; // New input for group name
  $account_type = $_POST['account_type'];

  $hashed_pwd = password_hash($password, PASSWORD_DEFAULT); // Updated password_hash function

  // Check the account type selected
  if ($account_type == 'admin') {
    $sql = "INSERT INTO admin (fullName, nrc, phoneNumber, sex, password, `group_name`)
          VALUES ('$full_name', '$NRC_NO', '$phone_number', '$sex', '$hashed_pwd', '$group_name')";
    $redirect_url = "loginadmin.php"; // Redirect to admin login page
  } else {
    $sql = "INSERT INTO user (fullName, nrc, phoneNumber, sex, password, `group_name`)
          VALUES ('$full_name', '$NRC_NO', '$phone_number', '$sex', '$hashed_pwd', '$group_name')";
    $redirect_url = "login.php"; // Redirect to regular user login page
  }

  $query = $conn->query($sql);

  if (!$query) {
    echo $conn->error; // Output error message
    exit();
  } else {
    header("Location: $redirect_url"); // Redirect to appropriate login page
    exit();
  }
}

$conn->close();
?>


<!DOCTYPE html>
<html>
<head>
  <title>VillBank.com</title>
  <link rel="stylesheet" href="style2.css">
</head>
<body>


  <center>
    <img class="VBlogo2" src="villbill1.png" alt="Village Bank Logo">
    <div class="wrapper">
      <form action="create.php" method="post">
        <h1 class="createacc"> Create account</h1>
        <br>
        <div class="input-box">
          <input name="full_name" class="input-b" type="text" placeholder="Full Names" required><br><br>
          <input name="NRC_NO" class="input-b" type="text" placeholder="NRC" required><br><br>
          <input name="phone_number" class="input-b" type="number" placeholder="Phone number" required><br><br>
          <input name="sex" class="input-b" type="text" placeholder="Enter gender." required><br><br>
          <input name="group_name" class="input-b" type="text" placeholder="Group Name" required><br><br>
          <input name="password" class="input-b" type="password" placeholder="Enter password" required><br><br>
          <input name="confirm_password" class="input-b" type="password" placeholder="Confirm password" required><br><br>
          <div>
          <label class="type" for="account_type">Account Type:</label>
			<select id="account_type" name="account_type" required>
			<option value="admin">Admin</option>
			<option value="member">Member</option>
			</select><br><br>

        </div>
		</div>
		
        <button type="submit" class="btn"><b>CREATE ACCOUNT</b></button>
      </form>
    </div>
  </center>
</body>
</html>
