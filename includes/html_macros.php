<?php

	function trd_labelData($label, $variable, $controlName = "", $isRequired = 0, $type="txt", $size=15  ) {
		echo "<tr>".td_labelData($label,$variable,$controlName,$isRequired,$type,$size)."</tr>";
	}
	function td_labelData($label, $variable, $controlName = "", $isRequired = 0, $type="txt", $size=15)	{
		if ($controlName) {
			echo "<td id=\"leftHand\">".($isRequired?"<b>":"").($label?"$label":"")."".($isRequired?"*</b>":":")."</td>";
			echo "<td id=\"rightHand\"><input size=\"$size\" type=\"$type\" name=\"$controlName\"  id=\"$controlName\" value=\"$variable\"/></td>";
		} else {
			echo "<td id=\"leftHand\"><b>".($label?"$label:":"")."</b></td>";
			echo "<td id=\"rightHand\">$variable</td>";
		}
	}
	function td_labelChk($label, $controlName, $isChecked=0) {
			echo "<td id=\"leftHand\">$label</b></td>";
			echo "<td><input type=\"checkbox\" name=\"$controlName\"  id=\"$controlName\"  value=\"1\" ".($isChecked?"checked":"")."></td>";
	}
	function trd_labelChk($label, $controlName, $isChecked=0) {
		
		echo "<tr>".td_labelChk($label, $controlName, $isChecked)."</tr>";
	}
	function errorPage($errorNo, $errorStr, $sql="") {
		
		if (!headers_sent()) header ("Location: error.php?error="."Query failed (" . $errorNo  . ") " . $errorStr . "<br>" . $sql);
		else echo ("<br>Query failed (" . $errorNo  . ") " . $errorStr . "<br>" . $sql);
	}	
	function trd_buildOption($label, $tableName, $idName, $labelName, $thisID, $retString, $mysqli, $incBlank=0) {		
		echo "<tr>".td_buildOption($label, $tableName, $idName, $labelName, $thisID, $retString, $mysqli, $incBlank)."</tr>";
	}	
	function trd_buildOptionSQL($label, $tableName, $idName, $labelName, $thisID, $where, $mysqli, $incBlank=0) {		
		echo "<tr>".td_buildOptionSQL($label, $tableName, $idName, $labelName, $thisID, $where, $mysqli, $incBlank)."</tr>";
	}
	function trd_buildCheckboxSQL($label, $tableName, $idName, $labelName, $thisID, $where, $mysqli) {		
		echo "<tr>".td_buildCheckboxSQL($label, $tableName, $idName, $labelName, $thisID, $where, $mysqli)."</tr>";
	}
	function td_buildOptionSQL($label, $tableName, $idName, $labelName, $thisID, $where, $mysqli, $incBlank=0) {
		
		$sql = "SELECT $idName, $labelName FROM $tableName $where;";
		$result = $mysqli->query($sql);
		if (!$result)  errorPage($mysqli->errno, $mysqli->error);
		
?>	
		<td id="leftHand"><?=$label?>: </td>
		<td id="rightHand"><select name=<?=$idName ?> id=<?=$idName ?>>                  
				<?php
				if ($incBlank) echo "<option></option>";
				while($row = $result->fetch_array()) {
				?>
					<option value="<?= $row[$idName] ?>" <?= $thisID==$row[$idName]?"selected":""?>><?= $row[$labelName] ?></option>
				<?php
					}
					$result->close();	
				?> 
			</select>   
		</td>
<?php
	}	

	function td_buildCheckboxSQL($label, $tableName, $idName, $labelName, $thisID, $retString, $mysqli) {
		$sql = "SELECT $idName, $labelName FROM $tableName;";
		$result = $mysqli->query($sql);
		if (!$result)  errorPage($mysqli->errno, $mysqli->error);
?>
		<td id="leftHand"><?=$label?>: </td>
		<td id="rightHand"><table>
				<?php
				while($row = $result->fetch_array()) {
				?>
					<tr><td><input type='checkbox' name='personality[]' value='<?= $row[$idName] ?>' <?= !is_null($thisID) && str_contains($thisID, $row[$idName])?"checked":"" ?> > <?= $row[$labelName] ?></td></tr>
				<?php
				}
				$result->close();	
				?> 
			</table>   
			<a href=<?="editTables.php?tableName=$tableName&$retString" ?>>Edit List</a>   
		</td>
<?php
	}	
	function td_buildOption($label, $tableName, $idName, $labelName, $thisID, $retString, $mysqli, $incBlank=0)	{		
?>	
		<td style="text-align: right;"><?=$label ?>: </td>
		<td style="text-align: left;">
			<select name=<?=$idName ?> id=<?=$idName ?>>                  
				<?= ($incBlank?"<option></option>":"") ?>
				<?php
					$result = $mysqli->query("SELECT * FROM $tableName");
					if (!$result)  errorPage($mysqli->errno, $mysqli->error);
					
					while($row = $result->fetch_array()) {
				?>
						<option value="<?= $row[$idName] ?>" <?= ($thisID==$row[$idName]?"selected":"") ?>>
						<?= $row[$labelName] ?></option>
				<?php
					}
					$result->close();	
				?> 
			</select>   
			<a href=<?="editTables.php?tableName=$tableName&$retString" ?>>Edit List</a>   
		</td>
<?php
	}
	function pixie_header($pageName, $userName = "", $url="", $isAdmin=0) {
	date_default_timezone_set('America/Los_Angeles');
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta content="text/html; charset=ISO-8859-1" http-equiv="content-type">
		<title><?= $pageName ?></title>
		<style type="text/css"></style>
		<link rel="stylesheet" type="text/css" href="css/normalize.css">
		<link rel="stylesheet" type="text/css" href="css/pixie.css">
		<link rel="stylesheet" type="text/css" href="css/responsive.css">
	</head>

	<body>
		<table>
			<tr>
				<!-- Pixie Icon -->
				<td ><center><img width=100 src="./img/AnimalTraxLogo.png"><center></td>

				<!-- Banner title -->
				<td style="vertical-align: bottom;">
				<font size=6><b><center>AnimalTrax Shelter Tracking System</center></b></font>
				<p><font size=5><?= $pageName ?></font></b>
				</td>
				<td style="text-align: right;">
					<?php if ($userName != "") { ?>
					<b>User:</b> <?= $userName ?><br>
					<a href="logout.php">Logout</a> <?php } ?>
				</td>
			</tr>
		
			<tr> <!-- Row 2, column 1: Sidebar menu -->
				<td id="sidebar">
					<font size=4>
						<nav>
							<ul>
								<li><a href="main.php">Main</a></li>
								<li><a href="findAnimal.php">Find Animal</a></li>
								<li><a href="editAnimal.php">Add Animal</a></li>	
								<li><a href="findPerson.php">Find Person</a></li>	
								<li><a href="editPerson.php">Add Person</a></li>	
								<li><a href="searchApplications.php">Applications</a></li>	
								<li><a href="viewAppointments.php">Appointments</a></li>
								<?= $isAdmin?'<li><a href="editUsers.php">Edit Users</a></li>':'' ?>	
							</ul>
						</nav>
					</font>
				</td>
				<td style="background-color: white;" colspan=2>
					<table border="1" style="width: 100%" ><tr><td>
<?php	
	}

	function pixie_footer() {
?>
					</td></tr></table>
				</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
			</tr>
		</table>
	</body>
	</footer>
	<footer>
</html>
<?php
	}
?>

