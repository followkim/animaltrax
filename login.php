<?php 	
	// turn on error reporting
	error_reporting(E_ALL);
	ini_set('display_errors', true); 
	
	include 'includes/utils.php';
	include 'includes/html_macros.php';
	
	[$userName,$isAdmin] = getLoggedinUser();
	if ($userName != "") header("location:main.php");
		
	$error = isset($_GET['error'])?$_GET['error']:"";
	
	// is this a POST?
	$isPost = ($_SERVER['REQUEST_METHOD'] == 'POST');
	
	if ($isPost) {
	
		// Connect to server and select databse.
		$mysqli = DBConnect();

		// Define $myusername and $mypassword
		$myusername=lbt($_POST['myusername']);
		$mypassword=lbt($_POST['mypassword']);

		$sql="SELECT * FROM Users WHERE username='$myusername' and password='$mypassword'";

		$result=$mysqli->query($sql);

		// Mysql_num_row is counting table row
		$count=$result->num_rows;
		
		// If result matched $myusername and $mypassword, table row must be 1 row
		if($count==1){
			$row = $result->fetch_array();
			$cookie_name = "pixie";
			$cookie_value = $row['username'].",".$row['isAdmin'];
//			$cookie_value = $myusername.",". "1";
			setcookie($cookie_name, $cookie_value, time() + 1200, "/");
			// 86400 = 1 day
			$result->close();
			header("location:main.php");
		}
		else {
			$result->close();
			header("location:login.php?error=1");
		}
	}

	pixie_header("Login");
?>

<?php if ($error) 
	echo "<font color='red'>Wrong Username or password!</font>";
?>
<p><p>
<form name="form1" method="post" action="login.php">
	<center>
		<table width="300" id="tabular">
			<tr >
				<td colspan=2><b>Member Login:</b></td>
			</tr>
			<tr>
				<td>Username: </td>
				<td><input name="myusername" type="text" id="myusername"></td>
			</tr>
			<tr>
				<td>Password: </td>
				<td><input type="password" name="mypassword" id="mypassword"></td>
			</tr>
			<tr>
				<td></td>
				<td><input type="submit" name="Submit" value="Login"></td>
			</tr>
		</table>
	</center>
</form>
<p><p>
<?php 	
	pixie_footer(); 
?>
