<?php 
	$title = "Shelter Electronic Record System";
	$description = $title;
	
	// turn on error reporting
	error_reporting(E_ALL);
	ini_set('display_errors', true); 
	
	include 'includes/utils.php';
	include 'includes/html_macros.php';
	include 'includes/panels.php';
	
	$userName = getLoggedinUser();
	if ($userName == "") header("location:login.php");
	
	$personID = (isset($_GET['personID'])?intval($_GET['personID']):0);
	$action = (isset($_GET['action'])?validateAction($_GET['action']):'');
	$positionTypeID = (isset($_GET['positionTypeID'])?intval($_GET['positionTypeID']):0);
	$fileID = (isset($_GET['fileID'])?intval($_GET['fileID']):0);

	// connect to the database, get information on the current animal
	$mysqli = DBConnect();

	// If there is no animal ID, then redirect to the findAnimal page.
	// This should NEVER happen.
	if (isset($_GET['personID'])) {
		$animalID =  intval($_GET['personID']);
	} else header('Location: ' . "findPerson.php", true, 302);

	// If a fileID was passed in, DELETE IT.
	if ($fileID>0) {
		$fileID =  $_GET['fileID'];
		$sql = "delete from File where fileID=$fileID;";
		$mysqli->query($sql);
		if ($mysqli->errno) errorPage($mysqli->errno, $mysqli->error, $sql);
	}

	// If a positionTypeID was passed in, DELETE IT.
	if ($positionTypeID>0) {
		$sql = "DELETE FROM PersonPosition WHERE positionTypeID=$positionTypeID and personID=$personID;"; 
		$mysqli->query($sql);
		if ($mysqli->errno) errorPage($mysqli->errno, $mysqli->error, $sql);
	}

	// is this a POST?  If so, then either a file add or a positionAdd
	$isPost = ($_SERVER['REQUEST_METHOD'] == 'POST');
	if ($isPost) {  
		// Upload a file
		if (isset($_FILES["fileToUpload"]["name"])) {
			echo "found file ".$_FILES["fileToUpload"]["name"]."<br>";
			$fileName = basename($_FILES["fileToUpload"]["name"]);
			$target_dir = "uploads/";
			$target_file = $target_dir . $fileName;
			$dateUploaded = date('Y-m-d');

			if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
				$sql ="insert into File (fileName, fileURL, dateUploaded, personID ) 
						VALUES ('$fileName', '$target_file', '$dateUploaded', $personID);";
				$mysqli->query($sql); echo $sql;
				if ($mysqli->errno) errorPage($mysqli->errno, $mysqli->error, $animalInfoSQL);
			} else $errString .= "Sorry, there was an error uploading your file: $fileName<br>";
		}

		// Add a position
		if ($_POST['positionTypeID']) {
			$startDate = Date2MySQL($_POST['startDate']);
			$startDate = ($startDate!=''?"'$startDate'":"NULL");
			$positionTypeID = intval($_POST['positionTypeID']);
			$note = lbt($_POST['note']);

			$sql = "INSERT INTO PersonPosition (positionTypeID, personID, note, startDate) VALUES  ($positionTypeID, $personID, '$note', $startDate)"; 
			$mysqli->query($sql);
			if ($mysqli->errno) {
				if ($mysqli->errno == 1062) $errString .= "You can't add the same vaccination on the same day.<br>";
				else errorPage($mysqli->errno, $mysqli->error, $sql);
			}
		}
	}
	

	// get information about the current animal
	$person_sql =   "SELECT * FROM Person where personID = $personID";
	$result = $mysqli->query($person_sql);
	if (!$result) errorPage($mysqli->errno, $mysqli->error, $person_sql);
	else {
	
		$row = $result->fetch_array();
		
		$firstName = $row['firstName'];
		$lastName = $row['lastName'];
		$secondary = $row['secondary'];
		$address1 = $row['address1'];
		$address2 = $row['address2'];
		$city = $row['city'];
		$state = $row['state'];
		$zip = $row['zip'];
		$homePhone = $row['homePhone'];
		$cellPhone = $row['cellPhone'];
		$workPhone = $row['workPhone'];
		$email = $row['email'];
		$note = $row['note'];
		$isOrg = ($row['isOrg']==1?1:0);
		
	}
	$result->close();
	
	
	pixie_header("View Person: ".($isOrg?"":$firstName." ")."$lastName", $userName);
 ?>
	<table id="criteria">    
		<tr>
			<td>
				<table> <!-- first column of demographic information -->
					<?php 
					if ($isOrg) {
						trd_labelData("Shelter Name", $lastName);
						trd_labelData("Main Contact", $firstName);
					} else {
						trd_labelData("First Name", $firstName);
						trd_labelData("Last Name", $lastName);
					}
					?>
				<?= ($secondary?trd_labelData("Secondary Contact", $secondary):"") ?>
				<?=trd_labelData("Address", prettyAddress($address1, $address2, $city, $state, $zip)) ?>
				<?=trd_labelData("Email", $email)?>
				</table>
			</td>
			<td>
				<table>			 						
					<?=trd_labelData("Cell Phone", $cellPhone)?>
					<?=trd_labelData("Work Phone", $workPhone)?>
					<?=trd_labelData(($isOrg?"Fax Number":"Home Phone"), $homePhone)?>
					<?=trd_labelData("Shelter/Organization", ($isOrg?"Yes":"No"))?>
				</table>
			</td>
		</tr>
		<tr>
			<td colspan="2" ><b>Notes:</b><br><?=$note?></td>
		</tr>
		<tr>
			<td colspan="2" id="leftHand">
				<a href=<?= "\"editPerson.php?personID=$personID\"" ?>>Edit Person</a>			
			</td>
		</tr>	
	</table>

	<hr>
	<table>
		<tr>
			<td width="50%"><?=currentAnimalsPanel($personID, $mysqli)?></td>			
			<td width="50%"><?=historyPanel($personID, $mysqli)?></td>
		</tr>
		<tr> 
			<td><?=filesPanel($personID, "P", $mysqli) ?></td>
			<td><?=applicationPanel($personID, $mysqli) ?></td>
		</tr>
		<tr>
			<td><?=currentPositionsPanel($personID, $mysqli) ?></td>			
			<td><?=surgeryPanel($personID, "P", $mysqli)?></td>	
		</tr>
	</table> <!-- End subtables -->
 </formm>
<?
	pixie_footer();
?>
