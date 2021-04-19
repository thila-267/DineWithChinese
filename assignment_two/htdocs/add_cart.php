<?php 
// Set the page title and include the HTML header:
$page_title = 'Add to Cart';
include ('includes/header.html');

if (isset ($_GET['pid']) && filter_var($_GET['pid'], FILTER_VALIDATE_INT,array('min_range' => 1)) ) { //Check for a print ID. 
	$pid = $_GET['pid'];
	// Check if the cart already contains one of these prints;
 	// If so, increment the quantity:
	if (isset($_SESSION['cart'][$pid])) {$_SESSION['cart'][$pid]['quantity']++; // Add another.

		// Display a message:
		echo '<div style="text-align:center; color: black; font-size: 25px; text-decoration:none;"><p align="center">One more has been added to your shopping cart.</p>';
		echo '<a style="color:white; background-color: #4d0000; padding: 14px 25px; display: inline-block; margin-right: 20px;" href="view_cart.php"> View Cart </a>';
		echo '<a style="color:white; background-color: #4d0000; padding: 14px 25px; display: inline-block;" href="browse_items.php"> Order more </a></div>';
	} else { // New product to the cart.

		// Get the print's price from the database:
		require ('..\mysqli_connect.php');// Connect to the database.
		$q = "SELECT price FROM items WHERE item_id=$pid";
 		$r = mysqli_query ($dbc, $q);
		if (mysqli_num_rows($r) == 1) { // Valid print ID.

			// Fetch the information.
			list($price) = mysqli_fetch_array ($r, MYSQLI_NUM);

			// Add to the cart:
			$_SESSION['cart'][$pid] = array('quantity' => 1, 'price' => $price);

			// Display a message:
			echo '<div style="text-align:center; color: black; font-size: 25px; text-decoration:none;"><p align="center">Added to your shopping cart.</p>';
			echo '<a style="color:white; background-color: #4d0000; padding: 14px 25px; display: inline-block; margin-right: 20px;" href="view_cart.php"> View Cart </a>';
			echo '<a style="color:white; background-color: #4d0000; padding: 14px 25px; display: inline-block;" href="browse_items.php"> Order more </a></div>';

		} else { // Not a valid print ID.
			echo '<div align="center">This page has been accessed in error!</div>';
		}
		
		mysqli_close($dbc);

	} // End of isset($_SESSION['cart'][$pid] conditional.

} else { // No print ID.
	echo '<div align="center">This page has been accessed in error!</div>';
}

include ('includes/footer.html');
?>