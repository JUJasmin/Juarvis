<?php include 'head.php'; ?>
	
<?php
if (isset($_POST) && !empty($_POST)) {
    // This is the postback so add the book to the database
    # Get data from form
    $newusername = "";
    $newpassword = "";
    $newemail = "";
    
    $newusername = trim($_POST['username']);
    $newpassword = trim($_POST['password']);
    $newemail = trim($_POST['email']);
    
    $newpassword = sha1($newpassword);
	
	echo $newusername;
	echo $newpassword;
	echo $newemail;

    if (!$newusername || !$newpassword || !$newemail) {
        printf("You must specify both username, email and a password");
        printf("<br><a href=registration.php>Try again</a>");
        exit();
    }

    $newusername = addslashes($newusername);
    $newemail = addslashes($newemail);
    $newpassword = addslashes($newpassword);
	
    $newusername = htmlentities($newusername);
    $newemail = htmlentities($newemail);
    $newpassword = htmlentities($newpassword);

    # Open the database
@ $db = new mysqli($dbserver, $dbuser, $dbpass, $dbname);

    if ($db->connect_error) {
        echo "could not connect: " . $db->connect_error;
        printf("<br><a href=registration.php>Registration failed, try again</a>");
        exit();
    }

    // Prepare an insert statement and execute it
    $stmt = $db->prepare("insert into users values ('', ?, ?, ?, '')");
    $stmt->bind_param('sss', $newusername, $newpassword, $newemail);
    $stmt->execute();
    printf("<br>Account created!");
    printf("<br><a href=index.php>Login</a>");
<<<<<<< HEAD
   
=======
>>>>>>> e8596b58fa69c6159f8a20c7ff1e4a6445435aa0
    exit;
}

?>

<h3>Welcome to Juarvis</h3>

<form class="registrationForm" action="registration.php" method="POST">
    <table>
        <tbody>
            <tr>
                <td>Username</td>
                <td><INPUT type="text" name="username"></td>
            </tr>
            <tr>
                <td>Email</td>
                <td><INPUT type="text" name="email"></td>
            </tr>
            <tr>
                <td>Password</td>
                <td><INPUT type="password" name="password"></td>
            </tr>
		<tr>
                <td>Confirm Password</td>
                <td><INPUT type="password" name="password"></td>
            </tr>
            <tr>
                <td></td>
                <td><INPUT type="submit" name="submit" value="Register"></td>
            </tr>
        </tbody>
    </table>
    <br>
</form>

<<<<<<< HEAD
</html>
<<<<<<< HEAD
<?php  include("footer.php"); ?>
=======

=======
>>>>>>> 5c487e0c3623adbc3fb9227e4a9ebb3bd6c5f70c
<?php
	include("footer.php");
?>
>>>>>>> e8596b58fa69c6159f8a20c7ff1e4a6445435aa0
