<?php 
	
	/*
	 * main.php
	 * Kimberley Anne Gray
	 * 
	 * Main file that the users lands on after logging in or selecting "Main" from submenu.
	 * 
	 */
	 

	// Pull in the main includes file
	include 'includes/utils.php';
	include 'includes/html_macros.php';
	include 'includes/panels.php';
	
	// turn on error reporting
	error_reporting(E_ALL);
	ini_set('display_errors', true); 

	// Get the current user, if not logged in redirect to the login page.
	$userName = getLoggedinUser();
	if ($userName == "") header("location:login.php");

	// connect to the database, get information on the current animal
	$mysqli = DBConnect();

	// Pull POST variables 
	$isPost = ($_SERVER['REQUEST_METHOD'] == 'POST');
	$start = ($isPost?$_POST['start']:date('m/d/y',strtotime('first day of last month')));
	$end = ($isPost?$_POST['end']:date('m/d/y',strtotime('last day of last month')));
	
	if (!$end) $end = date('m/d/Y');
	if (!$start) $start = date('1/1/2001');
	if (strtotime($start) > strtotime($end)) {
		$temp=$end;
		$end=$start;
		$start=$temp;
	}
	
	// Get averages 
	// get information about the current animal
	$sql =  "SELECT * FROM pixie.TransferHistory ";
	if ($start and $end) $sql .= " WHERE transferDate BETWEEN '".Date2MySQL($start)."' AND '".Date2MySQL($end)."'";
	$sql .= " order by animalID, transferDate;";
	
	$result = $mysqli->query($sql);
	if ($mysqli->errno) errorPage($mysqli->errno, $mysqli->error, $sql);

	$numTrials = 0;
	$trialDays = 0;
	$shelterDays = 0;
	$numShelter = 0;
	$numFails = 0;
	$failedTrialDays = 0;
	$new['animalID']=0;
	$old['animalID']=0;
	
	while ($row = $result->fetch_array()) {
		
		$new['animalID'] = $row['animalID'];
		$new['personID'] = $row['personID'];
		$new['transferTypeID'] = $row['transferTypeID'];
		$new['transferName'] = $row['transferName'];
		$new['transferDate'] = $row['transferDate'];
		
		if ($new['animalID'] == $old['animalID']) {
			
			// Entered into shelter
			if ($new['transferTypeID'] == 1) {
			}
			
			// From shelter to trial				
			if (($old['transferTypeID'] == 1) and ($new['transferName']=="Trial")) {
				$numTrials++;
				$numShelter++;
				$shelterDays += diffDays($new['transferDate'], $old['transferDate']);
			}
			
			// Same-day adoption
			if (($old['transferTypeID'] == 1) and ($new['transferName']=="Adopted")) {
				$numShelter++;
				$shelterDays += diffDays($new['transferDate'], $old['transferDate']);
			}

			// Adoption after trial
			if (($old['transferName']=="Trial") and ($new['transferName']=="Adopted") and ($old['personID']==$new['personID'])) {
				$trialDays += diffDays($new['transferDate'], $old['transferDate']);
			}

			// Adoption after foster -- treat as trial
			if (($old['transferName']=="Foster") and ($new['transferName']=="Adopted") and ($old['personID']==$new['personID'])) {
				$numTrials++;
				$trialDays += diffDays($new['transferDate'], $old['transferDate']);
			}
		
			// Foster into Adoption (different people)
			if (($old['transferName']=="Foster") and ($new['transferName']=="Adopted") and ($old['personID']!=$new['personID'])) {
				$fosterDays += diffDays($new['transferDate'], $old['transferDate']);
			}

			// Foster back to Pixie
			if (($old['transferName'] == "Foster") and ($new['transferTypeID'] == 1)) {
				$fosterDays += diffDays($new['transferDate'], $old['transferDate']);
			}

			// Failed trial (Trial back to pixie)
			if (($old['transferName'] == "Trial") and ($new['transferTypeID']==1)) {
				$numFails++;
				$failedTrialDays += diffDays($new['transferDate'], $old['transferDate']);
			}
			
			// Euthenasia :(		
			if (($old['transferTypeID'] == 1) and ($new['transferName']=="Euthanasia")) {
				$shelterDays += diffDays($new['transferDate'], $old['transferDate']);
			}

		} else {
			// This is a new animal
		}
		
		$old['animalID'] = $new['animalID'];
		$old['personID'] = $new['personID'];
		$old['transferTypeID'] = $new['transferTypeID'];
		$old['transferName'] = $new['transferName'];
		$old['transferDate'] = $new['transferDate'];
	}	

	pixie_header("Main", $userName);
?>
<form action="" method="POST">
	<table>
		<tr>
			<?=td_labelData("Start", $start, "start")?>
			<?=td_labelData("End", $end, "end")?>
			<td><input type="submit" value="Search" /></td>
		</tr>
	</table>
</form>
<hr>
<table id="criteria" width="100%">	
	<tr><td colspan=2>Animal Movement Counts between <?= $start ?> and <?= $end ?></td></tr>
	<tr>
		<td width="50%">
			<table id="tabular" width="100%">
				<tr>
					<th>&nbsp;</th>
					<th>&nbsp;Count&nbsp;</th>
				</tr>
					<?php
						// Get full list of transfers
						$transferTypesSQL = "select * from TransferType;";
						$result = $mysqli->query($transferTypesSQL);
						if ($mysqli->errno) errorPage($mysqli->errno, $mysqli->error, $transferTypesSQL);
						
						while($row = $result->fetch_array()) {
							$transferTypes[] = array (
								'transferTypeID' 	=> $row['transferTypeID'],
								'transferName' 		=> $row['transferName']
							);
						}
						
						foreach ($transferTypes as $transferType) {
							
							$cntSQL = "select count(animalID) as cnt from Transfer " .
								" where transferTypeID = ". $transferType['transferTypeID'] .
								" AND transferDate BETWEEN '".Date2MySQL($start)."' AND '".Date2MySQL($end)."'";

							$result = $mysqli->query($cntSQL);		
							if ($mysqli->errno) errorPage($mysqli->errno, $mysqli->error, $cntSQL);				
							$row = $result->fetch_array();
							echo trd_labelData($transferType['transferName'], $row['cnt']);
							
							$result->close();	
						}
						$sql = "select sum(fee) as cnt from Transfer 
							where transferDate BETWEEN '".Date2MySQL($start)."' AND '".Date2MySQL($end)."'";

						$result = $mysqli->query($sql);
						if ($mysqli->errno) errorPage($mysqli->errno, $mysqli->error, $person_sql);
						else {
							$row = $result->fetch_array();
							echo trd_labelData("Total Fees Collected", ($row['cnt']!=0?"$".$row['cnt']:"None"));			
						}
						$result->close();
					?>
			</table>		
		</td>
		
		<td width="50%">
			<table id="tabular" width="100%">
				<tr>
					<th>&nbsp;</th>
					<th>&nbsp;Count&nbsp;</th>
				</tr>
				<?=trd_labelData("Total Shelter Stays", $shelterDays)?>
				<?=trd_labelData("Average Shelter Stay", sprintf('%d', 	($numShelter>0?($shelterDays/$numShelter):0)))?>
				<?=trd_labelData("Total Trials", $trialDays)?>
				<?=trd_labelData("Average Trial Stay", sprintf('%d', 	($numTrials>0?($trialDays/$numTrials):0)))?>
				<?=trd_labelData("Total Failed Trial Days", $failedTrialDays)?>
				<?=trd_labelData("Average Failed Trial", sprintf('%d', 	($numFails>0?($failedTrialDays/$numFails):0)))?>
			</table>
		</td>

	</tr>
</table>

<hr>
<table id=tabular width="100%">
	<tr><td colspan="6">Upcoming Vaccinations</td></tr>
	<tr>
			<th>Name</th>
			<th>Vaccine</th>
			<th><b>Due</b></th>
			<th>Last Dose</th>
			<th>Note</th>
			<th>&nbsp;</th>
	</tr>
	<tr>
	<?php

		$date=date_create(date('Y-m-d'));
		date_add($date,new DateInterval('P1M'));
		$cutoff = date_format($date,"Y-m-d");

		$sql = "call pixieVaccinations('$cutoff');";
		$result = $mysqli->query($sql);
		if ($mysqli->errno) errorPage($mysqli->errno, $mysqli->error, $person_sql);
		while ($row = $result->fetch_array()) {
	?>
	<tr>
		<td id="rightHand"><a href="viewAnimal.php?animalID=<?=$row['animalID']?>"><?= $row['animalName'] ?></a></td>
		<td id="centerHand"><?= $row['medicationName'] ?></td>			
		<td id="centerHand">&nbsp;<font color ="<?= ($row['nextDose']<date('Y-m-d')?"red":"black") ?>"><b><?= MySQL2Date($row['nextDose']) ?></b></font></td> 
		<td id="centerHand"><?= MySQL2Date($row['startDate']) ?></td>			
		<td id="centerHand" style="white-space: pre-line;">&nbsp;<?= $row['note'] ?></td>			
		<td>
			<a href="viewVaccination.php?animalID=<?=$row['animalID']?>&medicationID=<?=$row['medicationID']?>&startDate=<?=$row['startDate']?>&action=edit&retPage=main">Edit</a>
		</td>
	</tr>
	<?php
		}
		$result->close();
		freeResult($mysqli);
	?>
	</tr>
</table>
	
<hr>
<table id=tabular width="100%">
	<tr><td colspan=6>Upcoming Surgeries:</td></tr>
	<tr>
	  <th>Date</th>
	  <th>Type</th>
	  <th>Name</th>
	  <th>Location</th>
	  <th>Notes</th>
	  <th>&nbsp;</th>
	</tr>
	 <?php

		$lastSurgery = 0;		
		$surgery_sql =  "SELECT * FROM AnimalSurgeries WHERE surgeryDate >= now();";
		$result = $mysqli->query($surgery_sql);
		if (!$result)errorPage($mysqli->errno, $mysqli->error, $surgery_sql);

		// We want to pull all the data into an array, so that we can peek forward
		// to determine how long an animal was at a surgery
		$numRows = $result->num_rows;
		
		while($row = $result->fetch_array()) {
			$surgeryTypeID = $row['surgeryTypeID'];	
			$surgeryType = $row['surgeryType'];	
			$animalID = $row['animalID'];
			$animalName = $row['animalName'];
			$surgeryDate = $row['surgeryDate'];
			$note = $row['note'];
			$personID = $row['personID'];
			$lastName = $row['lastName'];
	?>
	<tr>
		<td><?= MySQL2Date($surgeryDate) ?></td>
		<td><?= $surgeryType ?></td>
		<td>
			<a href=<?= "\"viewAnimal.php?animalID=".$animalID."\"" ?>>
			<?= $animalName ?></a>
		</td>
		<td>
			<a href=<?= "\"viewPerson.php?personID=".$personID."\"" ?>>
			<?= $lastName ?></a>
		</td>
		<td style="white-space: pre-line;"><?= substr($note, 0,300) ?>&nbsp;</td>
		<td>
			<a href="viewSurgery.php?<?=($animalID?"animalID=$animalID&":"")?>surgeryDate=<?=$surgeryDate?>&surgeryTypeID=<?=$surgeryTypeID?>">Edit</a>
		</td>
	</tr>
		<?php
		}
		$result->close();	
		?>
</table>
<hr>

   <b>Current Animals</b><br>					
	<table id="sortable" width=100%>		<!-- Current Animals Table-->
        <thead>
            <tr>
                <th><span>Name</span></th>
                <th><span>Aquired</span></th>
                <th><span>Species</span></th>
            </tr>
        </thead>
        <tbody>					
            <!-- insert data-->
             <?php
                $atHomeSQL = "select * from CurrentTransfer where personID = 1 order by animalName;";
                $result = $mysqli->query($atHomeSQL,  MYSQLI_STORE_RESULT);
                if ($mysqli->error) errorPage($mysqli->errno, $mysqli->error, $atHomeSQL);
                 
                // Generate the table
                while($row = $result->fetch_array()) {
            ?>
            <tr>
                <td class="lalign"><span><a href=<?= "\"viewAnimal.php?animalID=".$row['animalID']."\"" ?>><?= $row['animalName'] ?></a></span></td>
                <td><span><?= MySQL2Date($row['transferDate']) ?></span></td>
                <td><span><?= $row['speciesName'] ?></span></td>
            </tr>
		<?php
			}
			$result->close();	

		?>
        </tbody>
	</table>
    <script src="js/tablesorter/jquery-1.11.0.min.js" type="text/javascript" charset="utf-8"></script>
    <script type="text/javascript" src="js/tablesorter/jquery.tablesorter.min.js"></script>
    <script type="text/javascript" src="js/pixie.js"></script>

<?php 
	pixie_footer();
?>
