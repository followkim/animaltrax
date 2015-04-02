<?php 	
	/*
	 * viewSurgery.php
	 * This page is used to add and edit surgerys.  
	 * 
	 * Surgerys are done in three steps.  The first step should be completed by an animalID
	 * passed into the URL via GET.  The second step is when the personID is in the URL
	 * 
	 * Surgerys can also be deleted via GET.
	 */

	// turn on error reporting
	error_reporting(E_ALL);
	ini_set('display_errors', true); 
	
	// Pull in includes
	include 'includes/utils.php';
	include 'includes/html_macros.php';
	include 'includes/panels.php';
	
	// Check if the user is logged in
	$userName = getLoggedinUser();
	if ($userName == "") header("location:login.php");

	// Init the error string
	$errString = "";
	
	// If there is no animal ID, then redirect to the findAnimal page.
	// This should NEVER happen.
	if (isset($_GET['animalID'])) {
		$animalID =  intval($_GET['animalID']);
	} else header('Location: ' . "findAnimal.php", true, 302);

	// Pull Possible GET variables
	$personID = (isset($_GET['personID'])?intval($_GET['personID']):0);
	$surgeryDate = (isset($_GET['surgeryDate'])?$_GET['surgeryDate']:0);
	$surgeryTypeID = (isset($_GET['surgeryTypeID'])?$_GET['surgeryTypeID']:0);
	$action = (isset($_GET['action'])?validateAction($_GET['action']):'');
	$g_retPage = (isset($_GET['retPage'])?validateRetpage($_GET['retPage']):"viewSurgery");
	
	// Build retpage	
	$gets = ($animalID?"animalID=$animalID&":"");
	$gets .= ($surgeryTypeID?"surgeryTypeID=$surgeryTypeID&":"");
	$gets .= ($surgeryDate?"surgeryDate=$surgeryDate&":"");
	$gets .= "retPage=viewSurgery&";
	$getsNoPerson = $gets . "personID=$personID&";
	$gets .= ($personID?"personID=$personID&":"");
	$retPage =$g_retPage.".php?".$gets;
	$shortRetPage = "$g_retPage.php?".($animalID?"animalID=$animalID&":"").($personID>0?"personID=$personID":"");

	// connect to the DB
	$mysqli = DBConnect();


	// is this a POST?
	$isPost = ($_SERVER['REQUEST_METHOD'] == 'POST');

	// POST is either and edit or an insert.  
	// This is determined by a hidden p_action field passed as part of POST.
	if ($isPost) {
		// Pull in and validate POST varibles
		$p_action = validateAction($_POST['p_action']);	// Hidden field
		$p_surgeryDate = Date2MySQL($_POST['surgeryDate']);
		$p_surgeryTypeID = intval($_POST['surgeryTypeID']);
		$note = lbt($_POST['note']);
		
		// Check for required fields.
		if (!$p_surgeryDate) $errString .= "Surgery Date is required!<br>";
		if (!$p_surgeryTypeID) $errString .= "Surgery Type is required!<br>";

		if ($errString == "") {
			
			if ($p_action == "add") 
				$sql = "INSERT into AnimalSurgery VALUES ($p_surgeryTypeID, $animalID, '$p_surgeryDate', '$note', ".($personID>0?"$personID":"NULL").");";
			elseif ($p_action == "edit") 
				$sql = "UPDATE AnimalSurgery SET surgeryDate='$p_surgeryDate', surgeryTypeID = $p_surgeryTypeID, note='$note' , ".
						"animalID = $animalID, personID=".($personID>0?"$personID ":"NULL").
						" WHERE surgeryDate='$surgeryDate' AND animalID=$animalID AND surgeryTypeID = $surgeryTypeID;";
			$mysqli->query($sql);
			if ($mysqli->errno) {
				if ($mysqli->errno == 1062) $errString .= "You can't add a surgery to same animal on the same day.<br>";
				else errorPage($mysqli->errno, $mysqli->error, $p_action=="edit"?$updateSQL:$insertSQL);
			}
			else header('Location: ' . $shortRetPage, true, 302);
		}
	} // END POST
	
	
	// START GET
	if ($action == "delete") {
		if (!$surgeryDate) $errString .= "Surgery Date is required!<br>";
		if (!$animalID) $errString .= "Animal Name is required!<br>";
		if (!$surgeryTypeID) $errString .= "Surgery Type is required!<br>";	
		if ($errString == "")  {
			$sql = "DELETE FROM AnimalSurgery WHERE surgeryDate='$surgeryDate' AND animalID=$animalID and surgeryTypeID=$surgeryTypeID;";		
			if (!$mysqli->query($sql)) errorPage($mysqli->errno, $mysqli->error, $sql);
			else header('Location: ' . $shortRetPage, true, 302);
		}
	} // END GET
	
	// get information about the current surgery
	else if ($animalID and $surgeryDate and $surgeryTypeID) {
		$sql = "SELECT * FROM AnimalSurgery WHERE surgeryDate='$surgeryDate' AND animalID=$animalID and surgeryTypeID=$surgeryTypeID;";		
		$result = $mysqli->query($sql);
		if ($mysqli->errno) errorPage($mysqli->errno, $mysqli->error, $sql);
		else {
			$row = $result->fetch_array();	
			if ($personID==0) $personID = $row['personID'];
			$note = $row['note'];
			$result->close();
		}
	}
	
	// get information about the current person
	if ($personID>0) {
		$personSQL = "select * from Person where personID = $personID";
		$result = $mysqli->query($personSQL);
		if (!$result) errorPage($mysqli->errno, $mysqli->error, $personSQL);
		else {
			$row = $result->fetch_array();	
			$thisPerson = array(
				'Name' =>  ($row['isOrg']?$row['lastName']:$row['firstName']." ".$row['lastName']),
				'Address' => prettyAddress($row['address1'], $row['address2'], $row['city'], $row['state'], $row['zip']),
				'isOrg' => $row['isOrg'],
				'email' => $row['email']
			);
		}
	} else $thisPerson['isOrg'] = false;

	// get information about the current animal
	if ($animalID) {
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
	pixie_header("Add Surgery/Placement: $animalName", $userName);

?>
<font color="red"><?= $errString ?></font>

<table id="criteria" width="100%" border=1>
	<tr>
		<td width="50%">				<!-- STEP 1: Pick animal -->		
			<table id=criteria>
				<tr><td colspan=2><b>Step 1:</b> You are adding a new surgery for:</td></tr>
				<?php
					if ($animalID>0) {
						trd_labelData( "Name", $animalName);
						($estBirthdate?trd_labelData( "Birthdate", MySQL2Date($estBirthdate)):"");
						($estBirthdate?trd_labelData( "Age", prettyAge( $estBirthdate, date("Y-m-d"))):"");
						trd_labelData( "Species", $species);
					} else {
						
					} 
				?>
				<tr style="vertical-align: top;"><td colspan=2><a href="<?= "viewAnimal.php?animalID=$animalID"?>">Back to <?= $animalName ?></a></td></tr>
			</table>
		</td>
		<td width="50%">				<!-- STEP 2: Pick person -->
			<table>
				<tr><td colspan=2><b>Optional Step 2:</b> Where is this surgery to be performed?</td></tr>
				<?php
				if ($personID>0) {
					$personSQL = "select * from Person where personID = $personID";
					$result = $mysqli->query($personSQL);
					if (!$result) errorPage($mysqli->errno, $mysqli->error, $personSQL);
					else {
						$row = $result->fetch_array();		
						echo trd_labelData( "Name", $thisPerson['Name']);
						echo trd_labelData( "Address", $thisPerson['Address']);
						echo trd_labelData( "Email", $thisPerson['email']);
					}
					$result->close();	
				?>
				<tr><td colspan=2><a href="editPerson.php?<?=$gets?>">Edit Person</a></td></tr>
				<tr><td colspan=2><a href="viewSurgery.php?<?=$getsNoPerson."&personID=-1"?>">Remove Person</a></td></tr>
				<?php
				} // end if ($personID)
								?>
				<tr><td colspan=2><a href="findPerson.php?<?=$gets?>">Find Person</a></td></tr>
				<tr><td colspan=2><a href="editPerson.php?<?=$gets?>">Add New Person</a></td></tr>
			</table>
		</td>		
	</tr>
	<tr>
		<td colspan=2>					<!-- STEP 3: Surgery details -->
			<form action="" method="POST">
				<table width="100%"> 
					<tr><td colspan=2><b>Step 3:</b> Details </td></tr>
					<?php
						if ($surgeryDate and $animalID and $surgeryTypeID) {
							$sql = "select * from AnimalSurgery where surgeryTypeID = $surgeryTypeID AND animalID=$animalID and surgeryDate = '".Date2MySQL($surgeryDate)."'";
							$result = $mysqli->query($sql);
							if ($mysqli->errno) errorPage($mysqli->errno, $mysqli->error, $sql);
							$row = $result->fetch_array();
							$surgeryDate = MySQL2Date($row['surgeryDate']);
							$surgeryTypeID = $row['surgeryTypeID'];
							$note = $row['note'];
							$p_action="edit";
						} else {
							$surgeryDate = date("m/d/y");
							$surgeryTypeID = $note = '';
							$p_action="add";
						}
					?>
					<?= trd_labelData("Surgery Date", $surgeryDate, "surgeryDate", true) ?>
					<?= trd_buildOption("<b>Surgery Type*</b>", "SurgeryType", "surgeryTypeID", "surgeryType", $surgeryTypeID, "", $mysqli, 1) ?>
					<tr>
						<td id="leftHand"><b>Notes: </b></td>
						<td id="rightHand"><textarea type="memo" name="note" rows=10 cols="50"><?=$note?></textarea></td>
					</tr>
					<tr>
						<td colspan=2>
							<input  type="submit" value="<?= ucfirst($p_action) ?> Surgery" />
							<a href="<?=$shortRetPage?>">Cancel</a>
						</td>
					</tr>
					<tr><td><input hidden  type="txt" name="p_action" value="<?=$p_action ?>"></td></tr>
				</table>
			</form>
		</td>
	</tr>
</table>

<hr>
<table id=tabular width="100%">
	<tr><td colspan=6>Surgical History:</td></tr>
	<tr>
	  <th>Date</th>
	  <th>Type</th>
	  <th>Name</th>
	  <th>Location</th>
	  <th>Notes</th>
	  <th>&nbsp;</th>
	</tr>
	 <?php

		$lastSurgery = 0;		
		$surgery_sql =  "SELECT * FROM AnimalSurgeries where ". ($animalID?"animalID = $animalID":"personID=$personID").";";
		$result = $mysqli->query($surgery_sql);
		if (!$result)errorPage($mysqli->errno, $mysqli->error, $surgery_sql);

		// We want to pull all the data into an array, so that we can peek forward
		// to determine how long an animal was at a surgery
		$numRows = $result->num_rows;
		
		while($row = $result->fetch_array()) {
			$surgeryTypeID = $row['surgeryTypeID'];	
			$surgeryType = $row['surgeryType'];	
			$animalID = $row['animalID'];
			$animalName = $row['animalName'];
			$surgeryDate = $row['surgeryDate'];
			$note = $row['note'];
			$personID = $row['personID'];
			$lastName = $row['lastName'];
	?>
	<tr>
		<td><?= MySQL2Date($surgeryDate) ?></td>
		<td><?= $surgeryType ?></td>
		<td>
			<a href=<?= "\"viewAnimal.php?animalID=".$animalID."\"" ?>>
			<?= $animalName ?></a>
		</td>
		<td>
			<a href=<?= "\"viewPerson.php?personID=".$personID."\"" ?>>
			<?= $lastName ?></a>
		</td>
		<td style="white-space: pre-line;"><?= substr($note, 0,300) ?>&nbsp;</td>
		<td>
			<a href="viewSurgery.php?<?=($animalID?"animalID=$animalID&":"")?>surgeryDate=<?=$surgeryDate?>&surgeryTypeID=<?=$surgeryTypeID?>">Edit</a>
			<a href="viewSurgery.php?action=delete&<?=($animalID?"animalID=$animalID&":"")?>surgeryDate=<?=$surgeryDate?>&surgeryTypeID=<?=$surgeryTypeID?>">Delete</a>		
		</td>
	</tr>
		<?php
		}
		$result->close();	
		?>
</table>	

<?php pixie_footer(); ?>
