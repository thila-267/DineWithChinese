<?php 
// This page displays the available prints (products).
// Set the page title and include the HTML header:
$page_title = 'Browse the Prints';
include ('includes/header.html');
require ('..\mysqli_connect.php');

//query the database 
$q = "SELECT * FROM items AS item";

echo "<h1 style='text-align:center; color: black; font-size: 30px;'><b> OUR MENU </b></h1>";
$r = mysqli_query ($dbc, $q);
while ($row = mysqli_fetch_array ($r, MYSQLI_ASSOC)) {
	// Display each record:
	echo "<div align=\"left\" span class='info_display'>
			<b><a href=\"view_item.php?pid={$row['item_id']}\">{$row['item_name']}</b> ---- {$row['price']}<br />";
	echo '<p id="info_display_des">'.$row['item_description'].'</p><br /></span></div>';
}

mysqli_close($dbc);
include ('includes/footer.html');
?>