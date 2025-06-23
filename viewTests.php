<?php 
	
	/*
	 * viewTests.php
	 * 
	 * This is the main page where users can view tests for a single animal.
	 * Someone might come to this page for a bunch of reasons:
	 * 1. Simple GET-- the user navigated here from the viewAnimal page, and 
	 * 		the wants to view all the tests.  In this case, only animalID is set in the URL.
	 * 2. Edit GET - the user wants to edit a particular value.  In this case,
	 * 		the three primary keys are passed in via GET-- testDate, animalID and testID.
	 * 		Also, "edit" is passed in at the action.
	 * 2.5. Edit POST - the user wants to edit an exsisting row.  Ther user comes here after clicking
	 * 		the submit button after navigating to the page via path #2.
	 * 3. Delete GET - - the user wants to delete a particular row.  In this case,
	 * 		the three primary keys are passed in via GET-- testDate, animalID and testID.
	 * 		Also, "delete" is passed in at the action.
	 * 4. Add POST - the user wants to add a new row.  This is the default action of the form.
	 */
	 
	// Pull in the main includes file
	include 'includes/utils.php';
	include 'includes/html_macros.php';
	
	// Get the current user, if not logged in redirect to the login page.
	$userName = getLoggedinUser();
	if ($userName == "") header("location:login.php");

	// Init the error string
	$errString = "";
	
	// If there is no animal ID, then redirect to the findAnimal page.
	// NOTE: This should never happen.
	if (isset($_GET['animalID'])) {
		$animalID =  intval($_GET['animalID']);
	} else header('Location: ' . "findAnimal.php", true, 302);
	
	// Pull possible GET variables
	$testDate = isset($_GET['testDate'])?$_GET['testDate']:date('m/d/y');
	$testTypeID = isset($_GET['testTypeID'])?intval($_GET['testTypeID']):"";
	$action = isset($_GET['action'])?validateAction($_GET['action']):"";
	$retPage = isset($_GET['retPage'])?validateRetpage($_GET['retPage']):"viewTests";

	$mysqli = DBConnect();
	
	
	// Get information about the current animal
	$sql =  "SELECT * FROM Animal where animalID = $animalID";
	$result = $mysqli->query($sql);
	if (!$result) errorPage($mysqli->errno, $mysqli->error, $sql);
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
	$p_testTypeID = $isPost?$_POST['testTypeID']:"";
	$testResult = $isPost?$_POST['testResult']:"";
	$p_testDate = $isPost?Date2MySQL($_POST['testDate']):"";
	$note = $isPost?$_POST['note']:"";
	$p_action = $isPost?$_POST['action']:"";
	
	// For POST, we are either updating or deleting.  Check what was posted through "action" (a hidden input field.)
	if ($isPost) {
		
		if ($testResult == "") $errString .= "Result is required!<br>";
		if ($p_testDate == "") $errString .= "Date is required!<br>";
		if ($p_testDate > date('Y-m-d')) $errString .= "Date can't be greater then today.<br>";
		
		if ($errString == "")  {

			$qTestVal=lbt($testResult);
			$qNote=lbt($note);
			
			$nextDose = ($nextDose!=''?"'$nextDose'":"NULL");
			$insertSQL = "insert into Test VALUES 
				('$p_testDate', '$qTestVal', '$qNote', $p_testTypeID, $animalID);";
			$updateSQL = "update Test set 
						testDate='$p_testDate', testResult = '$qTestVal', 
						note='$qNote',  testTypeID=$p_testTypeID 
						WHERE testTypeID=$testTypeID and animalID=$animalID and testDate='$testDate';";
			
			$mysqli->query($p_action=="edit"?$updateSQL:$insertSQL);
			if ($mysqli->errno) {
				if ($mysqli->errno == 1062) $errString .= "You can't add the same test on the same day.<br>";
				else errorPage($mysqli->errno, $mysqli->error, $p_action=="edit"?$updateSQL:$insertSQL);
			}

			// If a return page was given, navigate back to it after the update/delete.
			if ($errString == "") header("location:$retPage.php?animalID=$animalID");
			
			// reset... 
			$action = $note = $nextDose = $medicationName = $testTypeID = "";
			$testDate = date('d/m/y');		
		}
	} 

	// Otherwise, this is a GET request-- prepare to either delete or edit
	else {	
		if ($action == "delete") {	
			$sql = "delete from Test WHERE animalID=$animalID and testTypeID=$testTypeID and testDate = '$testDate';";
			$result = $mysqli->query($sql);
			if ($mysqli->errno) errorPage($mysqli->errno, $mysqli->error, $sql);
			header("Location: $retPage.php?animalID=$animalID");
		}
		
		// For an edit, we want to pull the information on the row that we want to edit to show 
		// to the user
		else if ($action == "edit") {
			$sql = "select * FROM Test WHERE animalID=$animalID and testTypeID=$testTypeID AND testDate = '$testDate';";
			$result = $mysqli->query($sql);
			if ($mysqli->errno)   errorPage($mysqli->errno, $mysqli->error, $sql);
			else {		// should just have one row as we are adding by PK
				$row = $result->fetch_array();
				$testDate = MySQL2Date($row['testDate']);
				$testResult = $row['testResult'];
				$note = $row['note'];
				$result->close();
			}
		}
	}
	$testList = array();
	
	pixie_header("View Tests: $animalName", $userName);

?>

<font color=red><?=$errString?></font>

<!-- Add Tests Form -->
<form  action="" method="POST">
	<table id=criteria width=100% >
		<tr>
			<td>
				<table>
					<tr>
						<td id="leftHand"><b>Test Name</b>:</td>
						<td>
							<select name=testTypeID>                  
								<?php
									
									$sql = "select * FROM TestType WHERE species='' or species='$species';";
									$result = $mysqli->query($sql);
									if ($mysqli->errno)   errorPage($mysqli->errno, $mysqli->error, $sql);
									while ($row=$result->fetch_array()) {										
								?>
										<option value="<?= $row['testTypeID'] ?>"<?= ($testTypeID==$row['testTypeID']?"selected":"") ?>><?= $row['testName'] ?></option>
								<?php
									}
									$result->close();
								?> 
							</select>    
							<a href="editTables.php?tableName=TestType&retPage=viewTests&animalID=<?=$animalID?>">Edit List</a>   
						</td>
					</tr>
					<?= trd_labelData("Date", date('Y-m-d', strtotime($testDate)), "testDate", true, "date") ?>
					<?= trd_labelData("Value", $testResult, "testResult", true) ?>
					<?= trd_labelData("Note", $note, "note", false) ?>
					<tr>
						<td colspan="2"> 
							<input hidden type="txt" name="action" value="<?= $action ?>"/>
							<input type="submit" value="<?= ($action=="edit"?"Edit":"Add") ?> Test" /> 
							<a href="viewTests.php?animalID=<?= $animalID ?>"><?= ($action=="edit"?"Add New":"Cancel") ?></a>
						</td>
					</tr>
				</table>
			</td>
			<td>
				<table> <!-- first column of demographic information -->
					<tr><td colspan=2><b>Test for:</b></td></tr>
					<?= trd_labelData("Name", $animalName) ?>
					<?= trd_labelData("Birthdate", MySQL2Date($estBirthdate)) ?>
					<?= trd_labelData("Current age", $age) ?>
					<tr><td style="text-align: left;" colspan="2"><a href="viewAnimal.php?animalID=<?= $animalID ?>">Back to <?= $animalName ?></a></td></tr>
					<tr><td style="text-align: left;" colspan="2">Edit: 
						<a href="viewVaccination.php?animalID=<?= $animalID ?>">Vaccinations</a>
						<a href="viewVitals.php?animalID=<?= $animalID ?>">Vitals</a>
						<a href="addTransfer.php?animalID=<?= $animalID ?>">Transfers</a>
					</td></tr>
				</table>			
			</td>
		</tr>
		<tr>
			<td colspan=2>
				<table id=tabular width="100%">
					<tr>
					  <th width="150px">Test</th>
					  <th width="150px">Date</th>
					  <th width="150px">Value</th>
					  <th>Note</th>
					  <th width="100px">&nbsp;</th>
					</tr>
					<?php
						$sql = "select * FROM TestView WHERE animalID=$animalID ORDER BY testDate";
						$result = $mysqli->query($sql);
						if ($mysqli->errno) errorPage($mysqli->errno, $mysqli->error, $sql);
						while ($row=$result->fetch_array()) {
					?>
					<tr>
						<td><a href="viewTests.php?action=edit&animalID=<?= $animalID ?>&testTypeID=<?= $row['testTypeID'] ?>&testDate=<?= $row['testDate'] ?> "><?= $row['testName']?></td>
						<td><?= MySQL2Date($row['testDate']) ?></td>
						<td><?= $row['testResult']?>&nbsp;</font></td>
						<td  style="white-space: pre-line;"><?= $row['note'] ?>&nbsp;</td>
						<td>
							<a href="<?= "viewTests.php?animalID=$animalID&testTypeID=".$row['testTypeID']."&testDate=".$row['testDate']."&action=edit" ?>">Edit</a> \ 
							<a href="<?= "viewTests.php?animalID=$animalID&testTypeID=".$row['testTypeID']."&testDate=".$row['testDate']."&action=delete" ?>"
				                                onclick="return confirm('Are you sure you want to delete this record?  This action can not be undone.');">Delete</a>
						</td>
					</tr>
					<?php
						}
					?>
				</table>			
			</td>
		</tr>
	</table>
</form>


<?php 
	pixie_footer(); 
	
?>
