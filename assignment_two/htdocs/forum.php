<?php # forum.php
// This page shows the threads in a forum.
include ('includes/header.html');
require ('../mysqli_connect.php');

//checking if a customer is logged in
if (isset($_SESSION['customer_id'])) {
	$first = 'p.posted_on';
	$last = 'p.posted_on';
} else{
	echo("Please login to read or post reviews");
	exit();
}


// Query when the thread was first posted, when it was last replied to, and how many replies it's had:
$q = "SELECT t.thread_id, t.subject, customer_fname, customer_lname, COUNT(post_id) - 1 AS responses, MAX(DATE_FORMAT($last, '%M %D </br> %Y ')) AS last, MIN(DATE_FORMAT($first, '%M %D </br> %Y ')) AS first FROM threads AS t INNER JOIN posts AS p USING (thread_id) INNER JOIN customers AS u ON t.customer_id = u.customer_id GROUP BY (p.thread_id) ORDER BY last DESC";
$r = mysqli_query($dbc, $q);

if (mysqli_num_rows($r) > 0) {
	if (isset($_SESSION['customer_id'])){
	// Create a table to display the Subject/Reviews
	echo ' <h1> Reviews </h3>';
	echo '
	<table width="100%" class="review">
		<tr id="reviewhead">
			<td align="left" width="30%"><em> Subject </em>:</td>
			<td align="left" width="20%"><em> Posted by </em>:</td>
			<td align="left" width="10%"><em>Posted on</em>:</td>
			<td align="left" width="10%"><em> Replies </em>:</td>
			<td align="left" width="10%"><em>Last Reply</em>:</td>';
	if ($_SESSION['user_level'] == 1){
	echo'<td align="left" width="10%"><em>Delete Thread</em>:</td>
	</tr>';
	}
	// Fetch each thread:
	while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {

		echo '<tr>
				<td align="left"><a href="read.php?tid=' . $row['thread_id'] . '">' . $row['subject'] . '</a></td>
				<td align="left">' . $row['customer_fname'] . '&nbsp;' .  $row['customer_lname'] .'</td>
				<td align="center">' . $row['first'] . '</td>
				<td align="center">' . $row['responses'] . '</td>
				<td align="center">' . $row['last'] . '</td>';
	if($_SESSION['user_level'] == 1){ 
	echo '<td align="left"><a href="delete_thread.php?id=' . $row['thread_id'] . '">Delete</a></td>
	</tr>';
	}		
	}
	echo '</table>'; }
} else {
	echo '<p>There are currently no messages in this forum.</p>';
}
echo '<a style="color:white; background-color: #4d0000; padding: 14px 25px; display: inline-block; margin-right: 20px; margin-bottom: 40px;" href="post_form.php"> Add review </a>';


// Include the HTML footer file:
include ('includes/footer.html');
?>