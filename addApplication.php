<?php 
	/*
	 * addApplication.php
	 * Adds or edits an application.
	 * 
	 * POST will either add or update an application.  Which is determined by the presence of 
	 * 	an applicationID-- if present, then update, if not then add.
	 * GET variables will allow a user to view or delete an application.
	 */

	// Pull in includes
	include 'includes/utils.php';
	include 'includes/html_macros.php';
	include 'includes/panels.php';

	// turn on error reporting
	error_reporting(E_ALL);
	ini_set('display_errors', true); 
	date_default_timezone_set('America/Los_Angeles');	
	// Check that we have a valid logged in user
        [$userName,$isAdmin] = getLoggedinUser();
	if ($userName == "") header("location:login.php");
	
	// Init the error string
	$errString = "";

	// Pull in possible GET variables
	$animalID = (isset($_GET['animalID'])?intval($_GET['animalID']):0);
	$personID = (isset($_GET['personID'])?intval($_GET['personID']):0);
	$applicationID = (isset($_GET['applicationID'])?intval($_GET['applicationID']):0);
	$action = (isset($_GET['action'])?validateAction($_GET['action']):'');

	// Determine retPage
	if ($animalID) {
		$retPage = "viewAnimal.php?animalID=$animalID";
	} else if ($personID) {
		$retPage = "viewPerson.php?personID=$personID";
	} else $retPage = "viewApplication.php?applicationID=$applicationID";
	
	// connect to the database, get information on the current animal
	$mysqli = DBConnect();
	
	// If we have no applicationID AND no personID, then there is an error.  Go to the retPage.
	if (($personID+$applicationID)==0) {
		header('Location: ' . $retPage, true, 302);
	}	

// is this a POST?  if so, then we need to grab the posted values and write them to the DB.
	// If it isn't POST, then set the defaults
	$isPost = ($_SERVER['REQUEST_METHOD'] == 'POST');
	$applicationDate = $isPost?Date2MySQL($_POST['applicationDate']):date('Y-m-d');
	$species = $isPost?$_POST['species']:'';
	$gender = $isPost?$_POST['gender']:'';
	$breed = $isPost?$_POST['breed']:'';
	$personalityID = $isPost?$_POST['personalityID']:'';
	$minAge = $isPost?intval($_POST['minAge']):'';
	$maxAge = $isPost?intval($_POST['maxAge']):'';
	$minWeight = $isPost?intval($_POST['minWeight']):'';
	$maxWeight = $isPost?intval($_POST['maxWeight']):'';
	$minActivityLevel = $isPost?intval($_POST['minActivityLevel']):'';
	$maxActivityLevel = $isPost?intval($_POST['maxActivityLevel']):'';
	$numKids = $isPost?intval($_POST['numKids']):0;
	$numDogs = $isPost?intval($_POST['numDogs']):0;
	$numCats = $isPost?intval($_POST['numCats']):0;
	$note = $isPost?$_POST['note']:"";
	$needHypo = (isset($_POST['needHypo'])?1:0);
	$closed = (isset($_POST['closed'])?1:0);
	$rank = $isPost?intval($_POST['rank']):0;
    
	// Check required POST variables
	if ($isPost) {
		if ($species == "") $errString .=  "Species is required!<br>";
//		if ($breed == "") $errString .=  "Breed is required!<br>You can also use a description in this field, like <i>'small dog'</i> or <i>'sweet cat'</i>.<br>";
		if ($applicationDate == "") $errString .=  "Application Date is required!<br>";
	}
	
	// If all the expected variables are present, handle POST.
	if ($isPost and ($errString == "")) {
		
		// ADD NEW Placement if there is no applicationID but a personID
		if (($applicationID == 0) and ($personID > 0)) {			
			
			$insertSQL = sprintf("INSERT INTO pixie.Application (
				personID, applicationDate, species, gender,breed,
				minAge, maxAge, minWeight, maxWeight, minActivityLevel, maxActivityLevel,
				numKids, numDogs, numCats, needHypo, closed, `rank`, personalityID, note) 
				VALUES (%s, '%s', '%s', '%s', '%s',   %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, '%s', '%s');", 
				$personID, $applicationDate, lbt($species), lbt($gender), lbt($breed), 
				($minAge?$minAge:'NULL'), ($maxAge?$maxAge:'NULL'), ($minWeight?$minWeight:'NULL'), ($maxWeight?$maxWeight:'NULL'), 
				($minActivityLevel?$minActivityLevel:'NULL'), ($maxActivityLevel?$maxActivityLevel:'NULL'),
				$numKids, $numDogs, $numCats, $needHypo, $closed, $rank, lbt($personalityID), lbt($note)
			);

			$mysqli->query($insertSQL);
			if ($mysqli->errno) errorPage($mysqli->errno, $mysqli->error, $insertSQL);
		}

		// EDIT a current application.  Take the user to the animal's page when done.
		else if ($applicationID != 0) {
			$updateSQL = sprintf("UPDATE pixie.Application SET ".
				"applicationDate = 	'" .$applicationDate."'".
				",species = 		'" .lbt($species)."'".
				",gender = 		"  .($gender?"'".$gender."'":"NULL").
				",breed = 		'" .lbt($breed)."'".
				",maxAge =  		" .($maxAge?$maxAge:"NULL").
				",minAge =  		" .($minAge?$minAge:"NULL").
				",minWeight =  		" .($minWeight?$minWeight:"NULL").
				",maxWeight =  		" .($maxWeight?$maxWeight:"NULL").
				",minActivityLevel= 	" .($minActivityLevel?$minActivityLevel:"NULL").
				",maxActivityLevel= 	" .($maxActivityLevel?$maxActivityLevel:"NULL").
				",numKids = 		" .$numKids.
				",numDogs = 		" .$numDogs.
				",numCats = 		" .$numCats.
				",needHypo = 		" .$needHypo.
				",closed = 		" .$closed.
				",`rank` = 		" .$rank.
				",personalityID = 	'" .lbt($personalityID)."'".
				",note = 			'" .lbt($note)."'".
				" WHERE applicationID = " .$applicationID.";"
			);
			$mysqli->query($updateSQL);
			if ($mysqli->errno) errorPage($mysqli->errno, $mysqli->error, $updateSQL);
		}
		// At the end of a post, go back to the retPage
	        header('Location: ' . $retPage, true, 302);		
	} // END POST

	// Start GET.
	else if ($action && $applicationID) {
        if ($action == "delete") {			
            $sql = "DELETE from Application where applicationID=$applicationID;";
            $mysqli->query($sql);
            if ($mysqli->errno) errorPage($mysqli->errno, $mysqli->error, $sql);
            else header('Location: ' . "searchApplications.php", true, 302);
        }	
        
        // If an action was passed in, perform IT.  Either open or close an application.
        else if ($action == "open" or $action == "close") {
            $sql = "UPDATE Application set closed = ".($action=="close"?1:0)." where applicationID = ".$applicationID.";";
            $mysqli->query($sql);
            if ($mysqli->errno) errorPage($mysqli->errno, $mysqli->error, $sql);
        }
    }  
    
    // Pull information about the current application.
    if ($applicationID) {
        // get information about the current animal
        $applicationSQL =  "SELECT * FROM Application where applicationID = $applicationID";
    
        $result = $mysqli->query($applicationSQL);
        if ($mysqli->errno)  errorPage($mysqli->errno, $mysqli->error, $applicationSQL);
        else {
            $row = $result->fetch_array();	// Should just have one record, since fetched by PK.
            $applicationDate = $row['applicationDate'];
            $personID = $row['personID'];
            $species = $row['species'];
            $gender = $row['gender'];
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
            $personalityID = $row['personalityID'];
            $needHypo = $row['needHypo'];
            $closed = $row['closed'];
            $rank = $row['rank'];
            $note = $row['note'];
            $result->close();
        }
        
    }
        
	// If we have a personID, pull information about the current person. (Just the name.)
	if ($personID) {		
		// get information about the current Person
		$personSQL =  "SELECT firstName, lastName, isOrg FROM Person where personID = $personID";
		$result = $mysqli->query($personSQL);
		if (!$result)  errorPage($mysqli->errno, $mysqli->error, $personSQL);
		else {
			$row = $result->fetch_array();
			$personName = ($row['isOrg']?"":$row['firstName']." ") . $row['lastName'];
			$result->close();
		}
    }

	// If we have an animalID, pull information about the current animal. (Just the name.)
	if ($animalID) {		
		$animalSQL =  "SELECT animalName from Animal where animalID = $animalID";
		$result = $mysqli->query($animalSQL);
		if (!$result)  errorPage($mysqli->errno, $mysqli->error, $animalSQL);
		else {
			$row = $result->fetch_array();
			$animalName = $row['animalName'];
			$result->close();
		}
	}
	pixie_header(($applicationID==0?"Add":"Edit")." Application for ".$personName, $userName, "", $isAdmin);
?>

<font color="red"><?= $errString ?></font>

<form action="" method="POST" enctype="multipart/form-data">
	<table id=criteria>    
		<tr>
			<td>	<!-- Column 1 -->						
				<table> <!-- first column of demographic information -->
					<?=trd_labelData("Application Date", $applicationDate, "applicationDate", true, "date")?>
					<tr> <!-- Age -->
						<td id="leftHand">Age between:</td>
						<td><input size=2 name="minAge" value="<?=$minAge?>" type="txt"> and 
							<input size=2 name="maxAge" value="<?=$maxAge?>" type="txt"> years</td>
					</tr>
					<?=trd_labelData("Desired Breed", $breed, "breed")?>

					<tr> <!-- Species -->
						<td id="leftHand"><b>Species*</b></td><td id="rightHand"><select name=species>
								<option value="D" <?= ($species=='D')?"selected":"" ?>>Dog</option>
								<option value="C" <?= ($species=='C')?"selected":"" ?>>Cat</option>
								<option value="O" <?= ($species=='O')?"selected":"" ?>>Other</option>
							</select>				
						</td>
					</tr>
                    <tr> <!-- Rank -->
						<td id="leftHand">Rank</td><td id="rightHand"><select name=rank>
                                <?php
                                    for ($i = 1; $i <= 5; $i++) {
                                ?>
								<option value="<?=$i?>" <?= ($rank==$i)?"selected":"" ?>><?=$i?></option>
                                <?php } ?>
							</select>
						</td>
					</tr>
                </table>
			</td>
			<td>	<!-- Column 2 -->
				<table>			 					
					<tr> <!-- Activity -->
						<td id="leftHand">Activity Level between:</td>
						<td><input size=2 name="minActivityLevel" value="<?=$minActivityLevel?>" type="num"> and 
							<input size=2 name="maxActivityLevel" value="<?=$maxActivityLevel?>" type="num"></td>
					</tr>
					<tr> <!-- Gender -->
						<td id="leftHand">Desired Gender: </td><td id="rightHand" ><select name=gender>
									<option value=""></option>
									<option value="F" <?= ($gender==='F')?"selected":"" ?>>Female</option>
									<option value="M" <?= ($gender==='M')?"selected":"" ?>>Male</option>
							</select> 	
						</td>
					</tr>
					<tr> <!-- Weight -->
						<td id="leftHand">Weight between:</td>
						<td><input size=2 name="minWeight" value="<?=$minWeight?>" type="num"> and 
							<input size=2 name="maxWeight" value="<?=$maxWeight?>" type="num"> lbs</td>
					</tr>
					<?=trd_buildOption("Personality", "Personality", "personalityID", "personality", $personalityID, "addApplication", $mysqli, 1)?>
				</table>
			</td>
			<td>	<!-- Column 3 -->
				<table>
					<?=trd_labelData("Number of children", $numKids, "numKids", false, "num",2)?>
					<?=trd_labelData("Number of dogs", $numDogs, "numDogs", false, "num",2)?>
					<?=trd_labelData("Number of cats", $numCats, "numCats", false, "num",2)?>
					<?=trd_labelChk("Hypoallergetic needed?", "needHypo", $needHypo)?>
					<?=trd_labelChk("Closed?", "closed", $closed)?>
				</table>
			</td>
		</tr>
		<tr>
			<td colspan="3"><b>Note: </b><br><textarea type="memo" name="note" id="note" cols="30"><?=$note?></textarea></td>
		</tr>
		<?php if ($applicationID) { ?>
			<tr><td id="leftHand" colspan=3><font color="red"><a href="addApplication.php?action=delete&applicationID=<?=$applicationID?>" 
				onclick="return confirm('Are you sure you want to delete this record?  This action can not be undone.');">Delete Application</a></font></td></tr>
		<?php } ?>
	</table>
	<input type="submit" value="Submit Changes" />
	<a href="<?=$retPage?>">Cancel</a>
</form>

<table  id="sortable" width="100%">
    <thead>
        <tr>
            <?=(isset($retPage)?"<th></th>":"")?>
            <th><span>Date</span></th>
            <th><span>Name</span></th>
            <th><span>Species</span></th>
            <th><span>Rank</span></th>
            <th><span>Breed</span></th>
            <th><span>Note</span></th>
            <th>&nbsp;</th>
        </tr>
    </thead>
    <tbody>
<?php

	if (isset($_GET['applicationID'])) {

	        $sql = "SELECT * FROM ApplicationInfo WHERE applicationID = " . $_GET['applicationID'];
	        $result = $mysqli->query($sql);
	        if (!$result) errorPage($mysqli->errno, $mysqli->error, $sql);

	        // Generate the table
	        while($row = $result->fetch_array()) {
?>
		        <tr>
		                <td><?= MySql2Date($row['applicationDate']) ?></td>
		                <td><a href=<?= "\"viewPerson.php?personID=".$row['personID']."\"" ?>><?=$row['firstName']." ".$row['lastName'] ?></a>&nbsp;</td>
		                <td><?= ($row['species']=='D'?"Dog":($row['species']=='C'?"Cat":"Other"))?>&nbsp;</td>
		                <td><?= $row['rank'] ?>&nbsp;</td>
		                <td><?= $row['breed'] ?>&nbsp;</td>
		                <td><?= $row['note'] ?>&nbsp;</td>
		                <td><a href="viewApplication.php?applicationID=<?=$row['applicationID']?>">View / 
	                        <a href="addApplication.php?applicationID=<?=$row['applicationID']?>">Edit</td>
			        <td><a href="viewApplication.php?applicationID=<?=$row['applicationID']?>&closed=<?=$row['closed']?0:1?>"><?=$row['closed']?"Open":"Close"?></a></td>
		        </tr>
<?php
    		}
	}
?>
    <tbody>
</table>

<?php pixie_footer(); ?>

