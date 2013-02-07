<?php

	session_start();
	
	if($_SESSION["login"]!=1){
		header("Location: login.php");
		exit;
	}

?>

<html>

<head>
	<title>BRGR: The Burger Project Online</title>
</head>

<body>
	
	Hello <?php echo $_SESSION["id"]; ?>
	<br/><br/><br/>
	<a href="logout.php">Log out</a>
	
</body>

</html>