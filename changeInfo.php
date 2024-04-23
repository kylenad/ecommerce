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


<!DOCTYPE>
<html>
<head>
<meta charset="UTF-8">
  		<meta name="viewport" content="width=device-width, initial-scale=1.0">
  		<title>Customer Information</title>
  		<link rel="stylesheet" href="./css/style.css">
  		<link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		<link href="https://fonts.googleapis.com/css2?family=Lilita+One&display=swap" rel="stylesheet">
</head>
<div class="top-nav">
    <div class="logo">
      <a href="newmain.php"><img src="nile.png" alt=""></a>
    </div>

    <div class="search-container">
      <div class="search-bar">
        <form action="productSearch.php" method="GET">
          <input type="search" name=searchValue placeholder="Search for anything">
          <button type="submit">
            <svg width="24px" height="24px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M15.7955 15.8111L21 21M18 10.5C18 14.6421 14.6421 18 10.5 18C6.35786 18 3 14.6421 3 10.5C3 6.35786 6.35786 3 10.5 3C14.6421 3 18 6.35786 18 10.5Z" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </button>
        </form>
          
      </div>
      <div class="search-logo">
      </div>
    </div>

    <div class="icons-container">
      <div class="cart">
      <a href="">
        <svg width="800px" height="800px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M6.29977 5H21L19 12H7.37671M20 16H8L6 3H3M9 20C9 20.5523 8.55228 21 8 21C7.44772 21 7 20.5523 7 20C7 19.4477 7.44772 19 8 19C8.55228 19 9 19.4477 9 20ZM20 20C20 20.5523 19.5523 21 19 21C18.4477 21 18 20.5523 18 20C18 19.4477 18.4477 19 19 19C19.5523 19 20 19.4477 20 20Z" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
      </a>
      </div>

      <div class="account">
      <a href="changeInfo.php">
          <svg width="800px" height="800px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M4 21C4 17.4735 6.60771 14.5561 10 14.0709M19.8726 15.2038C19.8044 15.2079 19.7357 15.21 19.6667 15.21C18.6422 15.21 17.7077 14.7524 17 14C16.2923 14.7524 15.3578 15.2099 14.3333 15.2099C14.2643 15.2099 14.1956 15.2078 14.1274 15.2037C14.0442 15.5853 14 15.9855 14 16.3979C14 18.6121 15.2748 20.4725 17 21C18.7252 20.4725 20 18.6121 20 16.3979C20 15.9855 19.9558 15.5853 19.8726 15.2038ZM15 7C15 9.20914 13.2091 11 11 11C8.79086 11 7 9.20914 7 7C7 4.79086 8.79086 3 11 3C13.2091 3 15 4.79086 15 7Z" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
        </a>
      </div>

      <div class="heart">
        <a href="order_status.php">
          <svg width="800px" height="800px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M5 10H7C9 10 10 9 10 7V5C10 3 9 2 7 2H5C3 2 2 3 2 5V7C2 9 3 10 5 10Z" stroke="#000000" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
          <path d="M17 10H19C21 10 22 9 22 7V5C22 3 21 2 19 2H17C15 2 14 3 14 5V7C14 9 15 10 17 10Z" stroke="#000000" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
          <path d="M17 22H19C21 22 22 21 22 19V17C22 15 21 14 19 14H17C15 14 14 15 14 17V19C14 21 15 22 17 22Z" stroke="#000000" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
          <path d="M5 22H7C9 22 10 21 10 19V17C10 15 9 14 7 14H5C3 14 2 15 2 17V19C2 21 3 22 5 22Z" stroke="#000000" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
        </a>
      </div>
    </div>

  </div>
  <div class="bottom-nav">
  </div>
    <body>
        <div class="welcome">
        <h1>Customer Information</h1>
            <p><strong>First Name:</strong> <?= $customerInfo['fname'] ?></p>
			<p><strong>Last Name:</strong> <?= $customerInfo['lname'] ?></p>
			<p><strong>Customer ID:</strong> <?= $customerInfo['custID'] ?></p>
			<p><strong>Join Date:</strong> <?= $customerInfo['join_date'] ?></p>

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

        
        <?php if (isset($_SESSION['fname'])): ?>
            <form action="logout.php" method="POST">
                    <button type="submit">Log Out</button>
         </form>
         <?php endif; ?>
         
        </div>
     </body>

</html>