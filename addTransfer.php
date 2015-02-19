<?php 	
	/*
	 * addTransfer.php
	 * This page is used to add and edit transfers.  
	 * 
	 * Transfers are done in three steps.  The first step should be completed by an animalID
	 * passed into the URL via GET.  The second step is when the personID is in the URL
	 * 
	 * Transfers can also be deleted via GET.
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
	
	// Pull Possibe GET variables
	$personID = (isset($_GET['personID'])?intval($_GET['personID']):0);
	$transferDate = (isset($_GET['transferDate'])?$_GET['transferDate']:0);
	$action = (isset($_GET['action'])?validateAction($_GET['action']):'');
	$retPage = (isset($_GET['retPage'])?validateRetpage($_GET['retPage']):"addTransfer");
	
	// If there is no animal ID, then redirect to the findAnimal page.
	// This should NEVER happen.
	if (isset($_GET['animalID'])) {
		$animalID =  intval($_GET['animalID']);
	} else header('Location: ' . "findAnimal.php", true, 302);

	// Build retpage	
	$retPage .= ".php?";
	if ($animalID) $retPage .= "animalID=$animalID&";
	if ($personID) $retPage .= "personID=$personID";

	// connect to the DB
	$mysqli = DBConnect();

	// get information about the current animal
	$sql =  "SELECT * FROM AnimalInfo where animalID = $animalID";
	$result = $mysqli->query($sql);
	if ($mysqli->errno) errorPage($mysqli->errno, $mysqli->error, $sql);
	else {
		$row = $result->fetch_array();		
		$animalName = $row['animalName'];
		$species = $row['species'];
		$estBirthdate = $row['estBirthdate'];
	}
	$result->close();

	// is this a POST?
	$isPost = ($_SERVER['REQUEST_METHOD'] == 'POST');

	// POST is either and edit or an insert.  Which is determined by a hidden p_action field
	// passed as part of POST.
	if ($isPost) {
		// Pull in and validate POST varibles
		$p_action = validateAction($_POST['p_action']);	// Hidden field
		$p_transferDate = Date2MySQL($_POST['transferDate']);
		$transferTypeID = intval($_POST['transferTypeID']);
		$fee = lbt($_POST['fee'])+0;
		$note = lbt($_POST['note']);
		
		// Transfer date is the only date that is required.
		if (!$p_transferDate) $errString .= "Transfer Date is required!<br>";
		else {
			
			if ($p_action == "add") 
				$sql = "INSERT into Transfer VALUES ($animalID, $personID, '$p_transferDate', $transferTypeID, '$fee', '$note');";
			elseif ($p_action == "edit") 
				$sql = "UPDATE Transfer SET transferDate='$p_transferDate', transferTypeID = $transferTypeID, fee='$fee', note='$note' 
							WHERE transferDate='$transferDate' AND personID=$personID AND animalID=$animalID; ";
			$mysqli->query($sql);
			if ($mysqli->errno) {
				if ($mysqli->errno == 1062) $errString .= "You can't add a transfer to the same person for the same animal on the same day.<br>";
				else errorPage($mysqli->errno, $mysqli->error, $p_action=="edit"?$updateSQL:$insertSQL);
			}
			else header('Location: ' . "$retPage", true, 302);
		}
	} // END POST
	
	
	// START GET
	if ($action == "delete") {
		if (!$transferDate) $errString .= "Transfer Date is required!<br>";	// should never happen
		else {
			$sql = "DELETE FROM Transfer WHERE transferDate='$transferDate' AND animalID=$animalID and personID=$personID;";		
			if (!$mysqli->query($sql)) errorPage($mysqli->errno, $mysqli->error, $sql);
			header('Location: ' . "$retPage", true, 302);
		}
	} // END GET
	else if ($action == "edit") {
	}
	
	// get information about the current person
	if ($personID) {
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

	pixie_header("Add Transfer/Placement: $animalName", $userName);

?>
<font color="red"><?= $errString ?></font>

<table id="criteria" width="100%" border=1>
	<tr>
		<td width="50%">				<!-- STEP 1: Pick animal -->		
			<table id=criteria>
				<tr><td colspan=2><b>Step 1:</b> You are adding a new transfer or placement for:</td></tr>
				<?=trd_labelData( "Name", $animalName) ?>
				<?=($estBirthdate?trd_labelData( "Birthdate", MySQL2Date($estBirthdate)):"") ?>
				<?=($estBirthdate?trd_labelData( "Age", prettyAge( $estBirthdate, date("Y-m-d"))):"") ?>
				<?=trd_labelData( "Species", $species) ?>
				<tr style="vertical-align: top;"><td colspan=2><a href="<?= "viewAnimal.php?animalID=$animalID"?>">Back to <?= $animalName ?></a></td></tr>
			</table>
		</td>
		<td width="50%" rowspan=2>		<!-- STEP 3: Transfer details -->
			<form action="" method="POST">
				<table width="100%"> 
					<tr><td colspan=2><b>Step 3:</b> Details </td></tr>
					<?php
						if ($personID>0) {
					?>
						<?php
							if ($transferDate) {
								$sql = "select * from Transfer where personID = $personID and animalID=$animalID and transferDate = '".Date2MySQL($transferDate)."'";
								$result = $mysqli->query($sql);
								if ($mysqli->errno) errorPage($mysqli->errno, $mysqli->error, $sql);
								$row = $result->fetch_array();
								$transferDate = MySQL2Date($row['transferDate']);
								$transferTypeID = $row['transferTypeID'];
								$fee = $row['fee'];
								$note = $row['note'];
								$p_action="edit";
							} else {
								$transferDate = date("m/d/y");
								$transferTypeID = $fee = $note = '';
								$p_action="add";
							}		
						?>
					<?= trd_labelData("Transfer Date", $transferDate, "transferDate", true) ?>
					<?php 
						if ($personID==1) {	// Pixie							
							echo trd_labelData("Transfer Type", "Pixie");
							echo "<input hidden type=\"txt\" name=\"transferTypeID\" value=\"1\">";
						} else if ($thisPerson['isOrg']) {
							echo trd_buildOptionSQL("Transfer Type", "TransferType", "transferTypeID", "transferName", $transferTypeID, "where isOrg not in ('N', 'P')", $mysqli);
						} else {						
							echo trd_buildOptionSQL("Transfer Type", "TransferType", "transferTypeID", "transferName", $transferTypeID, "where isOrg not in ('Y', 'P')", $mysqli);
						}
					?>
					<?= trd_labelData("Associated Fees", $fee, "fee") ?>
					<tr>
						<td id="leftHand"><b>Notes: </b></td>
						<td id="rightHand"><textarea type="memo" name="note" rows=10 cols="50"><?=$note?></textarea></td>
					</tr>
					<tr>
						<td colspan=2>
							<input  type="submit" value="<?= ucfirst($p_action) ?> Transfer" />
							<a href="<?=$retPage?>">Cancel</a>
						</td>
					</tr>
					<tr><td><input hidden  type="txt" name="p_action" value="<?=$p_action ?>"></td></tr>
					<?php
						}
					?>
				</table>
			</form>
		</td>		
	</tr>
	<tr>
		<td>							<!-- STEP 2: Pick person -->
			<table  id=criteria>
				<tr><td colspan=2><b>Step 2:</b> Who is/was this placement with?</td></tr>
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
					if ($action=="edit") {
				?>
				<tr><td colspan=2>
					<i>Note: To change who the transfer is with, <br>please <a href="<?= "addTransfer.php?animalID=$animalID&transferDate=".Date2MySql($transferDate)."&personID=$personID&action=delete&retPage=addTransfer" ?>">delete</a> this row and add a new one.</i>										
				</td></tr>
				<?php 
					} 
				?>
				<tr><td colspan=2><a href="editPerson.php?animalID=<?=$animalID?>&personID=<?=$personID?>&retPage=addTransfer">Edit Person</a></td></tr>
				<?php
				} // end if ($personID)
				
				if ($action!="edit") {
				?>
				<tr><td colspan=2><a href="findPerson.php?animalID=<?=$animalID?>&retPage=addTransfer">Find Person</a></td></tr>
				<tr><td colspan=2><a href="editPerson.php?animalID=<?=$animalID?>&retPage=addTransfer">Add New Person</a></td></tr>
				<?php
				}
				?>
			</table>
		</td>
	</tr>
</table>
<hr>
<?= $animalName ?>'s History:
<table id=tabular width="100%">
	<tr>
	  <th>Date</th>
	  <th>Name</th>
	  <th>Type</th>
	  <th>Duration</th>
	  <th>Fee</th>
	  <th>Permanent</th>
	  <th>Notes</th>
	  <th>&nbsp;</th>
	</tr>
	 <?php

		$lastTransfer = 0;		
		$transfer_sql =   "SELECT * FROM TransferHistory where animalID = $animalID";
		$result = $mysqli->query($transfer_sql);
		if (!$result)errorPage($mysqli->errno, $mysqli->error, $transfer_sql);

		// We want to pull all the data into an array, so that we can peek forward
		// to determine how long an animal was at a transfer
		$numRows = $result->num_rows;
		
		while($row = $result->fetch_array()) {
			$transferArray[] = array(
				'Name' => $row['Name'],			
				'personID' => $row['personID'],
				'transferName' => $row['transferName'],
				'transferDate' => $row['transferDate'],
				'fee' => $row['fee'],
				'note' => $row['note']
			);
		}
		for ($i = 0; $i < $numRows; $i++)
		{
			if ($i < ($numRows - 1))
				$nextTransfer = $transferArray[$i+1]['transferDate'];
			else $nextTransfer = date("m/d/y");
	?>
	<tr>
		<td><?= MySQL2Date($transferArray[$i]['transferDate']) ?></td>
		<td>
			<a href=<?= "\"viewPerson.php?personID=".$transferArray[$i]['personID']."\"" ?>>
			<?= $transferArray[$i]['Name'] ?></a>
		</td>
		<td><?= $transferArray[$i]['transferName'] ?></td>
		<td><?= $nextTransfer?prettyAge($transferArray[$i]['transferDate'], $nextTransfer ,false):"" ?></td>
		<td><?= $transferArray[$i]['fee']!=0?"$".$transferArray[$i]['fee']:"&nbsp;" ?></td>
		<td style="white-space: pre-line;"><?= substr($transferArray[$i]['note'], 0,300) ?>&nbsp;</td>
		<td>
			<a href="<?= "addTransfer.php?animalID=$animalID&transferDate=".$transferArray[$i]['transferDate']."&personID=".$transferArray[$i]['personID']."&action=delete&retPage=addTransfer" ?>">Delete</a>
			<a href="<?= "addTransfer.php?animalID=$animalID&transferDate=".$transferArray[$i]['transferDate']."&personID=".$transferArray[$i]['personID']."&action=edit&retPage=addTransfer" ?>">Edit</a>
		
		</td>
	</tr>
		<?php
		}	
		?>
</table>	

<?php pixie_footer(); ?>
