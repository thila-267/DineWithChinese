<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type"content="text/html; charset=utf-8" />
	<title>Add a food item</title>
</head>
<body>
	<?php # Script 19.2 - add_print.php
	// This page allows the administrator to add a print (product).
	include ('includes/header.html');
	
	require ('../mysqli_connect.php');
	if ($_SERVER['REQUEST_METHOD'] == 'POST'){ // Handle the form.
		// Validate the incoming data...
		$errors = array( );

		// Check for a item name:
		if (!empty($_POST['item_name'])) {
			$in = trim($_POST['item_name']);
		} else {
			$errors[ ] = 'Please enter the item\'s name!';
		}

		// Check for a description (not required):
		$id = (!empty($_POST['item_description'])) ? trim($_POST['item_description']) : NULL;

		// Check for a price:
		if (is_numeric($_POST['price']) && ($_POST['price'] > 0)) {
			$p = (float) $_POST['price'];
		} else {
			$errors[ ] = 'Please enter the items\'s price!'; 
		}

		// Check for an image:
		if (is_uploaded_file ($_FILES['image']['tmp_name'])) {
			// Create a temporary file name:
			$temp = '../uploads/' . md5($_FILES['image']['name']);
			
			// Move the file over:
			if (move_uploaded_file($_FILES['image']['tmp_name'], $temp)) {

				echo '<p>The file has been uploaded!</p>';
				// Set the $i variable to the image's name:
				$i = $_FILES['image']['name'];

			} else { // Couldn't move the file over.
				$errors[ ] = 'The file could not be moved.';
				$temp = $_FILES['image']['tmp_name'];
			}

		} else { // No uploaded file.
			$errors[ ] = 'No file was uploaded.';
			$temp = NULL;
		}


		if (empty($errors)) { // If everything's OK.
			// Add the print to the database:
			$q = 'INSERT INTO items (item_name, item_description, price, image_name)
			VALUES (?, ?, ?, ?)';
			$stmt = mysqli_prepare($dbc, $q);
			mysqli_stmt_bind_param($stmt, 'ssds', $in, $id, $p, $i);
			mysqli_stmt_execute($stmt);

			// Check the results...
			if (mysqli_stmt_affected_rows($stmt) == 1) {
				// Print a message:
				echo '<p>The item has been added.</p>';

				// Rename the image:
				$id = mysqli_stmt_insert_id($stmt); // Get the print ID.
				rename ($temp, "../uploads/$id");

				// Clear $_POST:
				$_POST = array( );

			} else { // Error!
				echo '<p style="font-weight: bold; color: #C00">Your submission could not be processed due to a system error.</p>';
			}
			mysqli_stmt_close($stmt);
		} // End of $errors IF.

		// Delete the uploaded file if it still exists:
		if ( isset($temp) && file_exists ($temp) && is_file($temp) ) {
			unlink ($temp);
		}
	} // End of the submission IF.

	// Check for any errors and print them:
	if ( !empty($errors) && is_array($errors) ) {
		echo '<h1>Error!</h1>
		<p style="font-weight: bold; color: #C00">The following error(s) occurred:<br />';
		foreach ($errors as $msg) {
			echo " - $msg<br />\n";
		}
		echo 'Please reselect the image and try again.</p>';
	}
	// Display the form...
	?>

	<h1>ADD AN ITEM</h1>
	<!--form for entering the details-->
	<form enctype="multipart/form-data" action="add_item.php" method="post">
		<input type="hidden" name="MAX_FILE_SIZE" value="524288" />
		
		<fieldset><legend>Fill out the form to add an item to the menu:</legend>
		
		<p><b>Item Name:</b> <input type="text" name="item_name" size="30" maxlength="60" value="<?php if (isset($_POST['item_name'])) echo htmlspecialchars($_POST['item_name']); ?>"/></p>
	
		<p><b>Item Description:</b> <textarea name="item_description" cols="40" rows="3"><?php if (isset($_POST['item_description'])) echo $_POST['item_description']; ?></textarea> (optional)</p>
		
		<p><b>Price:</b> <input type="text" name="price" size="10" maxlength="10" value="<?php if(isset($_POST['price'])) echo $_POST['price']; ?>" /> <small>Do not include the dollar sign or commas.</small></p>
	
		<p><b>Image:</b> <input type="file" name="image" /></p>

		</fieldset>
		
		<div align="center"><input type="submit" name="submit" value="Submit" /></div>
	</form>
</body>
</html>