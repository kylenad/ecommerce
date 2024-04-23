<?php
session_start();

	// Include the database connection script
require 'includes/database-connection.php';
//include 'includes/log.php';

/* 
if ($logged_in) {                              // If already logged in
    header('Location: newmain.php');           // Redirect to account page
    exit;                                      // Stop further code running
}    
*/
if(isset($_SESSION['fname'])){
	header("Location: changeInfo.php");
	exit();
}

function getUserInfo($fname, $lname, $pdo) {
    $sql = "SELECT *
            FROM cust_info_table
            WHERE fname = :fname AND lname = :lname";

    $stmt = $pdo->prepare($sql);
    $stmt->execute(['fname' => $fname, 'lname' => $lname]);
    $orderInfo = $stmt->fetch(PDO::FETCH_ASSOC);

    return $orderInfo;
 }

if($_SERVER['REQUEST_METHOD'] == 'POST') {     // If form submitted
    $fname   = $_POST['fname'];          // Email user sent
    $lname = $_POST['lname'];       	 // Password user sent

    $userInfo = getUserInfo($fname, $lname, $pdo);

    if(!empty($userInfo)){
        //login();

		$_SESSION['custID'] = $userInfo['custID'];
		$_SESSION['fname'] = $userInfo['fname'];
		$_SESSION['lname'] = $userInfo['lname'];

        header('Location: newmain.php');
        exit;
    }
}
?>

<!DOCTYPE>
<html>

	<head>
		<meta charset="UTF-8">
  		<meta name="viewport" content="width=device-width, initial-scale=1.0">
  		<title>NILE</title>
  		<link rel="stylesheet" href="./style.css">
  		<link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		<link href="https://fonts.googleapis.com/css2?family=Lilita+One&display=swap" rel="stylesheet">
	</head>

	<body>

		<header>
			<div class="header-left">
				<div class="logo">
				<a href="newmain.php"><img src="nile.png" alt=""></a>
      			</div>
</div>

		<main>

			<div class="order-lookup-container">
				<div class="order-lookup-container">
					<h1>Account Log In</h1>
					<form action="login.php" method="POST">
						<div class="form-group">
							<label for="fname">First Name:</label>
							<input type="text" id="fname" name="fname" required>
						</div>

						<div class="form-group">
							<label for="lname">Last Name:</label>
							<input type="text" id="lname" name="lname" required>
						</div>

						<button type="submit">Log In</button>
					</form>
				</div>