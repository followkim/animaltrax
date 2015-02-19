<?php 	
	/*	editUser.php
	 */

	// Pull in the main includes file
	include 'includes/utils.php';
	include 'includes/html_macros.php';

	// Get the current user, if not logged in redirect to the login page.
	$loggedIn_User = getLoggedinUser();
	if ($loggedIn_User == "") header("location:login.php");

	$userID = (isset($_GET['userID'])?intval($_GET['userID']):'');
	$action = (isset($_GET['action'])?validateAction($_GET['action']):'');
	$errString = "";

	// is this a POST if so, grab the POST varibales.
	$isPost 	= ($_SERVER['REQUEST_METHOD'] == 'POST');
	$p_userID 	= ($isPost?intval($_POST['userID']):'');
	$p_userName = ($isPost?lbt($_POST['username']):'');
	$p_email 	= ($isPost?lbt($_POST['email']):'');
	$oldPassword= ($isPost?lbt($_POST['oldPassword']):"");
	$newPassword= ($isPost?lbt($_POST['newPassword']):"");
	$password1 	= ($isPost?lbt($_POST['password1']):"");
	$password2 	= ($isPost?lbt($_POST['password2']):"");
	
	$mysqli = DBConnect();
	
	if ($isPost) {
		
		// Check required values
		if ($p_userName=='') {
			$errString .= "Fields marked with a * are required.";
			if ($p_userID) {
				$userID = $p_userID;
				$action = "edit";
			}
		} else {
		
			// EDIT exsisting user
			if ($p_userID) {

				// Update SELF as user (when new password is provided)
				if ($newPassword) {

					if (strlen($newPassword)>4) {
						$sql = "select userID from Users WHERE userID=$p_userID and password='$oldPassword'";
						$result = $mysqli->query($sql);
						if ($mysqli->errno) errorPage($mysqli->errno, $mysqli->error, $sql);
						elseif (($result->num_rows)==1) {
							$result->close();
							$sql = "update Users set username='$p_userName', password='$newPassword', email='$p_email' WHERE userID=$p_userID and password='$oldPassword'";
							$mysqli->query($sql);
							if ($mysqli->errno) errorPage($mysqli->errno, $mysqli->error, $sql);
						} else $errString .= "Incorrect username or password.<br>";
					} else $errString .= "New password must be at least 6 characters long.<br>";

				// Edit other user, or user without changing password
				} else {
					$sql = "update Users set username='$p_userName', email='$p_email' WHERE userID=$p_userID";
					$mysqli->query($sql);
					if ($mysqli->errno) errorPage($mysqli->errno, $mysqli->error, $sql);
				}
				if (strlen($errString)) {
					$userID = $p_userID;
					$action="edit";
				}
			} // end EDIT
			
			// ADD New user 
			else {
				if ($password1==$password2) {
					if (strlen($password1)>4) {
						$sql = "INSERT into Users (username, email, password) VALUES ('$p_userName',  '$p_email', '$password1')";
						$mysqli->query($sql);
						if ($mysqli->errno) errorPage($mysqli->errno, $mysqli->error, $sql);
					} else $errString .= "New password must be at least 5 characters long.<br>";
				} else $errString .= "Passwords don't match!<br>";
			}
		}			
	} // END POST
	// Process GET
	if ($userID) {
		if ($action=="edit") {		
			$sql = "select username, email from Users WHERE userID = $userID";	
			$result = $mysqli->query($sql);
			if ($mysqli->errno) errorPage($mysqli->errno, $mysqli->error, $sql);
			else {
				$row = $result->fetch_array();
				$userName = $row['username'];
				$email = $row['email'];		
				$result->close();	
			}
		} elseif ($action=="delete") {
			$sql = "DELETE FROM Users WHERE userID = $userID";
			$mysqli->query($sql);
			if ($mysqli->errno) errorPage($mysqli->errno, $mysqli->error, $sql);
			$userID=0;
		}
	}
	pixie_header("View Users", $loggedIn_User);

?>
<table id="criteria">
	<tr><td colspan=2><?= ($action=="edit"?"Edit":"Add") ?> User:</td></tr>
	<form action="editUsers.php" method="POST">
		<?php 
		if ($loggedIn_User==$userName) {
			echo trd_labelData("User Name", $userName, "username", true);
			echo trd_labelData("Email", $email, "email");
			echo trd_labelData("Old Password", $oldPassword, "oldPassword");
			echo trd_labelData("New Password", $newPassword, "newPassword");
			
		} elseif ($action!="edit") { 
			echo trd_labelData("User Name", $userName, "username", true);
			echo trd_labelData("Email", $email, "email");
			echo trd_labelData("Password", $password1, "password1", true, "password");
			echo trd_labelData("Retype Password", $password2, "password2", true, "password");
		} else {
			echo trd_labelData("User Name", $userName);
			echo trd_labelData("Email", $email, "email");
		}			
		?>
		<tr>
			<td align="right">
				<input hidden type="txt" name="userID" value="<?= $userID ?>" >
				<input type="submit" value="<?= ($action=="edit"?"Edit":"Add") ?> User" /> 
				<a href="editUsers.php">Clear</a>
			</td>
		</tr>
	</form>
</table>

<font color="red"><?= $errString ?></font>
<hr>

<!-- Show search Results -->
<table table id=tabular>
	<tr><td colspan=3>People:</td></tr>
	<tr>
		<th>User Name</th>
		<th>Email</th>
		<th>&nbsp;</th>
	</tr>

<?php 

		$sql = "select userID, username, email from Users";
		$result = $mysqli->query($sql);
		if ($mysqli->errno) errorPage($mysqli->errno, $mysqli->error, $sql);
		
		// Generate the table.  The Person name is a  link to the viewPerson page
		// with the personID passed in the URL. If we are here to select a person for a page that 
		// we should be redirected back to, the first column is a "select" link that will take us to 
		// that person.

		// Generate the table
		while($row = $result->fetch_array()) {
?>
	<tr>
		<td><?= $row['username'] ?>&nbsp;</td>
		<td><?= $row['email'] ?>&nbsp;</td>
		<td>
			<a href="<?= "editUsers.php?userID=".$row['userID']."&action=edit"?>">Edit</a>
			<!-- a href="<?= "editUsers.php?userID=".$row['userID']."&action=delete"?>">Delete</a -->
		</td>
	</tr>
<?php
	}	
?>
	<tr>
		<td colspan=3>Found <?=$result->num_rows?> users.</td>
	</tr>
</table>
<?php 	
	$result->close();

	pixie_footer(); 
?>

