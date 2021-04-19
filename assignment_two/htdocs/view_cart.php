<?php
// This page also lets the user update the contents of the cart.

// Set the page title and include the HTML header:
ob_start();
$page_title = 'View Your Shopping Cart';
include ('includes/header.html');

// Check if the form has been submitted (to update the cart):
if ($_SERVER['REQUEST_METHOD'] == 'POST'){

	// Change any quantities:
	foreach ($_POST['qty'] as $k => $v) {

		// Must be integers!
		$pid = (int) $k;
		$qty = (int) $v;

		if ( $qty == 0 ) { // Delete.
			unset ($_SESSION['cart'][$pid]);
		} elseif ( $qty > 0 ) { // Change quantity.
			$_SESSION['cart'][$pid]['quantity'] = $qty;
		}

	} // End of FOREACH.

} // End of SUBMITTED IF.

// Display the cart if it's not empty...
if (!empty($_SESSION['cart'])) {

	// Retrieve all of the information for the prints in the cart:
	require ('..\mysqli_connect.php'); // Connect to the database.
	$q = "SELECT * FROM  items WHERE items.item_id IN (";foreach ($_SESSION['cart'] as $pid => $value) {
			$q .= $pid . ',';
		}
	$q = substr($q, 0, -1) . ')';
	$r = mysqli_query ($dbc, $q) or die( mysqli_error($dbc));	

	// Create a form and a table:
	echo "<h1 style='text-align:center; color: black; font-size: 30px;'><b> SHOPPING CART </b></h1>";
	echo '<form action="view_cart.php" method="post">
	<table style="margin-left: auto; margin-right:auto; margin-top: 20px; background-color: #ffe6e6;">
		<tr>
			<td style="font-size: 20px; text-align:center; width:30%; color:black; background-color: #ff9999;"><b>Item Name</b></td>
			<td style="font-size: 20px; text-align:center; width:20%; color:black; background-color: #ff9999;"><b>Price</b></td>
			<td style="font-size: 20px; text-align:center; width:10%; color:black; background-color: #ff9999;"><b>Qty</b></td>
			<td style="font-size: 20px; text-align:center; width:20%; color:black; background-color: #ff9999;"><b>Total Price</b></td>
		</tr>
	';

	$total = 0; // Total cost of the order.
	while ($row = mysqli_fetch_array ($r, MYSQLI_ASSOC)) {

		// Calculate the total and sub-totals.
		$subtotal = $_SESSION['cart'][$row['item_id']]['quantity'] * $_SESSION['cart'][$row['item_id']]['price'];
		$total += $subtotal;

		// Print the row:
		echo "\t<tr>
		<td style='font-size: 18px;' align=\"center\">{$row['item_name']}</td>
		<td style='font-size: 18px;' align=\"center\">\${$_SESSION['cart'][$row['item_id']]['price']}</td>
		<td style='font-size: 18px;' align=\"center\"><input type=\"text\" size=\"3\" name=\"qty[{$row['item_id']}]\"value=\"{$_SESSION['cart'][$row['item_id']]['quantity']}\" /></td>
		<td style='font-size: 18px;' align=\"center\">$" . number_format ($subtotal, 2) . "</td>
		</tr>\n";
	} // End of the WHILE loop.
	
	//Write total value to cookie 
	setcookie("Shopping_cart_total", $total, time() + (86400 * 30), "/");//1 day

	mysqli_close($dbc); // Close the database connection.

	// Print the total, close the table, and the form:
	echo '<tr><td><br /></td></tr><tr>
		<td colspan="3" align="right" style="font-size:20px; color:black;"><b>Total:</b></td>
		<td align="center" style="font-size:20px; color:black;"><b>$' . number_format ($total, 2) . '</b></td>
	</tr>
	</table>
	<br /><br />
	<div align="center"><input type="submit" name="submit" value="Update My Cart" />
	</form><p align="center" style="font-size:18px; color: black;">Enter a quantity of 0 to remove an item.
	<br /><br /><a style="color:white; background-color: #4d0000; padding: 14px 25px; text-align: center; text-decoration: none; display: inline-block; font-size: 25px;" href="checkout.php">Checkout</a></p></div>';

} else {
	echo '<div style="text-align:center; color: black; font-size: 25px; text-decoration:none;"><p align="center">Your cart is empty.</p>';
	echo '<a style="color:white; background-color: #4d0000; padding: 14px 25px; display: inline-block; margin-right: 20px; margin-bottom: 40px;" href="browse_items.php"> Order Now </a>';
}

include ('includes/footer.html');
ob_end_flush();
?>