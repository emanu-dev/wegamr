<?php
// Start the session
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html lang='pt-br'>
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="indexStyle.css">
	<title>Social Gamer - Log In</title>
</head>
<body>

	<?php 
		include 'db_conn_var.php';
	?>

	<?php

		if (isset($_GET['status']))
			{
				if (($_GET['status']) == "logout")
				{
					$_SESSION["logged_userID"] = null;
					echo "<p style='color: #ffffff'>Thank you for using Social Gamer!</p>";
				}
				
				if (($_GET['status']) == "notlogged")
				{
					echo "<p style='color: #ffffff'>Please login to access this content.</p>";
				}
			}

		try {
			$conn = new mysqli($url, $username, $password, $dbname);

			if ($conn->connect_error) {
				throw new Exception($conn->connect_error);
			}else {
				
			}
	    	
	    	$query = "CREATE TABLE IF NOT EXISTS user(userID int AUTO_INCREMENT, username varchar(15), password varchar(8), dob date, image mediumblob, PRIMARY KEY(userID));";
			mysqli_query($conn, $query);
	    	
	    	$query = "CREATE TABLE IF NOT EXISTS console(consoleID int AUTO_INCREMENT, cname varchar(15), manufacturer varchar(15), PRIMARY KEY(consoleID));";		    	
	    	mysqli_query($conn, $query);
	    	
	    	$query = "CREATE TABLE IF NOT EXISTS game(gameID int AUTO_INCREMENT, gname varchar(30), publisher varchar(15), rating varchar(1), consolename int, PRIMARY KEY(gameID), FOREIGN KEY (consolename) REFERENCES console(consoleID));";
			mysqli_query($conn, $query);
	    	
	    	$query = "CREATE TABLE IF NOT EXISTS owned_consoles(consoleID int, userID int, FOREIGN KEY consoleID(consoleID) REFERENCES console(consoleID), FOREIGN KEY (userID) REFERENCES user(userID));";
			mysqli_query($conn, $query);
			
			$query = "CREATE TABLE IF NOT EXISTS owned_games(gameID int, userID int, FOREIGN KEY gameID(gameID) REFERENCES game(gameID), FOREIGN KEY (userID) REFERENCES user(userID));";
			mysqli_query($conn, $query);
			
			$query = "CREATE TABLE IF NOT EXISTS friend(requesterID int, friendID int, accepted boolean, FOREIGN KEY requesterID(requesterID) REFERENCES user(userID), FOREIGN KEY (friendID) REFERENCES user(userID));";	
			mysqli_query($conn, $query);
			
			$query = "CREATE TABLE IF NOT EXISTS recommendation(userID int, gameID int, rec text, FOREIGN KEY userID(userID) REFERENCES user(userID), FOREIGN KEY (gameID) REFERENCES game(gameID));";				
			mysqli_query($conn, $query);
			
			$query = "CREATE TABLE IF NOT EXISTS tags(userID int, gameID int, tag varchar(10), FOREIGN KEY (userID) REFERENCES user(userID), FOREIGN KEY (gameID) REFERENCES game(gameID));";		
			mysqli_query($conn, $query);
			
			mysqli_close($conn);	
		}
		catch(Exception $e)
		{
		    echo $e->getMessage();
		}
	?>

	<div id="signBox">
		<p><img src="logo.png"><br>
			<form action="index.php" method="post">
			<input type="text" name="username" placeholder="Username"><br>
			<input type="password" name="password" placeholder="Password">
			<input type="submit" value="Log In"></p>
		</form>

		<?php
		if (isset($_POST['username']) && !empty($_POST['username']))
		{
			$user = $_POST['username'];
			$pass = $_POST['password'];
			if ($user != null && $pass != null) {
				try {
					$conn = new mysqli($url, $username, $password, $dbname);
		
					if ($conn->connect_error) {
						throw new Exception($conn->connect_error);
					}else {
					}				
		
					$query = "SELECT * FROM user WHERE username='".$user."' and password='".$pass."'";
					$result = $conn->query($query);

					if ($row = $result->num_rows > 0)
						{
							while($row = $result->fetch_assoc()) {
							   $_SESSION["logged_userID"] = $row["userID"];
							   $url="user_page.php";
							   echo '<META HTTP-EQUIV=REFRESH CONTENT="1; '.$url.'">';
							}
						}else{
							echo "<br>Wrong username/password combination. <br> If you do not have an account, click on Sign Up.<br>";
						}
					$conn->close();	
				}
				catch(Exception $e)
				{
				    echo $e->getMessage();
				}		
			} 			
		}


		?>

		<br>Don't have an account? <a href="signup.php">Sign up</a>
	</div>
</body>
</html>