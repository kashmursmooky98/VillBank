<?php
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

// Initialize variables
$pending_loan = 0;
$name = "";
$account_balance = 0;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'] ?? '';

    if (!empty($name)) {
        $loan_sql = "SELECT pendingLoan FROM transactions WHERE name = ? ORDER BY date DESC LIMIT 1";
        $loan_stmt = $conn->prepare($loan_sql);
        $loan_stmt->bind_param("s", $name);
        $loan_stmt->execute();
        $loan_stmt->bind_result($pending_loan);
        $loan_stmt->fetch();
        $loan_stmt->close();
    }

    // Validate and sanitize form data
    $deposit = isset($_POST['deposit']) ? floatval($_POST['deposit']) : 0;
    $savings = isset($_POST['savings']) ? floatval($_POST['savings']) : 0;
    $withdraws = isset($_POST['withdraws']) ? floatval($_POST['withdraws']) : 0;

    if ($deposit === 0 && $savings === 0 && $withdraws === 0) {
        echo "Please enter a deposit, withdrawal, or saving amount.";
        exit();
    }

    // Calculate account balance
    $account_balance = $deposit + $savings - $withdraws - $pending_loan;

    // Prepare SQL statement with parameterized query to prevent SQL injection
    $sql = "INSERT INTO transactions (name, deposit, savings, pendingLoan, withdraws, account_Balance, date)
            VALUES (?, ?, ?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql);

    // Bind values to parameters
    $stmt->bind_param("sddddd", $name, $deposit, $savings, $pending_loan, $withdraws, $account_balance);

    // Execute the statement
    if ($stmt->execute()) {
        // Execute successful, do something if needed
    } else {
        echo "Error: " . $stmt->error;
    }
}

// Fetch transaction data
$sql = "SELECT * FROM transactions"; 
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leader Account</title>
    <link rel="stylesheet" href="style3.css">
</head>
<body>
    <div id="wrapper">
        <div id="sidebar">
            <img class="VBlogo2" src="villbill1.png" alt="Village Bank Logo">
            <hr>
            <div id="sidebar-toggle">&#9776;</div>
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
            <p class="ie">-each table contains details for a particular full year.</p>
        </div>
    </div>

    <a class="profbutt" href="myprofile.php">
        <img class="prof" src="profile.jpg" alt="Village Bank profile">
        <p><b>Profile</b></p>
    </a>

    <nav>
    <a href=".php">HOME</a>
    <a href="villbank.php#details"><button>ABOUT US</button></a>
    <a href="villbank.php#contacts"><button>CONTACTS</button></a>
    <a href="villbank.php#help"><button>HELP</button></a>
    <a href="villbank.php#how-to"><button>HOW TO USE</button></a>
</nav>

    <div id="main-content">
        <center>
            <h1 class="accountt">MY ACCOUNT</h1>
        </center>

        <div class="balance-box">
            <h2>Account Balance:</h2>
            <p><?php echo number_format($account_balance, 2); ?> ZMW</p>
        </div>

        <div class="enter">
            <form id="userForm" method="POST">
                <label for="name">Member Name:</label>
                <input type="text" name="name" id="name" required><br>

                <label for="deposit">Deposit Amount:</label>
                <input type="number" id="deposit" name="deposit" step="0.01"><br>

                <label for="savings">Savings:</label>
                <input type="number" id="savings" name="savings" step="0.01"><br>

                <label for="withdraws">My Withdrawals:</label>
                <input type="number" id="withdraws" name="withdraws" step="0.01"><br>

                <button type="submit">Submit</button>
            </form>
        </div>

        <!-- Display table with transaction data -->
        <div>
            <h2 id="transactiontable">Transaction Data</h2>
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Deposit</th>
                        <th>Savings</th>
                        <th>Pending Loan</th>
                        <th>Withdraws</th>
                        <th>Account Balance</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Fetch data and display it in the table
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row["name"]) . "</td>";
                            echo "<td>" . htmlspecialchars($row["deposit"]) . "</td>";
                            echo "<td>" . htmlspecialchars($row["savings"]) . "</td>";
                            echo "<td>" . htmlspecialchars($row["pendingLoan"]) . "</td>";
                            echo "<td>" . htmlspecialchars($row["withdraws"]) . "</td>";
                            echo "<td>" . htmlspecialchars($row["account_Balance"]) . "</td>";
                            echo "<td>" . htmlspecialchars($row["date"]) . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='7'>No transactions found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <footer>
        <h1 class="foot"><img class="VBlogo" src="villbill1.png" alt="Village Bank Logo">Best group saving website.</h1>
        <a class="trust" href="aboutus">Why you can trust VILLBANK?</a>
        <hr>
    </footer>
</body>
</html>
