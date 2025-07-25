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

	// Pull in the main includes file
	include 'includes/utils.php';
	include 'includes/html_macros.php';

	// Get the current user, if not logged in redirect to the login page.
        [$userName,$isAdmin] = getLoggedinUser();
	if ($userName == "") header("location:login.php");

	$mysqli = DBConnect();

	// is this a POST if so, grab the POST varibales.
	$isPost = ($_SERVER['REQUEST_METHOD'] == 'POST');
	$name = $isPost?$_POST['name']:'';
	$species = $isPost?$_POST['species']:'';
	$breed = $isPost?$_POST['breed']:'';
	$showClosed = (isset($_POST['showClosed'])?1:0);

    // Check if we need to open/close an application (GET)
    if (isset($_GET['closed'])) {
        $shouldClose = $_GET['closed'];
        $applicationID = $_GET['applicationID'];
    
        $updateSQL = "update Application set closed = $shouldClose where applicationID = $applicationID;";							
        $result = $mysqli->query($updateSQL);
        if ($mysqli->errno) errorPage($mysqli->errno, $mysqli->error, $updateSQL);
        header('Location: ' . "viewApplications.php", true, 302);	
    }


	pixie_header("View Applications", $userName, "", $isAdmin);

?>

<form action="" method="POST">
	<table id=criteria>
		<?=trd_labelData("Name of applicant", $name, "name")?>
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
		</tr>
		<?=trd_labelData("Breed", $breed, "breed")?>
        <?=trd_labelChk("Show Closed?", "showClosed", $showClosed)?>
		<tr>
            <td align="right">
				<input type="submit" value="Search"/>
				<input type="submit" value="Clear" action="viewApplications.php" method="GET"/> <! TODO-- need to implement -->
			</td>
		</tr>
	</table>
</form>


<!-- Show applications -->
Open Applications:
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
            <th>&nbsp;</th>
        </tr>
    </thead>
    <tbody>
<?php
        $findName = str_replace(" ", "%", $name);
		$sql = "CALL FindApplication('".lbt($findName)."', '".lbt($breed)."', '".lbt($species)."', ".($showClosed).")";
		$result = $mysqli->query($sql);
		if (!$result) errorPage($mysqli->errno, $mysqli->error, $sql);

		// Generate the table
		while($row = $result->fetch_array()) {
?>
	<tr>
		<td><?= MySql2Date($row['applicationDate']) ?></td>
		<td><a href=<?= "\"viewPerson.php?personID=".$row['personID']."\"" ?>><?=$row['firstName']." ".$row['lastName'] ?></a>&nbsp;</td>
		<td><?= ($row['species']=='D'?"Dog":"Cat")?>&nbsp;</td>
		<td><?= $row['rank'] ?>&nbsp;</td>
		<td><?= $row['breed'] ?>&nbsp;</td>
		<td><?= $row['note'] ?>&nbsp;</td>
		<td><a href="addApplication.php?applicationID=<?=$row['applicationID']?>&personID=<?=$row['personID']?>">Edit</td>
        <td><a href="viewApplications.php?applicationID=<?=$row['applicationID']?>&closed=<?=$row['closed']?0:1?>"><?=$row['closed']?"Open":"Close"?></a></td>
	</tr>
<?php
	}
	
?>
    <tbody>
</table>
Found  <?=$result->num_rows?> <?=($result->num_rows==1?"application":"applications")?>.
<script src="http://code.jquery.com/jquery-1.11.0.min.js" type="text/javascript"></script>
<script type="text/javascript" src="js/jquery.tablesorter.min.js"></script>
<script type="text/javascript" src="js/pixie.js"></script>

<?php 	
		$result->close();
	pixie_footer(); 
?>

