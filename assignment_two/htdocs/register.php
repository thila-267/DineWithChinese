<?php # Script 18.6 - register.php
// This is the registration page for the site.
//require ('includes/config.inc.php');
$page_title = 'Register';
include ('includes/header.html');
if ($_SERVER['REQUEST_METHOD'] == 'POST'){ // Handle the form.
	// Need the database connection:
	require ('..\mysqli_connect.php');
	// Trim all the incoming data:
	$trimmed = array_map('trim', $_POST);

	$errors = array(); // Initialize an error array.
	
	// Assume invalid values:
	$fn = $ln = $e = $p = $ad = $pn = FALSE;
	
	//Check for first name 
	if (empty($_POST['first_name'])) {
		$errors[] = 'You forgot to enter your first name.';
	} else {
		$fn = mysqli_real_escape_string($dbc, $trimmed['first_name']);
	}	

	// Check for a last name:
	if (empty($_POST['last_name'])) {
		$errors[] = 'You forgot to enter your last name.';
	} else {
		$ln = mysqli_real_escape_string($dbc, $trimmed['last_name']);
	}
	
	if (empty($_POST['address'])) {
		$errors[] = 'You forgot to enter your address.';
	} else {
		$ad = mysqli_real_escape_string($dbc, $trimmed['last_name']);
	}

	if (empty($_POST['ph_number'])) {
		$errors[] = 'You forgot to enter your phone number.';
	} else {
		$pn = mysqli_real_escape_string($dbc, $trimmed['last_name']);
	}

	// Check for an email address:
	if (filter_var($trimmed['email'],FILTER_VALIDATE_EMAIL)) {
		$e = mysqli_real_escape_string($dbc, $trimmed['email']);
	} else {
		$errors[] = 'Please enter a valid email address!';
	}
	
	// Check for a password and match against the confirmed password:
	if (preg_match ('/^\w{4,20}$/', $trimmed['password1']) ) {
		if ($trimmed['password1'] == $trimmed['password2']) {
			$p = mysqli_real_escape_string ($dbc, $trimmed['password1']);
		} else {
			$errors[] = 'Your password did not match the confirmed password!';	
		}
	} else {
		$errors[] = 'Please enter a valid password!';
	}

	/*//------
	//Check for the profile image
	//Get image name
	$image = $_FILES['profile_image']['name'];
	//image file directory
	$target_dir = "upload/profile_images";
	//Check fi this directory exist or not?
	if (!is_dir($target_dir)) {
		mkdir($target_dir, 0777, true);
	}

	//Convert the image into the base64 format
	$target_file = $target_dir . basename($image);
	// Select file type
	$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
	// Valid file extensions
	$extensions_arr = array("jpg","jpeg","png","gif");
  
	// Check extension
	if( !in_array($imageFileType, $extensions_arr) ){
		$errors[] = 'Image is invalid.';
	}
	//-------*/
	
	if ($fn && $ln && $ad && $pn && $e && $p) { // If everything's OK...
		// Make sure the email address is available:
		$q = "SELECT customer_id FROM customers WHERE email='$e'";
		$r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " .mysqli_error($dbc));

		if (mysqli_num_rows($r) == 0) { // Available.
			// Create the activation code:
			//$a = NULL; 
			//md5(uniqid(rand( ), true));
			// Add the user to the database:
			$q = "INSERT INTO customers (email, pass, customer_fname, customer_lname, address, ph_number, registration_date) VALUES ('$e', SHA1('$p'), '$fn', '$ln', '$ad', '$pn', NOW( ) )";
			$r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " .mysqli_error($dbc));
			if (mysqli_affected_rows($dbc) == 1) { // If it ran OK.
				// Send the email:
				echo "Thank you. You are registered with us succesfully.  \n\n";
				//To activate your account,please click on this link:\n\n";
				$body .= BASE_URL . 'activate.php?x=' . urlencode($e) . "&y=$a";
				mail($trimmed['email'], 'Registration Confirmation', $body, 'From: admin@sitename.com');
				
				// Finish the page:
				echo '<h3>Thank you for registering! A confirmation email has been sent to your address. Please click on the link in that email in order to activate your account.</h3>';
				include ('includes/footer.html'); // Include the HTML footer.
				exit( ); // Stop the page.
			
			} else { // If it did not run OK.
				echo '<p class="error">You could not be registered due to a system error. We apologize for any inconvenience.</p>';
			}
		} else { // The email address is not available.
			echo '<p class="error">That email address has already been registered. If you have forgotten your password, use the link at right to have your password sent to you.</p>';
		}
	
	} else { // If one of the data tests failed.
		echo '<h1>Error!</h1>
			  <p class="error">The following error(s) occurred:<br />';
		//Print all the array of errors:
		foreach ($errors as $msg) { // Print each error.
			echo " - $msg<br />\n";
		}
		echo '<p class="error">Please try again.</p>';
	}
	mysqli_close($dbc);
} // End of the main Submit conditional.
?>

<h1>Register</h1>
<form action="register.php" method="post">
	<fieldset>
		<p><b>First Name:</b> <input type="text" name="first_name" size="20" maxlength="20" 
			value="<?php if (isset($trimmed['first_name'])) echo $trimmed['first_name']; ?>" /></p>
		
		<p><b>Last Name:</b> <input type="text" name="last_name" size="20" maxlength="40" 
			value="<?php if (isset($trimmed['last_name'])) echo $trimmed['last_name']; ?>" /></p>
		
		<p><b>Address:</b> <input type="text" name="address" size="20" maxlength="100" 
			value="<?php if (isset($trimmed['address'])) echo $trimmed['address']; ?>" /></p>

		<p><b>Phone number:</b> <input type="number" name="ph_number" size="10" maxlength="10" 
			value="<?php if (isset($trimmed['ph_number'])) echo $trimmed['ph_number']; ?>" /></p>

		<p><b>Email Address:</b> <input type="text" name="email" size="30" maxlength="60" 
			value="<?php if (isset($trimmed['email'])) echo $trimmed['email']; ?>" /> </p>
		
		<p><b>Password:</b> <input type="password" name="password1" size="20" maxlength="20" 
			value="<?php if (isset($trimmed['password1'])) echo $trimmed['password1']; ?>" /> <small>Use only letters, numbers, and the underscore. Must be between 4 and 20 characters long.</small></p>
		
		<p><b>Confirm Password:</b> <input type="password" name="password2" size="20" maxlength="20"value="<?php if (isset($trimmed['password2'])) echo $trimmed['password2']; ?>" /></p>

		<!--<p>Profile Picture: <input type='file' name='profile_image' /> </p>-->

	</fieldset>
	
	<div align="center"><input type="submit" name="submit" value="Register" /></div>
</form>

<?php include ('includes/footer.html'); ?>