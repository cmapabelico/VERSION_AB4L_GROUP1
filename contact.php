<?php

	session_start();

	//Connect to database
	$dbconn = pg_connect("host=localhost port=5432 dbname=TBP user=postgres password=password");
	
	//For guest user
	if(!isset($_SESSION["login"])){
		$_SESSION["login"] = 0;
	}
	
	//Check if user is logged in
	if($_SESSION["login"]!=1){
		$_SESSION["id"]=null; //guest
		if(!isset($_SESSION["tray"])) $_SESSION["tray"] = array(); //user tray
		if(!isset($_SESSION["traycontents"])) $_SESSION["traycontents"] = 0; //number of items in tray
		if(!isset($_SESSION["subtotal"])) $_SESSION["subtotal"] = 0; //total price due
	}
	
?>
<html>
<head>
	<title>BRGR: The Burger Project Online - Contact Us</title>
	<link rel="stylesheet" href="style.css" type="text/css"/>
</head>

<body>

	<center>
	<div class="body">

		<div class="nav">
			<img src="images/logo.png" width="500"/><br/><br/>
			<a href="home.php">Home</a> &nbsp &nbsp &nbsp &nbsp &nbsp
			<a href="menu.php">Menu</a> &nbsp &nbsp &nbsp &nbsp &nbsp
			<a href="gallery.php">Gallery</a> &nbsp &nbsp &nbsp &nbsp &nbsp
			<a href="contact.php">Contact Us</a> &nbsp &nbsp &nbsp &nbsp &nbsp
			<a href="help.php">Help</a>
		</div>
	
		<div class="content">
			<div class="user">
				<?php
				if($_SESSION["id"]!=null){
					$query = "select * from member where email='".$_SESSION["id"]."';";
					$result = pg_query($dbconn, $query);
					$row = pg_fetch_row($result);	
				?>
					You are logged in as <?php echo $row[2]." ".$row[3]; ?> | 
					<a href="edit.php">Profile</a> | 
					<?php
						if($_SESSION["id"]=='theburgerproject@gmail.com') echo '<a href="admin.php">Admin</a> | ';
						else echo '<a href="tray.php">Tray ('.$_SESSION["traycontents"].')</a> | ';
					?>
					<a href="logout.php">Log out</a><br/>
				<?php }
					else{
						echo 'Welcome guest! ';
						if(isset($_SESSION["traycontents"]) && isset($_SESSION["traycontents"])) echo '| <a href="tray.php">Tray ('.$_SESSION["traycontents"].') | ';
						echo '<a href="index.php">Log in</a> or <a href="register.php">Sign up</a>';
					}
				?>
			</div>
			
			<br/><br/>
			
			Contact details
			
			<br/>

		</div>
	</div>
	</center>
	
	<br/>

</body>

</html>