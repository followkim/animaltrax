<?php 

        /*
         * viewApppointments.php
         * Kimberley Anne Gray
         * 
         * View all the appoints with a person on one screen
         * 
         */


        // Pull in the main includes file
        include 'includes/utils.php';
        include 'includes/html_macros.php';
        include 'includes/panels.php';

        // turn on error reporting
        error_reporting(E_ALL);
        ini_set('display_errors', true); 
        date_default_timezone_set('America/Los_Angeles');

        // Get the current user, if not logged in redirect to the login page.
        [$userName,$isAdmin] = getLoggedinUser();
        if ($userName == "") header("location:login.php");

       // Init the error string
        $errString = "";

        // Pull Possible GET variables
        $personID =  isset($_GET['personID'])?intval($_GET['personID']):false;
        $animalID =  isset($_GET['animalID'])?intval($_GET['animalID']):false;
	
	// Get POST variables
        $isPost = ($_SERVER['REQUEST_METHOD'] == 'POST');
        $startDate = $isPost?$_POST['startDate']:false;
        $endDate = $isPost?  $_POST['endDate']  : false;
 
        $note = "";
        $subject = "";

        // connect to the database, get information on the current animal
        $mysqli = DBConnect();

	// Get the users name
       // get information about the current person
	if ($personID) {
	        $personSQL = "select * from Person where personID = $personID";
	        $result = $mysqli->query($personSQL);
	        if (!$result) errorPage($mysqli->errno, $mysqli->error, $personSQL);
	        else { 
	                $row = $result->fetch_array();
			$personName = "<a href=\"viewPerson.php?personID=$personID\">".($row['isOrg']?$row['lastName']:$row['firstName']." ".$row['lastName'])."</a>";
		}
	} else if ($animalID) {
	        $sql = "select * from Animal where animalID = $animalID";
	        $result = $mysqli->query($sql);
	        if (!$result) errorPage($mysqli->errno, $mysqli->error, $sql);
	        else { 
	                $row = $result->fetch_array();
			$animalName = "<a href=\"viewAnimal.php?animalID=$animalID\">".$row['animalName']."</a>";
		}
	}
        pixie_header("View Appointments for: ". ($personID?$personName:($animalID?$animalName:"All")), $userName, $isAdmin);

?>
<form  action="" method="POST">
<table width="75%" padding="15px">
        <tr><td colspan=2><b>Appointment History:</b></td></tr>
	<tr><td colspan=2>Between <input size="15" type="date" name="startDate" id="startDate" value="<?=$startDate?>"/> and <input size="15" type="date" name="endDate" id="endDate" value="<?=$endDate?>">  <input type="submit" value="Filter" /></td><tr>

         <?php

                $appt_sql =  "SELECT *, ap.note FROM Appointment ap LEFT JOIN Animal a ON a.animalID = ap.animalID LEFT JOIN Person p on p.personID = ap.personID where ".($personID?"ap.personID=$personID":($animalID?"a.animalID=$animalID":"1=1")) .
			" AND ap.apptDateTime between '".($startDate?$startDate:date('Y-m-d', strtotime('2000-01-01'))) ."' and '". (date('Y-m-d H:i:s', strtotime($endDate?$endDate." 23:59:59":'2100-01-01'))) . "' ORDER BY ap.apptDateTime DESC;";
                $result = $mysqli->query($appt_sql);
                if (!$result)errorPage($mysqli->errno, $mysqli->error, $appt_sql);

                while($row = $result->fetch_array()) {
                        $animalName = isset($row['animalID'])?"<a href=\"viewAnimal.php?animalID=".$row['animalID']."\">".$row['animalName']."</a>":false;
                        $personName = isset($row['personID'])?"<a href=\"viewPerson.php?personID=".$row['personID']."\">".($row['isOrg']?$row['lastName']:$row['firstName']." ".$row['lastName'])."</a>":false;
                        $apptDateTime = "<a href=\"editAppointment.php?personID=".$row['personID']."&apptDateTime=".$row['apptDateTime']."\">".MySQL2DateTime($row['apptDateTime']) . "</a>";
			echo "<table>";
			echo  trd_labelData("Person", $personName);
			echo  trd_labelData("Date / Time", $apptDateTime);
			echo  trd_labelData("Subject", $row['subject']);
			if ($animalName) echo  trd_labelData("Regarding", $animalName);
			echo  trd_labelData("Notes", "<div style=\"max-width: 600px;\">".$row['note']."</div>");
			echo "</table><hr>";
                } // end while
                $result->close();
 
        	if ($personID) {
?>
	                <tr><td colspan=2><a href="editAppointment.php?personID=<?=$personID?>">Add New</a></td></tr>
<?php		}
?>
</table>
</form>
<?php pixie_footer(); ?>
