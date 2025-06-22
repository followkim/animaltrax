<?php 

	
	/*
	 * viewVaccionation.php
	 * 
	 * This is the main page where users can view thier vaccinations for a single animal.
	 * Someone might come to this page for a bunch of reasons:
	 * 1. Simple GET-- the user navigated here from the viewAnimal page, and 
	 * 		the wants to view all the vaccinations.  In this case, only animalID is set in the URL.
	 * 2. Edit GET - the user wants to edit a particular value.  In this case,
	 * 		the three primary keys are passed in via GET-- startDate, animalID and medicationID.
	 * 		Also, "edit" is passed in at the action.
	 * 2.5. Edit POST - the user wants to edit an exsisting row.  Ther user comes here after clicking
	 * 		the submit button after navigating to the page via path #2.
	 * 3. Delete GET - - the user wants to delete a particular row.  In this case,
	 * 		the three primary keys are passed in via GET-- startDate, animalID and medicationID.
	 * 		Also, "delete" is passed in at the action.
	 * 4. Add POST - the user wants to add a new row.  This is the default action of the form.
	 */
	 
	// Pull in the main includes file
	include 'includes/utils.php';
	include 'includes/html_macros.php';

	error_reporting(E_ALL);
	ini_set('display_errors', 1);
	
	// Get the current user, if not logged in redirect to the login page.
	$userName = getLoggedinUser();
	if ($userName == "") header("location:login.php");
	
	$errString = "";

	// If there is no animal ID, then redirect to the findAnimal page.
	// This should never happen.
	if (isset($_GET['animalID'])) {
		$animalID =  intval($_GET['animalID']);
	} else header('Location: ' . "findAnimal.php", true, 302);

	// Pull the possible GET variables
	$startDate = (isset($_GET['startDate'])?$_GET['startDate']:date('Y-m-d'));
	$medicationID = (isset($_GET['medicationID'])?intval($_GET['medicationID']):"");
	$action = (isset($_GET['action'])?validateAction($_GET['action']):'');
	$retPage = (isset($_GET['retPage'])?validateRetpage($_GET['retPage']):"");

	$mysqli = DBConnect();
	
	// get information about the current animal
	$animalInfoSQL =  "SELECT * FROM Animal where animalID = $animalID";
	
	$result = $mysqli->query($animalInfoSQL);
	if (!$result) errorPage($mysqli->errno, $mysqli->error, $animalInfoSQL);
	else {
		// we should have just one row, since we are selecting by PK.
		$row = $result->fetch_array();
		$animalName = $row['animalName'];
		$gender = $row['gender'];
		$species = $row['species'];
		$estBirthdate = $row['estBirthdate'];
		$age = prettyAge( $row['estBirthdate'], date("Y-m-d"));
		$url = $row['url'];
	}
	$result->close();
		
	/* POST */
	
	// Set up for POST, grabbing the variables
	$isPost = ($_SERVER['REQUEST_METHOD'] == 'POST');
	$p_medicationID = $isPost?intval($_POST['medicationID']):"";
	$p_startDate = $isPost?Date2MySQL($_POST['startDate']):"";
	$lot = $isPost?$_POST['lot']:"";
	$expDate = $isPost?$_POST['expDate']:"";
	$nextDose = $isPost?Date2MySQL($_POST['nextDose']):"";
	$nextDoseNumber = $isPost?intval($_POST['nextDoseNumber']):"";
	$nextDoseInterval = $isPost?$_POST['nextDoseInterval']:"";
	$note = $isPost?$_POST['note']:"";
	$p_action = $isPost?$_POST['action']:"";

	// For POST, we are either updating or editing.  Check what was posted through "action" (a hidden input field.)
	if ($isPost) {
		$qExpDate = lbt($expDate);
		$qLot = lbt($lot);
		$qNote = lbt($note);
		
		// If the interval method is used, determine the new nextDose
		if ($nextDoseNumber!=0) {
			$date=date_create($p_startDate);
			date_add($date,new DateInterval('P'.$nextDoseNumber.$nextDoseInterval[0]));
			$nextDose = date_format($date,"Y-m-d");
		}

		// Do some error checking
		if ($nextDose != "" and $nextDose <= $p_startDate) $errString .= "<b>Next Dose</b> ($nextDose) can't be less then the administration date ($p_startDate).<br>";
		if ($p_startDate == "") $errString .= "<b>Date Given</b> is a required field.<br>";
		if ($p_startDate < $estBirthdate) $errString .= "<b>Next Dose</b> can't be less then $animalName's birthdate (".MySQL2Date($estBirthdate).")<br>";
		if ($p_startDate > date('Y-m-d')) $errString .= "<b>Next Dose</b> can't be greater then today.<br>";
		
		// If we don't have any errors, then we should continue.
		if ($errString == "") {

			// Fix nextDose
			if ($nextDose != '') $nextDose = "'$nextDose'";
			else $nextDose = "NULL";

			$checkSQL = "select * from Prescription where medicationID = $p_medicationID and animalID = $animalID and startDate = '$p_startDate'";
			$insertSQL = "insert into Prescription VALUES 
				($p_medicationID, $animalID, '$p_startDate', '$qLot', '$qExpDate', '$qNote', $nextDose);";
			$updateSQL = "update Prescription set medicationID=$p_medicationID, startDate='$p_startDate', 
					lot='$qLot', expDate='$qExpDate', nextDose=$nextDose, note='$qNote'
					WHERE medicationID=$p_medicationID and animalID=$animalID and startDate='$startDate';";
			if ($p_action=="edit") $mysqli->query($updateSQL);
			else {
				// check to see if row exsits.  If does, treat as edit.  Otherwise, add it.
				$rowExists = $mysqli->query($checkSQL);
				if ($mysqli->errno) errorPage($mysqli->errno, $mysqli->error, $p_action=="edit"?$updateSQL:$insertSQL);
	
				if ($rowExists->num_rows == 0) $mysqli->query($insertSQL);
				else $mysqli->query($updateSQL);

				if ($mysqli->errno) errorPage($mysqli->errno, $mysqli->error, $p_action=="edit"?$updateSQL:$insertSQL);
			}

			// If a return page was given, navigate back to it after the update/delete.
			if ($retPage) header("location:$retPage.php?animalID=$animalID");

			// reset... 
			$action = $note = $nextDose = $lot = $expDate = "";
			$medicationID = $p_medicationID;
			$startDate = date('m/d/y');		
		} 
	}
	
	// Otherwise, this is a GET request-- prepare to either delete or edit
	else {	

		if ($action == "delete") {
			$sql = "delete from Prescription WHERE animalID=$animalID and medicationID=$medicationID and startDate = '$startDate';";
			$result = $mysqli->query($sql);
			if ($mysqli->errno) errorPage($mysqli->errno, $mysqli->error, $sql);
			else header("Location: ".($retPage?$retPage:"viewVaccination").".php?animalID=$animalID");
		}
		
		// For an edit, we want to pull the information on the row that we want to edit to show 
		// to the user
		else if ($action == "edit") {
			$sql = "select p.*, m.* from Prescription p
					INNER JOIN Medication m on p.medicationID=m.medicationID 
			WHERE p.animalID=$animalID and p.medicationID=$medicationID and p.startDate = '$startDate';";
			$result = $mysqli->query($sql);
			if ($mysqli->errno)   errorPage($mysqli->errno, $mysqli->error, $sql);
			else {		// should just have one row as we are adding by PK
				$row = $result->fetch_array();
				$startDate = $row['startDate'];
				$lot = $row['lot'];
				$expDate = $row['expDate'];
				$note = $row['note'];
				$nextDose = $row['nextDose'];
				$medicationName = $row['medicationName'];
				$result->close();
			}
		}
	}

	// Get full list of Vaccination Names, stored in $vaccList
	// $vaccList is a 2D array:
	//	1D: medication information (name and ID)
	// 	2D: individual administrations (stored in 'adminInfo')
	$vaccListSQL = "select * from Medication where (species='' or species='$species') and isVaccination = 1";

	$vaccList = $mysqli->query($vaccListSQL);
	if (!$vaccList)  errorPage($mysqli->errno, $mysqli->error, $vaccListSQL);

	for ($i = 0; $i < $vaccList->num_rows; $i++) {
		$vaccRows = $vaccList->fetch_array();
		$vaccListArray[] = array (
			'medicationID' 		=> $vaccRows['medicationID'],
			'medicationName' 	=> $vaccRows['medicationName'],
			'nextDoseDays'		=> $vaccRows['nextDoseDays'],
			'adminInfo'		=> []
		);
	}
	$vaccList->close();
	
	for ($i = 0; $i < count($vaccListArray); $i++) {
		$thisMedID = $vaccListArray[$i]['medicationID'];
		$thisVaccSQL = "select * from Prescription where animalID = $animalID and medicationID = $thisMedID ORDER BY startDate;";
		$vaccList = $mysqli->query($thisVaccSQL);		
		if (!$vaccList)  errorPage($mysqli->errno, $mysqli->error, $thisVaccSQL);

		while ($vaccAdminRows = $vaccList->fetch_array()) {			
			$vaccListArray[$i]['adminInfo'][] = array(
				'startDate'	=> $vaccAdminRows['startDate'],
				'note' 		=> $vaccAdminRows['note'],
				'lot' 		=> $vaccAdminRows['lot'],
				'expDate' 	=> $vaccAdminRows['expDate'],
				'nextDose'	=> $vaccAdminRows['nextDose']
			);
		}
		$vaccList->close();
	}
		
	pixie_header("View Vaccinations: $animalName", $userName);

?>
<font color=red><?=$errString?></font>

<!-- Add Vaccinations Form -->
<form  action="" method="POST">
	<table id="criteria" >
		<tr>
			<td width=50%> <!-- Vaccination panel -->
				<table>
					<tr>
						<td nowrap>Vaccination Name:</td>
						<td>
							<select name=medicationID>                  
								<?php
									foreach ($vaccListArray as $thisVacc) {
								?>
								<option value="<?= $thisVacc['medicationID'] ?>"  <?= ($medicationID==$thisVacc['medicationID']?"selected":"") ?>><?= $thisVacc['medicationName'] ?></option>
								<?php
									}	
								?> 
							</select>    
							<a href="editTables.php?tableName=Medication&retPage=viewVaccination&animalID=<?=$animalID?>">Edit List</a>   
						</td>
					</tr>
					<?= trd_labelData( "Date Given", MySQL2Date($startDate), "startDate", true) ?>
					<?= trd_labelData( "Lot", $lot, "lot") ?>
					<?= trd_labelData( "Expiration Date", $expDate, "expDate") ?>
					<?= trd_labelData( "Next Due", MySQL2Date($nextDose), "nextDose") ?>
					<tr>
						<td></td>
						<td style="text-align: left;" >Or: <input type="txt" size=7 name="nextDoseNumber" value="" ><select name="nextDoseInterval">
									<option <?= ($nextDoseInterval=="D"?"selected":"") ?>value="D">Days</option>
									<option <?= ($nextDoseInterval=="W"?"selected":"") ?>value="W">Weeks</option>
									<option <?= ($nextDoseInterval=="M"?"selected":"") ?>value="M">Months</option>
									<option <?= ($nextDoseInterval=="Y"?"selected":"") ?>value="Y">Years</option>
							</select> 	
						</td>
					<tr>
						<td  style="text-align: right;" >Note: </td><td><textarea type="memo" name="note" cols="30"><?= $note ?></textarea></td>
					</tr>
						<td colspan="2"> 
							<input hidden type="txt" name="action" value="<?= $action ?>"/>
							<input type="submit" value="<?= ($action=="edit"?"Edit":"Add") ?> Vaccination" /> 
							<TODOinput type="submit" value="Cancel (not working)" formaction="<?="viewVaccination.php?animalID=$animalID"?>" /> 
							<a href="viewVaccination.php?animalID=<?= $animalID ?>">Cancel</a>
						</td>
					</tr>

				</table>
			</td>
			<td>
				<table>
					<tr><td colspan=2><u>Vaccination Information for:</u></td></tr>
					<?=trd_labelData( "Name", $animalName) ?>
					<?=trd_labelData( "Birthdate", MySQL2Date($estBirthdate)) ?>
					<?=trd_labelData( "Current age", $age) ?>
					<tr><td colspan=2><a href="viewAnimal.php?animalID=<?=$animalID?>">Back to <?=$animalName?></a></td></tr>
					<tr><td style="text-align: left;" colspan="2">Edit: <a href="viewTests.php?animalID=<?= $animalID ?>">Tests</a> <a href="viewVitals.php?animalID=<?= $animalID ?>">Vitals</a> <a href="addTransfer.php?animalID=<?= $animalID ?>">Transfers</a>
					</td></tr>
				</table>							
			</td>
		</tr>
	</table>
</form>

<table id=tabular>
<?php 
	foreach ($vaccListArray as $thisVacc) {
?>
	<tr >
		<td colspan="9">
			<font color="purple"><b><?= $thisVacc['medicationName'] ?></b></font>
		</td>
	</tr>
	<tr>
	  <th>Dose #</th>
	  <th>Date</th>
	  <?= ($estBirthdate?"<th>Age when given</th>":"") ?>
	  <th>Duration since previous</th>
	  <th>Next Due</th>
	  <th>Note</th>
	  <th>&nbsp;</th>
	</tr>
		<?php
			for ($i = 0; $i < count($thisVacc['adminInfo']); $i++) {
				$nextDose = $thisVacc['adminInfo'][$i]['nextDose'];
				if ($i>0) $lastDose = $thisVacc['adminInfo'][$i-1]['startDate'];
				else $lastDose = false;
				$isLast = ($i == (count($thisVacc['adminInfo'])-1));
		?>
	<tr>
		<td><?= $i+1 ?></td>
		<td><?= MySQL2Date($thisVacc['adminInfo'][$i]['startDate']) ?></td>
		<?= ($estBirthdate?"<td>".prettyAge($thisVacc['adminInfo'][$i]['startDate'], $estBirthdate)."</td>":"") ?>
		<td><?= ($lastDose?prettyAge($thisVacc['adminInfo'][$i]['startDate'], $lastDose):"&nbsp;") ?>&nbsp;</td>
		<td><font color ="<?= (!$isLast?"gray":($nextDose < date('Y-m-d')?"red":"black")) ?>" ><?= MySQL2Date($nextDose) ?></font>&nbsp;</td>
		<td  style="white-space: pre-line;"><?= $thisVacc['adminInfo'][$i]['note'] ?>&nbsp;</td>
		<td>
			<a href="<?= "viewVaccination.php?animalID=$animalID&medicationID=".$thisVacc['medicationID']."&startDate=".$thisVacc['adminInfo'][$i]['startDate']."&action=edit" ?>">Edit</a>							
			<a href="<?= "viewVaccination.php?animalID=$animalID&medicationID=".$thisVacc['medicationID']."&startDate=".$thisVacc['adminInfo'][$i]['startDate']."&action=delete" ?>">Delete</a>							
		</td>
	</tr>
			<?php
				}
			?>
	<tr><td colspan="9">&nbsp;</td></tr>
<?php
	}
?>
</table>
<?php pixie_footer(); ?>
