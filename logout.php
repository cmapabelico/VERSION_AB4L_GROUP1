<?php

	session_start();
	session_destroy();
	
	$_SESSION["login"] = 0;
	$_SESSION["id"] = null;
	
	header("Location: index.php");
	exit;

?>

<html>

<head>
	<title></title>
</head>

<body>
</body>

</html>