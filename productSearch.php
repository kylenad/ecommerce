<?php   										// Opening PHP tag
	
	// Include the database connection script
	require 'includes/database-connection.php';

  // Get the search value from the search query
  if(isset($_GET['searchValue'])) {
    $search_term = $_GET['searchValue'];
  } 

  function get_info(PDO $pdo, $search_term) {
    $sql = "SELECT * 
      FROM product_table
      WHERE productName LIKE :searchTerm";
    
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':searchTerm', '%' . $search_term . '%');
    $stmt->execute();

    return $stmt;
 }

 function get_item_price(PDO $pdo, $prod_id) {
    //Calculate the cost of the items 
    $sql_price = "SELECT productPrice 
    FROM price_table
    WHERE priceID= :price_id;";

    // Fetch the corresponding item price
    $cost = pdo($pdo, $sql_price, [
      ':price_id' => $prod_id,
    ])->fetch();

    return $cost;
 }

  function write_to_cart(PDO $pdo, $cust_id, $prod_id, $quantity) {
    $sql = "INSERT INTO cart_table (custID, productID, quantity)
      VALUES (:cust_id, :prod_id, :quantity)";

    $cart_item = pdo($pdo, $sql, [
      ':cust_id' => $cust_id,
      ':prod_id' => $prod_id,
      ':quantity' => $quantity
    ]);
  }

  if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_to_cart'])) {
    $cust_id = 2; // Place holder value until customer can log in
    $prod_id = $_POST['prodnum']; 
    $quantity = 1;

    write_to_cart($pdo, $cust_id, $prod_id, $quantity);
  }
// Closing PHP tag  ?> 

<!DOCTYPE>
<html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link rel="stylesheet" href="./css/style.css">
</head>
<body>
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
        <a href="">
          <svg width="800px" height="800px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M4 21C4 17.4735 6.60771 14.5561 10 14.0709M19.8726 15.2038C19.8044 15.2079 19.7357 15.21 19.6667 15.21C18.6422 15.21 17.7077 14.7524 17 14C16.2923 14.7524 15.3578 15.2099 14.3333 15.2099C14.2643 15.2099 14.1956 15.2078 14.1274 15.2037C14.0442 15.5853 14 15.9855 14 16.3979C14 18.6121 15.2748 20.4725 17 21C18.7252 20.4725 20 18.6121 20 16.3979C20 15.9855 19.9558 15.5853 19.8726 15.2038ZM15 7C15 9.20914 13.2091 11 11 11C8.79086 11 7 9.20914 7 7C7 4.79086 8.79086 3 11 3C13.2091 3 15 4.79086 15 7Z" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
        </a>
      </div>

      <div class="heart">
        <a href="">
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
    <button class="nav-btn"> buy again </button>
    <button class="nav-btn"> track order</button>
    <button class="nav-btn"> change order </button>
    <button class="nav-btn"> change info </button>
  </div>

  <div class="filter-container">
    <div class="left-filter">
        <button> Estimated Delivery </button>
        <button> All Filters </button>
    </div>
    <div class="right-filter">
      <button> Sort by: Relevancy </button>
    </div>
  </div>

  <?php
    if (!empty($search_term)) {
        $productSearch = get_info($pdo, $search_term);
        $count = 0; // Initialize a counter to keep track of the number of items

        // Output data of each row
        while ($row = $productSearch->fetch(PDO::FETCH_ASSOC)) {
            // Start a new row after every 4 items
            if ($count % 4 == 0) {
                echo '<div class="product-row">';
            }

            // Output each item
            echo '<div class=product-list-container>';
            echo '<div class="product-list-img"><a href="product.php?prodnum=' . $row['productID'] . '"><img src="' . $row['productURL'] . '" alt="' . $row['productName'] . '"></a></div>';
            echo '<p>' . $row['productName'] . '</p>';
            
            // Rating for items
            echo '<div class="product-list-star">';
            echo '<p>' . $row['productRating'] . '</p>';
            echo '<svg version="1.0" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="16px" height="16px" viewBox="0 0 64 64" enable-background="new 0 0 64 64" xml:space="preserve">';
            echo '<path fill="#231F20" d="M63.893,24.277c-0.238-0.711-0.854-1.229-1.595-1.343l-19.674-3.006L33.809,1.15C33.479,0.448,32.773,0,31.998,0s-1.48,0.448-1.811,1.15l-8.815,18.778L1.698,22.935c-0.741,0.113-1.356,0.632-1.595,1.343c-0.238,0.71-0.059,1.494,0.465,2.031l14.294,14.657L11.484,61.67c-0.124,0.756,0.195,1.517,0.822,1.957c0.344,0.243,0.747,0.366,1.151,0.366c0.332,0,0.666-0.084,0.968-0.25l17.572-9.719l17.572,9.719c0.302,0.166,0.636,0.25,0.968,0.25c0.404,0,0.808-0.123,1.151-0.366c0.627-0.44,0.946-1.201,0.822-1.957l-3.378-20.704l14.294-14.657C63.951,25.771,64.131,24.987,63.893,24.277z"/>';
            echo '</svg>';
            echo '<p>(' . $row['productReviewCount'] . ')</p>';
            echo '</div>';
            
            $cost = get_item_price($pdo, $row['productID']);
            echo '<div class="product-list-cost">';
            echo '<p>$' . $cost['productPrice'] . '</p>';
            echo '</div>';

            // Add form for adding item to cart
            echo '<form action="' . htmlspecialchars($_SERVER["PHP_SELF"]) . '?' . $_SERVER['QUERY_STRING'] . '" method="POST">';
            echo '<input type="hidden" name="prodnum" value="' . $row['productID'] . '">';
            echo '<button type="submit" class="product-list-cart" name="add_to_cart"> + Add to Cart</button>';
            echo '</form>';

            echo '</div>';
            // End the row after every 4 items
            if ($count % 4 == 3) {
                echo '</div>';
            }

            $count++; // Increment the counter
        }

        // Close the row if the total number of items is not divisible by 4
        if ($count % 4 != 0) {
            echo '</div>';
        }

        // If no items were found, display a message
        if ($count == 0) {
            echo '<p> No items found.</p>';
        }
    }
    ?>

  
</body>
</html>