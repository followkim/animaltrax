<?php 
	/*
	 * editAnimal.php
	 * This page is used to edit a single animal.  This page looks for 
	 * an animalID passed as part of the URL, and if none is pass in then 
	 * a new animal is created.
	 * 
	 * The page can also process requests related to file uploads.  
	 * 1. The page can process a POST request to add or edit an animal.  
	 * 2. If an animalID is passed into the URL with a "delete" action, 
	 * 		then that animal will be deleted.
	 */

	// Pull in includes
	include 'includes/utils.php';
	include 'includes/html_macros.php';

	// turn on error reporting
	error_reporting(E_ALL);
	ini_set('display_errors', true); 
	
	$userName = getLoggedinUser();
	if ($userName == "") header("location:login.php");
	
	// Init the error string
	$errString = "";

	$animalID = (isset($_GET['animalID'])?intval($_GET['animalID']):0);
	$action = (isset($_GET['action'])?validateAction($_GET['action']):'');
	
	// connect to the database, get information on the current animal
	$mysqli = DBConnect();
		
	// is this a POST?  if so, then we need to grab the posted values and write them to the DB.
	$isPost = ($_SERVER['REQUEST_METHOD'] == 'POST');
	
	// Pull POST variables or set defaults
	$animalName = $isPost?$_POST['animalName']:"";
	$species = $isPost?$_POST['species']:"";
	$breed = $isPost?$_POST['breed']:"";
	$gender = $isPost?$_POST['gender']:"";
	$markings = $isPost?$_POST['markings']:"";
	$estBirthdate = $isPost?Date2MySQL($_POST['estBirthdate']):"";
	$estbirthdateNumber = $isPost?intval($_POST['estbirthdateNumber']):"";
	$estbirthdateInterval = $isPost?$_POST['estbirthdateInterval']:"";
	$activityLevel = $isPost?(intval($_POST['activityLevel'])<=10?intval($_POST['activityLevel']):10):0;
	$note = $isPost?$_POST['note']:"";
	$microchipNumber = $isPost?$_POST['microchipNumber']:"";
	$microchipTypeID = $isPost?intval($_POST['microchipTypeID']):0;
	$dateImplanted = $isPost?Date2MySQL($_POST['dateImplanted']):"";
	$url = (isset($_POST['url'])?$_POST['url']:"");
	$isFixed = (isset($_POST['isFixed'])?1:0);
	$kids = ($isPost?$_POST['kids']:'U');
	$dogs = ($isPost?$_POST['dogs']:'U');
	$cats = ($isPost?$_POST['cats']:'U');
	$adoptionStatusID = $isPost?$_POST['adoptionStatusID']:'';
	$personalityID = $isPost?($_POST['personalityID']):'';
	$isHypo = (isset($_POST['isHypo'])?1:0);
    
    $heartwormPos = (isset($_POST['heartwormPos'])?1:0);
    $fiv = (isset($_POST['fiv'])?1:0);
    $felv = (isset($_POST['felv'])?1:0);

	// Check required variables
	if ($isPost) {
		if ($animalName == "") $errString .=  "Animal name is required!<br>";
		if ($species == "") $errString .=  "Animal species is required!<br>";
	}	
	// POST will check for a file to upload and add/edit an animal
	if ($isPost and ($errString == "")) {
		
		// Calculate the birthdate if an interval was used (otherwise use $estbirthdateNumber)
		if ($estbirthdateNumber>0) {
			$date=date_create(date('Y-m-d'));
			date_sub($date,new DateInterval('P'.$estbirthdateNumber.$estbirthdateInterval[0]));
			$estBirthdate = date_format($date,"Y-m-d");
		} 
		
		// Fix SQL date strings - need to pass in string NULL if they are blank.
		$estBirthdate = ($estBirthdate!=''?"'$estBirthdate'":"NULL");
		$dateImplanted = ($dateImplanted!=''?"'$dateImplanted'":"NULL");
		$microchipTypeID = ($microchipTypeID>0?$microchipTypeID:"NULL");

		// ADD NEW ANIMAL
		// As there isn't a animalID, we must be trying to add a new critter
		// Take the user to addTransfer when done
		if ($animalID == 0) {			
			$transferDate = Date2MySQL($_POST['transferDate']);
			$fee = isset($POST_['fee'])?$_POST['fee']+0:0;
			$transferNote = $_POST['transferNote'];
			
			$insertAnimalSQL = sprintf("insert into Animal 
				(animalName, species, breed, markings, gender, 
				estBirthdate, isFixed, note, activityLevel,
				microchipNumber, microchipTypeID, dateImplanted, url, 
				kids, dogs, cats, adoptionStatusID, personalityID, isHypo) 
				VALUES ('%s', '%s', '%s', '%s', '%s', %s, %s, '%s', %s, '%s', %s, %s, '%s', '%s', '%s', '%s', '%s', '%s', %s);",
				lbt($animalName), lbt($species), lbt($breed), lbt($markings), lbt($gender), 
				$estBirthdate, 	$isFixed, lbt($note), $activityLevel, 
				lbt($microchipNumber), $microchipTypeID, $dateImplanted, lbt($url), 
				$kids, $dogs, $cats, $adoptionStatusID, $personalityID, $isHypo
			);
			$mysqli->query($insertAnimalSQL);
			if ($mysqli->errno) errorPage($mysqli->errno, $mysqli->error, $insertAnimalSQL);

			// Now get the new animal
			$getAnimalSQL = "select max(animalID) as animalID from Animal";
			$result = $mysqli->query($getAnimalSQL);
			if ($mysqli->errno or !$result) errorPage($mysqli->errno, $mysqli->error, $getAnimalSQL);

			$row = $result->fetch_array();
			$animalID = $row['animalID'];
			if ($animalID > 0) {
				// Make sure that we have the right animal
				$checkAnimalSQL = "select animalName, animalID from Animal where animalID = $animalID";
				$result = $mysqli->query($checkAnimalSQL);
				if ($mysqli->errno or !$result) errorPage($mysqli->errno, $mysqli->error, $checkAnimalSQL);
				$row = $result->fetch_array();
				if ($row['animalName'] != $animalName) errorPage($mysqli->errno, "Unable to find newly inserted animal ($animalName)", $checkAnimalSQL);

				// New animals aways start at Pixie, can change later
				$transferDate = ($transferDate!=''?"'$transferDate'":"NULL");
				$insertTransferSQL = "insert into Transfer VALUES ($animalID, 1, $transferDate, 1, '".lbt($fee)."', '".lbt($transferNote)."');";
				$mysqli->query($insertTransferSQL);
				if ($mysqli->errno) errorPage($mysqli->errno, $mysqli->error, $insertTransferSQL);
                
                // Add vaccinations
                $vaccinationSQLInsert = "insert into Prescription (medicationID, animalID, startDate, nextDose, note) VALUES  ";
                $vaccinationSQL = $vaccinationSQLInsert;

                if (($_POST['dhpp1'])) $vaccinationSQL .= "(1, $animalID, '".Date2MySQL($_POST['dhpp1'])."', '".AddDays($_POST['dhpp1'], 14)."', 'Initial Intake'),";
                if (($_POST['dhpp2'])) $vaccinationSQL .= "(1, $animalID, '".Date2MySQL($_POST['dhpp2'])."', NULL, 'Initial Intake'),";
                if (($_POST['dhpp3'])) $vaccinationSQL .= "(1, $animalID, '".Date2MySQL($_POST['dhpp3'])."', NULL, 'Initial Intake'),";
                if (($_POST['fvrcp1'])) $vaccinationSQL .= "(16, $animalID, '".Date2MySQL($_POST['fvrcp1'])."', '".AddDays($_POST['fvrcp1'], 14)."', 'Initial Intake'),";
                if (($_POST['fvrcp2'])) $vaccinationSQL .= "(16, $animalID, '".Date2MySQL($_POST['fvrcp2'])."', NULL, 'Initial Intake'),";
                if (($_POST['fvrcp3'])) $vaccinationSQL .= "(16, $animalID, '".Date2MySQL($_POST['fvrcp3'])."', NULL, 'Initial Intake'),";
                if (($_POST['bordatella'])) $vaccinationSQL .= "(2, $animalID, '".Date2MySQL($_POST['bordatella'])."', '".AddDays($_POST['bordatella'], 365)."', 'Initial Intake'),";
                if (($_POST['rabies'])) $vaccinationSQL .= "(3, $animalID, '".Date2MySQL($_POST['rabies'])."', '".AddDays($_POST['rabies'], 365)."', 'Initial Intake'),";
                if (($_POST['flea'])) $vaccinationSQL .= "(4, $animalID, '".Date2MySQL($_POST['flea'])."', '".AddDays($_POST['flea'], 30)."', 'Initial Intake'),";
                if (($_POST['pyrantel1'])) $vaccinationSQL .= "(5, $animalID, '".Date2MySQL($_POST['pyrantel1'])."', '".AddDays($_POST['pyrantel1'], 30)."', 'Initial Intake'),";
                if (($_POST['pyrantel2'])) $vaccinationSQL .= "(5, $animalID, '".Date2MySQL($_POST['pyrantel2'])."', NULL, 'Initial Intake'),";
		if (strcmp($vaccinationSQLInsert, $vaccinationSQL) != 0) {
	                $vaccinationSQL = substr($vaccinationSQL, 0, -1) . ";";
	                $mysqli->query($vaccinationSQL);
	                if ($mysqli->errno) errorPage($mysqli->errno, $mysqli->error, $vaccinationSQL);
		}

                // Add tests
                $testSQLInsert = "insert into Test (testTypeID, animalID, testDate, testResult, note) VALUES  ";
                $testSQL = $testSQLInsert;
                if (($_POST['heartworm'])) $testSQL .= "(1, $animalID, '".Date2MySQL($_POST['heartworm'])."', ".($heartwormPos?"'positive'":"'negative'").", 'Initial Intake'),";
                if (($_POST['felvfiv'])) $testSQL .= "(2, $animalID, '".Date2MySQL($_POST['felvfiv'])."', ".($fiv?"'positive'":"'negative'").", 'Initial Intake'),";
                if (($_POST['felvfiv'])) $testSQL .= "(3, $animalID, '".Date2MySQL($_POST['felvfiv'])."',".($felv?"'positive'":"'negative   12/31/  '").", 'Initial Intake'),";

		if (strcmp($testSQLInsert, $testSQL) != 0) {
	                $testSQL = substr($testSQL, 0, -1) . ";";
	                $mysqli->query($testSQL);
	                if ($mysqli->errno) errorPage($mysqli->errno, $mysqli->error, $testSQL);
		}

                // Add weight
		if (isset($_POST['weightValue']) and is_numeric($_POST['weightValue'])) {
                	$vitalsSQL = "insert into VitalSign (vitalSignTypeID, animalID, vitalDateTime, vitalValue, note) VALUES  ";
                	$vitalsSQL .= "(7, $animalID, '".Date2MySQL($_POST['weightDate'])."', ".lbt($_POST['weightValue']).", 'Initial Intake');";
                        print($vitalsSQL);
	                $mysqli->query($vitalsSQL);
	                if ($mysqli->errno) errorPage($mysqli->errno, $mysqli->error, $vitalsSQL);
		}
//				header("location:addTransfer.php?animalID=".$animalID);
				header("location:viewAnimal.php?animalID=".$animalID);

        } // end add new animal
			else errorPage($mysqli->errno, "Unable to find newly inserted animal ($animalID)", $getAnimalSQL);

		}

		// EDIT a current animal.  Take the user to the animal's page when done.
		else {
			$sql = sprintf("update Animal SET 
				animalName = '%s', species = '%s', breed = '%s', markings = '%s', gender = '%s', 
				estBirthdate = %s, isFixed = %s, isHypo = %s, 
                kids = '%s',  dogs = '%s',  cats = '%s',  
				adoptionStatusID = '%s', activityLevel = %s, personalityID = '%s',
				microchipNumber = '%s',  microchipTypeID = %s, dateImplanted = %s,
				note = '%s', url = '%s' WHERE animalID = $animalID;",
				lbt($animalName), lbt($species), lbt($breed), lbt($markings), lbt($gender), 
				$estBirthdate, $isFixed, $isHypo, $kids, $dogs, $cats, 
				$adoptionStatusID, $activityLevel, $personalityID, lbt($microchipNumber), $microchipTypeID, 
				$dateImplanted, lbt($note), lbt($url)
			);
			$result = $mysqli->query($sql);
			if ($mysqli->errno) errorPage($mysqli->errno, $mysqli->error, $sql);
			else header('Location: ' . "viewAnimal.php?animalID=$animalID", true, 302);
		}

		// Was a file uploaded?  If so, upload and overwrite the URL with the uploaded file.
		if (isset($_FILES["fileToUpload"]["name"])) {
			$target_dir = "uploads/";
                        $dateUploaded = date('Y-m-d');
			$fileName = basename($_FILES["fileToUpload"]["name"]);
			$target_file = $target_dir . $fileName;

			if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
				$url = $target_file;
                                $sql ="insert into File (fileName, fileURL, dateUploaded, animalID )
                                                VALUES ('$fileName', '$target_file', '$dateUploaded', $animalID);";
                                $mysqli->query($sql);
                                if ($mysqli->errno) errorPage($mysqli->errno, $mysqli->error, $animalInfoSQL);

				// set the url
				$sql = sprintf("update Animal set url = '%s' where animalID = %s;", lbt($url), $animalID);
	                        $result = $mysqli->query($sql);
	                        if ($mysqli->errno) errorPage($mysqli->errno, $mysqli->error, $sql);
 
			} else echo "Sorry, there was an error uploading your file: $fileName";
		}

	} // END POST

	// Start GET
	else if (($action == "delete") && ($animalID>0)) {			
		$sql = "DELETE from Animal where animalID=$animalID;";
		$mysqli->query($sql);
		if ($mysqli->errno) errorPage($mysqli->errno, $mysqli->error, $sql);
		else header('Location: findAnimal.php');
	}
	
	if ($animalID) {
		// get information about the current animal
		$animalInfoSQL =  "SELECT * FROM Animal where animalID = $animalID";
	
		$result = $mysqli->query($animalInfoSQL);
		if (!$result)  errorPage($mysqli->errno, $mysqli->error, $animalInfoSQL);
		else {
	
			$row = $result->fetch_array();
			
			$animalName = $row['animalName'];
			$species = $row['species'];
			$l_species = ($species=='D'?"Dog":($species=='C'?"Cat":($species=='O'?"Other":"")));
			$breed = $row['breed'];
			$gender = $row['gender'];
			$markings = $row['markings'];
			$estBirthdate = MySQL2Date($row['estBirthdate']);
			$activityLevel = $row['activityLevel'];
			$note = $row['note'];
			$url = $row['url'];
			$microchipNumber = $row['microchipNumber'];
			$microchipTypeID = $row['microchipTypeID'];
			$dateImplanted = MySQL2Date($row['dateImplanted']);
			$isFixed = $row['isFixed'];
			$kids = $row['kids'];
			$dogs = $row['dogs'];
			$cats = $row['cats'];
			$adoptionStatusID = $row['adoptionStatusID'];
			$personalityID = $row['personalityID'];
			$isHypo = $row['isHypo'];
			$result->close();
		}
	}
	
	pixie_header(($animalID==0?"Add":"Edit")." Animal: $animalName", $userName);
 ?>
<font color="red"><?= $errString ?></font>
<form action="" method="POST" enctype="multipart/form-data">
	<table id=criteria>    
		<tr>
			<td>	<!-- Column 1 -->						
				<table > <!-- first column of demographic information -->
					<tr><?=td_labelData("Name", $animalName, "animalName", true)?></tr>
					<tr>
						<td style="text-align: right;">Gender: </td>
						<td style="text-align: left;" >				
							<select name=gender>
									<option value=""></option>
									<option value="F" <?= ($gender==='F')?"selected":"" ?>>Female</option>
									<option value="M" <?= ($gender==='M')?"selected":"" ?>>Male</option>
									<option value="O" <?= ($gender==='O')?"selected":"" ?>>Other/Unknown</option>
							</select> 	
						</td>
					</tr>
					<tr><?=td_labelChk("Neutered/Spayed?", "isFixed", $isFixed)?></tr>
					<tr><?=td_labelData("Birthdate", $estBirthdate, "estBirthdate")?></tr>
					<tr>
						<td></td>
						<td style="text-align: left;" >
							Or: <input type="txt" size=7 name="estbirthdateNumber" value="<?= $estbirthdateNumber ?>">
							<select name="estbirthdateInterval"> 
									<option <?= ($estbirthdateInterval=="D"?"selected":"") ?> value="D">Days</option>
									<option <?= ($estbirthdateInterval=="W"?"selected":"") ?> value="W">Weeks</option>
									<option <?= ($estbirthdateInterval=="M"?"selected":"") ?> value="M">Months</option>
									<option <?= ($estbirthdateInterval=="Y"?"selected":"") ?> value="Y">Years</option>
							</select> 	
						</td>
					</tr>
					<tr>
						<td style="text-align: right;"><b>Species*</b> </td>
						<td style="text-align: left;">
							<select name=species>
								<option value="D" <?= ($species=='D')?"selected":"" ?>>Dog</option>
								<option value="C" <?= ($species=='C')?"selected":"" ?>>Cat</option>
								<option value="O" <?= ($species=='O')?"selected":"" ?>>Other</option>
							</select> 							
						</td>
					</tr>
					<?=trd_labelData("Breed", $breed, "breed")?>
					<?=trd_labelData("Markings", $markings, "markings")?>
                    <?=trd_buildOption("Personality", "Personality", "personalityID", "personality", $personalityID, "retPage=editAnimal&animalID=$animalID", $mysqli, true)?>
                </table>
			</td>
			<td>	<!-- Column 2 -->
				<table>			 						
					<tr><?=td_labelData("Microchip", $microchipNumber, "microchipNumber")?></tr>
					<tr><?=td_labelData("Date Implanted", $dateImplanted, "dateImplanted")?></tr>
					<tr><?=td_buildOption("Microchip Manufacturer", "MicrochipType", "microchipTypeID", "microchipName", $microchipTypeID, "retPage=editAnimal&animalID=$animalID", $mysqli, true) ?></tr>
					<tr><?=td_labelData("Activity Level (1-10)", $activityLevel, "activityLevel")?></tr>
					<tr>
						<td style="text-align: right;">Good with <?=($species=="D"?"other":"")?> dogs?</td>
						<td>
							<input type="radio" name="dogs" value="Y" <?= ($dogs=='Y')?"checked":"" ?>>Yes
							<input type="radio" name="dogs" value="N" <?= ($dogs=='N')?"checked":"" ?>>No
							<input type="radio" name="dogs" value="U" <?= ($dogs=='U')?"checked":"" ?>>Unsure
						</td>
					</tr>
					<tr>
						<td style="text-align: right;">Good with <?=($species=="C"?"other":"")?> cats?</td>
						<td>
							<input type="radio" name="cats" value="Y" <?= ($cats=='Y')?"checked":"" ?>>Yes
							<input type="radio" name="cats" value="N" <?= ($cats=='N')?"checked":"" ?>>No
							<input type="radio" name="cats" value="U" <?= ($cats=='U')?"checked":"" ?>>Unsure
						</td>
					</tr>
					<tr>
						<td style="text-align: right;">Good with kids?</td>
						<td>
							<input type="radio" name="kids" value="Y" <?= ($kids=='Y')?"checked":"" ?>>Yes
							<input type="radio" name="kids" value="N" <?= ($kids=='N')?"checked":"" ?>>No
							<input type="radio" name="kids" value="U" <?= ($kids=='U')?"checked":"" ?>>Unsure
						</td>
					</tr>
					<?=trd_labelChk("Hypoallergetic Breed?", "isHypo", $isHypo)?>
					<?=trd_buildOption("Adoption Status", "AdoptionStatus", "adoptionStatusID", "adoptionStatus", $animalID==0?"P":$adoptionStatusID, "retPage=editAnimal&animalID=$animalID", $mysqli, false) ?>

				</table>
			</td>
			<td>	<!-- Column 3 -->
				<table>
					<?php if ($species) { ?>
					<tr><td colspan="2"><b>Picture: </b><br><img class="animal-picture" src="<?= ($url==""?"img/$l_species.jpg":$url) ?>"></img></td></tr>
					<?php } ?>
					<?=trd_labelData("Picture URL", $url, "url")?>
					<tr><td colspan="2">
							Or, select picture to upload:<br>
							<input type="file" name="fileToUpload" id="fileToUpload">
					</td></tr>
				</table>
			</td>
		</tr>
		<tr>
			<td colspan="3"><b>Note: </b><br><textarea type="memo" name="note" cols="30"><?=$note?></textarea></td>
		</tr>
		<?php if ($animalID) { ?>
			<tr><td  style="text-align: right;" colspan=3><font color="red"><a href="editAnimal.php?action=delete&animalID=<?=$animalID?>">Delete Animal</a></font></td></tr>
		<?php } ?>
	</table>
	
<?php 
	// If this is a new animal, then we want to allow the user to record when he/she came to pixie.
	if ($animalID==0) { ?>
	<hr>
    <table border =1>
        <tr>
            <td  class="intake_pixie"><center><b>Transferred to Pixie</b></center>
                <table  id=criteria> 
                    <?=trd_labelData("Arrival", date('m/d/y'), "transferDate")?>
                    <?=trd_buildOption("Location", "TransferType", "transferTypeID", "transferName", "", "retPage=findAnimal", $mysqli) ?>
                    <?=trd_labelData("Intake Fee", 0, "fee")?>
                    <tr><td></td><td><i>Use a negative number if Pixie paid this fee.</i></td></tr>
                    <tr>
                        <td style="text-align: right;"><b>Notes: </b></td>
                        <td style="text-align: left;"><b><textarea type="memo" name="transferNote" cols="30"></textarea></td>
                    </tr>	
                    <tr>
                        <!-- td colspan="2">Note: You will add the <u>initial transfer information</u> on the next page.</td -->
                    </tr>
                </table>
            </td>
            <td class="intake_dogs"><center><b>Dogs</b></center>
                <table id=criteria>
                    <?=trd_labelData("DHPP (1st)", '', "dhpp1")?>
                    <?=trd_labelData("DHPP (2nd)", '', "dhpp2")?>
                    <?=trd_labelData("DHPP (3rd)", '', "dhpp3")?>
                    <?=trd_labelData("Bordatella", '', "bordatella")?>
                    <?=trd_labelData("Heartworm: Date", '', "heartworm")?>
					<?=trd_labelChk("Heartworm: positive?", "", 0)?>
                </table>
            </td>
            <td  class="intake_cats"><center><b>Cats</b></center>
                <table id=criteria>
                    <?=trd_labelData("FVRCP (1st)", '', "fvrcp1")?>
                    <?=trd_labelData("FVRCP (2nd)", '', "fvrcp2")?>
                    <?=trd_labelData("FVRCP (3rd)", '', "fvrcp3")?>
                    <?=trd_labelData("FELV/FIV: Date", '', "felvfiv")?>
					<?=trd_labelChk("FELV: positive?", "felv", 0)?>
					<?=trd_labelChk("FIV: positive?", "fiv", 0)?>
                </table>
            </td>
            <td><center><b>Both</b></center>
                <table id=criteria>
                    <?=trd_labelData("Rabies", '', "rabies")?>
                    <?=trd_labelData("Pyrantel (1st)", '', "pyrantel1")?>
                    <?=trd_labelData("Pyrantel (2nd)", '', "pyrantel2")?>
                    <?=trd_labelData("Flea Treatment", '', "flea")?>
                    <?=trd_labelData("Initial Weight: Date", date('m/d/y'), "weightDate")?>
                    <?=trd_labelData("Initial Weight: Value", '', "weightValue")?>
                </table>
            </td>
        </tr>
    </table>
<?php 
}
?>

	<input type="submit" value="Submit Changes" />  <a href="<?=($animalID?"viewAnimal.php?animalID=$animalID":"findAnimal.php")?>">Cancel</a>
</form>
    <script src="http://code.jquery.com/jquery-1.11.0.min.js" type="text/javascript"></script>
    <script type="text/javascript" src="js/editAnimal.js"></script>
<?php 
	pixie_footer(); 
?>

