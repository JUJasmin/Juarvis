<?php
	include 'config.php';
	include 'header.php';
	include 'session.php';
?>

<?php
	//set session variables
	$userid = $_SESSION['user_id'];
	$username = $_SESSION['username'];

	//establish db connection
  @ $db = new mysqli($dbserver, $dbuser, $dbpass, $dbname);

	if ($db->connect_error) {
			echo "could not connect: " . $db->connect_error;
			printf("<br><a href=index.php>Return to home page.</a>");
			exit();
	}

	//declare variables
	$currentusername = '';
	$currentpassword = '';
	$currentemail = '';

	//set current user information in variables
	$stmt = $db->prepare("SELECT username, password, email FROM users WHERE user_id='$userid'");
	$stmt->bind_result($currentusername, $currentpassword, $currentemail);
	$stmt->execute();
	$stmt->fetch();

	//set variables
	$newusername = "";
	$newpassword = "";
	$newemail = "";
	$confirmpassword = "";

	//safety yes
	$newusername = addslashes($newusername);
	$newpassword = addslashes($newpassword);
	$newemail = addslashes($newemail);
	$confirmpassword = addslashes($confirmpassword);

	$newusername = htmlentities($newusername);
	$newpassword = htmlentities($newpassword);
	$newemail = htmlentities($newemail);
	$confirmpassword = htmlentities($confirmpassword);

	$newusername = mysqli_real_escape_string($db, $newusername);
	$newpassword = mysqli_real_escape_string($db, $newpassword);
	$newemail = mysqli_real_escape_string($db, $newemail);
	$confirmpassword = mysqli_real_escape_string($db, $confirmpassword);

	//check forms
	if (isset($_POST['btnSave']) && !empty($_POST)) {

	//get data from form
		$newusername = trim($_POST['newusername']);
		$newpassword = trim($_POST['newpassword']);
		$newemail = trim($_POST['newemail']);
		$confirmpassword = trim($_POST['confirmpassword']);

		if (empty($confirmpassword)) {
			echo "<script type='text/javascript'> alert('Please confirm with your old password.'); </script>";
			header("Refresh:0");
			exit();
		}

		if (sha1($confirmpassword) != $currentpassword) {
			echo "<script type='text/javascript'> alert('Wrong password.'); </script>";
			header("Refresh:0");
			exit();
		}

		if (!empty($confirmpassword) && sha1($confirmpassword) == $currentpassword) {

			//check if email already exists in db, if not insert
			if ($newemail != "") {
				@ $db = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
				$sql = "SELECT email FROM users WHERE email = '$newemail'";
				$result = $db->query($sql);

				if ($result->num_rows > 0){
					echo "<script type='text/javascript'> alert('That email already exists!'); </script>";
					header("Refresh:0");
					exit ();
				}
				else {
					@ $db = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
					$stmt = $db->prepare("UPDATE users SET email = '$newemail' WHERE user_id='$userid'");
					$stmt->execute();
				}
			}
			else{
				$newemail = $currentemail;
				@ $db = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
				$sql = "SELECT email FROM users WHERE email = '$newemail'";
				$result = $db->query($sql);
			}

			//check if username already exists in db, if not insert
			if ($newusername != "") {
				@ $db = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
				$sql = "SELECT username FROM users WHERE username = '$newusername'";
				$result = $db->query($sql);

				if ($result->num_rows > 0){
					echo "<script type='text/javascript'> alert('That username is already taken!'); </script>";
					header("Refresh:0");
					exit();
				}
				else {
					@ $db = new mysqli($dbserver, $dbuser, $dbpass, $dbname);

					$stmt = $db->prepare("UPDATE users SET username = '$newusername' WHERE user_id='$userid'");
					$stmt->execute();
				}
			}
			else{
				$newusername = $currentusername;
				@ $db = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
				$sql = "SELECT username FROM users WHERE username = '$newusername'";
				$result = $db->query($sql);
			}

			//insert new passwrod, else insert old password
			if ($newpassword != ""){
					//hash newpassword
					$newpassword = sha1($newpassword);

					@ $db = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
					$stmt = $db->prepare("UPDATE users SET password = '$newpassword' WHERE user_id='$userid'");
					$stmt->execute();
			}
			else {
				@ $db = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
				$stmt = $db->prepare("UPDATE users SET password = '$currentpassword' WHERE user_id='$userid'");
				$stmt->execute();
			}
		}
		header("Refresh:0");
}

else{
 	echo "<script type='text/javascript'> alert('Please fill something out in the form.'); </script>";
 }

?>

<div class="settingsDiv">
		 <h2>Settings<h2>
		 <h3>Fill in the forms below to change your settings.</h3>
		 <form method="POST" action="settings.php" class="settingsForm">
				 <h4>Change Username</h4>
				 <input type="text" name="newusername" placeholder="<?php echo $currentusername ?>" class="inputField"/>
				 <h4>Change Password</h4>
				 <input type="password" name="newpassword" placeholder="New Password" class="inputField"/>
				 <h4>Change Email</h4>
				 <input type="email" name="newemail"  placeholder="<?php echo $currentemail ?>" class="inputField"/>
 <h3 id="confirmPass">Confirm with your old password</h3>
 <input type="password" name="confirmpassword" placeholder="Confirm Password" class="inputField"/>
 <input type="submit" value="Save Changes" class="button" name="btnSave">
		 </form>
 </div>

<?php include 'footer.php'; ?>
