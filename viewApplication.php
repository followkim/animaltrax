<?php 	
	/*	viewApplication.php
	 * 	Allows a user to view a list of all current applications in the system.
	 * 
	 * 	This page will handle three types of requests:
	 * 	1. The user lands on this page with no POST variables set.
	 * 	2. The user clicks the "closed" link, to be returned to this page with the application closed
	 * 	3. 
	 * 
	 */

	// turn on error reporting
	error_reporting(E_ALL);
	ini_set('display_errors', true); 
        date_default_timezone_set('America/Los_Angeles');

	// Pull in the main includes file
	include 'includes/utils.php';
	include 'includes/html_macros.php';

	// Get the current user, if not logged in redirect to the login page.
        [$userName,$isAdmin] = getLoggedinUser();
	if ($userName == "") header("location:login.php");

        if (isset($_GET['applicationID'])) {
		$applicationID = $_GET['applicationID'];
	} else {
	        header('Location: ' . "searchApplications.php", true, 302);	
	}
	$mysqli = DBConnect();
	
	$retString = ((isset($_GET['animalID']))?"viewAnimal.php?animalID=".$_GET['animalID']:((isset($_GET['personID']))?"viewPerson.php?personID=".$_GET['personID']:(isset($_GET['retPage'])?$_GET['retPage'].".php":"searchApplications.php")));

    // Check if we need to open/close an application (GET)
    if (isset($_GET['closed'])) {
        $shouldClose = $_GET['closed'];
    	
        $updateSQL = "update Application set closed = $shouldClose where applicationID = $applicationID;";							
        $result = $mysqli->query($updateSQL);
        if ($mysqli->errno) errorPage($mysqli->errno, $mysqli->error, $updateSQL);
        header('Location: ' . $retString, true, 302);	
    }

	pixie_header("View Application", $userName, "", $isAdmin);

?>

<?php
	$sql =  "SELECT * FROM ApplicationInfo where applicationID = ".$_GET['applicationID'];
	$result = $mysqli->query($sql);
	if ($mysqli->errno) errorPage($mysqli->errno, $mysqli->error, $sql);
	else {
		// we should have just one row, since we are selecting by PK.
	        $row = $result->fetch_array();
		$applicationID = $row['applicationID'];
               	$applicationDate = $row['applicationDate'];
		$species = $row['species'];
		$gender = $row['gender'];
		$minAge = $row['minAge'];
		$maxAge = $row['maxAge'];
		$minWeight = $row['minWeight'];
		$maxWeight = $row['maxWeight'];
		$breed = $row['breed'];
		$minActivityLevel = $row['minActivityLevel'];
		$maxActivityLevel = $row['maxActivityLevel'];
		$needHypo = ($row['needHypo']==0?'No':'Yes');
		$closed = ($row['closed']==0?'No':'Yes');
		$numDogs = $row['numDogs'];
		$numCats = $row['numCats'];
		$numKids = $row['numKids'];
		$personality = $row['personality'];
		$note = $row['note'];
		$personName = $row['firstName'] . " " . $row['lastName'];
		$personID = $row['personID'];
		$rank = $row['rank'];
	}
	$result->close();
?>





	<table id="criteria" width=100%>  
                <tr>
                        <td>    <!-- Column 1 -->
                                <table> <!-- first column of demographic information -->
                                        <?=trd_labelData("Applicant Name", "<a href=viewPerson.php?personID=".$personID.">".$personName."</a>")?>
                                        <?=trd_labelData("Application Date", MySQL2Date($applicationDate))?>
                                        <tr> <!-- Age -->
                                                <td id="leftHand"><b>Age between:</b></td><td id="rightHand" ><?=$minAge?$minAge:0?> and <?=$maxAge?$maxAge:"&infin;"?> years</td>
                                        </tr>
                                        <?=trd_labelData("Desired Breed", $breed)?>

                                        <?=trd_labelData("Species", $species)?>
                                        <?=trd_labelData("Rank", $rank)?>
		                </table>
                        </td>
                        <td>    <!-- Column 2 -->
                                <table>
                                        <tr><td id="leftHand"><b>Activity Level between:</b></td><td><?=$minActivityLevel?$minActivityLevel:0?> and <?=$maxActivityLevel?$maxActivityLevel:"&infin;"?></td></tr>
                                        <?=trd_labelData("Desired Gender", $gender?$gender:"Any")?>
                                        <tr><td id="leftHand"><b>Weight between:</b></td><td><?=$minWeight?$minWeight:0?> and <?=$maxWeight?$maxWeight:"&infin;"?></td></tr>
                                        <?=trd_labelData("Personality", $personality)?>
                                </table>
                        </td>
                        <td>    <!-- Column 3 -->
                                <table>
                                        <?=trd_labelData("Number of children", $numKids)?>
                                        <?=trd_labelData("Number of dogs", $numDogs)?>
                                        <?=trd_labelData("Number of cats", $numCats)?>
                                        <?=trd_labelData("Hypoallergetic needed", $needHypo)?>
                                        <?=trd_labelData("Closed", $closed)?>
                                </table>
                        </td>
                </tr>
                <tr>
                        <td colspan="3"><b>Note: </b><br><?=$note?></td>
                </tr>
        </table>

       <a href="addApplication.php?applicationID=<?=$applicationID?>"><input type="submit" value="Edit" /></a>  <a href="<?=$retString?>">Cancel</a><br><br>


<!-- Show applications -->
<b>Matches:</b>
<table  id="sortable" width="100%">
    <thead>
        <tr>
            <th><span>Animal Name</span></th>
            <th><span>Breed</span></th>
            <th><span>Species</span></th>
            <th><span>Gender</span></th>
            <th><span>Age</span></th>
            <th><span>Location</span></th>
        </tr>
    </thead>
    <tbody>
<?php
	$sql = "CALL matchAnimals(".$_GET['applicationID'].", 0);";
	$result = $mysqli->query($sql);
	if (!$result) errorPage($mysqli->errno, $mysqli->error, $sql);

	// Generate the table
	while($row = $result->fetch_array()) {
?>
	<tr>
		<td><a href="viewAnimal.php?animalID=<?= $row['animalID'] ?>"><?= $row['animalName'] ?>&nbsp;</a></td>
		<td><?= $row['breed'] ?>&nbsp;</td>
		<td><?= ($row['species']=='D'?"Dog":($row['species']=='C'?"Cat":"Other"))?>&nbsp;</td>
		<td><?= ($row['gender']=="M"?"Male":($row['gender']=="F"?"Female":"Other")) ?>&nbsp;</td>
		<td><?= PrettyAge($row['dob'], date('Y-m-d')) ?>&nbsp;</td>
		<td><?= $row['transferName'] ?>&nbsp;</td>
	</tr>
<?php
	}
	
?>
    <tbody>
</table>
Found  <?=$result->num_rows?> <?=($result->num_rows==1?"match":"matches")?>.
<script src="http://code.jquery.com/jquery-1.11.0.min.js" type="text/javascript"></script>
<script type="text/javascript" src="js/jquery.tablesorter.min.js"></script>
<script type="text/javascript" src="js/pixie.js"></script>

<?php 	
		$result->close();
		freeResult($mysqli);
	pixie_footer(); 
?>

