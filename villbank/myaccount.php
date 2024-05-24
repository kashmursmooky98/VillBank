<?php
// Database connection details (replace with your actual credentials)
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

// Function to sanitize user input
function sanitizeInput($data) {
  global $conn;
  return $conn->real_escape_string(trim(strip_tags($data)));
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get user input
  $month = sanitizeInput($_POST["month"]);
  $deposit = sanitizeInput($_POST["deposit"]);
  $withdrawal = sanitizeInput($_POST["withdraws"]);
  $savings = sanitizeInput($_POST["savings"]);
  $balance = sanitizeInput($_POST["balance"]);

 

  if ($stmt->execute()) {
    echo "Transaction for " . $month . " saved successfully!";
  } else {
    echo "Error: " . $sql . "<br>" . $conn->error;
  }

  $stmt->close();
}

// Fetch data for the table
$sql = "SELECT month, deposit, withdraws, savings, account_Balance FROM transactions WHERE YEAR(date) = 2024";
$result = $conn->query($sql);

$data_2024 = [];
if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $data_2024[] = $row;
  }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Financial Summary</title>
  <link rel="stylesheet" href="style3.css">
</head>
<body>
  <div id="wrapper">
    <div id="sidebar">
      <img class="VBlogo2" src="villbill1.png" alt="Village Bank Logo">
      <hr>
      <ul>
        <li class="dropdown">
          <a href="#" class="dropbtn">Menu</a>
          <ul class="dropdown-content">
            <li><a href="home.php">Home</a></li>
            <li><a href="contacts.php">Contact</a></li> 
            <li><a href="help.php">Help</a></li>
            <li><a href="loans.php">Loans</a></li> 
          </ul>
        </li>
        <li class="dropdown">  
          <a class="dropbtn">(Transactions and Receipts)</a>
          <ul class="dropdown-content">
            <li><a href="trans.php">Transactions</a></li>
            <li><a href="receipt.php">Receipts</a></li>
          </ul>
        </li>
        <li><a class="logout" href="login.php">Log Out</a></li> 
      </ul>
      <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
      <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
      <br>
      <p class="ie">-each table contains details for a particular full year.</p>
    </div>
  </div>
  <a class="profbutt" href="myprofile.php"><img class="prof" src="profile.jpg" alt="Village Bank profile">
  <p><b>Profile</b></p></a>

  <nav>
    <a href=".php">HOME</a>
    <a href="villbank.php#details"><button>ABOUT US</button></a>
    <a href="villbank.php#contacts"><button>CONTACTS</button></a>
    <a href="villbank.php#help"><button>HELP</button></a>
    <a href="villbank.php#how-to"><button>HOW TO USE</button></a>
  </nav>

  <div id="main-content">
    <center><h1 class="accountt">MY ACCOUNT</h1></center>
    <table id="summaryTable">
      <thead>
        <tr>
          <th>YEAR: 2024</th>
          <th>My Deposits</th>
          <th>My Withdraws</th>
          <th>My Savings</th>
          <th>My Balance</th>
        </tr>
      </thead>
      <tbody>
        <?php
        if ($result->num_rows > 0) {
          while($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<th>" . $row["month"] . "</th>";
            echo "<td>" . $row["deposit"] . "</td>";
            echo "<td>" . $row["withdraws"] . "</td>";
            echo "<td>" . $row["savings"] . "</td>";
            echo "<td>" . $row["account_Balance"] . "</td>";
            echo "</tr>";
          }
        } else {
          echo "<tr><td colspan='5'>No transactions found</td></tr>";
        }
        ?>
      </tbody>
    </table>
  </div>

  <footer>
    <h1 class="foot"><img class="VBlogo" src="villbill1.png" alt="Village Bank Logo">Best group saving website.</h1>
    <a class="trust" href="aboutus">Why you can trust VILLBANK?</a>
    <hr>
  </footer>
</body>
</html>
