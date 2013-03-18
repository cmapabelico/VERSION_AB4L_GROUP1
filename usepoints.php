<?php

	include("prodclass.php");
	session_start();
	$dbconn = pg_connect("host=localhost port=5432 dbname=TBP user=postgres password=password");

	$error ='';
	$msg = '';
	
	$payment = 0;
	$updatesub = 0;
	$upoints = 0;
	$points= 0;
	$var = 0;
	
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
		header("Location: tray.php");
	}
	
	//Get user details from database
	$query = "select * from member where email='".$_SESSION["id"]."'";
	$result = pg_query($dbconn, $query) or die('Query failed: ' . pg_last_error());
	$row = pg_fetch_row($result);
	
	
	$num_items = $_SESSION["traycontents"];	
	$subtotal = $_SESSION["subtotal"];
	//$change = $payment - $_SESSION["subtotal"];
	$temp = $payment;
	
		
	$email = $row[0];
	$pw = $row[1];
	$floor = $row[7];
	$bldg = $row[8];
	$street = $row[9];
	$area = $row[10];
	$city = $row[11];
	$landmark = $row[12];
	
	
	if(isset($_POST["submit3"])){
		$var = $_POST["newsub"];
		//echo $_SESSION['subtotal'];
		//echo $var;
		$_SESSION['subtotal'] = $var;
		//echo $var;
		//echo $_SESSION['subtotal'];
		//$query = "update orders set oprice = $var where order_id = '".$_SESSION["order_id"]."'";
		header('location:checkout.php');
		
	}
	
	if(isset($_POST["update"])){
		$upoints = $_POST["points"];
		$var = $_SESSION["subtotal"];
		$var -= (($upoints*0.02)*$var);
		
		$query = "select * from member where email='".$_SESSION["id"]."'";
		$result = pg_query($dbconn, $query);
		$row = pg_fetch_row($result);
		$cpoints = $row[19] - $upoints;
		$query1 = "update member set points =$cpoints where email =  '".$_SESSION["id"]."'";
		pg_query($dbconn, $query1) or die('Query failed: ' . pg_last_error());
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
					<a href="edit.php">Edit</a> | 
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
			
			
			<form name="checkoutform3" action="usepoints.php" method="POST">
				<table class="traytable" cellspacing="0" cellpadding="0" width="400">
						<tr class="th">
							<td><b>Product Name</b></td>
							<td><b>Quantity</b></td>
							<td><b>Price</b></td>
						</tr>
					<?php
						$subtotal = 0;
						//Print tray items
						$n = count($_SESSION["tray"]);
						for($i=0;$i<$n;$i++){
					?>
						<tr>
							<td><?php echo $_SESSION["tray"][$i]->name; ?></td>
							<td><?php echo $_SESSION["tray"][$i]->qty; ?> </td>
							<td><?php echo $_SESSION["tray"][$i]->price * $_SESSION["tray"][$i]->qty; ?></td>
						</tr>
					<?php
						}
					?>					
		
					
					
					<tr><td>Subtotal</td><td colspan="2"><?php echo $_SESSION["subtotal"]; ?></td></tr>
					<tr><td>Available Points</td><td colspan="2"><?php 
						$query = "select * from member where email='".$_SESSION["id"]."'";
						$result = pg_query($dbconn, $query);
						$row = pg_fetch_row($result);
						echo $row[19];
					?></td></tr>
					<tr>
						<td>How much points?</td>
						<td colspan="2"><input type="number" min="0" name="points" value="<?php echo $points; ?>"/> 
							<input type="submit" name="update" value="&#10003;"/>
						</td>
					</tr>
					<tr><td class="title">New subtotal</td><td colspan="2">
						<input type="hidden" name="newsub" value="<?php echo $var;?>"/>
						<input type="text" name="newsub" disabled="true" value="<?php echo $var;?>"/></td></tr>
					<tr><td class="title">Email</td><td colspan="2"><input type="text" name="email" disabled="true" value="<?php echo $email; ?>" /></td></tr>
					<tr><td class="title">Password</td><td colspan="2"><input type="password" name="password"/><?php if($error!='') echo $error; ?></td></tr>
					<tr><td></td><td><input type="submit" name="submit3" value="Update Transaction"/></td><td></td></tr>
				</table>
			</form>
			
			<?php if($msg!='') echo $msg; ?>
			
		</div>
	
	</div>
	</center>

</body>

</html>