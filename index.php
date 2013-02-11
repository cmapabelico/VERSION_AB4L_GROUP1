<?php

	session_start();

?>

<html>

<head>
	<title>BRGR: The Burger Project Online</title>
</head>

<body>
	
	<?php
		if(!empty($_SESSION["id"])) echo "Hello ".$_SESSION["id"];
		else echo "Hello guest";
	?> 
	
	<br/><br/><br/>
	
	<?php
		if(!empty($_SESSION["id"])) echo '<a href="logout.php">Log out</a>';
		else{
			echo '<a href="login.php">Log in</a><br/>';
			echo '<a href="register.php">Register</a><br/>';
		}
	?>
	
</body>

</html>