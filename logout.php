<?php

	session_start();
	session_destroy();
	
	$_SESSION["login"] = 0;
	$_SESSION["id"] = null;
	$_SESSION["traycontents"] = 0;
	$_SESSION["subtotal"] = 0;
	
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