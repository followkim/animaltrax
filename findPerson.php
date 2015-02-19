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
	$userName = getLoggedinUser();
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

	pixie_header("Find Person", $userName);

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
<table  id="tabular" width="100%">
	<tr><td colspan=6>People:</td></tr>
	<tr>
		<?=(isset($retPage)?"<th></th>":"")?>
		<th>Name</th>
		<th>Address</th>
		<th>City</th>
		<th>Email</th>
		<th>Shelter?</th>
	</tr>

<?php
		$findPersonSQL = "CALL FindPerson('".lbt($name)."', '".lbt($email)."', '".lbt($telephone)."', $positionTypeID, ".($isOrg).")";
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
		<td><a href=<?= "\"viewPerson.php?personID=".$row['personID']."\"" ?>><?= ($row['isOrg']?"":$row['firstName']." ") ?><?= $row['lastName'] ?></a>&nbsp;</td>
		<td><?= $row['address1'] ?>&nbsp;</td>
		<td><?= $row['city'] ?>&nbsp;</td>
		<td><?= $row['email'] ?>&nbsp;</td>
		<td><?= $row['isOrg']?"Yes":"No" ?>&nbsp;</td>
	</tr>
<?php
	}
	
?>
	<tr><td colspan=6>Found  <?=$result->num_rows?> <?=($result->num_rows==1?"person":"people")?>.</td></tr>
</table>
<?php 	
		$result->close();
	}
	else print "<font color='red'>Please enter some criteria above.</font>";
	pixie_footer(); 
?>

