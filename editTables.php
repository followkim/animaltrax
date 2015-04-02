<?php 

	// Pull in includes
	include 'includes/utils.php';
	include 'includes/html_macros.php';

	// turn on error reporting
	error_reporting(E_ALL);
	ini_set('display_errors', true); 
	
	$userName = getLoggedinUser();
	if ($userName == "") header("location:login.php");

	// Pull the possible GET strings
	$action = isset($_GET['action'])? $_GET['action']:"";
	$tableName = isset($_GET['tableName'])? $_GET['tableName']:"";
	$PK = isset($_GET['PK'])? $_GET['PK']:"";

	// These aren't used on this page, but we want to make sure
	// that we get people back to where they came from
	$animalID = isset($_GET['animalID'])? intval($_GET['animalID']):"";
	$personID = isset($_GET['personID'])? intval($_GET['personID']):"";
	$retPage = isset($_GET['retPage'])? $_GET['retPage']:"";
	$retString = ($retPage?"retPage=$retPage&":"") .
		($animalID?"animalID=$animalID&":"") .
		($personID?"personID=$personID&":"");
	$retString = substr($retString, 0, -1);
	
	// connect to the database
	$mysqli = DBConnect();
	
	// Get information on the current selected table
	// Store the field names in the $fieldArray array
	$fieldArray = array();
	$dataArray = array();

	// Get the column names
	$selectSQL = "select * from $tableName;";				
	$result = $mysqli->query($selectSQL);
	if (!$result)  errorPage($mysqli->errno, $mysqli->error, $selectSQL);
	
	while ($finfo = $result->fetch_field()) 
		$fieldArray[] = $finfo->name;
	$result->close();
	
	// Init the information for updating 
	foreach($fieldArray as $thisField) 
			$updateData[$thisField]="";
	
	// is this a POST?  if so, then we need to grab the posted values and write them to the DB.
	$isPost = ($_SERVER['REQUEST_METHOD'] == 'POST');
	
	if ($isPost) {
		if ($action == "edit") {
			$sql = "update $tableName SET ";
			for ($i = 1; $i < count($fieldArray); $i++) 
				$sql = $sql . " ".$fieldArray[$i]."='".$_POST[$fieldArray[$i]]."',";
			$sql = substr($sql, 0, -1) . "  WHERE " . $fieldArray[0] ."=". $_POST[$fieldArray[0]];
			echo $sql."<br>";
		} else {
			$sql = "INSERT INTO $tableName (";
			for ($i = 1; $i < count($fieldArray); $i++) 
				$sql = $sql . $fieldArray[$i].",";
			$sql = substr($sql, 0, -1) . ") VALUES (";
			for ($i = 1; $i < count($fieldArray); $i++) 
				$sql = $sql . "'".$_POST[$fieldArray[$i]]."',";
			$sql = substr($sql, 0, -1) . ");";
		}	
		$mysqli->query($sql);
		if ($mysqli->errno) errorPage($mysqli->errno, $mysqli->error, $sql);
	}

	// GET: action=delete
	if ($action == "delete") {	
		$sql = "DELETE from $tableName WHERE $fieldArray[0]=$PK";
		$mysqli->query($sql);
		if ($mysqli->errno)  errorPage($mysqli->errno, $mysqli->error, $sql);
		$action="";	
	}  
	
	// GET: action=edit		(Get the current values to update)
	else if ($action=="edit") {
		$sql = "select * from $tableName WHERE $fieldArray[0]=$PK";
		$result = $mysqli->query($sql);
		if ($mysqli->errno)  errorPage($mysqli->errno, $mysqli->error, $sql);

		$row = $result->fetch_array();
		$updateData = array();
		foreach($fieldArray as $thisField) {
			$updateData[$thisField]=$row[$thisField];
		}
		$result->close();

	} 

	// Get updated data
	$selectSQL = "select * from $tableName;";				
	$result = $mysqli->query($selectSQL);
	if ($mysqli->errno) errorPage($mysqli->errno, $mysqli->error, $selectSQL);
	while ($dataArray[] = $result->fetch_array());
	$result->close();


	pixie_header("Edit Tables".($tableName?": ".$tableName:""), $userName);

 ?>
<table  border="1" cellspacing="0" cellpadding="0" width="100%">
	<tr>
	<?php
		foreach ($fieldArray as $fieldName) {		
			print "<th>".$fieldName."</th>";
		}
	?>
		<th>&nbsp;</th>
	</tr>

	<?php
		foreach ($dataArray as $thisData) {
	?>
	<tr>
	<?php
			if (($thisData[0]>0) OR ($thisData[0]!='')) {
				foreach ($fieldArray as $thisField) {
					print "<td>&nbsp;".$thisData[$thisField]."</td>";
				}
	?>
				<td>
					<a href="editTables.php?tableName=<?=$tableName?>&PK=<?=$thisData[$fieldArray[0]]?>&action=edit&<?=$retString?>">Edit</a>
					<a href="editTables.php?tableName=<?=$tableName?>&PK=<?=$thisData[$fieldArray[0]]?>&action=delete&<?=$retString?>">Delete</a>
				</td>
			</tr>
	<?php
			}
		}
	?>
</table>

<hr>


<?= ($action=="edit"?"Edit":"Add") ?> Row:
 <form action="" method="POST">
	<input hidden type="txt" name="<?=$fieldArray[0]?>" value="<?=$updateData[$fieldArray[0]]?>" />
	<table>
		<?php
			for ($i = 1; $i < count($fieldArray); $i++) {
		?>
			<tr><td style="text-align: right;"><?=$fieldArray[$i]?>: </td><td><input type="txt" name="<?=$fieldArray[$i]?>" value="<?=$updateData[$fieldArray[$i]]?>" /></td></tr>
		<?php
		}
		?>
	</table>
	<input hidden type="txt" name="action" value="<?= $action ?>"/>
	<input type="submit" value="<?= ($action=="edit"?"Update":"Add") ?> Row" /> 
	<TODOinput type="submit" value="Cancel (not working)" formaction="<?="viewVaccination.php?animalID=$animalID"?>" /> 
	<a href="<?=$retPage?>.php?animalID=<?= $animalID ?>">Cancel</a>
	<?php if ($action) { ?><a href="editTables.php?tableName=<?=$tableName?>&retPage=<?=$retPage?>&animalID=<?= $animalID ?>">Add Row</a> <?php } ?>

</form>
<br><?php if ($retPage) { ?><a href="<?=$retPage?>.php?&<?= $retString ?>">Return to <?=$retPage ?></a> <?php } ?>
<?php pixie_footer(); ?>

