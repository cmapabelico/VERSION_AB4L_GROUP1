<?php

	session_start();
	
	//Connect to database
	$dbconn = pg_connect("host=localhost port=5432 dbname=TBP user=postgres password=password");

	if($_SESSION["id"]!='theburgerproject@gmail.com'){
		header("Location:index.php");
	}
	
?>

<html>

<head>
	<title>BRGR: The Burger Project Online - Admin</title>
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
						else echo '<a href="tray.php">Tray</a> | ';
					?>
					<a href="logout.php">Log out</a><br/>
				<?php }
					else{
						echo 'Welcome guest! <a href="index.php">Log in</a> or <a href="register.php">Sign up</a>';
					}
				?>
			</div>
			
			<br/>
			
			<a id="adminproducts" href="product.php"><img src="images/admin_products.jpg"/></a> 			
			<a id="adminmembers" href="member.php"><img src="images/admin_members.jpg"/></a>
			<a id="adminsummary" href="summary.php"><img src="images/admin_summary.jpg"/></a>
			<br/>
			<a id="adminmembers" href="order.php"><img src="images/admin_pendingorders.jpg"/></a>
			<a id="adminmembers" href="clearedorder.php"><img src="images/admin_clearedorders.jpg"/></a>
			
			<br/>
			
		</div>
	
	</div>
	</center>
	
</body>

</html>