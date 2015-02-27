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
	
	// Get the current user, if not logged in redirect to the login page.
	$userName = getLoggedinUser();
	if ($userName == "") header("location:login.php");

    // Init the error string
	$errString = "";

	$mysqli = DBConnect();

	// If a fileID was passed in, DELETE IT.
	if (isset($_GET['fileID'])) {
		$fileID =  $_GET['fileID'];
		$sql = "delete from File where fileID=$fileID;";
		$mysqli->query($sql);
		if ($mysqli->errno) errorPage($mysqli->errno, $mysqli->error, $sql);
	}

	// If there is no animal ID, then redirect to the findAnimal page.
	// NOTE: This should NEVER happen, and is likely the cause of URL manipulation.
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
	
	// Handle POST request-- the user wants to upload a file.
	$isPost = ($_SERVER['REQUEST_METHOD'] == 'POST');
	if ($isPost) {
		$fileName = basename($_FILES["fileToUpload"]["name"]);
		if ($fileName) {
			$target_dir = "uploads/";
			$target_file = $target_dir . $fileName;
			$dateUploaded = date('Y-m-d');

			if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
				$sql ="insert into File (fileName, fileURL, dateUploaded, animalID ) 
						VALUES ('$fileName', '$target_file', '$dateUploaded', $animalID);";
				$mysqli->query($sql);
				if ($mysqli->errno) errorPage($mysqli->errno, $mysqli->error, $animalInfoSQL);
			} else $errString .= "Sorry, there was an error uploading your file: $fileName<br>";
		}
	}
	pixie_header("View Animal: $animalName", $userName, $url);

 ?>
<font color=red><?=$errString?></font>		
<table  width=100%>  
	<tr> <!-- Animal demographic information -->
		<td>							
			<table id="criteria"> 
				<?=trd_labelData("Name", $animalName)?>
				<?=trd_labelData("Gender", $gender)?>
				<?=trd_labelData("Birthdate", MySQL2Date($estBirthdate))?>
				<?=trd_labelData("Age", $age)?>
				<?=trd_labelData("Species", $species)?>
				<?=trd_labelData("Breed", $breed)?>
				<?=trd_labelData("Markings", $markings)?>
				<?=trd_labelData("Fixed", $isFixed)?>
				<?=trd_labelData("Note", $note)?>
			</table>
		</td>
		<td>
			<table id="criteria">			 						
				<?=trd_labelData("Microchip", $microchipNumber)?>
				<?=trd_labelData("Date Implanted", MySQL2Date($dateImplanted))?>
				<?=trd_labelData("Manufacturer", $microchipName)?>
				<?=trd_labelData("Activity Level", $activityLevel)?>
				<?=trd_labelData("Good with kids", $kids)?>
				<?=trd_labelData("Good with ".($species=="Dog"?"other":"")." dogs", $dogs)?>
				<?=trd_labelData("Good with ".($species=="Cat"?"other":"")." cats", $cats)?>
				<?=trd_labelData("Adoption Status", $adoptionStatus)?>
				<?=trd_labelData("Personality", $personality)?>
			</table>
		</td>
		<td id="leftHand">
			<table id=criteria>
				<tr><td id="rightHand"><b>Picture:</b></td></tr>
				<tr><td><img class="animal-picture" src="<?= ($url==""?"img/$species.jpg":$url) ?>"></img></td></tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan=3 id="leftHand"><a href=<?= "\"editAnimal.php?animalID=$animalID\"" ?>>Edit Animal</a></td>
	</tr>	<!-- end demographic information -->
</table>		

<table>
	<tr>
		<td colspan=2><?=transferPanel($animalID, $mysqli)?></td>
	</tr>
	<tr>
		<td><?=TestPanel($animalID, $species, $mysqli)?></td>
		<td><?=matchesPanel($animalID, $mysqli)?></td>
	</tr>
	<tr>
		<td><?=VaccinationPanel ($animalID, $species, $mysqli)?></td>	
		<td><?=filesPanel($animalID, "A", $mysqli) ?></td>
	</tr>
	<tr>
		<td><?=vitalsPanel($animalID, $species, $mysqli)?></td>
		<td><?=surgeryPanel($animalID, "A", $mysqli)?></td>	
	</tr>
</table>
<?php pixie_footer(); ?>
