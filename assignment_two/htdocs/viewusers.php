<?php # view_users.php Script
$page_title = 'View Customers';

include ('includes/header.html');

echo '<h1>Registered Customers</h1>';
require ('../mysqli_connect.php');

// Number of records to show per page: 20
$display = 20;

// Determine how many pages there are...
if (isset($_GET['p']) && is_numeric($_GET['p'])) { 
	$pages = $_GET['p'];
} else { 
	
 	// Count the number of records:
	$q = "SELECT COUNT(customer_id) FROM customers";
	$r = @mysqli_query ($dbc, $q);
	$row = @mysqli_fetch_array ($r, MYSQLI_NUM);
	$records = $row[0];

	// Calculate the number of pages...
	if ($records > $display) {
		$pages = ceil ($records / $display);
	} else {
		$pages = 1;
	}
} 

// Determine where in the database to start returning results...
if (isset($_GET['s']) && is_numeric($_GET['s'])) {
	$start = $_GET['s'];
} else {
	$start = 0;
}

//Default is by registration date.
$sort = (isset($_GET['sort'])) ? $_GET['sort'] : 'rd';

// Determine the sorting order:
switch ($sort) {
	case 'ln':
		$order_by = 'customer_lname ASC';
		break;
	case 'fn':
		$order_by = 'customer_fname ASC';
		break;
	case 'rd':
		$order_by = 'registration_date ASC';
		break;
		case 'ul':
		$order_by = 'user_level ASC';
		break;
	default:
		$order_by = 'registration_date ASC';
		$sort = 'rd';
		break;
}

// Define the query:
$q = "SELECT customer_fname, customer_lname, DATE_FORMAT(registration_date, '%M %d, %Y') AS dr, user_level, customer_id FROM customers ORDER BY $order_by LIMIT $start, $display";		
$r = @mysqli_query ($dbc, $q); 

echo '<table align="center" cellspacing="0" cellpadding="5" width="75%">

<tr>
	<td align="left"><b>Edit</b></td>
	<td align="left"><b>Delete</b></td>
	<td align="left"><b><a href="view_users.php?sort=ln">Last Name</a></b></td>
	<td align="left"><b><a href="view_users.php?sort=fn">First Name</a></b></td>
	<td align="left"><b><a href="view_users.php?sort=rd">Date Registered</a></b></td>
	<td align="left"><b><a href="view_users.php?sort=rd">User Level</a></b></td>
</tr>
';

//Check the outcome of SQL query execution and print all the records....
$bg = '#eeeeee'; 
while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
	$bg = ($bg=='#eeeeee' ? '#ffffff' : '#eeeeee');
	//----

	echo '<tr bgcolor="' . $bg . '">
		<td align="left"><a href="edit_user.php?id=' . $row['customer_id'] . '">Edit</a></td>
		<td align="left"><a href="delete_user.php?id=' . $row['customer_id'] . '">Delete</a></td>
		<td align="left">' . $row['customer_lname'] . '</td>
		<td align="left">' . $row['customer_fname'] . '</td>
		<td align="left">' . $row['dr'] . '</td>
		<td align="left">' . $row['user_level'] . '</td>
	</tr>
	';
} 
echo '</table>';

//Free up the data (records) stored in $r variable
mysqli_free_result ($r);
mysqli_close($dbc);


if ($pages > 1) {
	
	echo '<br /><p>';
	$current_page = ($start/$display) + 1;
	
	// If it's not the first page, make a "Previous" button:
	if ($current_page != 1) {
		echo '<a href="view_users.php?s=' . ($start - $display) . '&p=' . $pages . '&sort=' . $sort . '">Previous</a> ';
	}
	
	
	for ($i = 1; $i <= $pages; $i++) {
		if ($i != $current_page) {
			echo '<a href="view_users.php?s=' . (($display * ($i - 1))) . '&p=' . $pages . '&sort=' . $sort . '">' . $i . '</a> ';
		} else {
			echo $i . ' ';
		}
	} 
	
	// If it's not the last page, make a "Next" button:
	if ($current_page != $pages) {
		echo '<a href="view_users.php?s=' . ($start + $display) . '&p=' . $pages . '&sort=' . $sort . '">Next</a>';
	}
	
	echo '</p>'; 
} 
include ('includes/footer.html');
?>