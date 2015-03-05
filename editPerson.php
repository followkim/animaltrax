<?php 
	// turn on error reporting
	error_reporting(E_ALL);
	ini_set('display_errors', true); 
		
	include 'includes/utils.php';
	include 'includes/html_macros.php';
	
	// Check that the user is logged in, if not redirect back to login page
	$userName = getLoggedinUser();
	if ($userName == "") header("location:login.php");
	
	$errString = "";
	
	// Grab the GET variables
	$personID = (isset($_GET['personID'])?intval($_GET['personID']):0);
	$action = 	(isset($_GET['action'])?validateAction($_GET['action']):'');
	$retPage = (isset($_GET['retPage'])?$_GET['retPage']:"");

	// We might have been redirected to this page from addTransfer.php or viewSurgery
	// Determine retPage
	if (isset($_GET['retPage'])) {
		$retPage = $_GET['retPage'].".php?";
		if (isset($_GET['personID'])) $retPage .= "personID=".$_GET['personID']."&";
		if (isset($_GET['animalID'])) $retPage .= "animalID=".$_GET['animalID']."&";
		if (isset($_GET['surgeryDate'])) $retPage .= "surgeryDate=".$_GET['surgeryDate']."&";
		if (isset($_GET['surgeryTypeID'])) $retPage .= "surgeryTypeID=".$_GET['surgeryTypeID']."&";
		if (isset($_GET['action'])) $retPage .= "action=".$_GET['action']."&";
	} else $retPage = "viewPerson.php?" . ($personID>0?"personID=$personID":"");


	// Connect to the database
	$mysqli = DBConnect();
	$isPost = $_SERVER['REQUEST_METHOD'] == 'POST';
	$firstName = ($isPost?$_POST['firstName']:"");	$lastName = ($isPost?$_POST['lastName']:"");
	$secondary = ($isPost?$_POST['secondary']:"");	$address1 = ($isPost?$_POST['address1']:"");
	$address2 = ($isPost?$_POST['address2']:"");	$city = ($isPost?$_POST['city']:"");
	$state = ($isPost?$_POST['state']:"");			$zip = ($isPost?$_POST['zip']:"");
	$homePhone = ($isPost?$_POST['homePhone']:"");	$workPhone = ($isPost?$_POST['workPhone']:"");
	$cellPhone = ($isPost?$_POST['cellPhone']:"");	$email = ($isPost?$_POST['email']:"");
	$note = ($isPost?$_POST['note']:"");			$isOrg = (isset($_POST['isOrg'])?1:0);

	// Check required variables
	if ($isPost and $lastName == "")  $errString .= "* Last Name/Shelter Name is required!<br>";
	
	// is this a POST?  That means that we want to complete the edit/insert.
	if ($isPost and $lastName) {
				
		$update_sql = "update Person SET ".
			"firstName ='".lbt($firstName)."',	lastName	= '".lbt($lastName)."', ".
			"secondary ='".lbt($secondary)."',	address1	= '".lbt($address1)."', ".
			"address2 = '".lbt($address2)."', 	city		= '".lbt($city)."', ".
			"state = 	'".lbt($state)."',		zip 		= '".lbt($zip)."', ".
			"homePhone ='".lbt($homePhone)."',	workPhone	= '".lbt($workPhone)."', ".
			"cellPhone ='".lbt($cellPhone)."',	email		= '".lbt($email)."',".	
			"note = 		'".lbt($note)."',		isOrg 		= $isOrg ".
			"WHERE personID = '$personID';";
			
		$insert_sql = "INSERT INTO Person ".
			"(firstName, lastName, secondary, address1, address2, city, state, zip, homePhone, cellPhone, workPhone, email, note, isOrg) ".
			"VALUES (" .
				"'".lbt($firstName)."',	'".lbt($lastName).	"', ".
				"'".lbt($secondary)."', '".lbt($address1).	"', ".
				"'".lbt($address2) ."', '".lbt($city).		"', ".
				"'".lbt($state)    ."', '".lbt($zip).		"', ".
				"'".lbt($homePhone)."', '".lbt($cellPhone).	"', ".
				"'".lbt($workPhone)."', '".lbt($email).		"', ".			
				"'".lbt($note)	   ."', 		$isOrg);";										
		
		$mysqli->query($personID==0?$insert_sql:$update_sql);
		if ($mysqli->errno) errorPage($mysqli->errno, $mysqli->error, $personID==0?$insert_sql:$update_sql);
	
		// after an insert, we will need to get the new PersonID
		if ($personID == 0) {
			$select_sql = "select max(personID) as personID from Person;";							
			$result = $mysqli->query($select_sql);
			if ($mysqli->errno) errorPage($mysqli->errno, $mysqli->error, $select_sql);
			else {
				$row = $result->fetch_array();
				$personID = $row['personID'];
				$result->close();	
                $retPage .= "personID=".$personID."&";
            }
		} 
		
		// redirect back to the info page 
		header('Location: ' . "$retPage", true, 302);	
	}
	
	// Otherwise, this is NOT a POST, so we want to allow the user to edit page information.
	// We need to set up the variables-- either from a query, or blank.
	if ($personID > 0) { 
		
		if ($action=="delete") {
			$deleteSQL = "DELETE FROM Person where personID = $personID";	
			$mysqli->query($deleteSQL);
			if ($mysqli->errno) errorPage($mysqli->errno, $mysqli->error, $deleteSQL);
			// redirect back to the info page 
			header('Location: ' . "findPerson.php", true, 302);
		}
		else {
			$person_sql =   "SELECT * FROM Person where personID = $personID";	
			$result = $mysqli->query($person_sql);
			if (!$result) errorPage($mysqli->errno, $mysqli->error, $person_sql);
			else {
				$row = $result->fetch_array();
				$firstName = $row['firstName'];	$lastName = $row['lastName'];
				$secondary = $row['secondary'];	$address1 = $row['address1'];
				$address2 = $row['address2'];	$city = $row['city'];
				$state = $row['state'];			$zip = $row['zip'];
				$homePhone = $row['homePhone'];	$workPhone = $row['workPhone'];
				$cellPhone = $row['cellPhone'];	$email = $row['email'];
				$note = $row['note'];			$isOrg = $row['isOrg'];
				$result->close();
			}
		}
	}
	pixie_header(($personID==0?"Add":"Edit")." Person: ".($isOrg?"":$firstName." ")."$lastName", $userName);

 ?> 
<font color="red"><?= $errString ?></font>
<form action="" method="POST">
	<table id="criteria">    
		<tr>
			<td width="50%">							
				<table> <!-- first column of demographic information -->
					<?=trd_labelData("First Name/Main Contact", $firstName, "firstName", false)?>
					<?=trd_labelData("Last Name/Shelter Name", $lastName, "lastName", true)?>
					<?=trd_labelData("Secondary Contact", $secondary, "secondary")?>
					<?=trd_labelData("Address 1", $address1, "address1")?>
					<?=trd_labelData("Address 2", $address2, "address2")?>
					<?=trd_labelData("City", $city, "city")?>
					<?=trd_labelData("State", $state, "state")?>
					<?=trd_labelData("Zip Code", $zip, "zip")?>
					<?=trd_labelData("Email", $email, "email", false, "email")?>
					</table>
			</td>
			<td style="vertical-align: top; width: 50%;">
				<table>			 						
					<?=trd_labelData("Home Phone/Fax", $homePhone, "homePhone", false, "tel")?>
					<?=trd_labelData("Cell Phone", $cellPhone, "cellPhone", false, "tel")?>
					<?=trd_labelData("Work Phone", $workPhone, "workPhone", false, "tel")?>
					<?=trd_labelChk("Shetler or organization?", "isOrg", $isOrg)?>
				</table>
			</td>
		</tr>
		<tr>
			<td colspan="2"><b>Note: </b><br><textarea type="memo" name="note" cols="60"><?=$note ?></textarea></td>
		</tr>
		<tr><td><i>Values marked with a '*' are required.</i></td></tr>
		<tr>
			<td>
				<input type="submit" value="Submit Changes" />
				<a href="<?= $retPage ?>">Cancel</a>
			</td>
			<td  style="text-align: right;"><?php if ($personID) { ?><a id=delete href="editPerson.php?personID=<?=$personID?>&action=delete">Delete Person</a></font><?php } ?></td>
		</tr>
	</table>
</form>
	
<?php
	pixie_footer();
?>
