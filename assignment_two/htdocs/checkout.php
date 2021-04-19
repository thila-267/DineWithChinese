<?php 
// This page would come after the billing process.
// This page assumes that the billing process worked (the money has been taken).

// Set the page title and include the HTML header:
$page_title = 'Order Confirmation';
include ('includes/header.html');

//first checking if the customer is logged in before checking out
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true){
	// customer id is retrieved using session
	$cid = $_SESSION['customer_id'];

	// Total is retrieved using cookie
	$total = $_COOKIE["Shopping_cart_total"]; // Temporary.

	require ('../mysqli_connect.php');// Connect to the database.

	// Turn autocommit off:
	mysqli_autocommit($dbc, FALSE);

	// Add the order to the orders table...
	$q = "INSERT INTO orders (customer_id,total) VALUES ($cid, $total)";
	$r = mysqli_query($dbc, $q);
	if (mysqli_affected_rows($dbc) == 1) {

		// Need the order ID:
		$oid = mysqli_insert_id($dbc);

		// Insert the specific order contents into the database...

		// Prepare the query:
		$q = "INSERT INTO order_contents(order_id, item_id, quantity, price)VALUES (?, ?, ?, ?)";
		$stmt = mysqli_prepare($dbc, $q);
		mysqli_stmt_bind_param($stmt, 'iiid',$oid, $pid, $qty, $price);
		
		// Execute each query; count the total affected:
		$affected = 0;
		foreach ($_SESSION['cart'] as $pid =>$item) {
			$qty = $item['quantity'];
			$price = $item['price'];
			mysqli_stmt_execute($stmt);
			$affected += mysqli_stmt_affected_rows($stmt);
		}

		// Close this prepared statement:
		mysqli_stmt_close($stmt);

		// Report on the success....
		if ($affected == count($_SESSION['cart'])) { // Whohoo!

			// Commit the transaction:
			mysqli_commit($dbc);

			// Clear the cart:
			unset($_SESSION['cart']);	

			// Message to the customer:
			echo '<p>Thank you for your order. We will prepare your food as soon as possible.</p>';

		} else { // Rollback and report the problem.

			mysqli_rollback($dbc);

			echo '<p>Your order could not be processed due to a system error. You will be contacted in order to have the problem fixed. We apologize for the inconvenience.</p>';
		
			// Send the order information to the administrator.
		}	

	} else { // Rollback and report the problem.

		mysqli_rollback($dbc);

		echo '<p>Your order could not be processed due to a system error. You will be contacted in order to have the problem fixed. We apologize for the inconvenience.</p>';

		// Send the order information to the administrator.

	}

mysqli_close($dbc);
} else{//not logged in
	echo '<div style="text-align:center; color: black; font-size: 25px; text-decoration:none;"><p align="center">Please register or login before checkout.</p>';
	echo '<a style="color:white; background-color: #4d0000; padding: 14px 25px; display: inline-block; margin-right: 20px;" href="login.php"> Log In </a>';
	echo '<a style="color:white; background-color: #4d0000; padding: 14px 25px; display: inline-block;" href="register.php"> Register </a></div>';
}

include ('includes/footer.html');
?>