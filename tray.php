<?php

	include("prodclass.php");
	session_start();
	$dbconn = pg_connect("host=localhost port=5432 dbname=TBP user=postgres password=password");

?>
<html>

<head>
	<title>BRGR: The Burger Project Online - Menu</title>
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
					<a href="edit.php">Edit</a> | 
					<?php
						if($_SESSION["id"]=='theburgerproject@gmail.com') echo '<a href="admin.php">Products</a> | ';
						else echo '<a href="tray.php">Tray</a> | ';
					?>
					<a href="logout.php">Log out</a><br/>
				<?php }
					else{
						echo 'Welcome guest! <a href="index.php">Log in</a> or <a href="register.php">Sign up</a>';
					}
				?>
				
			</div>
			
			<br/><br/><br/>
			
			<?php if($_SESSION["traycontents"]>0){ ?>
				
					<table>
					<tr><td>Name</td><td>Quantity</td><td>Price</td></tr>
					<?php
						for($i=0;$i<count($_SESSION["tray"]);$i++){
							echo "<tr>";
							echo "<td>".$_SESSION["tray"][$i]->get_name()."</td>";
							echo "<td>".$_SESSION["tray"][$i]->get_qty()."</td>";
							echo "<td>".$_SESSION["tray"][$i]->get_price()."</td>";
							echo "</tr>";
						}
					?>		
					</table>
			
			<?php		
				}
				else{
					echo "Tray is empty";
				}	
			?>
			
		</div>
	
	</div>
	</center>

</body>

</html>