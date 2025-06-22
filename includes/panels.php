<?php
    function transferPanel($animalID, $mysqli) {
?>
	<table id="tabular" width="100%"> 				<!-- Transfer table -->
		<tr><td colspan="7"><b>Movement History</b></td></tr>	
		<tr>
		  <th>Date</th>
		  <th>Name</th>
		  <th>Type</th>
		  <th>Duration</th>
		  <th>Fee</th>
		  <th>Note</th>
		  <th>&nbsp;</th>
		</tr>
		<?php
			$lastTransfer = 0;	// Init lastTransfer
			$transfer_sql =   "SELECT * FROM TransferHistory where animalID = $animalID";

			$result = $mysqli->query($transfer_sql);
			if (!$result) errorPage($mysqli->errno, $mysqli->error, $transfer_sql);
			
			// We want to pull all the data into an array, so that we can peek forward
			// to determine how long an animal was at a transfer
			$numRows = $result->num_rows;
			
			while($row = $result->fetch_array()) {
				$transferArray[] = array(
					'Name' => $row['Name'],			
					'personID' => $row['personID'],
					'transferName' => $row['transferName'],
					'transferDate' => $row['transferDate'],
					'fee' => $row['fee'],
					'note' => $row['note']
				);
			}
			for ($i = 0; $i < $numRows; $i++)
			{
				if ($i < ($numRows - 1))
					$nextTransfer = $transferArray[$i+1]['transferDate'];
				else $nextTransfer = date("m/d/y");
		?>
		<tr>
			<td><?= MySQL2Date($transferArray[$i]['transferDate']) ?></td>
			<td> 
				<a href=<?= "\"viewPerson.php?personID=".$transferArray[$i]['personID']."\"" ?>>
				<?= $transferArray[$i]['Name'] ?></a>
			</td>
			<td><?= $transferArray[$i]['transferName'] ?>&nbsp;</td>
			<td><?= $nextTransfer?prettyAge($transferArray[$i]['transferDate'], $nextTransfer ,false):"&nbsp;"?>&nbsp;</td>
			<td><?= $transferArray[$i]['fee']>0?"$".$transferArray[$i]['fee']:"&nbsp;" ?></td>
			<td><?= $transferArray[$i]['note'] ?>&nbsp;</td>
			<td>
				<a href="<?= "addTransfer.php?animalID=$animalID&transferDate=".$transferArray[$i]['transferDate']."&personID=".$transferArray[$i]['personID']."&action=delete&retPage=viewAnimal" ?>">Delete</a>
				<a href="<?= "addTransfer.php?animalID=$animalID&transferDate=".$transferArray[$i]['transferDate']."&personID=".$transferArray[$i]['personID']."&action=edit&retPage=viewAnimal" ?>">Edit</a>							
			</td>
		</tr>
		<?php
			}	
		?>
		<tr>
			<td colspan="7"><a href="<?="addTransfer.php?animalID=$animalID"?>" />Add/Edit History</a></td>
		</tr>
	</table>	
<?php
}

function vitalsPanel ($animalID, $species, $mysqli) {

?>

	<table id="tabular" width="100%">		
		<tr><td colspan="5"><b>Vital Signs</b></td></tr>
		<tr>
			<th>Date</th>	<!-- First column for the date -->
			<?php
				// Get a list of all the different possible VS for this animal
				$sql = "select * FROM VitalSignType WHERE species='' or species='".$species[0]."'";
				$result = $mysqli->query($sql);
				if ($mysqli->errno) errorPage($mysqli->errno, $mysqli->error, $sql);
				while ($row=$result->fetch_array()) {
					$vitalList[] =$row['vitalSignTypeID'];
					echo "<th><center>".$row['vitalSignShortName']."</center></th>";
				}
				$result->close();
			?>
		</tr>
		<?php
			// Pull all vital information, place in date buckets
			$sql = "SELECT * FROM VitalSign WHERE animalID='$animalID' ORDER BY vitalDateTime";
			$result = $mysqli->query($sql);
			if ($mysqli->errno) errorPage($mysqli->errno, $mysqli->error, $sql);
			$lastDate = 0; 
			$i=-1;
			while ($row=$result->fetch_array()) {
				if ($lastDate != MySQL2Date($row['vitalDateTime'])) {
					$lastDate = MySQL2Date($row['vitalDateTime']);
					$i++;
				}
				$vitalDates[$i]['date']=MySQL2Date($row['vitalDateTime']);
				$vitalDates[$i][$row['vitalSignTypeID']]=$row['vitalValue'];
			}
			$result->close();

			if (isset($vitalDates)) foreach ($vitalDates as $vitalDate) {
				echo "<tr><td>".$vitalDate['date']."</td>";
				foreach ($vitalList as $vital) {
					if (isset($vitalDate[$vital])) echo "<td><center>".$vitalDate[$vital]."</center></td>";
					else echo "<td>&nbsp;</td>";
				}						
				echo "</tr>";
			}
		?>
		</tr>
		<tr>
			<td colspan="5"><a href=<?="viewVitals.php?animalID=$animalID"?> />Add/Edit Vital Signs</a></td>
		</tr>
	</table>	

<?php
}

function filesPanel($id, $who, $mysqli) {

?>

	<table  id="tabular" width="100%"> 					
		<tr><td style="vertical-align: top; width: 50%;" colspan="3"><b>Files</b></td></tr>
		<tr>
		  <th>Name</th>
		  <th>Uploaded</th>
		  <th>&nbsp;</th>
		</tr>
		<?php 
			$lastTransfer = 0;	// Init lastTransfer
			if ($who == 'A') $sql =   "SELECT * FROM File where animalID = $id";
			else if ($who == 'P') $sql =   "SELECT * FROM File where personID = $id";

			$result = $mysqli->query($sql);
			if ($mysqli->errno) errorPage($mysqli->errno, $mysqli->error, $sql);
								
			while($row = $result->fetch_array()) {
				$fileName = $row['fileName'];
				$fileID = $row['fileID'];
				$dateUploaded = $row['dateUploaded'];
				$fileURL = $row['fileURL'];
		?>
		<tr>
			<td><a href="<?= $fileURL ?>" target="_blank"><?= $fileName ?></a></td>
			<td><?= MySQL2Date($dateUploaded) ?></td>
			<td>
				<a href="<?= $fileURL ?>" target="_blank">Download</a>
				<a href="<?= ($who=='A'?"viewAnimal":"viewPerson")?>.php?<?= ($who=='A'?"animalID":"personID")?>=<?=$id?>&fileID=<?=$fileID?>">Delete</a>
			</td>
		</tr>
		<?php 
			}
		?>
		<tr>
			<td colspan="3">
				<form action="<?= ($who=='A'?"viewAnimal":"viewPerson")?>.php?<?= ($who=='A'?"animalID":"personID")?>=<?=$id?>" method="post" enctype="multipart/form-data">
					Select file to upload:
					<input type="file" name="fileToUpload" id="fileToUpload">
					<input type="submit" value="Upload File" name="submit">
				</form>					
			</td>
		</tr>
	</table> 	
<?php

}

function VaccinationPanel ($animalID, $species, $mysqli)
{

?>

	<table id="tabular" width="100%"> 	
	<tr><td colspan="4"><b>Vaccination History</b></td></tr>
	<tr>
	  <th width="1">Name</th>
	  <th width="1">Doses</th>
	  <th >Dates</th>
	  <th width="1">Next Due</th>
	</tr>
	<?php

		// Get full list of vaccinations, stored in $vaccList
		$vaccListSQL = "select * from Medication where isVaccination = 1 and (species='' or species='".$species[0]."')";
		$vaccList = $mysqli->query($vaccListSQL);
		if (!$vaccList) errorPage($mysqli->errno, $mysqli->error, $vaccListSQL);

		while($vaccRows = $vaccList->fetch_array()) {
			$localVaccList[] = array(
				'medicationID' 	=> $vaccRows['medicationID'],
				'medicationName'=> $vaccRows['medicationName']
			);
		}
		$vaccList->close();
								
		foreach ($localVaccList as $currentMedication) {
			$thisMedicationID = $currentMedication['medicationID'];
			$thisMedicationName = $currentMedication['medicationName'];
			
			$thisVaccSQL = "SELECT * FROM Prescription p 
							WHERE animalID = $animalID and medicationID = $thisMedicationID 
							ORDER by startdate;";
		
			$thisVacc = $mysqli->query($thisVaccSQL);		
			if ($mysqli->error) errorPage($mysqli->errno, $mysqli->error, $thisVaccSQL);
			
			// Loops through the set, and add the dates together in a single string
			$vaccDateStr = '';
			$numDoses = 0;
			$nextDose = '';
			
			while($vaccRow = $thisVacc->fetch_array()) {
				$numDoses++;
				$nextDose = ($vaccRow['nextDose']?MySQL2Date($vaccRow['nextDose']):"");
				$vaccDateStr = $vaccDateStr . MySQL2Date($vaccRow['startDate']) . ", ";
			}
			$thisVacc->close();	
	?>
	<tr>
		<td style="text-align: right;"><?= $thisMedicationName ?>&nbsp;</td>
		<td style="text-align: center;"><?= $numDoses ?>&nbsp;</td>
		<td style="text-align: left;"><?= substr($vaccDateStr, 0, -2) ?>&nbsp;</td>
		<td style="text-align: center;">
            <font <?= (diffDays(date('m/d/y'), $nextDose, false)<0?"color=\"red\"":"color=\"black\"") ?> >
                <?= $nextDose ?>&nbsp;
            </font>
        </td>
	</tr>
<?php
	}
?>
	<tr><td colspan="5"><a href=<?="viewVaccination.php?animalID=$animalID"?>>Add/Edit Vaccinations</a></td></tr>
</table>

<?php
}

function animalPanel($animalID, $mysqli) {
	
	$sql =  "SELECT * FROM AnimalInfo where animalID = $animalID";
	$result = $mysqli->query($sql);
	if ($mysqli->errno) errorPage($mysqli->errno, $mysqli->error, $sql);
	else {
		$row = $result->fetch_array();		
		$animalName = $row['animalName'];
		$species = $row['species'];
		$estBirthdate = $row['estBirthdate'];
		$url = $row['url'];
	}
	$result->close();

?>
	<table width=100%>
		<tr>
			<td width="1"><img src="<?= ($url==""?"img/$species.jpg":$url) ?>" height="50px"></img></td>
			<td style="white-space: nowrap; text-align: left;">
				Name: <?=$animalName?>
				<br><font size=small><?=$species?></font>
			</td>
		</tr>
	</table>
<?php
}


function testPanel($animalID, $species, $mysqli) {
?>
	<table id=tabular width="100%">
		<tr><td colspan="5"><b>Completed Tests</b></td></tr>
		<tr>
		  <th width="150px">Test</th>
		  <th width="150px">Date</th>
		  <th width="150px">Value</th>
		  <th>Note</th>
		  <th width="100px">&nbsp;</th>
		</tr>
		<?php
			$sql = "select * FROM TestView WHERE animalID=$animalID ORDER BY testDate";
			$result = $mysqli->query($sql);
			if ($mysqli->errno) errorPage($mysqli->errno, $mysqli->error, $sql);
			while ($row=$result->fetch_array()) {
		?>
		<tr>
			<td><?= $row['testName']?></td>
			<td><?= MySQL2Date($row['testDate']) ?></td>
			<td><?= $row['testResult']?>&nbsp;</font></td>
			<td><?= $row['note'] ?>&nbsp;</td>
			<td>
				<a href="<?= "viewTests.php?animalID=$animalID&testTypeID=".$row['testTypeID']."&testDate=".$row['testDate']."&action=edit" ?>">Edit</a>							
				<a href="<?= "viewTests.php?animalID=$animalID&testTypeID=".$row['testTypeID']."&testDate=".$row['testDate']."&retPage=viewAnimal&action=delete" ?>">Delete</a>							
			</td>
		</tr>
		<?php
			}
			$result->close();
		?>
	<tr><td colspan="5"><a href=<?="viewTests.php?animalID=$animalID"?>>Add/Edit Tests</a></td></tr>
	</table>
<?php
}


function applicationPanel($personID, $mysqli) {

?>	
	<table  id="tabular" width="100%"> 					
		<tr><td style="vertical-align: top; width: 100%;" colspan="6"><b>Applications</b></td></tr>
		<tr>
		  <th>Date</th>
		  <th>Rank</th>
		  <th>Species</th>
		  <th>Name</th>
		  <th>Matches</th>
		  <th>&nbsp;</th>
		</tr>
		<?php 
			$application = array();
			$sql =   "SELECT * FROM Application where personID = $personID order by applicationDate";
			$result = $mysqli->query($sql);
			if ($mysqli->errno) errorPage($mysqli->errno, $mysqli->error, $sql);
								

			while($row = $result->fetch_array()) {
				$application[]  = array(
					'applicationDate' 	=> $row['applicationDate'],
					'species' 			=> $row['species'],
					'breed' 			=> $row['breed'],
					'applicationID' 	=> $row['applicationID'],
					'personID' 			=> $row['personID'],
					'closed' 			=> $row['closed'],
					'rank' 			    => $row['rank']
				);
			}
			$result->close();
			foreach ($application as $thisApplication) {
		?>
		<tr>
			<td><?= MySQL2Date($thisApplication['applicationDate']) ?></td>
			<td><?= $thisApplication['rank'] ?></td>
			<td><?= ($thisApplication['species']=='C'?"Cat":($thisApplication['species']=='D'?"Dog":"Error")) ?></td>
			<td><?= $thisApplication['breed'] ?></td>
			<td>
				<?php
					if ($thisApplication['closed']) {
                        print "<i>closed</i>";
                    } else {
                        $sql = "call matchAnimals(".$thisApplication['applicationID'].", 0);";
                        $result = $mysqli->query($sql);
                        if ($mysqli->errno) errorPage($mysqli->errno, $mysqli->error, $sql);
                        else {
                            while($row = $result->fetch_array()) { ?>
                                <a href="viewAnimal.php?animalID=<?=$row['animalID']?>"><?=$row['animalName']?></a><br>                                
                            <?php }
                        	$result->close();
                            freeResult($mysqli);
                        }
                    }
				?>
			</td>
			<td>
				<a href="addApplication.php?applicationID=<?= $thisApplication['applicationID'] ?>&action=edit&personID=<?= $thisApplication['personID'] ?>">Edit</a>
				<?php 
                    $thisPage = basename($_SERVER['PHP_SELF']);
                    if ($thisApplication['closed']==0) print "<a href=\"".$thisPage."?applicationID=".$thisApplication['applicationID']."&action=close&personID=".$thisApplication['personID']."\">Close</a>";
                    else print "<a href=\"".$thisPage."?applicationID=".$thisApplication['applicationID']."&action=open&personID=".$thisApplication['personID']."\">Open</a>";
                ?>
			</td>
		</tr>
		<?php 
			}
		?>
		<tr>
			<td colspan=6><a href="addApplication.php?personID=<?= $personID ?>">Add New Application</a></td>
		</tr>
	</table> 	
<?php

}


function matchesPanel($animalID, $mysqli) {

?>

	<table  id="tabular" width="100%"> 					
		<tr><td style="vertical-align: top; width: 100%;" colspan="5"><b>Possible Matches</b></td></tr>
		<tr>
		  <th>Date</th>
		  <th>Name</th>
		  <th>Breed</th>
		  <th>&nbsp;</th>
		</tr>
		<?php
			$sql = "call matchAnimals(0, $animalID);";
			$result = $mysqli->query($sql);
			if ($mysqli->errno) errorPage($mysqli->errno, $mysqli->error, $sql);
			else {
				while($row = $result->fetch_array()) {
		?>
		<tr>
			<td id=centerHand><?=MySQL2Date($row['applicationDate'])?></td>
			<td id=rightHand><a href="viewPerson.php?personID=<?=$row['personID']?>"><?=$row['firstName']?> <?=$row['lastName']?></a></td>
			<td id=rightHand><?=$row['breed']?></td>
			<td id=rightHand><a href="addApplication.php?applicationID=<?=$row['applicationID']?>&personID=<?=$row['personID']?>&animalID=<?=$animalID?>">Edit/View</a></td>
		</tr>
		<?php
				}
				$result->close();
				freeResult($mysqli);
			}
		?>
	</table> 	
<?php
}

function matchesPanelMain($mysqli) {

?>

	<table  id="tabular" width="100%"> 					
		<tr><td style="vertical-align: top; width: 100%;" colspan="5"><b>Possible Matches</b></td></tr>
		<tr>
		  <th>Date</th>
		  <th>Animal</th>
		  <th>Potential Owner</th>
		  <th>&nbsp;</th>
		</tr>
		<?php
			$sql = "call matchAnimals(0, 0);";
			$result = $mysqli->query($sql);
			if ($mysqli->errno) errorPage($mysqli->errno, $mysqli->error, $sql);
			else {
				while($row = $result->fetch_array()) {
		?>
		<tr>
			<td id=centerHand><?=MySQL2Date($row['applicationDate'])?></td>
			<td id=rightHand><a href="viewAnimal.php?animalID=<?=$row['animalID']?>"><?=$row['animalName']?></a></td>
			<td id=rightHand><a href="viewPerson.php?personID=<?=$row['personID']?>"><?=$row['firstName']?> <?=$row['lastName']?></a></td>
			<td id=rightHand><a href="addApplication.php?applicationID=<?=$row['applicationID']?>&personID=<?=$row['personID']?>&animalID=<?=$row['animalID']?>">Edit/View</a></td>
		</tr>
		<?php
				}
				$result->close();
				freeResult($mysqli);
			}
		?>
	</table> 	
<?php

}

function currentAnimalsPanel($personID, $mysqli) {
?>
    <b>Current Animals</b><br>					
	<table id="sortable" width=100%>		<!-- Current Animals Table-->
        <thead>
            <tr>
                <th><span>Name</span></th>
                <th><span>Aquired</span></th>
                <th><span>Species</span></th>
                <th><span>Status</span></th>
            </tr>
        </thead>
        <tbody>					
            <!-- insert data-->
             <?php
                $atHomeSQL = "select * from CurrentTransfer where personID = $personID order by animalName;";
                $result = $mysqli->query($atHomeSQL,  MYSQLI_STORE_RESULT);
                if ($mysqli->error) errorPage($mysqli->errno, $mysqli->error, $atHomeSQL);
                 
                // Generate the table
                while($row = $result->fetch_array()) {
            ?>
            <tr>
                <td><span><a href=<?= "\"viewAnimal.php?animalID=".$row['animalID']."\"" ?>><?= $row['animalName'] ?></a></span></td>
                <td><span><?= MySQL2Date($row['transferDate']) ?></span></td>
                <td><span><?= $row['speciesName'] ?></span></td>
                <td><span><?= $row['Status'] ?></span></td>
            </tr>
		<?php
			}
			$result->close();	

		?>
        </tbody>
	</table>
<?php
}

function pixieAnimalsPanel($personID, $mysqli) {
?>
    <b>Current Animals</b><br>
        <table id="sortable" width=100%>                <!-- Current Animals Table-->
        <thead>
            <tr>
                <th><span>Name</span></th>
                <th><span>Aquired</span></th>
                <th><span>Species</span></th>
                <th><span>Status</span></th>
                <th><span>Location</span></th>
            </tr>
        </thead>
        <tbody>
            <!-- insert data-->
             <?php
                $atHomeSQL = "select * from CurrentTransfer where pixieResponsible = 'Y'  order by animalName;";
                $result = $mysqli->query($atHomeSQL,  MYSQLI_STORE_RESULT);
                if ($mysqli->error) errorPage($mysqli->errno, $mysqli->error, $atHomeSQL);
                 
                // Generate the table
                while($row = $result->fetch_array()) {
            ?>
            <tr>
                <td><span><a href=<?= "\"viewAnimal.php?animalID=".$row['animalID']."\"" ?>><?= $row['animalName'] ?></a></span></td>
                <td><span><?= MySQL2Date($row['transferDate']) ?></span></td>
                <td><span><?= $row['speciesName'] ?></span></td>
                <td><span><?= $row['Status'] ?></span></td>
                <td><span><?= $row['CurrentPerson'] ?></span></td>
            </tr>
                <?php
                        }
                        $result->close();       

                ?>
        </tbody>
        </table>
<?php
}


	
function historyPanel($personID, $mysqli) {
?>
	<table  id=tabular width=100%>   	<!--  Transfer History Table -->
		<tr><td colspan="3"><b>History</b></td></tr>
		<tr>
			<th>Animal</th>
			<th>Date</th>
			<th>Type</th>
		</tr>

		<!-- insert data-->
		 <?php
			$transfer_sql =   "SELECT * FROM TransferHistory where personID = $personID";
			$result = $mysqli->query($transfer_sql);
			if (!$result) errorPage($mysqli->errno, $mysqli->error, $transfer_sql);

			// Generate the table
			while($row = $result->fetch_array()) {
		?>
		<tr>
			<td><a href=<?= "\"viewAnimal.php?animalID=".$row['animalID']."\"" ?>><?= $row['animalName'] ?></a></td>
			<td id=centerHand><?= MySQL2Date($row['transferDate']) ?></td>
			<td id=centerHand><?= $row['transferName'] ?></td>
		</tr>
		<?php
			}	
			$result->close();
		?>
	</table>
<?php
}

function currentPositionsPanel ($personID, $mysqli) {
?>
	<form action="" method="POST">
		<table id=tabular width=100%>		<!-- Current Positions Table-->
			<tr><td colspan=4><b>Current Positions</b></td></tr>
			<tr>
				<th>Position Name</th>
				<th>Start Date</th>
				<th>Note</th>
				<th>&nbsp;</th>
			</tr>
			<?php
				$sql =   "select * from CurrentPositions where personID=$personID order by startDate";
				$result = $mysqli->query($sql);
				if ($mysqli->errno) errorPage($mysqli->errno, $mysqli->error, $sql);

				// Generate the table
				while($row = $result->fetch_array()) {
			?>
			<tr>
				<td><?=$row['positionName'] ?></td>
				<td><?= MySQL2Date($row['startDate']) ?>&nbsp;</td>
				<td><?= $row['note'] ?>&nbsp;</td>
				<td><a href="viewPerson.php?action=delete&personID=<?=$row['personID']?>&positionTypeID=<?=$row['positionTypeID']?>">Delete</a></td>
			
			</tr>
				<?php
					}	
				$result->close();
				?>
			<tr>
				<td>
					<select name=positionTypeID>                  
						<option value=""></option>
						<?php
							$result = $mysqli->query("SELECT * FROM PositionType");
							if (!$result)  errorPage($mysqli->errno, $mysqli->error);
						
							while($row = $result->fetch_array()) {
						?>
							<option value="<?= $row['positionTypeID'] ?>"><?= $row['positionName'] ?></option>
						<?php
							}
							$result->close();	
						?> 
					</select>   
				</td>
				<td><input size=8 name=startDate></td>
				<td><input name=note></td>
				<td><input type="submit" value="Add Position" /></td>
			</tr>
			<tr>
				<td colspan=4><a href="editTables.php?tableName=PositionType&retPage=viewPerson&personID=<?=$personID?>">Edit Positions</a></td>
			</tr>
		</table>	
	</form>
<?php
}



function surgeryPanel($id, $who, $mysqli) {
?>
	<table  id="tabular" width="100%"> 					
		<tr><td style="vertical-align: top; width: 50%;" colspan="4"><b><?=($who=='P'?"Upcoming ":"")?>Surgeries</b></td></tr>
		<tr>
		  <th>Type</th>
		  <th>Date</th>
		  <th><?=($who=='A'?"Location":"Animal Name")?></th>
		  <th>&nbsp;</th>
		</tr>
		<?php 
			$lastTransfer = 0;	// Init lastTransfer
			if ($who == 'A') $sql =   "SELECT * FROM AnimalSurgeries where animalID = $id";
			else if ($who == 'P') $sql =   "SELECT * FROM AnimalSurgeries where personID = $id and surgeryDate >= '".date('Y-m-d')."'";

			$result = $mysqli->query($sql);
			if ($mysqli->errno) errorPage($mysqli->errno, $mysqli->error, $sql);
								
			while($row = $result->fetch_array()) {
				$name = ($who=='P'?$row['animalName']:$row['lastName']);
				$animalID = $row['animalID'];
				$personID = $row['personID'];
				$surgeryType = $row['surgeryType'];
				$surgeryTypeID = $row['surgeryTypeID'];
				$surgeryDate = $row['surgeryDate'];
				$note = $row['note'];
		?>
		<tr>
			<td><?=$surgeryType?></td>
			<td><?= MySQL2Date($surgeryDate) ?></td>
			<td><a href="<?=($who=='P'?"viewAnimal.php?animalID=$animalID":"viewPerson.php?personID=$personID")?>"><?= $name ?></a></td>
			<td>
				<a href="viewSurgery.php?<?=($animalID?"animalID=$animalID&":"")?>surgeryDate=<?=$surgeryDate?>&surgeryTypeID=<?=$surgeryTypeID?>">Edit</a>
				<a href="viewSurgery.php?action=delete&<?=($animalID?"animalID=$animalID&":"")?>surgeryDate=<?=$surgeryDate?>&surgeryTypeID=<?=$surgeryTypeID?>">Delete</a>
			</td>
		</tr>
		<?php 
			}
		?>
		<tr>
			<td colspan="4">
				<?php if ($who=='A') { ?><a href="viewSurgery.php?<?=($who=='A'?"animalID=":"personID=")?><?=$id?>">View/Edit Surgeries</a> <?php } ?>
			</td>
		</tr>
	</table> 	
<?php

}

?>
