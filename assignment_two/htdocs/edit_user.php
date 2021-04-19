<?php # edit_user.php Script

$page_title = 'Edit a User';
include ('includes/header.html');

//----------------------------------
echo '<h1>Edit Customer Details</h1>';

// Check for a valid customer ID, through GET or POST:
if ( (isset($_GET['id'])) && (is_numeric($_GET['id'])) ) {  
	$id = $_GET['id'];
} elseif ( (isset($_POST['id'])) && (is_numeric($_POST['id'])) ) { 
	$id = $_POST['id'];
} else { // No valid ID, kill the script.
	echo '<p class="error">This page has been accessed in error.</p>';
	include ('includes/footer.html'); 
	exit();
}

//require connection
require ('../mysqli_connect.php'); 

// Check if the form has been submitted:
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	// VALIDATE USER INPUTS
	$errors = array();	
	//Check for a first name:
	if (empty($_POST['customer_fname'])) {
		$errors[] = 'You forgot to enter your first name.';
	} else {
		$fn = mysqli_real_escape_string($dbc, trim($_POST['customer_fname']));
	}	
	//Check for a last name:
	if (empty($_POST['customer_lname'])) {
		$errors[] = 'You forgot to enter your last name.';
	} else {
		$ln = mysqli_real_escape_string($dbc, trim($_POST['customer_lname']));
	}
	//Check for an email address:
	if (empty($_POST['email'])) {
		$errors[] = 'You forgot to enter your email address.';
	} else {
		$e = mysqli_real_escape_string($dbc, trim($_POST['email']));
	}
	
	//Validate user inputs:
	if (empty($errors)) { // If everything's OK.	
		//  Test for unique email address:
		$q = "SELECT customer_id FROM customers WHERE email='$e' AND customer_id != $id";
		$r = @mysqli_query($dbc, $q);

		//Check the outcome of SQL query execution
		if (mysqli_num_rows($r) == 0) {
	
			// Make the query:
			$q = "UPDATE customers SET customer_fname='$fn', customer_lname='$ln', email='$e' WHERE customer_id=$id LIMIT 1";
			$r = @mysqli_query ($dbc, $q);

			if (mysqli_affected_rows($dbc) == 1) { 
				// Print a message:
				echo '<p>The users has been edited.</p>';					
			} else { 
				echo '<p class="error">No change OR System error. </p>'; 
				echo '<p>' . mysqli_error($dbc) . '<br />Query: ' . $q . '</p>'; // 
			}				
		} else { 
		// Already registered
			echo '<p class="error">The email address has already been registered.</p>';
		}

	} else { 
		//Print the error message
		echo '<p class="error">The following error(s) occurred:<br />';
		foreach ($errors as $msg) { // Print each error.
			echo " - $msg<br />\n";
		}
		echo '</p><p>Please try again.</p>';	
	} 
} 


// Retrieve the user's information:
$q = "SELECT customer_fname, customer_lname, email FROM customers WHERE customer_id=$id";	
$r = @mysqli_query ($dbc, $q);

if (mysqli_num_rows($r) == 1) { 
	// Get the customers information:
	$row = mysqli_fetch_array ($r, MYSQLI_NUM);
	
	// Create the form:
	echo '<form action="edit_user.php" method="post">
			<p>First Name: <input type="text" name="customer_fname" size="15" maxlength="15" 
							value="' . $row[0] . '" /></p>
			<p>Last Name: <input type="text" name="customer_lname" size="15" maxlength="30" 
							value="' . $row[1] . '" /></p>
			<p>Email Address: <input type="text" name="email" size="20" maxlength="60" 
							value="' . $row[2] . '"  /> </p>
			<p><input type="submit" name="submit" value="Submit" /></p>
			<input type="hidden" name="id" value="' . $id . '" />
			</form>';

} else { // Not a valid user ID.
	echo '<p class="error">This page has been accessed in error.</p>';
}
mysqli_close($dbc);
include ('includes/footer.html');
?>