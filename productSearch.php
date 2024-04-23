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