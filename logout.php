<?php
	unset($_COOKIE["pixie"]);

	// empty value and expiration one hour before
	$res = setcookie("pixie", '', time() - 3600, '/');
	header("location:login.php");
?>
