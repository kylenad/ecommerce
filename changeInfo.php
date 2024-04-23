<?php
session_start();

require 'includes/database-connection.php';

if(!isset($_SESSION['fname'])){
    header("Location: login.php");
    exit();
}

if(isset($_SESSION['custID'])){
    $custID = $_SESSION['custID'];
}

function getCustomerInfo($custID, $pdo) {
    try {
        $sql = "SELECT cust_info_table.*, cust_cards_table.card_number 
                FROM cust_info_table 
                LEFT JOIN cust_cards_table ON cust_info_table.custID = cust_cards_table.custID
                WHERE cust_info_table.custID = :custID";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['custID' => $custID]);
        return $stmt->fetch(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}

function updateCardNumber($custID, $cardNumber, $pdo) {
    try {
        $sql = "UPDATE cust_cards_table SET card_number = :card_number WHERE custID = :custID";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['custID' => $custID, 'card_number' => $cardNumber]);
        return $stmt->rowCount();

    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['updateCard'])) {
    $custID = $_POST['custID'];
    $cardNumber = $_POST['card_number'];

    $updateCount = updateCardNumber($custID, $cardNumber, $pdo);
    if ($updateCount > 0) {
        echo "<p>Card number updated successfully.</p>";
    } else {
        echo "<p>Update failed or no changes made.</p>";
    }
}

/*
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['fetchInfo'])) {
    // Handle fetch
    $custID = $_POST['custID'];
    $customerInfo = getCustomerInfo($custID, $pdo);
} */

$customerInfo = getCustomerInfo($custID, $pdo);

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Information</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Lilita+One&display=swap" rel="stylesheet">
    <style>
        :root {
            --backgroundColor: #CCDAD1; /* Assuming this is a dark background */
            --pallete1: #6F6866; /* Gray */
            --pallete2: #788585; /* Lighter Gray */
            --pallete3: #9CAEA9; /* Green */
            --pallete4: #CCDAD1; /* Light Green */
            --pallete5: #F8F8F8 /* Off White */
        }
        body {
            padding-top: 20px;
            background-color: var(--backgroundColor);
            color: var(--pallete3); /* Primary text color now green */
        }
        .container {
            max-width: 600px;
            background-color: var(--pallete5); 
            color: var(--pallete2); /* Secondary text color */
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            padding: 20px; 
            padding-bottom: 40px; 
}

        .card {
           background-color: var(--pallete3); /* Card background */
           width: 80%; 
           max-height: 800px; 
           max-width: 480px; 
           margin: 20px auto; 
           padding: 20px; 
           box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); 
           border-radius: 8px; 
}
        .btn-primary {
            background-color: var(--pallete3); /* Main green color for primary buttons */
            border-color: var(--pallete4);
        }
        .btn-danger {
            background-color: var(--pallete1);
            border-color: var(--pallete2);
        }
        .logout-btn {
            margin-top: 15px;
        }
        .btn {
            color: var(--backgroundColor); /* Ensuring readable text on buttons */
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="text-center mb-4">
            <a href="newmain.php"><img src="nile.png" alt="Nile Home Icon" class="mb-4" style="width: 120px;"></a>
            <h1>Welcome, <?= htmlspecialchars($customerInfo['fname']) ?>!</h1>
        </div>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Customer Information</h5>
                <p class="card-text"><strong>First Name:</strong> <?= htmlspecialchars($customerInfo['fname']) ?></p>
                <p class="card-text"><strong>Last Name:</strong> <?= htmlspecialchars($customerInfo['lname']) ?></p>
                <p class="card-text"><strong>Customer ID:</strong> <?= htmlspecialchars($customerInfo['custID']) ?></p>
                <p class="card-text"><strong>Join Date:</strong> <?= htmlspecialchars($customerInfo['join_date']) ?></p>

                <?php if ($customerInfo): ?>
                    <form action="" method="post">
                        <input type="hidden" name="custID" value="<?= htmlspecialchars($customerInfo['custID']); ?>">
                        <div class="form-group">
                            <label for="card_number">Card Number</label>
                            <input type="text" class="form-control" id="card_number" name="card_number" 
                                   value="<?= htmlspecialchars($customerInfo['card_number'] ?? ''); ?>" placeholder="Enter new card number">
                        </div>
                        <button type="submit" name="updateCard" class="btn btn-primary">Update Card Number</button>
                    </form>
                <?php endif; ?>

                <?php if (isset($_SESSION['fname'])): ?>
                    <form action="logout.php" method="POST" class="logout-btn">
                        <button type="submit" class="btn btn-danger">Log Out</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>