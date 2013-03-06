<?php

	session_start();

	//Connect to database
	$dbconn = pg_connect("host=localhost port=5432 dbname=TBP user=postgres password=password");
	
?>
<!DOCTYPE html>
<html>
<head>
	<title>BRGR: The Burger Project Online</title>
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
			<a href="contact.php">Contact Us</a>
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
						echo 'Welcome guest! <a href="index.php">Log in</a> or <a href="register.php">Sign up</a>';
					}
				?>
			</div>
			
			<br/><br/><br/>
			
			<table>
			
				<tr>
					<td><img class="gallery" src="images/gallery/1.jpg"/></td>
					<td><img class="gallery" src="images/gallery/2.jpg"/></td>
				</tr>
				<tr>
					<td><img class="gallery" src="images/gallery/3.jpg"/></td>
					<td><img class="gallery" src="images/gallery/4.jpg"/></td>
				</tr>
				<tr>
					<td><img class="gallery" src="images/gallery/5.jpg"/></td>
					<td><img class="gallery" src="images/gallery/6.jpg"/></td>
				</tr>
				<tr>
					<td><img class="gallery" src="images/gallery/7.jpg"/></td>
					<td><img class="gallery" src="images/gallery/8.jpg"/></td>
				</tr>
				<tr>
					<td><img class="gallery" src="images/gallery/9.jpg"/></td>
					<td><img class="gallery" src="images/gallery/10.jpg"/></td>
				</tr>
			
			
			</table>

		</div>
	</div>
	</center>

</body>

</html>