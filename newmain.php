<?php
include 'includes/log.php';
?>
<?php   										// Opening PHP tag
	
	// Include the database connection script
	require 'includes/database-connection.php';

	/*
	 * Retrieve toy information from the database based on the toy ID.
	 * 
	 * @param PDO $pdo       An instance of the PDO class.
	 * @param string $id     The ID of the toy to retrieve.
	 * @return array|null    An associative array containing the toy information, or null if no toy is found.
	 */
	function get_product(PDO $pdo, string $id) {

		// SQL query to retrieve toy information based on the toy ID
		$sql = "SELECT * 
			FROM product_table
			WHERE productID= :id;";	// :id is a placeholder for value provided later 
		                               // It's a parameterized query that helps prevent SQL injection attacks and ensures safer interaction with the database.


		// Execute the SQL query using the pdo function and fetch the result
		$product = pdo($pdo, $sql, ['id' => $id])->fetch();		// Associative array where 'id' is the key and $id is the value. Used to bind the value of $id to the placeholder :id in  SQL query.

		// Return the toy information (associative array)
		return $product;
	}

	// Retrieve info about toy with ID '0001' from the db using provided PDO connection
	$product = get_product($pdo, '12');
	$product1 = get_product($pdo, '2');
	$product2 = get_product($pdo, '3');
	$product3 = get_product($pdo, '4');
	$product4 = get_product($pdo, '5');
	$product5 = get_product($pdo, '6');
	$product6 = get_product($pdo, '7');
	

// Closing PHP tag  ?> 

<!DOCTYPE>
<html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link rel="stylesheet" href="./css/style.css">
	<link rel="preconnect" href="https://rsms.me/">
	<link rel="stylesheet" href="https://rsms.me/inter/inter.css">
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
      <div class ="login">
        <?= $logged_in ? '<a href="logout.php">Log Out</a>' : '<a href="login.php">Log In</a>'; ?>
    </div>
      <div class="cart">
      <a href="">
        <svg width="800px" height="800px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M6.29977 5H21L19 12H7.37671M20 16H8L6 3H3M9 20C9 20.5523 8.55228 21 8 21C7.44772 21 7 20.5523 7 20C7 19.4477 7.44772 19 8 19C8.55228 19 9 19.4477 9 20ZM20 20C20 20.5523 19.5523 21 19 21C18.4477 21 18 20.5523 18 20C18 19.4477 18.4477 19 19 19C19.5523 19 20 19.4477 20 20Z" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
      </a>
      </div>

      <div class="account">
      <a href="about.php">
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
    <div class="track-order">
      <a href="order_status.php" class="nav-btn">Track Order</a>
    </div>
    <button class="nav-btn"> change order </button>
    <a href="changeInfo.php" class="nav-btn"> Change Info</a>
  </div>

  <div class="welcome">
    <h2> Welcome back, USER! </h2>
  </div>

  <div class="main-banner-container">
    <h1> Deals of the Day </h1>
    <h3> Up to 30% Off For New Users </h3>
    <p> Save now </p>
    <p> Participating shops only. Terms Apply. </h2>
  </div>

  <div class="daily-items">
    <div class="item-container">
      <div class="main main-items1"> <a href="product.php?prodnum=<?= $product['productID'] ?>"><img src="<?= $product['productURL'] ?>" alt="productName"></a> </div>
      <div class="main main-items2"><a href="product.php?prodnum=<?= $product1['productID'] ?>"><img src="<?= $product1['productURL'] ?>" alt="productName"></a></div>
      <div class="main main-items3"><a href="product.php?prodnum=<?= $product2['productID'] ?>"><img src="<?= $product2['productURL'] ?>" alt="productName"></a></div>
      <div class="main main-items4"><a href="product.php?prodnum=<?= $product3['productID'] ?>"><img src="<?= $product3['productURL'] ?>" alt="productName"></a></div>
      <div class="main main-items5"><a href="product.php?prodnum=<?= $product4['productID'] ?>"><img src="<?= $product4['productURL'] ?>" alt="productName"></a></div>
      <div class="main main-items6"><a href="product.php?prodnum=<?= $product5['productID'] ?>"><img src="<?= $product5['productURL'] ?>" alt="productName"></a></div>
      <div class="main main-items7"><a href="product.php?prodnum=<?= $product6['productID'] ?>"><img src="<?= $product6['productURL'] ?>" alt="productName"></a></div>
    </div>
    <div class="option-container">
      <div class="items item1"></div>
      <div class="items item2"></div>
      <div class="items item3"></div>
      <div class="items item4"></div>
    </div>
  </div>
</body>
</html>



