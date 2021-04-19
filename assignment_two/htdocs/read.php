<?php #Read.php 
// This page shows the messages in a thread.


include ('includes/header.html');
require ('../mysqli_connect.php'); 

// Check for a thread ID...
$tid = FALSE;
if (isset($_GET['tid']) && filter_var($_GET['tid'], FILTER_VALIDATE_INT, array('min_range' => 1)) ) {
	
	//thread ID:
	$tid = $_GET['tid'];
	
	if (isset($_SESSION['customer_id'])) {
		$posted = 'p.posted_on';
	}

	// Run the query:
	$q = "SELECT t.subject, p.message, customer_fname, customer_lname, DATE_FORMAT($posted, '%M %D %Y') AS posted FROM threads AS t LEFT JOIN posts AS p USING (thread_id) INNER JOIN customers AS u ON p.customer_id = u.customer_id WHERE t.thread_id = $tid ORDER BY p.posted_on ASC";
	$r = mysqli_query($dbc, $q);
	
	if (!(mysqli_num_rows($r) > 0)) {
		$tid = FALSE; // Invalid thread ID!
	}
	
} 

// Get the messages
if ($tid) { 
	
	$printed = FALSE; 

	// Fetch each:
	while ($messages = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
		
		if (!$printed) {
			echo "<h1>{$messages['subject']}</h1>\n";
			$printed = TRUE;
		}
	
		// Print the message:
		echo '<table id="readtable" align="center">';
		echo '<tr>';
		echo '<td>';
		echo "<b>{$messages['customer_fname']} {$messages['customer_lname']} </b> ({$messages['posted']})<br/>{$messages['message']}<br/> <br/>
		</td></tr> ";
		echo "</table>";

	} 
		
	// Show the form to post a message
	include ('post_form.php');
	
} else { 
	echo '<p>This page has been accessed in error.</p>';
}

include ('includes/footer.html');
?>