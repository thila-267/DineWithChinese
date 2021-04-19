<?php # Post.php
// This page handles the message post.

include ('includes/header.html');
require ('../mysqli_connect.php');


if ($_SERVER['REQUEST_METHOD'] == 'POST') { 

	
	// Validate thread ID ($tid), which may not be present:
	if (isset($_POST['tid']) && filter_var($_POST['tid'], FILTER_VALIDATE_INT, array('min_range' => 1)) ) {
		$tid = $_POST['tid'];
	} else {
		$tid = FALSE;
	}

	// If there's no thread ID, a subject must be provided:
	if (!$tid && empty($_POST['subject'])) {
		$subject = FALSE;
		echo '<p>Please enter a Title for this post.</p>';
	} elseif (!$tid && !empty($_POST['subject'])) {
		$subject = htmlspecialchars(strip_tags($_POST['subject']));
	} else { 
		$subject = TRUE;
	}
	
	// Validate the body:
	if (!empty($_POST['body'])) {
		$body = htmlentities($_POST['body']);
	} else {
		$body = FALSE;
		echo '<p>Please enter a body for this post.</p>';
	}
	
	if ($subject && $body) { //If all is correct then continue
	
		// Add the message to the database		
		if (!$tid) { 
			$q = "INSERT INTO threads (customer_id, subject) VALUES ( {$_SESSION['customer_id']}, '" . mysqli_real_escape_string($dbc, $subject) . "')";
			$r = mysqli_query($dbc, $q);
			if (mysqli_affected_rows($dbc) == 1) {
				$tid = mysqli_insert_id($dbc);
			} else {
				echo '<p>Your post could not be handled due to a system error.</p>';
			}
		} 
		
		if ($tid) { // Add this to the replies table:
			$q = "INSERT INTO posts (thread_id, customer_id, message, posted_on) VALUES ($tid, {$_SESSION['customer_id']}, '" . mysqli_real_escape_string($dbc, $body) . "', UTC_TIMESTAMP())";
			$r = mysqli_query($dbc, $q);
			if (mysqli_affected_rows($dbc) == 1) {
				echo '<p>Your post has been entered.</p>';
				echo '<a href="forum.php" title="review">Return to Reviews </a>';
				
			} else {
				echo '<p>Your post could not be handled due to a system error.</p>';
				echo '<p><a href="forum.php "Return to reviews</a></p>';
			}
		} 
	
	} else { 
		include ('includes/post_form.php');
	}

} else { 

	// Display the form:
	include ('includes/post_form.php');

}

include ('includes/footer.html');
?>