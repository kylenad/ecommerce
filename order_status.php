<?php
    require 'includes/database-connection.php';
    //require 'product_images.php'; // Include the mapping file

    function getOrderInfo($orderNum, $pdo) {
        try {
            $sql = "SELECT order_status_table.*, product_table.*, order_info_table.*
                    FROM product_table
                    JOIN order_info_table ON product_table.productID = order_info_table.itemID
                    JOIN order_status_table ON order_status_table.orderID = order_info_table.orderID
                    WHERE order_status_table.orderID = :orderNum";

            $stmt = $pdo->prepare($sql);
            $stmt->execute(['orderNum' => $orderNum]);
            $orderInfo = $stmt->fetchALL(PDO::FETCH_ASSOC); // Use fetchAll to get all rows

        } catch (PDOException $e) {
            die("Error executing query: " . $e->getMessage());
        }
        return $orderInfo;
    }
    
	// Check if the request method is POST (i.e, form submitted)
    $orderInfo = [];
    $formSubmitted = false;
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		
        $formSubmitted = true;
		// Retrieve the value of the 'orderNum' field from the POST data
        $orderNum = $_POST['orderNum'];


		    /*
		    * TO-DO: Retrieve info about order from the db using provided PDO connection
		    */
		$orderInfo = getOrderInfo($orderNum, $pdo);
		
	}
    
// Closing PHP tag  ?> 

<!DOCTYPE>
<html>

	<head>
		<meta charset="UTF-8">
  		<meta name="viewport" content="width=device-width, initial-scale=1.0">
  		<title>Nile</title>
  		<link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		<link href="https://fonts.googleapis.com/css2?family=Lilita+One&display=swap" rel="stylesheet">
		<link rel="stylesheet" href="./css/style.css">
        <style>
        	/* Override text color for specific elements */
    		h1, label, .error-message {
        		color: var(--pallete1); /* Adjust this variable if needed */
    		}

			.order-details p {
        		color: var(--pallete1); /* Or any color that offers contrast against the background */
        		/* Additional styles for font-size, font-family etc., if needed */
    		}
        </style>
	</head>

	<body>

		<header>
			<div class="header-left">
				<div class="logo">
					<img src="nile.png" alt="Nile Logo">
      			</div>

	      		<nav>
	      			<ul>
	      				<li><a href="newmain.php">Home</a></li>
			        </ul>
			    </nav>
		   	</div>

		    
		</header>

		<main>

			<div class="order-lookup-container">
				<div class="order-lookup-container">
					<h1>Order Lookup</h1>
					<form action="order_status.php" method="POST">
						<div class="form-group">
							<label for="orderNum">Order Number:</label>
							<input type="number" id="orderNum" name="orderNum" required>
						</div>

						<button type="submit">Lookup Order</button>
					</form>
				</div>
				
				<!-- 
				  -- TO-DO: Check if variable holding order is not empty. Make sure to replace null with your variable!
				  -->
				<?php if ($formSubmitted): ?>
				    <?php if (!empty($orderInfo)): ?>
					    <div class="order-details">

						    <!-- 
				  		      -- TO DO: Fill in ALL the placeholders for this order from the db
  						      -->
						    <h1>Order Details</h1>
                            <?php foreach ($orderInfo as $item): ?>
								<p><img src="<?= htmlspecialchars($item['productURL']) ?>" alt="<?= htmlspecialchars($item['productName']) ?>"></p>
                                <p><strong>Product:</strong> <?= htmlspecialchars($item['productName']) ?></p>
                                <p><strong>Quantity:</strong> <?= htmlspecialchars($item['quantity']) ?></p>
                                <p><strong>Total Cost:</strong> $<?= htmlspecialchars(number_format($item['tot_cost'], 2)) ?></p>
                                <p><strong>Status:</strong> <?= htmlspecialchars($item['status']) ?></p>
                                <p><strong>Date Ordered:</strong> <?= htmlspecialchars($item['date_ordered']) ?></p>
                                <p><strong>Date Delivered:</strong> <?= htmlspecialchars($item['date_delivered']) ?></p>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p>No order details found for the provided order number. Please try again.</p>
				    <?php endif; ?>
                <?php endif; ?>

			</div>

		</main>

	</body>

</html>