<?php

	include("prodclass.php");
	session_start();
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
	
	$brgr='';
	$bun='';
	$cheese='';
	$premium='';
	$basic='';
	$sauce='';
	$price = 0;
	
	$errorbrgr='';
	$errorbun='';
	
	if(isset($_POST["submitburger"])){
	
		if(isset($_POST["brgr"])){
			$brgr = $_POST["brgr"];
			$q = "select * from product where pname='".$brgr."';";
			$r = pg_query($dbconn, $q);
			$row = pg_fetch_row($r);
			$price += $row[1];
		}else{
			$errorbrgr = "BRGR required";
		}
		
		if(isset($_POST["bun"])){
			$bun = $_POST["bun"];
			$q = "select * from product where pname='".$bun."';";
			$r = pg_query($dbconn, $q);
			$row = pg_fetch_row($r);
			$price += $row[1];
		}else{
			$errorbun = "Bun required";
		}
		
		if(isset($_POST["cheese"])){
			$c = $_POST["cheese"];
			if(count($c) > 0){
				for($i=0;$i < count($c);$i++){
					//get item details
					$q = "select * from product where pname='".$c[$i]."';";
					$r = pg_query($dbconn, $q);
					$row = pg_fetch_row($r);
					$cheese.= $row[0]."<br/>";
					$price += $row[1];
				}
			}
		}
		else{
			$cheese = null;
		}
		
		if(isset($_POST["premium"])){
			$pr = $_POST["premium"];
			if(count($pr) > 0){
				for($i=0;$i < count($pr);$i++){
					//get item details
					$q = "select * from product where pname='".$pr[$i]."';";
					$r = pg_query($dbconn, $q);
					$row = pg_fetch_row($r);
					$premium.= $row[0]."<br/>";
					$price += $row[1];
				}
			}
		}
		else{
			$premium = null;
		}
		
		if(isset($_POST["basic"])){
			$b = $_POST["basic"];
			if(count($b) > 0){
				for($i=0;$i < count($b);$i++){
					//get item details
					$q = "select * from product where pname='".$b[$i]."';";
					$r = pg_query($dbconn, $q);
					$row = pg_fetch_row($r);
					$basic.= $row[0]."<br/>";
					$price += $row[1];
				}
			}
		}
		else{
			$basic = null;
		}
		
		if(isset($_POST["sauce"])){
			$s = $_POST["sauce"];
			if(count($s) > 0){
				for($i=0;$i < count($s);$i++){
					//get item details
					$q = "select * from product where pname='".$s[$i]."';";
					$r = pg_query($dbconn, $q);
					$row = pg_fetch_row($r);
					$sauce.= $row[0]."<br/>";
					$price += $row[1];
				}
			}
		}
		else{
			$sauce = null;
		}
		
		if($brgr!='' && $bun!=''){
			$b = new Burger($brgr, $bun, $cheese, $premium, $basic, $sauce, 1, $price);
			array_push($_SESSION["tray"], $b);
			$_SESSION["traycontents"]++;
			$_SESSION["subtotal"]+=$price;
			header("Location: tray.php");
		}
		
	}
	
?>
<html>

<head>
	<title>BRGR: The Burger Project Online - Menu: Custom Burger</title>
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
		
			<img src="images/customburger.png"/>
		
			<table class="createtable">
			<form name="createburger" action="create.php" method="POST">
			
			<tr><td colspan="2">
			<h1>STEP 1: CHOOSE A BRGR * <?php if($errorbrgr!='') echo '<text class="error">'.$errorbrgr.'</text>'; ?></h1>
			<center>
			<?php
				$query = "select * from product where ptype='brgr'";
				$result = pg_query($dbconn, $query) or die('Query failed: ' . pg_last_error());	
				while($row=pg_fetch_row($result)){
					echo '<input type="radio" name="brgr" value="'.$row[0].'"/> '.$row[0].' (P'.$row[1].') ';
				}
			?>
			</center><br/>
			</td></tr>
			
			<tr><td colspan="2">
			<h1>STEP 2: CHOOSE A BUN * <?php if($errorbun!='') echo '<text class="error">'.$errorbun.'</text>'; ?></h1>
			<center>
			<?php
				$query = "select * from product where ptype='bun'";
				$result = pg_query($dbconn, $query) or die('Query failed: ' . pg_last_error());	
				while($row=pg_fetch_row($result)){
					echo '<input type="radio" name="bun" value="'.$row[0].'"/> '.$row[0].' (P'.$row[1].') ';
				}
			?>
			</center><br/>
			</td></tr>
		
			<tr><td>
			<h1>STEP 3: CHOOSE A CHEESE</h1>
			<?php
				$query = "select * from product where ptype='cheese'";
				$result = pg_query($dbconn, $query) or die('Query failed: ' . pg_last_error());	
				while($row=pg_fetch_row($result)){
					echo '<input type="checkbox" name="cheese[]" value="'.$row[0].'"/> '.$row[0].'(P'.$row[1].') <br/>';
				}
			?>
			</td>
			<td>
			<h1>STEP 4: CHOOSE YOUR PREMIUM TOPPINGS</h1>
			<?php
				$query = "select * from product where ptype='premium'";
				$result = pg_query($dbconn, $query) or die('Query failed: ' . pg_last_error());	
				while($row=pg_fetch_row($result)){
					echo '<input type="checkbox" name="premium[]" value="'.$row[0].'"/> '.$row[0].'(P'.$row[1].') <br/>';
				}
			?>
			</td></tr>
			
			<tr><td>
			<h1>STEP 5: CHOOSE YOUR BASIC TOPPINGS</h1>
			<?php
				$query = "select * from product where ptype='basic'";
				$result = pg_query($dbconn, $query) or die('Query failed: ' . pg_last_error());	
				while($row=pg_fetch_row($result)){
					echo '<input type="checkbox" name="basic[]" value="'.$row[0].'"/> '.$row[0].'(P'.$row[1].') <br/>';
				}
			?>
			</td>
			<td>
			<h1>STEP 6: CHOOSE A SAUCE</h1>
			<?php
				$query = "select * from product where ptype='sauce'";
				$result = pg_query($dbconn, $query) or die('Query failed: ' . pg_last_error());	
				while($row=pg_fetch_row($result)){
					echo '<input type="checkbox" name="sauce[]" value="'.$row[0].'"/> '.$row[0].'(P'.$row[1].') <br/>';
				}
			?>			
			</td></tr>
			
			<tr><td colspan="2">
			<center><input type="submit" name="submitburger" value="Finish"/></center>
			</td></tr>
			
			</form>
			</table>
			
		</div>
	
		<br/>
	
	</div>
	</center>

	<br/>
	
</body>

</html>