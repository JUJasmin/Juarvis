<?php

@ $db = new mysqli('localhost', 'root', '', 'juarvis');
session_start();

$error=''; // Variable To Store Error Message

if (isset($_POST['submit'])) {
	
if (empty($_POST['username']) || empty($_POST['userpass'])) {
	$error = "Username or Password is invalid";
}
else
{
	// Define $username and $password
	$username=$_POST['username'];
	$password=$_POST['userpass'];
	
	// Establishing Connection with Server by passing server_name, user_id and password as a parameter
	$connection = mysql_connect("localhost", "root", "juarvis");
	
	// To protect MySQL injection for Security purpose
	$username = stripslashes($username);
	$username = htmlentities($username);
	$password = stripslashes($password);
	$username = mysql_real_escape_string($username);
	$password = mysql_real_escape_string($password);
	
	// Selecting Database
	$db = mysql_select_db("users", $connection);
	
	// SQL query to fetch information of registerd users and finds user match.
	$query = mysql_query("select * from login where password='$password' AND username='$username'", $connection);
	$rows = mysql_num_rows($query);
	
if ($rows == 1) {
	$_SESSION['username']=$username; // Initializing Session
	header("location: main.php"); // Redirecting To home page
} else {
	$error = "Username or Password is invalid";
	}
	mysql_close($connection); // Closing Connection
	}
}
?> 

<div class="login-form">
			<div class="top-login">
				<span><img src="img/group.png" alt=""/></span>
			</div>
			<h1>Welcome to Juarvis!</h1>
			<div class="login-top">
			<form>
				<div class="login-ic">
					<i ></i>
					<input type="text"  value="User name" onfocus="this.value = '';" onblur="if (this.value == '') {this.value = 'User name';}"/>
					<div class="clear"> </div>
				</div>
				<div class="login-ic">
					<i class="icon"></i>
					<input type="password"  value="Password" onfocus="this.value = '';" onblur="if (this.value == '') {this.value = 'password';}"/>
					<div class="clear"> </div>
				</div>
			
				<div class="log-bwn">
					<input type="submit"  value="Login" >
				</div>
				</form>
			</div>
</div>		
</body>
</html>
