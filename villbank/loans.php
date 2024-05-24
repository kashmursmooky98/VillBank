<?php
session_start(); // Start the session to access session variables

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "villbankdb";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);



$errors = []; // Array to store validation errors

// Server-side validation
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["name"])) {
        $errors[] = "Customer name is required.";
    }

    if (!isset($_POST["loans"]) || $_POST["loans"] < 100) {
        $errors[] = "Loan amount must be at least K500.";
    }

    // Check optional income and credit score constraints (if provided)
    $income_constraint = isset($_POST["income_constraint"]) ? $_POST["income_constraint"] : null;
    $credit_score_constraint = isset($_POST["credit_score_constraint"]) ? $_POST["credit_score_constraint"] : null;

    if ($income_constraint !== null && $_POST["income"] < $income_constraint) {
        $errors[] = "Minimum income requirement not met.";
    }

    if ($credit_score_constraint !== null && $_POST["credit_score"] < $credit_score_constraint) {
        $errors[] = "Minimum credit score requirement not met.";
    }

    if (empty($errors)) {
        // Connect to database
        $servername = "localhost";
        $username = "root";
        $password = ""; // Enter your database password here
        $dbname = "villbankdb";

        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Get form data
        $customer_name = $_POST["name"];
        $loan_amount = $_POST["loans"];
        $interest_rate = 10; // Fixed interest rate (10%)

        // Calculate total amount payable
        $total_amount_payable = $loan_amount + ($loan_amount * ($interest_rate / 100));

        // Prepare SQL query
        $sql = "INSERT INTO transactions (name, loans, interest_rate, total_amount_payable, application_date, income, credit_score) VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssss", $customer_name, $loan_amount, $interest_rate, $total_amount_payable, date("Y-m-d"), $_POST["income"], $_POST["credit_score"]);

        if ($stmt->execute()) {
            $message = "Loan application submitted successfully! Your reference number is: " . $conn->insert_id;
        } else {
            $message = "Error applying for loan: " . $conn->error;
        }

        $stmt->close();
        $conn->close();
    } else {
        $message = "Please fix the following errors:";
        $message .= "<ul>";
        foreach ($errors as $error) {
            $message .= "<li>$error</li>";
        }
        $message .= "</ul>";
    }
}

// Fetch available loans
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$loans_sql = "SELECT * FROM transactions";
$loans_result = $conn->query($loans_sql);

$total_loan_amount = 0;
while ($loan = $loans_result->fetch_assoc()) {
    $total_loan_amount += $loan['loans'];
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fname = $_POST["name"];
    $amount = $_POST["amount"];

    // Send email to admin
    $to = "admin@example.com";
    $subject = "Loan Application - Authorization Required";
    $message = "First Name: $name\nLoan Amount: $amount";
    $headers = "From: webmaster@example.com";

    mail($to, $subject, $message, $headers);

    // Redirect to a thank you page
    header("Location: thankyou.html");
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Villbank Loan Application</title>
  <link rel="stylesheet" href="transandloan.css"> <!-- Link to the external CSS file -->
</head>
<body>
  
    <div id="content">
      <nav>
        <a href="home.php">HOME</a>
        <a href="villbank.php#details"><button>ABOUT US</button></a>
        <a href="villbank.php#contacts"><button>CONTACTS</button></a>
        <a href="villbank.php#help"><button>HELP</button></a>
        <a href="villbank.php#how-to"><button>HOW TO USE</button></a>
      </nav>
	  </div>
	  
      <div class="loantable">
        <form class="apply" action="loan_application.php" method="post" id="loan-form">
          
          <center>
            <h1 class="villloan">Villbank Loan Application</h1>
          </center>
          <?php if (isset($message)) echo "<div id='message'>$message</div>"; ?>
          <label class="loanapply" for="customer_name">Customer Name: </label>
          <input type="text" name="customer_name" id="customer_name" required>

          <label class="loanapply" for="loan_amount">Loan Amount: </label>
          <input type="number" name="loan_amount" id="loan_amount" min="100" step="0.01" required>

          <label class="loanapply" for="income">Income (Optional): </label>
          <input type="number" name="income" id="income" step="0.01">

          <label class="loanapply" for="credit_score">Credit Score (Optional): </label>
          <input type="number" name="credit_score" id="credit_score" min="0" max="850">

          <div class="applyp">
            <h3 class="loanp">Constraints (Optional):</h3>

            <label class="loanapply" for="income_constraint">Minimum Income (K):</label>
            <input type="number" name="income_constraint" id="income_constraint" min="0" step="0.01">

            <label class="loanapply" for="credit_score_constraint">Minimum Credit Score:</label>
            <input type="number" name="credit_score_constraint" id="credit_score_constraint" min="0" max="850">
          </div>
          <br>
          <button type="submit">Apply for Loan</button>
        </form>
      </div>
	  
      <div class="loans-info">
        <h2>Available Loans</h2>
        <?php if ($loans_result->num_rows > 0): ?>
          <ul>
            <?php while ($loan = $loans_result->fetch_assoc()): ?>
              <li><?php echo "Loan ID: " . $loan['id'] . " - Amount: K" . $loan['loan_amount']; ?></li>
            <?php endwhile; ?>
          </ul>
        <?php else: ?>
          <p>No loans available.</p>
        <?php endif; ?>
        <h3>Total Loan Amount for the Group: K<?php echo $total_loan_amount; ?></h3>
      </div>
    </div>
 
  <footer class="footer1">
    <h1 class="foot">VILLBANK</h1>
    <a class="trust" href="aboutus">Why you can trust VILLBANK?</a>
    <hr>
    <p>About us<br>Help and info<br>Contact us<br></p>
    <p>@villbank.com.zm</p>
    <br>
    <p>VillBANK is an online platform that allows people in the community to save money and borrow money with low rates. It allows users to deposit and borrow money remotely as long as the group leader approves the loan or withdraw transaction. VILLBANK helps in keeping track of transactions and gives receipts of members.</p>
  </footer>
</body>
</html>
