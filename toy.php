<?php   										// Opening PHP tag
	
	// Include the database connection script
	require 'includes/database-connection.php';

	// Retrieve the value of the 'toynum' parameter from the URL query string
	//		i.e., ../toy.php?toynum=0001
	$toy_id = $_GET['toynum'];



	/*
	 * TO-DO: Define a function that retrieves ALL toy and manufacturer info from the database based on the toynum parameter from the URL query string.
	 		  - Write SQL query to retrieve ALL toy and manufacturer info based on toynum
	 		  - Execute the SQL query using the pdo function and fetch the result
	 		  - Return the toy info

	 		  Retrieve info about toy from the db using provided PDO connection
	 */

	 function getToyInfo($toy_id, $pdo) {
		$sql = "SELECT toy.*, manuf.name AS manuf_name, manuf.street, manuf.phone, manuf.contact, manuf.city, manuf.State, manuf.ZipCode 
				From toy
				JOIN manuf ON toy.manid = manuf.manid
				WHERE toy.toynum = :toy_id";
		
		$stmt = $pdo-> prepare($sql);
		$stmt->execute(['toy_id' => $toy_id]);
		$toyInfo = $stmt->fetch(PDO::FETCH_ASSOC);
		return $toyInfo;

	 }


	 $toyInfo = getToyInfo($toy_id, $pdo);

// Closing PHP tag  ?> 

<!DOCTYPE>
<html>

	<head>
		<meta charset="UTF-8">
  		<meta name="viewport" content="width=device-width, initial-scale=1.0">
  		<title>Toys R URI</title>
  		<link rel="stylesheet" href="css/style.css">
  		<link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		<link href="https://fonts.googleapis.com/css2?family=Lilita+One&display=swap" rel="stylesheet">
	</head>

	<body>

		<header>
			<div class="header-left">
				<div class="logo">
					<img src="imgs/logo.png" alt="Toy R URI Logo">
      			</div>

	      		<nav>
	      			<ul>
	      				<li><a href="index.php">Toy Catalog</a></li>
	      				<li><a href="about.php">About</a></li>
			        </ul>
			    </nav>
		   	</div>

		    <div class="header-right">
		    	<ul>
		    		<li><a href="order.php">Check Order</a></li>
		    	</ul>
		    </div>
		</header>

		<main>
			<!-- 
			  -- TO DO: Fill in ALL the placeholders for this toy from the db
  			  -->
			
			<div class="toy-details-container">
				<div class="toy-image">
					<!-- Display image of toy with its name as alt text -->
					<img src="<?= $toyInfo['imgSrc'] ?>" alt="<?= $toyInfo['name'] ?>">
				</div>

				<div class="toy-details">

					<!-- Display name of toy -->
			        <h1><?= $toyInfo['name'] ?></h1>

			        <hr />

			        <h3>Toy Information</h3>

			        <!-- Display description of toy -->
			        <p><strong>Description:</strong> <?= $toyInfo['description'] ?></p>

			        <!-- Display price of toy -->
			        <p><strong>Price:</strong> $ <?= $toyInfo['price'] ?></p>

			        <!-- Display age range of toy -->
			        <p><strong>Age Range:</strong> <?= $toyInfo['agerange'] ?></p>

			        <!-- Display stock of toy -->
			        <p><strong>Number In Stock:</strong> <?= $toyInfo['numinstock'] ?></p>

			        <br />

			        <h3>Manufacturer Information</h3>

			        <!-- Display name of manufacturer -->
			        <p><strong>Name:</strong> <?= $toyInfo['manuf_name'] ?> </p>

			        <!-- Display address of manufacturer -->
			        <p><strong>Address:</strong> <?= $toyInfo['street'], ", ", $toyInfo['city'], ", ", $toyInfo['State'], " ", $toyInfo['ZipCode'] ?></p>

			        <!-- Display phone of manufacturer -->
			        <p><strong>Phone:</strong> <?= $toyInfo['phone'] ?></p>

			        <!-- Display contact of manufacturer -->
			        <p><strong>Contact:</strong> <?= $toyInfo['contact'] ?></p>
			    </div>
			</div>
		</main>

	</body>
</html>
