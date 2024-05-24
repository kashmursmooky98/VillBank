<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Transactions and Receipts</title>
<link rel="stylesheet" href="transactions.css">
</head>
<body>
    <header>
        <h1>Transactions and Receipts</h1>
    </header>

    <div class="buttons">
        <button onclick="window.location.href='new_transaction.php'">Add New Transaction</button>
        <button onclick="window.location.href='download_receipts.php'">Download Receipts</button>
    </div>

    <?php
    // Connect to the database
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "villbankdb";
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

 // Fetch transactions from the database
$sql = "SELECT *, 
        CASE
            WHEN description LIKE '%deposit%' THEN 'Deposit'
            WHEN description LIKE '%withdraw%' THEN 'Withdrawal'
            WHEN description LIKE '%loan payment%' THEN 'Loan Payment'
            ELSE 'Checking Balance'
        END AS transaction_type
        FROM transactions";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Output transactions
    echo "<table>";
    echo "<tr><th>Date</th><th>Description</th><th>Amount</th><th>Type</th><th>Status</th></tr>";
    while($row = $result->fetch_assoc()) {
        echo "<tr><td>" . $row["date"] . "</td><td>" . $row["transaction_type"] . "</td><td>" . $row["account_Balance"] . "</td><td></td><td></td></tr>";
    }
    echo "</table>";
} else {
    echo "No transactions found.";
}


    // Close the database connection
    $conn->close();
    ?>

    <!-- Add your HTML for receipts or additional features here -->
</body>
</html>
