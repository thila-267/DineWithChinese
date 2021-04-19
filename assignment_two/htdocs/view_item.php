<?php 
$row = FALSE; // Assume nothing!
if (isset($_GET['pid']) && filter_var($_GET['pid'], FILTER_VALIDATE_INT,array('min_range' => 1)) ) { // make sure there's a print ID!

	$pid = $_GET['pid'];
	// Get the print info:
	require ('..\mysqli_connect.php');//Connect to the database.
	/*$q = "SELECT CONCAT_WS(' ', first_name, middle_name, last_name)AS artist, print_name, price,description, size, image_name FROM artists, prints WHERE artists.artist_id=prints.artist_id AND prints.print_id=$pid";*/

	$q = "SELECT * from items AS item where item_id=$pid";

	$r = mysqli_query ($dbc, $q);
	if (mysqli_num_rows($r) == 1) { //Good to go!
		// Fetch the information:
		$row = mysqli_fetch_array ($r,MYSQLI_ASSOC);
		
		// Start the HTML page:
		$page_title = $row['item_name'];
		include ('includes/header.html');
		

		if ($image = @getimagesize ("../uploads/$pid")) {
			echo "<div span class='details' style='margin-top:50px;'><table><tr><td><img height='300' width='300' src=\"show_image.php?image=$pid&name=" . urlencode($row['image_name']) . "\" $image[3] alt=\"{$row['item_name']}\" /></td>\n";
		} else {
			echo "<div align=\"center\">No image available.</div>\n";
		}
		// Display a header:
		echo "<td><div id='ele' style='margin-left:50px;'><b>{$row['item_name']}</b><br />";
		
		echo "\${$row['price']} <br /> <br /><a href=\"add_cart.php?pid=$pid\">Add to Cart</a><br />";

		// Add the description or a default message:
		echo '<p>' . ((is_null($row['item_description'])) ? '(No description available)' :$row['item_description']) . '</p></td></tr></table></div></div>';
		
		// Get the image information and display the image
		/*$image_base64 = base64_encode(file_get_contents("upload/profile_images/ca_chowmein.jpg"));
		$image_encoded = 'data:image/' . 'jpg' . ';base64,' . $image_base64;
		//echo $image_encoded;
		echo "<img style='width:50px;height:40px;' src=" . $row['images'] . ">";
		//$size = getimagesize("C:/wamp64/www/assignment_two/uploads/$pid");
		//echo $size;
		//echo urlencode($row['image_name']);*/
		
	} // End of the mysqli_num_rows( ) IF.

	mysqli_close($dbc);

} // End of $_GET['pid'] IF.

if (!$row) { // Show an error message.
	$page_title = 'Error';
	include ('includes/header.html');
	echo '<div align="center">This page has been accessed in error!</div>';
}

// Complete the page:
include ('includes/footer.html');
?>