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
if(isset($_SESSION['email'])){
	header("Location: changeInfo.php");
	exit();
}

function getUserInfo($email, $password, $pdo) {
    $sql = "SELECT *
            FROM cust_info_table
            WHERE email = :email AND password = :password";

    $stmt = $pdo->prepare($sql);
    $stmt->execute(['email' => $email, 'password' => $password]);
    $orderInfo = $stmt->fetch(PDO::FETCH_ASSOC);

    return $orderInfo;
 }

if($_SERVER['REQUEST_METHOD'] == 'POST') {     // If form submitted
    $email   = $_POST['email'];          // Email user sent
    $password = $_POST['password'];       	 // Password user sent

    $userInfo = getUserInfo($email, $password, $pdo);

    if(!empty($userInfo)){
        //login();

		$_SESSION['custID'] = $userInfo['custID'];
		$_SESSION['email'] = $userInfo['email'];
		$_SESSION['password'] = $userInfo['password'];
        $_SESSION['fname'] = $userInfo['fname'];

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
  		<title>NILE: Login</title>
  		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  		<link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		<link href="https://fonts.googleapis.com/css2?family=Lilita+One&display=swap" rel="stylesheet">
    <style>
        :root {
            --backgroundColor: #CCDAD1; /* Assuming this is a dark background */
            --pallete1: #6F6866; /* Gray */
            --pallete2: #788585; /* Lighter Gray */
            --pallete3: #9CAEA9; /* Green */
            --pallete4: #CCDAD1; /* Light Green */
            --pallete5: #F8F8F8; /* Off White */
            --pallete6: #000000; /* Black */
        }
        body {
            padding-top: 20px;
            background-color: var(--backgroundColor);
            color: var(--pallete1); /* Primary text color now green */
        }
        label {
            color: var(--pallete6);
        }
        h1 {
          color: var(--pallete5)
        }
        h5 {
            color: var(--pallete6);
        }
        p {
            color: var(--pallete6);
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
            background-color: var(--pallete1); /* Main green color for primary buttons */
            border-color: var(--pallete4);
        }
        .btn-danger {
            background-color: var(--pallete1);
            border-color: var(--pallete4);
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
        </div>

			<div class="card">
				<div class="card-body">
					<h1 class="card-title">Account Log In</h1>
					<form action="login.php" method="POST">
						<div class="form-group">
							<label for="email"><strong>Email:</strong></label>
							<input type="text" id="email" name="email" required>
						</div>

						<div class="form-group">
							<label for="password"><strong>Password:</strong></label>
							<input type="text" id="password" name="password" required>
						</div>

						<button type="submit">Log In</button>
					</form>
				</div>