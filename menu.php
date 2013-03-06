<?php

	include("prodclass.php");
	session_start();
	$dbconn = pg_connect("host=localhost port=5432 dbname=TBP user=postgres password=password");

	//ORDER
	if(isset($_POST["submit"])){
	
		if(isset($_POST["premade"])){
			$premade = $_POST["premade"];
			if(count($premade) > 0){
				for($i=0;$i < count($premade);$i++){
					//get item details
					$q = "select * from product where pname='".$premade[$i]."';";
					$r = pg_query($dbconn, $q);
					$row = pg_fetch_row($r);
					//add to tray
					$p = new Product($row[0], $row[1], 1);
					array_push($_SESSION["tray"], $p);
					$_SESSION["traycontents"]++;
					$_SESSION["subtotal"]+=$row[1];
				}
			}	
		}
		
		if(isset($_POST["sides"])){
			$sides = $_POST["sides"];
			if(count($sides) > 0){
				for($i=0;$i < count($sides);$i++){
					//get item details
					$q = "select * from product where pname='".$sides[$i]."';";
					$r = pg_query($dbconn, $q);
					$row = pg_fetch_row($r);
					//add to tray
					$p = new Product($row[0], $row[1], 1);
					array_push($_SESSION["tray"], $p);
					$_SESSION["traycontents"]++;
					$_SESSION["subtotal"]+=$row[1];
				}
			}	
		}

		if(isset($_POST["milkshake"])){
			$milkshake = $_POST["milkshake"];
			if(count($milkshake) > 0){
				for($i=0;$i < count($milkshake);$i++){
					//get item details
					$q = "select * from product where pname='".$milkshake[$i]."';";
					$r = pg_query($dbconn, $q);
					$row = pg_fetch_row($r);
					//add to tray
					$p = new Product($row[0], $row[1], 1);
					array_push($_SESSION["tray"], $p);
					$_SESSION["traycontents"]++;
					$_SESSION["subtotal"]+=$row[1];
				}
			}
		}
		
		
		if(isset($_POST["beverage"])){
			$beverage = $_POST["beverage"];
			if(count($beverage) > 0){
				for($i=0;$i < count($beverage);$i++){
					//get item details
					$q = "select * from product where pname='".$beverage[$i]."';";
					$r = pg_query($dbconn, $q);
					$row = pg_fetch_row($r);
					//add to tray
					$p = new Product($row[0], $row[1], 1);
					array_push($_SESSION["tray"], $p);
					$_SESSION["traycontents"]++;
					$_SESSION["subtotal"]+=$row[1];
				}
			}
		}
		header("Location:tray.php");
	}
	
	if(isset($_POST["create"])){
		header("Location: create.php");
	}
	
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
					<a href="edit.php">Profile</a> | 
					<?php
						if($_SESSION["id"]=='theburgerproject@gmail.com') echo '<a href="admin.php">Admin</a> | ';
						else echo '<a href="tray.php">Tray ('.$_SESSION["traycontents"].')</a> | ';
					?>
					<a href="logout.php">Log out</a><br/>
				<?php }
					else{
						echo 'Welcome guest! ';
						if($_SESSION["traycontents"] > 0) echo '<a href="tray.php">Tray ('.$_SESSION["traycontents"].') | ';
						echo '<a href="index.php">Log in</a> or <a href="register.php">Sign up</a>';
					}
				?>
				
			</div>
			
			<br/><br/><br/>
			
			<table class="menutable"><tr>
			<form name="menuform" action="menu.php" method="POST">
		
			<td>
			<h1>DESIGNR BRGRS</h1><br/>
			<?php
				$query = "select * from product where ptype='premade'";
				$result = pg_query($dbconn, $query) or die('Query failed: ' . pg_last_error());
				while($row=pg_fetch_row($result)){
					echo '<input type="checkbox" name="premade[]" value="'.$row[0].'"/> '.$row[0].'<br/>';
				}
			?>
			</td>
		
			<td>
			<h1>SIDES</h1><br/>	
			<?php
				$query = "select * from product where ptype='sides'";
				$result = pg_query($dbconn, $query) or die('Query failed: ' . pg_last_error());	
				while($row=pg_fetch_row($result)){
					echo '<input type="checkbox" name="sides[]" value="'.$row[0].'"/> '.$row[0].'<br/>';
				}
			?>
			</td>

			<td>
			<h1>MILKSHAKES</h1><br/>
			<?php
				$query = "select * from product where ptype='milkshake'";
				$result = pg_query($dbconn, $query) or die('Query failed: ' . pg_last_error());	
				while($row=pg_fetch_row($result)){
					echo '<input type="checkbox" name="milkshake[]" value="'.$row[0].'"/> '.$row[0].'<br/>';
				}
			?>
			</td>
			
			<td>
			<h1>BEVERAGES</h1><br/>
			<?php
				$query = "select * from product where ptype='beverage'";
				$result = pg_query($dbconn, $query) or die('Query failed: ' . pg_last_error());	
				while($row=pg_fetch_row($result)){
					echo '<input type="checkbox" name="beverage[]" value="'.$row[0].'"/> '.$row[0].'<br/>';
				}
			?>
			</td>
			
			</tr>
			
			<tr><td colspan="4"><center>
				<input style="width: 200px;" type="submit" name="submit" value="Add items to tray"/><br/>
				<input style="width: 200px;" type="submit" name="create" value="Create custom burger"/><br/>
			</center></td></tr>
			
			<tr>
				<td class="menupic" colspan="2"><img src="images/menu/1.jpg"/></td>
				<td class="menupic" colspan="2"><img src="images/menu/2.jpg"/></td>
			</tr>
			<tr>
				<td class="menupic" colspan="2"><img src="images/menu/3.jpg"/></td>
				<td class="menupic" colspan="2"><img src="images/menu/4.jpg"/></td>
			</tr>
			<tr>
				<td class="menupic" colspan="2"><img src="images/menu/5.jpg"/></td>
				<td class="menupic" colspan="2"><img src="images/menu/6.jpg"/></td>
			</tr>
			
			</form>				
			</table>
			
		</div>
	
	</div>
	</center>

</body>

</html>