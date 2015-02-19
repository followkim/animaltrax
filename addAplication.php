<?php 
	/*
	 * addApplication.php
	 */

	$title = "Shelter Electronic Record System";
	$description = $title;
	
	include 'includes/utils.php';
	include 'includes/html_macros.php';

	// turn on error reporting
	error_reporting(E_ALL);
	ini_set('display_errors', true); 
	
	$userName = getLoggedinUser();
	if ($userName == "") header("location:login.php");
	
	// Init the error string
	$errString = "";
	$applicationID = (isset($_GET['applicationID'])?intval($_GET['applicationID']):0);
	$personID = (isset($_GET['personID'])?intval($_GET['personID']):0);
	$action = (isset($_GET['action'])?validateAction($_GET['action']):'');
	
	// connect to the database, get information on the current animal
	$mysqli = DBConnect();
		
	// is this a POST?  if so, then we need to grab the posted values and write them to the DB.
	$isPost = ($_SERVER['REQUEST_METHOD'] == 'POST');
	
	$applicationID = $isPost?intval($_POST['applicationID']):0;
	$applicationDate = $isPost?Date2MySQL($_POST['applicationDate']):"";
	$species = $isPost?$_POST['species']:"";
	$gender = $isPost?$_POST['gender']:"";
	$breed = $isPost?$_POST['breed']:"";
	$personality = $isPost?$_POST['personality']:"";
	$minAge = $isPost?intval($_POST['minAge']):0;el
	3apihttp://api.rubyonrails.org/
	$maxAge = $isPost?intval($_POST['maxAge']):99;
	$minWeight = $isPost?intval($_POST['minWeight']):0;
	$maxWeight = $isPost?intval($_POST['maxWeight']):600;
	$minActivityLevel = $isPost?intval($_POST['minActivityLevel']):0;
	$maxActivityLevel = $isPost?intval($_POST['maxActivityLevel']):99;
	$numKids = $isPost?intval($_POST['numKids']):0;
	$numDogs = $isPost?intval($_POST['numDogs']):0;
	$numCats = $isPost?intval($_POST['numCats']):0;
	$note = $isPost?$_POST['note']:"";

	if (($personID+$applicationID)==0) {
		header('Location: ' . "findPerson.php", true, 302);
	}	

	// Check required variables
	if ($isPost and ($species == "")) $errString .=  "Species is required!<br>";
	if ($isPost and ($applicationDate == "")) $errString .=  "Application Date is required!<br>";
		
	// POST will check for a file to upload and add/edit an animal
	if ($isPost and ($errString == "")) {
		
		// ADD NEW Placement
		if (($applicationID == 0) and ($personID > 0)) {			
			
			$insertSQL = sprintf("INSERT INTO pixie.Application (
				personID, applicationDate, species, gender,breed,
				minAge, maxAge, minWeight, maxWeight, minActivityLevel, maxActivityLevel,
				numKids, numDogs, numCats, personality, note) 
				VALUES (%s, %s, '%s', '%s', '%s', %s, %s, %s, %s, %s, %s, %s, %s, %s, '%s', '%s');", 
				$personID, $applicationDate, $species[0], $gender[0], lbt($breed), 
				$minAge, $maxAge, $minWeight, $maxWeight, $minActivityLevel, $maxActivityLevel,
				$numKids, $numDogs, $numCats, $personality[0], lbt($note)
			);
			$mysqli->query($insertSQL);
			if ($mysqli->errno) errorPage($mysqli->errno, $mysqli->error, $insertSQL);
		}

		// EDIT a current application.  Take the user to the animal's page when done.
		else if ($applicationID > 0) {
			$updateSQL = sprintf("UPDATE pixie.Application SET ".
				"applicationDate = 	" .$applicationDate.
				",species = 		'" .$species[0]."'".
				",gender = 			'" .$gender[0]."'".
				",breed = 			'" .lbt($breed)."'".
				",maxAge =  		" .$maxAge.
				",minAge =  		" .$minAge.
				",minWeight =  		" .$minWeight.
				",maxWeight =  		" .$maxWeight.
				",minActivityLevel = " .$minActivityLevel.
				",maxActivityLevel = " .$maxActivityLevel.
				",numKids = 		" .$numKids.
				",numDogs = 		" .$numDogs.
				",numCats = 		" .$numCats.
				",personality = 	'" .$personality[0]."'".
				",note = 			'" .lbt($note)."'".
				"WHERE applicationID = " .$applicationID
			); 
		
			$result = $mysqli->query($sql);
			if (!$result) errorPage($mysqli->errno, $mysqli->error, $updateSQL);
			else header('Location: ' . "viewPerson.php?personID=$personID", true, 302);
		}
	} // END POST

	// Start GET
	else if (($action == "delete") && ($applicationID>0)) {			
		$sql = "DELETE from Application where applicationID=$applicationID;";
		$mysqli->query($sql);
		if ($mysqli->errno) errorPage($mysqli->errno, $mysqli->error, $sql);
			else header('Location: ' . "viewPerson.php?personID=$personID", true, 302);
	}
	
	// Edit application
	if ($applicationID) {
		// get information about the current animal
		$applicationSQL =  "SELECT * FROM Application where applicationID = $applicationID";
	
		$result = $mysqli->query($applicationSQL);
		if (!$result)  errorPage($mysqli->errno, $mysqli->error, $applicationSQL);
		else {
	
			$row = $result->fetch_array();
			$applicationDate = $row['applciationDate'];
			$personID = $row['personID'];
			$species = $row['species'];
			$minAge = $row['minAge'];
			$maxAge = $row['maxAge'];
			$minWeight = $row['minWeight'];
			$maxWeight = $row['maxWeight'];
			$breed = $row['breed'];
			$minActivityLevel = $row['minActivityLevel'];
			$maxActivityLevel = $row['maxActivityLevel'];
			$numKids = $row['numKids'];
			$numDogs = $row['numDogs'];
			$numCats = $row['numCats'];
			$personality = $row['personality'];
			$note = $row['note'];			
			$result->close();
		}
		
		// get information about the current Person
		$personSQL =  "SELECT * FROM Person where personID = $personID";
	
		$result = $mysqli->query($personSQL);
		if (!$result)  errorPage($mysqli->errno, $mysqli->error, $personSQL);

	}
	
	pixie_header(($animalID==0?"Add":"Edit")." Application", $userName);
 ?>
<font color="red"><?= $errString ?></font>
<form action="" method="POST" enctype="multipart/form-data">
	<table id=criteria>    
		<tr>
			<td>	<!-- Column 1 -->						
				<table> <!-- first column of demographic information -->
					<?=trd_labelData("Application Date", $applicationDate, "applicationDate", true)?>
					<tr> <!-- Age -->
						<td style="text-align: right;">Age between:</td>
						<td><input size=8 name="minAge" value="<?=$minAge?>" type="txt"> and 
							<input size=8 name="maxAge" value="<?=$maxAge?>" type="txt"></td>
					</tr>
					<?=trd_labelData("Desired Breed", $breed, "breed")?>
					<tr> <!-- Species -->
						<td style="text-align: right;">Species: </td>
						<td style="text-align: left;">
							<select name=species>
								<option value=""></option>
								<option value="D" <?= ($species=='D')?"selected":"" ?>>Dog</option>
								<option value="C" <?= ($species=='C')?"selected":"" ?>>Cat</option>
							</select> 							
						</td>
					</tr>
				</table>
			</td>
			<td>	<!-- Column 2 -->
				<table>			 					
					<tr> <!-- Activity -->
						<td style="text-align: right;">Activity Level between:</td>
						<td><input size=8 name="minActivityLevel" value="<?=$minActivityLevel?>" type="txt"> and 
							<input size=8 name="maxActivityLevel" value="<?=$maxActivityLevel?>" type="txt"></td>
					</tr>
					<tr> <!-- Gender -->
						<td style="text-align: right;">Desired Gender: </td>
						<td style="text-align: left;" >				
							<select name=gender>
									<option value=""></option>
									<option value="F" <?= ($gender==='F')?"selected":"" ?>>Female</option>
									<option value="M" <?= ($gender==='M')?"selected":"" ?>>Male</option>
									<option value="O" <?= ($gender==='O')?"selected":"" ?>>Other/Unknown</option>
							</select> 	
						</td>
					</tr>
					<tr> <!-- Weight -->
						<td style="text-align: right;">Weight between:</td>
						<td><input size=8 name="minWeight" value="<?=$minWeight?>" type="txt"> and 
							<input size=8 name="maxWeight" value="<?=$maxWeight?>" type="txt"></td>
					</tr>
				</table>
			</td>
			<td>	<!-- Column 3 -->
				<table>
					<?=trd_labelData("Number of children", $numKids, "numKids")?>
					<?=trd_labelData("Number of dogs", $numKids, "numDogs")?>
					<?=trd_labelData("Number of cats", $numKids, "numCats")?>
					<tr> <!-- personality -->
						<td style="text-align: right;">Personality: </td>
						<td style="text-align: left;">
							<select name=personality>
								<option value=""></option>
								<option value="P" <?= ($personality=='P')?"selected":"" ?>>Playful</option>
								<option value="E" <?= ($personality=='E')?"selected":"" ?>>Energetic</option>
								<option value="A" <?= ($personality=='A')?"selected":"" ?>>Affectionate</option>
								<option value="I" <?= ($personality=='I')?"selected":"" ?>>Independent</option>
								<option value="C" <?= ($personality=='C')?"selected":"" ?>>Calm</option>
							</select> 							
						</td>
					</tr>				
				</table>
			</td>
		</tr>
		<tr>
			<td colspan="3"><b>Note: </b><br><textarea type="memo" name="note" cols="30"><?= $note ?></textarea></td>
		</tr>
		<?php if ($applicationID) { ?>
			<tr><td  style="text-align: right;" colspan=3><font color="red"><a href="addApplication.php?action=delete&applicationID=<?=$applicationID?>">Delete Application</a></font></td></tr>
		<?php } ?>
	</table>
	
    <input type="submit" value="Submit Changes" /> 
    <a href="<?=($personID?"viewPerson.php?personID=$personID":"findPerson.php")?>">Cancel</a>
</form>
<?php pixie_footer(); ?>

