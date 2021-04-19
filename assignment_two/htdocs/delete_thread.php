<?php # delete_thread.php

$page_title = 'Delete a Customer';
include ('includes/header.html');


echo '<h1>Delete a Thread</h1>';

if ( (isset($_GET['id'])) && (is_numeric($_GET['id'])) ) { // 
	$id = $_GET['id'];
} elseif ( (isset($_POST['id'])) && (is_numeric($_POST['id'])) ) { 
	$id = $_POST['id'];
} else { 
	echo '<p class="error">This page has been accessed in error.</p>';
	
	include ('includes/footer.html'); 
	exit();
}

require ('../mysqli_connect.php');

// Check if the form has been submitted:
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	if ($_POST['sure'] == 'Yes') { // Delete the record.
		
		
		$q = "DELETE FROM threads WHERE thread_id=$id LIMIT 1";		
		$r = @mysqli_query ($dbc, $q);

		
		if (mysqli_affected_rows($dbc) == 1) { // If it ran OK.
			echo '<p>The thread has been deleted.</p>';	
		} else { 
			echo '<p class="error">The thread could not be deleted due to a system error.</p>'; 
			echo '<p>' . mysqli_error($dbc) . '<br />Query: ' . $q . '</p>'; 
		}	
	} else { // No confirmation of deletion.
		echo '<p>The customer has NOT been deleted.</p>';	
	}
} else { 
	//Retrieve the customers information:
	$q = "SELECT CONCAT(subject,(' by '), customer_fname, (' '), customer_lname ) FROM threads as t INNER JOIN customers AS c ON t.customer_id = c.customer_id where thread_id=$id";
	$r = @mysqli_query ($dbc, $q);

	//Check the outcome of SQL query execution
	if (mysqli_num_rows($r) == 1) {
				$row = mysqli_fetch_array ($r, MYSQLI_NUM);

		//Display the record being deleted:
		echo "<h3>Name: $row[0]</h3>
		Are you sure you want to delete this Thread?";
		
		//Create the form:
		echo '<form action="delete_thread.php" method="post">
				<input type="radio" name="sure" value="Yes" /> Yes 
				<input type="radio" name="sure" value="No" checked="checked" /> No
				<input type="submit" name="submit" value="Submit" />
				<input type="hidden" name="id" value="' . $id . '" />
			  </form>';
	
	} else { 
		echo '<p class="error">This page has been accessed in error.</p>';
	}

} 
mysqli_close($dbc);
include ('includes/footer.html');
?>