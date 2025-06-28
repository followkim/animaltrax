<?php 	
	/*	findPerson.php
	 * 	Allows a user to search for any person that is stored within the system
	 * 
	 * 	This page will handle three types of requests:
	 * 	1. The user lands on this page with no POST variables set.
	 * 	2. The user inserts some search criteria and presses the "search" button, in which case we come in 
	 * 		with POST variables set that should be passed into the "FindPerson" stored procedure.
	 * 	3. The user clicks the "Clear" button, which should reset the search criteria to the initial (as seen in #1.)
	 * 
	 * 	There is also a special case where the user is redirected here to find a person that an animal is 
	 * 	being transfered to.  In this case, the URL will contain an animalID an action (page) that should be returned to.
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

	// is this a POST if so, grab the POST varibales.
	$isPost = ($_SERVER['REQUEST_METHOD'] == 'POST');
	$name = $isPost?$_POST['name']:'';
	$email = $isPost?$_POST['email']:'';
	$telephone = $isPost?$_POST['telephone']:'';
	$positionTypeID = $isPost?intval($_POST['positionTypeID']):0;
	$isOrg = (isset($_POST['isOrg'])?1:0);
	
	// We might have been redirected to this page from addTransfer.php or viewSurgery
	// Determine retPage
	if (isset($_GET['retPage'])) {
		$retPage = $_GET['retPage'].".php?";
		if (isset($_GET['animalID'])) $retPage .= "animalID=".$_GET['animalID']."&";
		if (isset($_GET['surgeryDate'])) $retPage .= "surgeryDate=".$_GET['surgeryDate']."&";
		if (isset($_GET['surgeryTypeID'])) $retPage .= "surgeryTypeID=".$_GET['surgeryTypeID']."&";
		if (isset($_GET['action'])) $retPage .= "action=".$_GET['action']."&";
	}
	$mysqli = DBConnect();

	pixie_header("Find Person", $userName, "", $isAdmin);

?>
<table id=criteria width="100%">
	<form action="" method="POST">
		<tr>
			<?=td_labelData("Name", $name, "name")?>
			<?=td_labelData("Email", $email, "email")?>
		</tr>
		<tr>
			<?=td_labelData("Phone (any)", $telephone, "telephone")?>
			<td  style="text-align: right;">Position: </td>
			<td>
				<select name=positionTypeID>
					<option value=""></option>
					<?php
						$result = $mysqli->query("SELECT * FROM PositionType");
						if (!$result)  errorPage($mysqli->errno, $mysqli->error);
					
						while($row = $result->fetch_array()) {
							echo "<option value=\"".$row['positionTypeID']."\">".$row['positionName']."</option>";
						}
						$result->close();	
					?> 
				</select>   
			</td>		
		</tr>
		<tr>
			<?=td_labelChk("Shelters only?", "isOrg", $isOrg)?>
			<td></td>
		</tr>
		<tr>
			<td colspan=2></td>
			<td align="right" colspan="5">
				<input type="submit" value="Search" />
				<input type="submit" value="Clear" action="findPerson.php" method="GET"/> <! TODO-- need to implement -->
				<?php if (isset($retPage)) { ?><a href="<?= $retPage ?>">Return</a><?php } ?>
			</td>
		</tr>
	</form>
</table>

<?php
		if ($name or $email or $telephone or $isOrg or $positionTypeID) {
?>
<hr>

<!-- Show search Results -->
<table  id="sortable" width="100%">
    <thead>
        <tr>
            <?=(isset($retPage)?"<th></th>":"")?>
            <th><span>First</span></th>
            <th><span>Last</span></th>
            <th><span>Address</span></th>
            <th><span>City</span></th>
            <th><span>Email</span></th>
            <th><span>Shelter?</span></th>
        </tr>
    </thead>
    <tbody>
<?php
        $findName = str_replace(" ", "%", $name);
		$findPersonSQL = "CALL FindPerson('".lbt($findName)."', '".lbt($email)."', '".lbt($telephone)."', $positionTypeID, ".($isOrg).")";
		$result = $mysqli->query($findPersonSQL);
		if (!$result) errorPage($mysqli->errno, $mysqli->error, $findPersonSQL);

		// Generate the table
		while($row = $result->fetch_array()) {
?>
	<tr>
		<?php
			if (isset($retPage)) { 
		?>				
				<td><a href="<?=$retPage."personID=".$row['personID']?>">Select</a></td>
		<?php	
			} 
		?>
		<td><a href=<?= "\"viewPerson.php?personID=".$row['personID']."\"" ?>><?=$row['firstName']?></a>&nbsp;</td>
		<td><a href=<?= "\"viewPerson.php?personID=".$row['personID']."\"" ?>><?= $row['lastName']?></a>&nbsp;</td>
		<td><?= $row['address1'] ?>&nbsp;</td>
		<td><?= $row['city'] ?>&nbsp;</td>
		<td><?= $row['email'] ?>&nbsp;</td>
		<td><?= $row['isOrg']?"Yes":"No" ?>&nbsp;</td>
	</tr>
<?php
	}
	
?>
    </tbody>
</table>
Found  <?=$result->num_rows?> <?=($result->num_rows==1?"person":"people")?>.
<script src="http://code.jquery.com/jquery-1.11.0.min.js" type="text/javascript"></script>
<script type="text/javascript" src="js/jquery.tablesorter.min.js"></script>
<script type="text/javascript" src="js/pixie.js"></script>

<?php 	
		$result->close();
	}
	else print "<font color='red'>Please enter some criteria above.</font>";
	pixie_footer(); 
?>

