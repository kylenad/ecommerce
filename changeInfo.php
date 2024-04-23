<?php
require 'includes/database-connection.php';

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

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['fetchInfo'])) {
    // Handle fetch
    $custID = $_POST['custID'];
    $customerInfo = getCustomerInfo($custID, $pdo);
}
?>


<!DOCTYPE>
<html>
<head>
<meta charset="UTF-8">
  		<meta name="viewport" content="width=device-width, initial-scale=1.0">
  		<title>Customer Information</title>
  		<link rel="stylesheet" href="css/style.css">
  		<link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		<link href="https://fonts.googleapis.com/css2?family=Lilita+One&display=swap" rel="stylesheet">
</head>
    <body>
        <h1>Update Customer Information</h1>
        <form action="" method="post">
         <input type="text" name="custID" placeholder="Enter Customer ID" required>
         <input type="submit" name="fetchInfo" value="Fetch Information">
         </form>

        <?php if (isset($customerInfo) && $customerInfo): ?>
           <form action="" method="post">
            <input type="hidden" name="custID" value="<?php echo htmlspecialchars($customerInfo['custID']); ?>">
            <input type="text" name="fname" value="<?php echo htmlspecialchars($customerInfo['fname']); ?>" placeholder="First Name">
            <input type="text" name="lname" value="<?php echo htmlspecialchars($customerInfo['lname']); ?>" placeholder="Last Name">
            <input type="text" name="card_number" value="<?php echo htmlspecialchars($customerInfo['card_number'] ?? ''); ?>" placeholder="Card Number">
            <input type="submit" name="updateInfo" value="Update Information">
            <input type="submit" name="updateCard" value="Update Card Number">
           </form>
        <?php endif; ?>
     </body>

</html>