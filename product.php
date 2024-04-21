<?php   										// Opening PHP tag
	
	// Include the database connection script
	require 'includes/database-connection.php';

	 function get_info(PDO $pdo, $id) {
			$sql = "SELECT * 
				FROM product_table
				WHERE productID= :id;";
				
			$product = pdo($pdo, $sql, ['id' => $id])->fetch();		

			return $product;
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

    function write_to_watchlist(PDO $pdo, $cust_id, $prod_id, $quantity) {
      $sql = "INSERT INTO watchlist_table (custID, productID, quantity)
        VALUES (:cust_id, :prod_id, :quantity)";

      $cart_item = pdo($pdo, $sql, [
        ':cust_id' => $cust_id,
        ':prod_id' => $prod_id,
        ':quantity' => $quantity
      ]);
    }

    function write_to_order(PDO $pdo, $cust_id, $prod_id, $quantity) {
      //Calculate the cost of the items 
      $sql_price = "SELECT productPrice 
        FROM price_table
        WHERE priceID= :price_id;";

      // Fetch the corresponding item price
      $cost = pdo($pdo, $sql_price, [
        ':price_id' => $cust_id,
      ])->fetch();

      // Calculate the total cost for the order
      $total_cost = $cost["productPrice"] * $quantity;
      
      // Determine the latest order number and add 1 to it.
      $sql_max = "SELECT MAX(orderID) AS ord_id FROM order_info_table";
      $max_order_num = pdo($pdo, $sql_max)->fetch();
      $new_order_num = $max_order_num["ord_id"] + 1;
      
      // Make an order : insert all information into order_table
      $sql = "INSERT INTO order_info_table (orderID, custID, itemID, quantity, tot_cost)
        VALUES (:orderID, :custID, :itemID, :quantity, :tot_cost)";

      $order = pdo($pdo, $sql, [
        ':orderID' => $new_order_num,
        ':custID' => $cust_id,
        ':itemID' => $prod_id,
        ':quantity' => $quantity,
        ':tot_cost' => $total_cost
      ]);
    }

    // The form has been submitted using post method, add to cart is clicked, and prodnum is set in the form 
    if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_to_cart']) && isset($_POST['prodnum'])) {
      $cust_id = 2; // Place holder value until customer can log in
      $prod_id = $_POST['prodnum']; 
      $quantity = $_POST['quantity'];

      write_to_cart($pdo, $cust_id, $prod_id, $quantity);
    }

    // The form has been submitted using post method, add to watchlist is clicked, and prodnum is set in the form
    if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_to_watch']) && isset($_POST['prodnum'])) {
      $cust_id = 2; // Place holder value until customer can log in
      $prod_id = $_POST['prodnum']; 
      $quantity = $_POST['quantity'];

      write_to_watchlist($pdo, $cust_id, $prod_id, $quantity);
    }

    if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['buy-item']) && isset($_POST['prodnum'])) {
      $cust_id = 2; // Place holder value until customer can log in
      $prod_id = $_POST['prodnum']; 
      $quantity = intval($_POST['quantity']);

      write_to_order($pdo, $cust_id, $prod_id, $quantity);
    }

    $prod_id = $_GET['prodnum']; 
    $product = get_info($pdo, $prod_id);
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
      <a href="index.php"><img src="nile.png" alt=""></a>
    </div>

    <div class="search-container">
      <div class="search-bar">
        <input type="search" placeholder="Search for anything">
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
  
  <div class="main-hub-container">
    <div class="back-to-prev">
      <a href="index.php">
        <span class="arrow left"></span>
        <span class="ux-textspans"> Back to home page </span>
      </a>
      <div class="link-list-container">
        <a href="index.php"><span> Home </span> <span class="arrow right"></span></a>
        <a href="list.php"><span> Products </span> <span class="arrow right"></span></a>
        <a href="product.php"><span> Items </span> <span class="arrow right"></span></a>
      </div>
    </div>
    <div class="item-container-product">
      <div class="left-container">
        <div class="img-scroll">
          <img src="<?= $product['productURL'] ?>" alt="productName">
        </div>
        <div class="bottom-img">
          <div class="mini-img"></div>
          <div class="mini-img"></div>
          <div class="mini-img"></div>
          <div class="mini-img"></div>
        </div>
      </div>
      <div class="right-container">
        <div class="title">
          <p> <?= $product['productName'] ?> </p>
        </div>
        <div class="vendor">

        </div>

        <div class="price"></div>

        <div class="quantity"></div>

       
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?' . $_SERVER['QUERY_STRING']; ?>" method="POST">
          <input type="hidden" name="prodnum" value="<?php echo isset($_GET['prodnum']) ? $_GET['prodnum'] : ''; ?>">
          <input type="number" name="quantity" value="<?php echo isset($_POST['quantity']) ? $_POST['quantity'] : '1'; ?>" min=1 oninput="validity.valid||(value='');">
          <button type="submit" class="user-btn" name="buy-item">Buy</button>
          <button type="submit" class="user-btn" name="add_to_cart">Add to Cart</button>
          <button type="submit" class="user-btn" name="add_to_watch">Add to Watchlist</button>
      </form>
      

        <div class="status info"></div>
      </div>
    </div>
    <div class="recent-viewed-container"></div>
    <div class="about-item"></div>
    <div class="reviews"></div>
  </div>
</body>
</html>
