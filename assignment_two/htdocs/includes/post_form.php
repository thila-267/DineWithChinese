<?php # Post_form.php
// This page shows the form for posting messages.

// Only display this form if the customer is logged in:
if (isset($_SESSION['customer_id'])) {
	// Display the form:
	echo '<form action="post.php" method="post" accept-charset="utf-8">';
	
	// If on read.php...
	if (isset($tid) && $tid) {

		// Print a caption:
		echo '<h3 style="color:red"> Reply </h3>';
	
		// Add the thread ID as a hidden input:
		echo '<input name="tid" type="hidden" value="' . $tid . '" />';
		
	} else { // New thread

		echo '<h1>Add a New Review</h1>';
	
		// Title of Review:
		echo '<p><em>Title </em>: <input name="subject" type="text" size="60" maxlength="100" ';

		// Check for existing value:
		if (isset($subject)) {
			echo "value=\"$subject\" ";
		}
	
		echo '/></p>';
	
	} 
	
	// Create the body textarea:
	echo '<p> Body: <br> <textarea align="center" name="body" id="replytext" >';
	echo '</textarea></p>';
	
	// Finish the form:
	echo '<br> <input name="submit" type="submit" value="Submit" />
	</form>';
	echo '</br> <a href=forum.php> Return to Review </a>';
//} else {
//	echo '<p>You must be logged in to post messages.</p>';
//}

include ("includes/footer.html");
?>