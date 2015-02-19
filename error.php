<?php 
	
	/*
	 * error.php
	 */
	 

	// Pull in the main includes file
	include 'includes/utils.php';
	include 'includes/html_macros.php';
	
	// Grab the GET variables
	$errorString = (isset($_GET['error'])?$_GET['error']:"An unknown error occured.");

	pixie_header("Error Page", $userName);
?>

<p>We are sorry, but we have encountered an error.  
<p>The information associated with this error is:</p>
<p><b><?= $errorString ?></b></p>

<p>Please contact the administrator for assistance, sending the error information above.</p>

<?php pixie_footer(); ?>
