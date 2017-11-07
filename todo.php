<div class='tododiv'>
<?php


	//establish db connection
	@ $db = new mysqli($dbserver, $dbuser, $dbpass, $dbname);

	//error message if no connection to db
	if ($db->connect_error) {
		echo "could not connect: " . $db->connect_error;
		exit();
	}

	//create tabbuttons for each list
	//declare the variables here so that they are global
	$listname='';
	$list_id='';

	$sql1 = "SELECT listname, list_id FROM lists"; //where user_id = 'username'";
	$result1 = mysqli_query($db, $sql1);

	//fetch the result from our query as an "associative array" and place into row and set this to our new list array
	//we are doing this so that we can go through the query result in an easier way and select and set values to new variables to be used later
	while( $row = mysqli_fetch_assoc($result1)){
    $list_array[] = $row;
	}
	echo "<div id='tabDiv'>";
	foreach ($list_array as $value) {
		$listname = $value["listname"];
		$list_id = $value["list_id"];
?>

<!--We exit php here and create the button in html to solve a problem with ''-->
	<button class='listTab' onclick='openList(event,"l<?php echo $list_id ?>")' ><?php echo $listname ?></button>

<?php

	}
	echo "</div>";

	echo "<div id='listParent'>";
	//get and display all lists from DB
	$list_id='';

	$sql = "SELECT listname, list_id FROM lists"; //where user_id = 'username'";
	$result = mysqli_query($db, $sql);

	while( $row = mysqli_fetch_assoc($result)){
    $new_array[] = $row;
	}

	foreach ($new_array as $value) {

		$list_id = $value["list_id"];
		echo "<div class='listContent' id='l$list_id'>";

		echo "<h3>";
		print_r($value["listname"]);
		echo "</h3>";

		echo "<form method='post' action='main.php'>";
		echo "<input type='submit' name='deletelist' value='X'/>";
		echo "<input type='hidden' name='id' value='$list_id'/>";
		echo "</form>";

 		//first check if there are tasks with list id
		$taskSql = "SELECT task_id, taskname FROM tasks WHERE list_id = '$list_id' "; //and user_id = 'username'";
		$tasksRes = $db->query($taskSql);

		if($tasksRes->num_rows > 0) {
			while($row = mysqli_fetch_assoc($tasksRes)){
				$new_task_array[] = $row;

}
			//for the array, get and display all tasknames (this is the 'value' in the array)
			foreach ($new_task_array as $value) {
				print_r($value["taskname"]);

				$task_id = $value["task_id"];

				echo "<form method='post' action='main.php'>";
				echo "<input type='submit' name='deletetask' value='x'/>";
				echo "<input type='hidden' name='id' value='$task_id'/>";
				echo "</form>";

				echo "<br>";
			}
 		}
		echo "</div>";
	}
	echo "</div>";
?>

<!--Javascript for when we display the lists and tasks-->
	<script type="text/javascript">

	function openList(event,list_id) {
	    // Declare all variables
	    var i, listContent, listTab;

			var lp= document.getElementById('listParent');
			var td= document.getElementById('tabDiv');
	    // Get all elements with class listContent and hide them
	    //listContent = document.getElementsByClassName("listContent");
	    for (i = 0; i < lp.children.length; i++) {
				lp.children[i].style.display='none';
	    }

			for (i = 0; i < td.children.length; i++) {
				td.children[i].classList.remove('currentList');
	    }

			document.getElementById(list_id).style.display='block';

	    //Show the current tab, and add an "active" class called currentList to the button that opened the tab
	    event.currentTarget.classList.add("currentList");
	}
	</script>

<?php
	//Create new list if user submits new list. Will NOT run first time user goes to page
	if (isset($_POST['submitlist'])) {

	    //If newlist is not set echo error message
	    if (empty($_POST['newlist'])) {
		printf("You must add a listname, try again.");
		exit();
	    }

	else {
	    # Get data from form
		$newlist = "";
		$newlist = trim($_POST['newlist']);

		//security myes
		$newlist = addslashes($newlist);
		$newlist = htmlentities ($newlist);
		$newlist = htmlentities ($db, $newlist);

		//add list to db
		$stmt = $db->prepare("INSERT INTO lists (list_id, listname) VALUES ('', ?)");
		$stmt->bind_param('s', $newlist);
		$stmt->execute();
		printf("<br>List Added!");
		header("Refresh:0");
	  }
	}

	//Create new task for specific list
	if (isset($_POST['submittask'])) {

	    //If newlist is not set, write error message, and do not continue with code
	    if (empty($_POST['newtask'])) {
			printf("You must add a task, try again.");
			exit();
			}

		else {
	    # Get data from form
	    $newtask = "";
	    $newtask = trim($_POST['newtask']);
			$newtaskdesc = "";
	    $newtaskdesc = trim($_POST['newtaskdesc']);
			$newStartDate = "";
			$newStartDate = trim($_POST['newStartDate']);
			$newEndDate = "";
			$newEndDate = trim($_POST['newEndDate']);
			$tasklist = "";
			$tasklist = trim($_POST['tasklist']);

			//security
			$newtask = addslashes($newtask);
			$newtaskdesc = addslashes($newtaskdesc);
			$newStartDate = addslashes($newStartDate);
			$newEndDate = addslashes($newEndDate);
			$tasklist = addslashes($tasklist);

			$newtask = htmlentities ($newtask);
			$newtaskdesc = htmlentities($newtaskdesc);
			$newStartDate = htmlentities($newStartDate);
			$newEndDate = htmlentities($newEndDate);
			$tasklist = htmlentities ($tasklist);

			$newtask = mysqli_real_escape_string($db, $newtask);
			$newtaskdesc = mysqli_real_escape_string($db, $newtaskdesc);
			$newStartDate = mysqli_real_escape_string($db, $newStartDate);
			$newEndDate = mysqli_real_escape_string($db, $newEndDate);
			$tasklist = mysqli_real_escape_string($db, $tasklist);

			//insert tasks into db in the correct way and print out task added!
	    $stmt = $db->prepare("INSERT INTO tasks (taskname, taskdesc, sdate, edate, list_id) VALUES (?, ?, ?, ?, ?)");
	    $stmt->bind_param('ssssi', $newtask, $newtaskdesc, $newStartDate, $newEndDate, $tasklist);
	    $stmt->execute();
	    printf("<br>Task Added!");
	    header("Refresh:0");
	  }
	}

	//Remove list and tasks with same list_id if deletebutton is clicked

	if (isset($_POST['deletelist'])) {

		//hidden id from the echo:d form is used to determine which list should be deleted
		$id = $_POST['id'];

		$stmt = $db->prepare ("DELETE FROM lists WHERE list_id = '$id'");
		$stmt->execute();

		$stmt = $db->prepare ("DELETE FROM tasks WHERE list_id = '$id'");
		$stmt->execute();

		header("Refresh:0");
  }


//Remove specific task in a list if deletebutton is clicked

	if (isset($_POST['deletetask'])) {

		//hidden id from the echo:d form is used to determine which task should be deleted
		$id = $_POST['id'];

		$stmt = $db->prepare ("DELETE FROM tasks WHERE task_id = '$id'");
		$stmt->execute();

		header("Refresh:0");
	}

?>

<br>
<h2>Create new list</h2>
<form action="main.php" method="POST">
	<h3>List name</h3>
	<input type="text" name="newlist" placeholder="Add a list name" class="inputField">
	<br>
	<input type="submit" name="submitlist" value="Create" class="button">
</form>

<h2>Add new task</h2>
<form action="main.php" method="POST">
	<h3>Task name</h3>
	<input type="text" name="newtask" placeholder="Add a task name" class="inputField">
	<br>
	<h3>Task description</h3>
	<input type="text" name="newtaskdesc" placeholder="Add a task description" class="inputField">
	<br>
	<h3>Start date</h3>
	<input type="date" name="newStartDate" class="inputField">
	<br>
	<h3>End date</h3>
	<input type="date" name="newEndDate" class="inputField">
	<br>
	<h3>Select list</h3>
  <?php

  $sql3 = "SELECT list_id,listname FROM lists";
  $result3 = mysqli_query($db, $sql3);

  echo "<select class='select' name='tasklist'>";
  while ($row3 = mysqli_fetch_assoc($result3)) {
      echo "<option class='options' value='" . $row3['list_id'] ."'>" .$row3['listname'] ."</option><br>";
  }
  echo "</select>";
  ?>

	<input type="submit" name="submittask" value="Add" class="button">
</form>
</div>
