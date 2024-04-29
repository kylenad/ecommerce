<?php
    require 'includes/database-connection.php';
    //require 'product_images.php'; // Include the mapping file
    //Test1

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
  		<link rel="stylesheet" href="./style.css">
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
        .card {
          background-color: var(--pallete5); /* Off White */
          border-radius: 10px;
          box-shadow: 0 4px 8px rgba(0,0,0,0.2);
          padding: 20px;
          margin: 20px auto; /* This centers the card in the middle of the page */
          width: 80%; 
          max-width: 600px; /* Maximum width of the card */
          color: var(--pallete6); /* Text color */
          margin-top: 165px;
        }
        .order-lookup-container h1 {
          margin-bottom: 20px; 
        }

        .order-lookup-container label {
          display: block;
          margin-bottom: 10px;
          font-size: 1.5em;
        }

        .order-lookup-container input[type="number"] {
          margin-bottom: 10px; 
          font-size: 1.5em;
        }

        .order-lookup-container {
          display: flex;
          flex-direction: column;
          justify-content: space-between;
          height: 150px; 
        }
        .order-lookup-container button[type="submit"] {
          font-size: 1em; /* Smaller font size for the button */
          padding: 15px 30px;
          
        }
        
        .order-details {
            color: #000000; /* Sets text color to black */
			      display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            width: 100%;
			      padding-top: 25px;
        }

		    .user-btn {
                padding: 20px 40px;
                font-size: 1.5em;
        }

        .error-message {
            color: #FF0000; /* Sets error messages to red for visibility */
			      display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            width: 100%;
			      padding-top: 25px;
        }
        </style>
	</head>
		<header>
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
      <a href="cart.php">
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
		</header>

		<main>

			<div class="order-lookup-container">
				<div class="welcome">
					<h1>Order Lookup</h1>
					<form action="order_status.php" method="POST">
						
						<div class="form-group">
							<label for="orderNum">Order Number:</label>
							<input type="number" id="orderNum" name="orderNum" required>
						</div>

						<button type="submit">Lookup Order</button>
					</form>
				</div>
				
				<?php if ($formSubmitted): ?>
				    <?php if (!empty($orderInfo)): ?>
              <div class="card order-details">

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
                    <div class="card error-message">
                        <p>No order details found for the provided order number. Please try again.</p>
                    </div>
				    <?php endif; ?>
                <?php endif; ?>

			</div>

		</main>

	</body>

</html>

