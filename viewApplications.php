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
	$userName = getLoggedinUser();
	if ($userName == "") header("location:login.php");

	$mysqli = DBConnect();

// Clock if we need to open/close an application
    if (isset($_GET['closed'])) {
        $shouldClose = $_GET['closed'];
        $applicationID = $_GET['applicationID'];
    
        $updateSQL = "update Application set closed = $shouldClose where applicationID = $applicationID;";							
        $result = $mysqli->query($updateSQL);
        if ($mysqli->errno) errorPage($mysqli->errno, $mysqli->error, $updateSQL);
    }


	pixie_header("View Applications", $userName);

?>




<!-- Show applications -->
Open Applications:
<table  id="tabular" width="100%">
	<tr>
		<?=(isset($retPage)?"<th></th>":"")?>
		<th>Date</th>
		<th>Name</th>
		<th>Species</th>
        <th>Rank</th>
        <th>Breed</th>
		<th>&nbsp;</th>
	</tr>

<?php
		$sql = "select * from Application a inner join Person p on p.personID = a.personID where closed=0 order by applicationDate";
		$result = $mysqli->query($sql);
		if (!$result) errorPage($mysqli->errno, $mysqli->error, $sql);

		// Generate the table
		while($row = $result->fetch_array()) {
?>
	<tr>
		<td><?= MySql2Date($row['applicationDate']) ?>&nbsp;</td>
		<td><a href=<?= "\"viewPerson.php?personID=".$row['personID']."\"" ?>><?= ($row['isOrg']?"":$row['firstName']." ") ?><?= $row['lastName'] ?></a>&nbsp;</td>
		<td><?= ($row['species']=='D'?"Dog":"Cat")?>&nbsp;</td>
		<td><?= $row['rank'] ?>&nbsp;</td>
		<td><?= $row['breed'] ?>&nbsp;</td>
		<td>
            <a href="addApplication.php?applicationID=<?=$row['applicationID']?>&personID=<?=$row['personID']?>">Edit</a>
            <a href="viewApplications.php?applicationID=<?=$row['applicationID']?>&closed=1">Close</a>
        </td>
	</tr>
<?php
	}
	
?>
	<tr><td colspan=6>Found  <?=$result->num_rows?> <?=($result->num_rows==1?"application":"applications")?>.</td></tr>
</table>
<?php 	
		$result->close();
?>

<!-- Show closed applications -->
Closed Applications:
<table  id="tabular" width="100%">
	<tr>
		<?=(isset($retPage)?"<th></th>":"")?>
		<th>Date</th>
		<th>Name</th>
		<th>Species</th>
        <th>Rank</th>
        <th>Breed</th>
		<th>&nbsp;</th>
	</tr>

<?php
		$sql = "select * from Application a inner join Person p on p.personID = a.personID where closed=1 order by applicationDate";
		$result = $mysqli->query($sql);
		if (!$result) errorPage($mysqli->errno, $mysqli->error, $sql);

		// Generate the table
		while($row = $result->fetch_array()) {
?>
	<tr>
		<td><?= MySql2Date($row['applicationDate']) ?>&nbsp;</td>
		<td><a href=<?= "\"viewPerson.php?personID=".$row['personID']."\"" ?>><?= ($row['isOrg']?"":$row['firstName']." ") ?><?= $row['lastName'] ?></a>&nbsp;</td>
		<td><?= ($row['species']=='D'?"Dog":"Cat")?>&nbsp;</td>
		<td><?= $row['rank'] ?>&nbsp;</td>
		<td><?= $row['breed'] ?>&nbsp;</td>
		<td>
            <a href="addApplication.php?applicationID=<?=$row['applicationID']?>&personID=<?=$row['personID']?>">Edit</a>
            <a href="viewApplications.php?applicationID=<?=$row['applicationID']?>&closed=0">Open</a>
        </td>
	</tr>
<?php
	}
	
?>
	<tr><td colspan=6>Found  <?=$result->num_rows?> <?=($result->num_rows==1?"application":"applications")?>.</td></tr>
</table>
<?php 	
		$result->close();
	pixie_footer(); 
?>

