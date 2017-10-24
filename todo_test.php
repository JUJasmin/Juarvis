<?php
  include 'config.php';

  //establish db connection
  @ $db = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
	if ($db->connect_error) {
		echo "could not connect: " . $db->connect_error;
		exit();
	}

  //get all lists and their tasks from DB
	$sql = "SELECT listname FROM list"; //where user_id = 'login_user'";
	$result = mysqli_query($db, $sql);

  $listname = "";

if (mysqli_num_rows($result) > 0) {
    // output data of each row
    while($row = mysqli_fetch_assoc($result)) {
        echo "list: " . $row["listname"]. "<br>";
    }
}

else{
      echo "0 results";
    }

  //display all lists and their tasks as a table?

$sql2 = "SELECT taskname FROM tasks where list_id = list_id"; //and user_id = 'login_user'";
	$result2 = mysqli_query($db, $sql2);

$listname = "";

if (mysqli_num_rows($result2) > 0) {
    // output data of each row
    while($row = mysqli_fetch_assoc($result2)) {
        echo "task: " . $row["taskname"]. "<br>";
    }
}

else{
      echo "0 results";
    }

  //create new list

if (isset($_POST) && !empty($_POST)) {
    # Get data from form
   	$newlist = "";
	$newlist = trim($_POST['newlist']);

	if (!$newlist) {
		printf("You must add a listname, try again.");
		exit();
	    }

	$stmt = $db->prepare("insert list (list_id, listname) VALUES ('', ?)");
	    $stmt->bind_param('s', $newlist);
	    $stmt->execute();
	    printf("<br>List Added!");
	header: "location:todo_test.php';
}

/*/ $sql = "INSERT INTO list (list_id, listname) VALUES ('', ?)";

if ($db->query($sql) === TRUE) {
    echo "New list added";
}
else {
    echo "Error: " . $sql . "<br>" . $db->error;
}

$db->close(); /*/

  //in this list create new tasks
  //tasks should hold information such as taskname, taskdesc, sdate, edate, rdate, status(meaning if it is completed or not)

  //insert this new lists and tasks to DB

  //change task to completed and mark it as grey or other CSS

?>

<form action="todo_test.php" method="POST">
	<input type="text" name="newlist" placeholder="Listname" class="inputField">
  <br>
  <input type="submit" name="submit" value="Add" class="button">
</form>
