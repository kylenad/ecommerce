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
        <path fill-rule="evenodd" clip-rule="evenodd" d="M1 6C1 4.89543 1.89543 4 3 4H14C15.1046 4 16 4.89543 16 6V7H19C21.2091 7 23 8.79086 23 11V12V15V17C23.5523 17 24 17.4477 24 18C24 18.5523 23.5523 19 23 19H22H18.95C18.9828 19.1616 19 19.3288 19 19.5C19 20.8807 17.8807 22 16.5 22C15.1193 22 14 20.8807 14 19.5C14 19.3288 14.0172 19.1616 14.05 19H7.94999C7.98278 19.1616 8 19.3288 8 19.5C8 20.8807 6.88071 22 5.5 22C4.11929 22 3 20.8807 3 19.5C3 19.3288 3.01722 19.1616 3.05001 19H2H1C0.447715 19 0 18.5523 0 18C0 17.4477 0.447715 17 1 17V6ZM16.5 19C16.2239 19 16 19.2239 16 19.5C16 19.7761 16.2239 20 16.5 20C16.7761 20 17 19.7761 17 19.5C17 19.2239 16.7761 19 16.5 19ZM16.5 17H21V15V13H19C18.4477 13 18 12.5523 18 12C18 11.4477 18.4477 11 19 11H21C21 9.89543 20.1046 9 19 9H16V17H16.5ZM14 17H5.5H3V6H14V8V17ZM5 19.5C5 19.2239 5.22386 19 5.5 19C5.77614 19 6 19.2239 6 19.5C6 19.7761 5.77614 20 5.5 20C5.22386 20 5 19.7761 5 19.5Z" fill="#000000"/>
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