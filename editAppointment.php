<?php 	
	/*
	 * editAppointment.php
	 * This page is used to add and edit surgerys.  
	 * 
	 * Appointments are done in three steps.  The optional second step is to add an animal.
	 *
	 * Appointments can also be deleted via GET.
	 */

	// turn on error reporting
	error_reporting(E_ALL);
	ini_set('display_errors', true); 
        date_default_timezone_set('America/Los_Angeles');
	
	// Pull in includes
	include 'includes/utils.php';
	include 'includes/html_macros.php';
	include 'includes/panels.php';
	
	// Check if the user is logged in
        [$userName,$isAdmin] = getLoggedinUser();
	if ($userName == "") header("location:login.php");

	// Init the error string
	$errString = "";
	
	// If there is no personID or apptDateTime, then redirect to the findAnimal page.
	// This should NEVER happen.
	if (isset($_GET['personID'])) {
		$personID =  intval($_GET['personID']);
	} else header('Location: ' . "main.php", true, 302);

	// Pull Possible GET variables
        $apptDateTime = (isset($_GET['apptDateTime']))?$_GET['apptDateTime']:isset($_GET['apptDateTime']);
        $animalID = (isset($_GET['animalID']))?intval($_GET['animalID']):isset($_GET['animalID']);
	$action = (isset($_GET['action'])?validateAction($_GET['action']):'');
	$g_retPage = (isset($_GET['retPage'])?validateRetpage($_GET['retPage']):"editAppointment");
	$note = "";
	$subject = "";
	// connect to the DB
	$mysqli = DBConnect();


	// is this a POST?
	$isPost = ($_SERVER['REQUEST_METHOD'] == 'POST');

	// POST is either and edit or an insert.  
	// This is determined by a hidden p_action field passed as part of POST.
	if ($isPost) {
		// Pull in and validate POST varibles
		$p_apptDateTime = $_POST['apptDate']." ".$_POST['apptTime'];
		$p_animalID = isset($_POST['animalID'])?intval($_POST['animalID']):isset($_POST['animalID']);
		$p_personID = $personID;	// can't update person
		$p_note = lbt($_POST['note']);
		$p_subject = lbt($_POST['subject']);
		
		// Check for required fields.
		if (!$p_apptDateTime) $errString .= "Application Date is required!<br>";

		if ($errString == "") {
		
			$sql = "CALL AppointmentUpsert($p_personID, '$p_apptDateTime', '$p_subject', '$p_note', ".($p_animalID>0?$p_animalID:'NULL').", '".($apptDateTime?$apptDateTime:$p_apptDateTime)."')";
			errorPage(0,'', $sql);
			$mysqli->query($sql);
			if ($mysqli->errno) errorPage($mysqli->errno, $mysqli->error, $sql);

			// navigate to the new appointment page
			else header('Location: ' . "main.php", true, 302);
		}
	} // END $isPost

	// Build retpage	
	$gets = "personID=$personID&".($apptDateTime?"apptDateTime=".$apptDateTime:"")."&retPage=editApplication";
	$retPage =$g_retPage.".php?".$gets;
	$shortRetPage = "$g_retPage.php?personID=$personID";


	// START GET
	if ($action == "delete") {
		if (!$apptDateTime) $errString .= "Appointment date is required!<br>";
		if (!$personID) $errString .= "Person is required!<br>";
		if ($errString == "")  {
			$sql = "DELETE FROM Appointment WHERE apptDateTime='$apptDateTime' AND personID=$personID;";
			if (!$mysqli->query($sql)) errorPage($mysqli->errno, $mysqli->error, $sql);
			else header('Location: ' . $shortRetPage, true, 302);
		}
	} // END delete
	
	// handle request to add/remove animal
//	if ($animalID > 0 or $animalID == -1) {
//               $sql = "UPDATE Appointment SET animalID = ".($animalID>0?$animalID:'NULL')." WHERE apptDateTime='$apptDateTime' AND personID=$personID;";
//                $result = $mysqli->query($sql);
//                if ($mysqli->errno) errorPage($mysqli->errno, $mysqli->error, $sql);
//	}
	// END UPDATES / DELETES

	// GET APPPT, PERSON, ANIMAL details
	// get information about the current appointment

	if ($personID and $apptDateTime) {	// this is an edit
		$sql = "SELECT * FROM Appointment WHERE apptDateTime='$apptDateTime' AND personID=$personID;";
		$result = $mysqli->query($sql);
		if ($mysqli->errno) errorPage($mysqli->errno, $mysqli->error, $sql);
		else {
			$row = $result->fetch_array(); 
			if (!$animalID) $animalID = (isset($row['animalID'])?$row['animalID']:isset($row['animalID']));
			$subject = $row['subject'];
			$note = $row['note'];
			$result->close();
		}
	}
	// get information about the current person
	$personSQL = "select * from Person where personID = $personID";
	$result = $mysqli->query($personSQL);
	if (!$result) errorPage($mysqli->errno, $mysqli->error, $personSQL);
	else {
		$row = $result->fetch_array();
		$thisPerson = array(
			'Name' =>  ($row['isOrg']?$row['lastName']:$row['firstName']." ".$row['lastName']),
			'Address' => prettyAddress($row['address1'], $row['address2'], $row['city'], $row['state'], $row['zip']),
			'isOrg' => $row['isOrg'],
			'email' => $row['email'],
			'phone' => ($row['isOrg']?($row['workPhone']?$row['workPhone']:($row['cellPhone']?$row['cellPhone']:$row['homePhone'])):($row['homePhone']?$row['homePhone']:($row['cellPhone']?$row['cellPhone']:$row['workPhone'])))
		);
	}

	// get information about the current animal
	if ($animalID>0) {
		$sql =  "SELECT animalName, species, estBirthdate FROM AnimalInfo where animalID = $animalID";
		$result = $mysqli->query($sql);
		if ($mysqli->errno) errorPage($mysqli->errno, $mysqli->error, $sql);
		else {
			$row = $result->fetch_array();
			$animalName = $row['animalName'];
			$species = $row['species'];
			$estBirthdate = $row['estBirthdate'];
			$result->close();
		}
	}

	pixie_header("Add Appointment: ". $thisPerson['Name'], $userName, $isAdmin);
?>
<font color="red"><?= $errString ?></font>

<table id="criteria" width="100%" border=1>
	<tr>
		<td width="50%">				<!-- STEP 1: Show Person -->

			<table>
				<tr><td colspan=2><b>This appointment is with:</b></td></tr>
			<?php
				echo trd_labelData( "Name", '<a href="viewPerson.php?personID='.$personID.'">'.$thisPerson['Name'].'</a>');
				if ($thisPerson['Address']) echo trd_labelData( "Address", $thisPerson['Address']);
				if ($thisPerson['email']) echo trd_labelData( "Email", $thisPerson['email']);
				if ($thisPerson['phone']) echo trd_labelData( "Phone", $thisPerson['phone']);
			?>
			</table>

		</td>
		<td width="50%">				<!-- STEP 2: Pick animal -->
                       <table id=criteria>
				<tr><td colspan=2><b>Optional Step 2:</b> Is this about a specific animal?</td></tr>
                                <?php
				if ($animalID>0) {
					trd_labelData( "Name", '<a href="viewAnimal.php?animalID='.$animalID.'">'.$animalName.'</a>');
					($estBirthdate?trd_labelData( "Birthdate", MySQL2Date($estBirthdate)):"");
					($estBirthdate?trd_labelData( "Age",       prettyAge( $estBirthdate, date("Y-m-d"))):"");
					trd_labelData( "Species", $species);
				?>
					<tr><td colspan=2><a href="editAppointment.php?<?=$gets?>&animalID=-1">Remove Animal</a></td></tr>
				<?php
				}
                                ?>
					<tr><td colspan=2><a href="findAnimal.php?<?=$gets?>">Select <?=($animalID>0?"New ":"")?>Animal</a></td></tr>
                        </table>
		</td>
	</tr>
	<tr>
		<td colspan=2>					<!-- STEP 3: Appointment details -->
			<form action="" method="POST">
				<table width="100%"> 
					<tr><td colspan=2><b>Step 3:</b> Details </td></tr>
					<?= trd_labelData("Date", ($apptDateTime?date('Y-m-d', strtotime($apptDateTime)):date('Y-m-d')), "apptDate", true, "date") ?>
					<?= trd_labelData("Time", ($apptDateTime?date('H:i', strtotime($apptDateTime)):date('H:i')), "apptTime", true, "time") ?>
					<td id="leftHand">Subject:</td><td id="rightHand"><input size="50" type="txt" name="subject" id="subject" value="<?=$subject?>"></td>
					<tr>
						<td id="leftHand">Notes:</td>
						<td id="rightHand"><textarea type="memo" name="note" rows=10 cols="50"><?=$note?></textarea></td>
					</tr>
					<tr>
						<td>
							<input  type="submit" value="<?=$apptDateTime?"Edit":"Add"?> Appointment" />
<?php					if ($apptDateTime) {
?>							<a href="<?=$shortRetPage?>">Add New</a>
<?php					} else {
							echo "<a href=\"".($apptDateTime?"main.php":"viewPerson.php?personID=$personID")."\">Back To ".($apptDateTime?"Main":$thisPerson['Name'])."</a>";
					}
?>
						</td>
						<td id="leftHand">
<?php					if ($apptDateTime) {
?>							<a href="editAppointment.php?action=delete&personID=<?=$personID?>&apptDateTime=<?=$apptDateTime?>" 
	`							 onclick="return confirm('Are you sure you want to delete this record?  This action can not be undone.');">Delete Appointment</a>
<?php					}
?>
						</td>
					</tr>
					<tr><td><input hidden  type="txt" name="p_action" value="<?=$p_action ?>"><input hidden type="txt" name="animalID" id="animalID" value="<?=$animalID?>"></td></tr>
				</table>
			</form>
		</td>
</table>

<hr>
<table id=tabular width="100%">
	<tr><td colspan=6><b>Appointment History:</b></td></tr>
	<tr>
	  <th>Date</th>
	  <th>Person</th>
	  <th>Animal</th>
	  <th>Subject</th>
	  <th>&nbsp;</th>
	</tr>
	 <?php

		$surgery_sql =  "SELECT *, ap.note FROM Appointment ap LEFT JOIN Animal a ON a.animalID = ap.animalID LEFT JOIN Person p on p.personID = ap.personID where ap.personID=$personID ORDER BY ap.apptDateTime DESC;";
		$result = $mysqli->query($surgery_sql);
		if (!$result)errorPage($mysqli->errno, $mysqli->error, $surgery_sql);

		// We want to pull all the data into an array, so that we can peek forward
		// to determine how long an animal was at a surgery
		$numRows = $result->num_rows;
		
		while($row = $result->fetch_array()) {
			$animalID = $row['animalID'];
			$animalName = $row['animalName'];
			$apptDateTime = $row['apptDateTime'];
			$subject = $row['subject'];
			$personID = $row['personID'];
			$personName = $row['firstName'] . " " . $row['lastName'];
	?>
	<tr>
		<td><a href="editAppointment.php?<?=($personID?"personID=$personID&":"")?>apptDateTime=<?=$apptDateTime?>"><?= MySQL2DateTime($apptDateTime) ?></a></td>
		<td><a href=<?= "\"viewPerson.php?personID=".$personID."\"" ?>><?= $personName ?></a></td>
		<td><a href=<?= "\"viewAnimal.php?animalID=".$animalID."\"" ?>><?= $animalName ?></a></td>
		<td style="white-space: pre-line;"><?= $subject ?>&nbsp;</td>
		<td>
			<a href="editAppointment.php?personID=<?=$personID?>&apptDateTime=<?=$apptDateTime?>">Edit</a> / 
			<a href="editAppointment.php?action=delete&personID=<?=$personID?>&apptDateTime=<?=$apptDateTime?>">Delete</a>
		</td>
	</tr>
		<?php
		} // end while
		$result->close();
		?>
</table>

<?php pixie_footer(); ?>
