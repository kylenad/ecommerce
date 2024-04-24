<?php   										// Opening PHP tag
  // Include the database connection script
  require 'includes/database-connection.php';
  include 'includes/log.php';

  if(isset($_SESSION['custID'])){
      $custID = $_SESSION['custID'];
  }

  function get_info(PDO $pdo, $id) {
    $sql = "SELECT * 
      FROM product_table
      WHERE productID= :id;";
      
    $product = pdo($pdo, $sql, ['id' => $id])->fetch();		

    return $product;
  }

  function write_to_cart(PDO $pdo, $cust_id, $prod_id, $quantity) {
    $sql_check = "SELECT *
      FROM cart_table
      WHERE custID=:cust_id AND productID= :prod_id;";

    $existing_item = pdo($pdo, $sql_check, [
      ':cust_id' => $cust_id, 
      ':prod_id' => $prod_id
    ])->fetch();

    if($existing_item) {
      // If item exist in cart, update the quantity
      $sql_update = "UPDATE cart_table SET quantity= quantity + :quantity
      WHERE custID= :cust_id AND productID= :prod_id;";

      $update_cart = pdo($pdo, $sql_update, [
        ':cust_id' => $cust_id,
        ':prod_id' => $prod_id,
        ':quantity' => $quantity
      ]);
    } else {
      // If item doesn't exist in cart, add to cart
      $sql = "INSERT INTO cart_table (custID, productID, quantity)
        VALUES (:cust_id, :prod_id, :quantity)";
  
      $cart_item = pdo($pdo, $sql, [
        ':cust_id' => $cust_id,
        ':prod_id' => $prod_id,
        ':quantity' => $quantity
      ]);
    }
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

  function get_vendor_info(PDO $pdo, $brand_id) {
    $sql = "SELECT * 
      FROM ven_info_table
      WHERE vendorID= :id;";
      
    $vendor = pdo($pdo, $sql, ['id' => $brand_id])->fetch();	

    return $vendor;
  }

  function get_review_info(PDO $pdo, $prod_id) {
    $sql = "SELECT * 
      FROM review_table AS rt
      WHERE rt.productID= :id;";
      
    $review = pdo($pdo, $sql, ['id' => $prod_id])->fetchAll(PDO::FETCH_ASSOC);		

    return $review;
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

  // The form has been submitted using post method, add to cart is clicked, and prodnum is set in the form 
  if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_to_cart']) && isset($_POST['prodnum'])) {
    $cust_id = $custID; 
    $prod_id = $_POST['prodnum']; 
    $quantity = $_POST['quantity'];

    write_to_cart($pdo, $cust_id, $prod_id, $quantity);
  }

  // The form has been submitted using post method, add to watchlist is clicked, and prodnum is set in the form
  if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_to_watch']) && isset($_POST['prodnum'])) {
    $cust_id = $custID; // Place holder value until customer can log in
    $prod_id = $_POST['prodnum']; 
    $quantity = $_POST['quantity'];

    write_to_watchlist($pdo, $cust_id, $prod_id, $quantity);
  }

  if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['buy-item']) && isset($_POST['prodnum'])) {
    $cust_id = $custID; // Place holder value until customer can log in
    $prod_id = $_POST['prodnum']; 
    $quantity = intval($_POST['quantity']);

    write_to_order($pdo, $cust_id, $prod_id, $quantity);
  }

  // Get Product Information 
  $prod_id = $_GET['prodnum']; 
  $product = get_info($pdo, $prod_id);
  $vendor = get_vendor_info($pdo, intval($product['brandID']));
  $review = get_review_info($pdo, $prod_id);
  $cost = get_item_price($pdo, $prod_id);
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
  
  <div class="main-hub-container">
    <div class="back-to-prev">
      <a href="newmain.php">
        <span class="arrow left"></span>
        <span class="ux-textspans"> Back to home page </span>
      </a>
      <div class="link-list-container">
        <a href="newmain.php"><span> Home </span> <span class="arrow right"></span></a>
        <a href="productSearch.php"><span> Products </span> <span class="arrow right"></span></a>
        <a href="product.php"><span> Items </span> <span class="arrow right"></span></a>
      </div>
    </div>
    <div class="item-container-product">
      <div class="left-container">
        <div class="img-scroll">
          <img src="<?= $product['productURL'] ?>" alt="productName">
        </div>
      </div>
      <div class="right-container">
        <div class="title">
          <h2> <?= $product['productName'] ?> </h2>
        </div>
        <div class="vendor">
          <div class="company-icon">
            <svg fill="#000000" width="36px" height="36px" viewBox="0 0 512 512" enable-background="new 0 0 512 512" id="Stay_x5F_organize" version="1.1" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
            <g>
            <path d="M488.303,229.44l-26.801-5.893c-5.053-1.107-8.932-5.08-10.004-10.143c-4.242-20.045-11.472-38.979-21.233-56.311   c-2.387-4.236-2.236-9.438,0.387-13.533l16.389-25.586c3.336-5.207,2.597-12.033-1.776-16.406l-27.195-27.197   c-4.373-4.373-11.197-5.111-16.404-1.777L378.79,87.241c-4.35,2.785-9.916,2.748-14.256-0.059   c-16.818-10.889-35.375-19.313-55.166-24.771c-4.697-1.295-8.289-5.084-9.332-9.844l-6.469-29.617   c-1.324-6.041-6.676-10.451-12.859-10.451h-38.465c-6.184,0-11.533,4.41-12.855,10.451l-5.863,26.786   c-1.1,5.027-5.037,8.958-10.063,10.051c-19.986,4.352-38.85,11.707-56.109,21.551c-4.248,2.42-9.479,2.301-13.598-0.336   L117.923,64.46c-5.207-3.332-12.035-2.592-16.406,1.779l-27.199,27.2c-4.373,4.371-5.111,11.2-1.777,16.407l15.131,23.629   c2.771,4.326,2.75,9.858-0.016,14.19c-10.576,16.566-18.775,34.793-24.119,54.205c-1.295,4.703-5.082,8.301-9.848,9.348   l-30.766,6.725c-6.041,1.326-10.422,6.676-10.422,12.859v38.463c0,6.184,4.381,11.533,10.422,12.859l28.128,6.156   c5.014,1.1,8.93,5.02,10.038,10.031c4.303,19.453,11.459,37.838,20.979,54.701c2.396,4.244,2.268,9.453-0.361,13.557l-17.297,27.02   c-3.336,5.205-2.595,12.031,1.778,16.404l27.199,27.199c4.373,4.371,11.198,5.111,16.405,1.775l24.54-15.713   c4.336-2.777,9.877-2.746,14.215,0.033c16.268,10.42,34.145,18.545,53.178,23.922c4.668,1.318,8.229,5.09,9.266,9.824l6.9,31.298   c1.324,6.039,6.674,10.168,12.857,10.168h38.465c6.184,0,11.531-4.129,12.857-10.168l6.189-28.145   c1.105-5.041,5.063-8.906,10.107-9.992c19.514-4.197,37.969-11.222,54.908-20.661c4.23-2.359,9.408-2.202,13.488,0.411   l26.771,17.148c5.207,3.336,12.033,2.597,16.404-1.774l27.201-27.199c4.369-4.371,5.109-11.198,1.777-16.405l-15.232-23.78   c-2.789-4.359-2.744-9.938,0.074-14.279c10.732-16.521,19.082-34.732,24.574-54.143c1.322-4.662,5.094-8.215,9.822-9.248   l30.147-6.645c6.041-1.324,10.197-6.674,10.197-12.857v-38.465C498.5,236.114,494.344,230.765,488.303,229.44z M254.407,407.585   c-82.277,0-148.979-66.701-148.979-148.979s66.701-148.979,148.979-148.979s148.977,66.701,148.977,148.979   S336.685,407.585,254.407,407.585z"/>
            <path d="M196.716,237.659c12.982,0,23.504-10.525,23.504-23.504c0-12.982-10.521-23.506-23.504-23.506   c-12.98,0-23.504,10.523-23.504,23.506C173.212,227.134,183.735,237.659,196.716,237.659z"/>
            <path d="M316.192,237.659c12.982,0,23.504-10.525,23.504-23.504c0-12.982-10.521-23.506-23.504-23.506   c-12.98,0-23.504,10.523-23.504,23.506C292.688,227.134,303.212,237.659,316.192,237.659z"/>
            <circle cx="256.364" cy="229.3" r="23.506"/>
            <path d="M255.5,262.124c-25.129,0-45,20.371-45,45.498v9.342c0,1.818,1.614,3.536,3.437,3.536h84.406   c1.818,0,2.157-1.718,2.157-3.536v-9.342C300.5,282.495,280.629,262.124,255.5,262.124z"/>
            <path d="M226.599,258.633c-8.012-7.124-19.295-11.559-30.852-11.559c-25.133,0-46.247,20.288-46.247,45.397v9.336   c0,1.824,2.968,3.692,4.792,3.692h45.162C200.452,284.5,211.034,268.627,226.599,258.633z"/>
            <path d="M315.993,246.978c-11.609,0-22.168,4.573-30.191,11.722c15.502,9.988,26.025,25.801,27.023,46.801h45.338   c1.822,0,3.337-1.868,3.337-3.692v-9.336c0-12.555-5.102-23.941-13.334-32.152C339.93,252.062,328.569,246.978,315.993,246.978z"/>
            </g>
            </svg>
          </div>
          <div class="company-info">
            <h3> <?= $vendor['venName'] ?> </h3>
            <div class="company-info-tags">
              <div class="tag">
                <p> 100% positive </p>
              </div>
              <div class="tag">
                <p> Sells other items </p>
              </div>
              <div class="tag">
                <p> Shipping World-wide </p>
              </div>
            </div>
          </div>
        </div>


        <div class="price">
          <h3> $ <?= $cost['productPrice'] ?> </h3>
        </div>

        <div class="deliver-container">
          <svg width="24px" height="24px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M8.5 12.5L10.5 14.5L15.5 9.5" stroke="#1C274C" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
          <path d="M7 3.33782C8.47087 2.48697 10.1786 2 12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12C2 10.1786 2.48697 8.47087 3.33782 7" stroke="#1C274C" stroke-width="1.5" stroke-linecap="round"/>
          </svg>
          <p> Arrives soon! Get it by April 30-May 4 if you order today </p>
        </div>

        <div class="user-interaction-container">
          <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?' . $_SERVER['QUERY_STRING']; ?>" method="POST">
              <input type="hidden" name="prodnum" value="<?php echo isset($_GET['prodnum']) ? $_GET['prodnum'] : ''; ?>">
              <div class="quantity-container">
                <label for="quantity"> Quantity: </label>
                <input type="number" id="quantity" name="quantity" value="<?php echo isset($_POST['quantity']) ? $_POST['quantity'] : '1'; ?>" min=1 oninput="validity.valid||(value='');">
                <label for="quantity"> Mutliple items in Stock </label>
              </div>
          
              <button type="submit" class="user-btn" name="buy-item">Buy now</button>
              <div class="product-btn-option">
                <button type="submit" class="user-btn-v2" name="add_to_cart">Add to Cart</button>
                <button type="submit" class="user-btn-v2" name="add_to_watch">Add to Watchlist</button>
              </div>
          </form>
        </div>
      

        <div class="status info"></div>
      </div>
    </div>
    <div class="recent-viewed-container"></div>

    <div class="about-item">
      <h1> About Item </h1>
      <p> <?= $product['productDescription'] ?> </p>
    </div>
    
    <div class="reviews">
      <div class="reviews-title">
        <h1> Reviews </h1> 
        <h1> (<?= count($review) ?>) </h1>
      </div>
      <?php
        if (!empty($review)) {
            // Loop through each vendor record
            foreach ($review as $row) {
              
               // Rating for items
              echo '<div class="product-list-star">';
              echo '<p>' . $row['rating'] . '</p>';
              
              if($row['rating'] < 5) {
                for($i=0; $i< $row['rating']; $i++) {
                  echo '<svg version="1.0" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="16px" height="16px" viewBox="0 0 64 64" enable-background="new 0 0 64 64" xml:space="preserve">';
                  echo '<path fill="#231F20" d="M63.893,24.277c-0.238-0.711-0.854-1.229-1.595-1.343l-19.674-3.006L33.809,1.15C33.479,0.448,32.773,0,31.998,0s-1.48,0.448-1.811,1.15l-8.815,18.778L1.698,22.935c-0.741,0.113-1.356,0.632-1.595,1.343c-0.238,0.71-0.059,1.494,0.465,2.031l14.294,14.657L11.484,61.67c-0.124,0.756,0.195,1.517,0.822,1.957c0.344,0.243,0.747,0.366,1.151,0.366c0.332,0,0.666-0.084,0.968-0.25l17.572-9.719l17.572,9.719c0.302,0.166,0.636,0.25,0.968,0.25c0.404,0,0.808-0.123,1.151-0.366c0.627-0.44,0.946-1.201,0.822-1.957l-3.378-20.704l14.294-14.657C63.951,25.771,64.131,24.987,63.893,24.277z"/>';
                  echo '</svg>';
                }

                for($i=0; $i< 5-$row['rating']; $i++) {
                  echo '<svg version="1.0" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="16px" height="16px" viewBox="0 0 64 64" enable-background="new 0 0 64 64" xml:space="preserve">';
                  echo '<path fill="#231F20" fill-opacity="0.2" d="M63.893,24.277c-0.238-0.711-0.854-1.229-1.595-1.343l-19.674-3.006L33.809,1.15C33.479,0.448,32.773,0,31.998,0s-1.48,0.448-1.811,1.15l-8.815,18.778L1.698,22.935c-0.741,0.113-1.356,0.632-1.595,1.343c-0.238,0.71-0.059,1.494,0.465,2.031l14.294,14.657L11.484,61.67c-0.124,0.756,0.195,1.517,0.822,1.957c0.344,0.243,0.747,0.366,1.151,0.366c0.332,0,0.666-0.084,0.968-0.25l17.572-9.719l17.572,9.719c0.302,0.166,0.636,0.25,0.968,0.25c0.404,0,0.808-0.123,1.151-0.366c0.627-0.44,0.946-1.201,0.822-1.957l-3.378-20.704l14.294-14.657C63.951,25.771,64.131,24.987,63.893,24.277z"/>';
                  echo '</svg>';
                }

              } else {
                for($i=0; $i<5; $i++) {
                  echo '<svg version="1.0" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="16px" height="16px" viewBox="0 0 64 64" enable-background="new 0 0 64 64" xml:space="preserve">';
                  echo '<path fill="#231F20" d="M63.893,24.277c-0.238-0.711-0.854-1.229-1.595-1.343l-19.674-3.006L33.809,1.15C33.479,0.448,32.773,0,31.998,0s-1.48,0.448-1.811,1.15l-8.815,18.778L1.698,22.935c-0.741,0.113-1.356,0.632-1.595,1.343c-0.238,0.71-0.059,1.494,0.465,2.031l14.294,14.657L11.484,61.67c-0.124,0.756,0.195,1.517,0.822,1.957c0.344,0.243,0.747,0.366,1.151,0.366c0.332,0,0.666-0.084,0.968-0.25l17.572-9.719l17.572,9.719c0.302,0.166,0.636,0.25,0.968,0.25c0.404,0,0.808-0.123,1.151-0.366c0.627-0.44,0.946-1.201,0.822-1.957l-3.378-20.704l14.294-14.657C63.951,25.771,64.131,24.987,63.893,24.277z"/>';
                  echo '</svg>';
                }
              }

              echo '</div>';
              
              echo '<div class="review-row">';
              echo '<p>' . $row['rev_text'] . '</p>'; 
              echo '<p>' . $row['Helpfulness'] . '</p>'; 
              echo '</div>';

            }
        } else {
            echo '<p> No Review information available.</p>';
        }
      ?>
    </div>
  </div>
</body>
</html>
