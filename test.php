<?php 
	/*
	 * viewAnimal.php
	 * This page is used to view a single animal, including the transfer
	 * history and vaccination/health information.  From this page a new
	 * transfer and health information can be edited.  This page expects
	 * an animalID passed as part of the URL.
	 * 
	 * The page can also process requests related to file uploads.  
	 * 1. The page can process a POST request to add a file.  
	 * 2. If a fileID is passed into the URL, then that file will be deleted.
	 */

	// turn on error reporting
	error_reporting(E_ALL);
	ini_set('display_errors', true); 
	 
	// Pull in the main includes file
	include 'includes/utils.php';
	include 'includes/html_macros.php';
	include 'includes/panels.php';
	

	$mysqli = DBConnect();

	// If there is no animal ID, then redirect to the findAnimal page.
	// This should NEVER happen.
	if (isset($_GET['animalID'])) {
		$animalID =  intval($_GET['animalID']);
	} else header('Location: ' . "findAnimal.php", true, 302);

	// get information about the current animal
	$sql =  "SELECT * FROM AnimalInfo where animalID = $animalID";
	$result = $mysqli->query($sql);
	if ($mysqli->errno) errorPage($mysqli->errno, $mysqli->error, $sql);
	else {
		// we should have just one row, since we are selecting by PK.
		$row = $result->fetch_array();
		$animalName = $row['animalName'];
		$breed = $row['breed'];
		$species = $row['species'];
		$markings = $row['markings'];
		$gender = $row['gender'];
		$estBirthdate = $row['estBirthdate'];
		$isFixed = $row['isFixed'];
		$dogs = $row['dogs'];
		$cats = $row['cats'];
		$kids = $row['kids'];
		$adoptionStatus = $row['adoptionStatus'];
		$personality = $row['personality'];
		$activityLevel = ($row['activityLevel']>0?$row['activityLevel']:'');
		$age = prettyAge( $row['estBirthdate'], date("Y-m-d"));
		$note = $row['note'];
		$microchipNumber = $row['microchipNumber'];
		$dateImplanted = $row['dateImplanted'];
		$microchipName = $row['microchipName'];
		$url = $row['url'];
	}
	$result->close();
	

//	pixie_header("View Animal: $animalName", $userName, $url);

 ?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta content="text/html; charset=ISO-8859-1" http-equiv="content-type">
		<title><?= $pageName ?></title>
		<style type="text/css"></style>
		<link rel="stylesheet" type="text/css" href="css/normalize.css">
		<link rel="stylesheet" type="text/css" href="css/pixie.css">
		<link rel="stylesheet" type="text/css" href="css/responsive.css">
	</head>

	<body>
		<div id="wrapper">
			<section>
				<ul id="gallery">
					<li>
						<a href="<?=$url?>">
							<img src="<?=$url?>" alt="">
							<p>1.</p>
						</a>
					</li>
					<li>
						<a href="<?=$url?>">
							<img src="<?=$url?>" alt="">
							<p>2.</p>
						</a>
					</li>
					<li>
						<a href="<?=$url?>">
							<img src="<?=$url?>" alt="">
							<p>3.</p>
						</a>
					</li>
					<li>
						<a href="<?=$url?>">
							<img src="<?=$url?>" alt="">
							<p>4.</p>
						</a>
					</li>
					<li>
						<a href="<?=$url?>">
							<img src="<?=$url?>" alt="">
							<p>5.</p>
						</a>
					</li>
					<li>
						<a href="<?=$url?>">
							<img src="<?=$url?>" alt="">
							<p>6.</p>
						</a>
					</li>
				</ul>
			</section>
		</div>

	</body>
</html>
