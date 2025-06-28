<?php 	

	/*	findAnimal.php
	 * 	Allows a user to search for any animal that is stored within the system
	 * 
	 * 	This page will handle two types of requests:
	 * 	1. The user lands on this page with no POST variables set, in which they are shown 
	 * 		all the animals that currently have a TransferType of "Pixie" (which means that 
	 * 		they are in the shelter.
	 * 	2. The user inserts some search criteria and presses the "search" button, in which case we come in 
	 * 		with POST variables set that should be passed into the "FindAnimal" stored procedure.
	 * 	3. The user clicks the "Clear" button, which should reset the search criteria to the initial (as seen in #1.)
	 * 
	 */
	 
	// turn on error reporting
	error_reporting(E_ALL);
	ini_set('display_errors', true); 
		
	// Pull in the main includes file
	include 'includes/utils.php';
	include 'includes/html_macros.php';
	
	// Get the current user, if not logged in redirect to the login page.
        [$userName,$isAdmin] = getLoggedinUser();
	if ($userName == "") header("location:login.php");
	else pixie_header("Find Animal", $userName, "", $isAdmin);

	// is this a POST if so, grab the POST varibales.
	$isPost = ($_SERVER['REQUEST_METHOD'] == 'POST');
	$name = ($isPost?lbt($_POST['name']):"");
	$microchipNumber = ($isPost?lbt($_POST['microchipNumber']):"");
	$species = ($isPost?$_POST['species']:"");
	$gender = ($isPost?$_POST['gender']:"");
	$onlyAdoptable = $isPost?isset($_POST['onlyAdoptable'])?1:0:1;
	$notFixed = isset($_POST['notFixed']);
	$transferTypeID = ($isPost?intval($_POST['transferTypeID']):"0");	// 1 is Pixie, use as default
	$start = ($isPost?$_POST['start']:''); //date('m/d/y',strtotime('first day of last month')));
	$end = ($isPost?$_POST['end']:''); //date('m/d/y',strtotime('last day of last month')));
	$adoptionStatusID = ($isPost?$_POST['adoptionStatusID']:"");

	if ($start and !$end) $end = date('m/d/Y');
	if (!$start and $end) $start = date('1/1/2001');
	if (strtotime($start) > strtotime($end)) {
		$temp=$end;
		$end=$start;
		$start=$temp;
	}

	// Connect to the DB
	$mysqli = DBConnect();
	
?>
<form action="" method="POST">
	<table id=criteria>
		<tr>
			<?=td_labelData("Name", $name, "name")?>
			<?=td_labelData("Microchip Number", $microchipNumber, "microchipNumber")?>
		</tr>
		<tr>
			<td style="text-align: right;">Species: </td>
			<td style="text-align: left;">
				 <select name=species>
				  <option value=""></option>
				  <option value="D" <?= ($species=='D'?"selected":"") ?>>Dog</option>
				  <option value="C" <?= ($species=='C'?"selected":"") ?>>Cat</option>
				  <option value="O" <?= ($species=='O'?"selected":"") ?>>Other</option>
				</select> 		
			</td>
			<?=td_buildOption("Status", "TransferType", "transferTypeID", "transferName", $transferTypeID, "retPage=findAnimal", $mysqli, true) ?>
		</tr>
		<tr>
			<td style="text-align: right;">Gender: </td>
			<td style="text-align: left;">
				<select name=gender>W
				  <option value=""></option>
				  <option value="F" <?= ($gender=='F'?"selected":"") ?>>Female</option>
				  <option value="M" <?= ($gender=='M'?"selected":"") ?>>Male</option>
				  <option value="O" <?= ($gender=='O'?"selected":"") ?>>Other/Unknown</option>
				</select> 		
			</td>
			<td style="text-align: right;">Status between:</td>
			<td><input size=8 name="start" value="<?=$start?>" type="txt"> and <input size=8 name="end" value="<?=$end?>" type="txt"></td>
		</tr>
		<tr>
			<td></td>
			<td style="text-align: left;">
				<input type="checkbox" name="notFixed" value="1" <?= ($notFixed?"checked":"") ?>>Not Neutered/Spayed
				<br><input type="checkbox" name="onlyAdoptable" value="1" <?= ($onlyAdoptable?"checked":"") ?>>Only Show Adoptable?
			</td>
			<?=td_buildOption("Adoption Status", "AdoptionStatus", "adoptionStatusID", "adoptionStatus", $adoptionStatusID, "retPage=findAnimal", $mysqli, true) ?></tr>
		</tr>
		<tr><td align="right">
				<input type="submit" value="Search"/>
				<input type="submit" value="Clear" action="findAnimal.php" method="GET"/> <! TODO-- need to implement -->
			</td>
		</tr>
	</table>
</form>

<?php
		if ($start or $end or $name or $microchipNumber or $species or $gender or $onlyAdoptable or $notFixed or $transferTypeID or $adoptionStatusID) {
?>

<hr>

<!-- Show search Results -->
<table id="sortable">
    <thead>
        <tr>
            <th><span>Name</span></th>
            <th><span>Species</span></th>
            <th><span>Age</span></th>
            <th><span>Caretaker</span></th>
            <th><span>Status</span></th>
            <th><span>Date</span></th>
            <th><span>Microchip Number</span></th>
            <th><span>Adoptable?</span></th>
            <th><span>Fixed?</span></th>
        </tr>
    </thead>
    <tbody>

<?php 

	if (!($start and $transferTypeID)) { 
		$findSQL = "SELECT * FROM CurrentTransfer WHERE ";
			if ($name) $findSQL.= "(animalName like '%$name%') AND ";
			if ($microchipNumber) $findSQL.= "(microchipNumber like '%$microchipNumber%') AND ";
			if ($species) $findSQL.= "(species = '$species') AND ";
			if ($gender) $findSQL.= "(gender = '$gender') AND ";
			if ($transferTypeID) $findSQL.= "(transferTypeID = '$transferTypeID') AND ";
			if ($onlyAdoptable) $findSQL.= "(adoptable='Yes') AND ";
			if ($notFixed) $findSQL.= "(!isFixed) AND ";
			if ($start) $findSQL .= "(transferDate BETWEEN '".Date2MySQL($start)."' AND '".Date2MySQL($end)."') AND ";
			if ($adoptionStatusID) $findSQL .= "(adoptionStatusID = '$adoptionStatusID') AND ";
		$findSQL .= "(animalID>0) ORDER BY transferDate;";
	} else $findSQL = "CALL FindAnimal('$name', '$microchipNumber', '$species', '$gender', '$transferTypeID', '$adoptionStatusID', '$onlyAdoptable', '$notFixed', '".Date2MySQL($start)."', '".Date2MySQL($end)."')";

	$result = $mysqli->query($findSQL);
	if ($mysqli->errno) errorPage($mysqli->errno, $mysqli->error, $findSQL);
	if ($start) echo "Activity between $start and $end.";

	// Generate the table.  The Animal name is a  link to the viewAnimal page
	// with the animalID passed in the URL.  The Person is also a link 
	// to the viewPerson page (with the personID passed in the URL.)
	while($row = $result->fetch_array()) {
?>
        <tr>
            <td><span><a href=<?= "\"viewAnimal.php?animalID=".$row['animalID']."\"" ?>><?= $row['animalName'] ?></a></span></td>
            <td><span><?= $row['speciesName'] ?></td>
            <td><span><?= prettyAge( $row['estBirthdate'], date("Y-m-d")) ?></span></td>

            <td><span>
                <a href=<?= "\"viewPerson.php?personID=".$row['personID']."\"" ?>>
                    <?= $row['CurrentPerson'] ?>
                </a>&nbsp;
            </span></td>
            <td><span><?= $row['transferName'] ?></span></td>
            <td><span><?= MySQL2Date($row['transferDate']) ?></span></td>
            <td><span><?= $row['microchipNumber'] ?></span></td>
            <td><span><?= $row['Adoptable'] ?></span></td>
            <td><span><?= $row['Fixed'] ?></span></td>
        </tr>
<?php
	}
	
?>
    </tbody>
</table>
Found  <?=$result->num_rows?> <?=($result->num_rows==1?"animal":"animals")?>.

<script src="http://code.jquery.com/jquery-1.11.0.min.js" type="text/javascript"></script>
<script type="text/javascript" src="js/jquery.tablesorter.min.js"></script>
<script type="text/javascript" src="js/pixie.js"></script>

<?php 	
	$result->close();
	}
	else print "<font color='red'>Please enter some criteria above.</font>";
	pixie_footer(); 
?>
