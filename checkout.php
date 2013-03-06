<?php

	include("prodclass.php");
	session_start();
	$dbconn = pg_connect("host=localhost port=5432 dbname=TBP user=postgres password=password");

	$showform1 = true;
	$showform2 = false;
	$showform3 = false;
	
	$error ='';
	$msg = '';
	
	//Guest details
	$gname = '';
	$gcontact = '';
	
	$payment = 0;
	
	//Get user details from database
	$query = "select * from member where email='".$_SESSION["id"]."'";
	$result = pg_query($dbconn, $query) or die('Query failed: ' . pg_last_error());
	$row = pg_fetch_row($result);
	
	$email = $row[0];
	$pw = $row[1];
	$floor = $row[7];
	$bldg = $row[8];
	$street = $row[9];
	$area = $row[10];
	$city = $row[11];
	$landmark = $row[12];
	
	if(isset($_POST["submit1"])){
		
		$payment = $_POST["payment"];
		if($payment < $_SESSION["subtotal"]){
			$error = "Insufficient payment";
		}
		else{
			$showform1 = false;
			$showform2 = true;
		}
	
	}
	
	if(isset($_POST["submit2"])){
	
		$error='';
	
		$payment = $_POST["payment"];
		
		$floor = $_POST["floor"];
		$bldg = $_POST["bldg"];
		$street = $_POST["street"];
		$area = $_POST["area"];
		$city = $_POST["city"];
		$landmark = $_POST["landmark"];
		
		if(empty($street) || empty($area) || empty($city) || empty($landmark)){
			$showform1 = false;
			$showform2 = true;
			$error = "* Please enter street, area, city, and a landmark";
		}
		else{
			$showform1 = false;
			$showform2 = false;
			$showform3 = true;
		}
	
	}
	
	if(isset($_POST["submit3"])){
	
		$error='';
	
		$payment = $_POST["payment"];
		$floor = $_POST["floor"];
		$bldg = $_POST["bldg"];
		$street = $_POST["street"];
		$area = $_POST["area"];
		$city = $_POST["city"];
		$landmark = $_POST["landmark"];
		
		if($_SESSION["id"]!=null){
			$password = md5($_POST["password"]);
			if($password!=$pw){
				$error = "Wrong password";
				$showform1 = false;
				$showform2 = false;
				$showform3 = true;
			}
			else{
				$showform1 = false;
				$showform2 = false;
				$showform3 = false;
				$msg = "Congratulations!";
				//Reset number of tray contents, subtotal, & tray
				$_SESSION["traycontents"] = 0;
				$_SESSION["subtotal"] = 0;
				$newtray = array();
				$_SESSION["tray"] = $newtray;
			}
		}
		else{
			$gname = $_POST["guestname"];
			$gcontact = $_POST["guestcontact"];
			if(empty($gname) || empty($gcontact)){
				$error = "* Incomplete details";
				$showform1 = false;
				$showform2 = false;
				$showform3 = true;
			}
			else{
				$showform1 = false;
				$showform2 = false;
				$showform3 = false;
				$msg = "Congratulations!";
				//Reset number of tray contents, subtotal, & tray
				$_SESSION["traycontents"] = 0;
				$_SESSION["subtotal"] = 0;
				$newtray = array();
				$_SESSION["tray"] = $newtray;
			}	
		}
		
	}
	
?>
<html>

<head>
	<title>BRGR: The Burger Project Online - Checkout</title>
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
			
			<?php if($showform1) { ?>
			<table class="traytable" cellspacing="0" cellpadding="0">
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
				<!-- Subtotal -->
				<tr class="th">
					<td id="empty"></td>
					<td><b>Subtotal</b></td>
					<td><?php  echo $_SESSION["subtotal"]; ?></td>
				</tr>
			</table>
			
			<form name="checkoutform" action="checkout.php" method="POST">
					Prepare change for <input type="number" min="0" name="payment" value="<?php echo $payment; ?>"/> 
					<input type="submit" name="submit1" value="Submit"/><br/>
					<?php if($error!='') echo $error; ?>
			</form>
			<?php } ?>
			
			
			<?php if($showform2) { ?>
			<form name="checkoutform2" action="checkout.php" method="POST">
				<table class="editformtable">
					<tr><td class="title">Floor/Dept/House no</td><td><input type="text" name="floor" placeholder="Floor/Dept/House no" value="<?php echo $floor; ?>" /></td></tr>
					<tr><td class="title">Building</td><td><input type="text" name="bldg" placeholder="Building" value="<?php echo $bldg; ?>" /></td></tr>
					<tr><td class="title">Street</td><td><input type="text" name="street" placeholder="Street" value="<?php echo $street; ?>" /></td></tr>
					<tr><td class="title">Area</td><td><input type="text" name="area" placeholder="Area" value="<?php echo $area; ?>" /></td></tr>
					<tr><td class="title">City</td><td><input type="text" name="city" placeholder="City" value="<?php echo $city; ?>" /></td></tr>
					<tr><td class="title">Landmark/s</td><td><input type="text" name="landmark" placeholder="Landmark" value="<?php echo $landmark; ?>" /></td></tr>
				</table>
				
					<?php if($error!='') echo $error.'<br/><br/>'; ?>
				
					<input type="hidden" name="payment" value="<?php echo $payment; ?>"/>
					<input type="submit" name="submit2" value="Submit"/>
			</form>
			<?php } ?>
			
			<?php if($showform3){ ?>
			<form name="checkoutform3" action="checkout.php" method="POST">
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
					<tr><td colspan="3"><br/></td></tr>
					<tr><td class="title">Floor/Dept/House no</td><td colspan="2"><input type="text" name="floor" disabled="true" value="<?php echo $floor; ?>" /></td></tr>
					<tr><td class="title">Building</td><td colspan="2"><input type="text" name="bldg" disabled="true" value="<?php echo $bldg; ?>" /></td></tr>
					<tr><td class="title">Street</td><td colspan="2"><input type="text" name="street" disabled="true" value="<?php echo $street; ?>" /></td></tr>
					<tr><td class="title">Area</td><td colspan="2"><input type="text" name="area" disabled="true" value="<?php echo $area; ?>" /></td></tr>
					<tr><td class="title">City</td><td colspan="2"><input type="text" name="city" disabled="true" value="<?php echo $city; ?>" /></td></tr>
					<tr><td class="title">Landmark/s</td><td colspan="2"><input type="text" name="landmark" disabled="true" value="<?php echo $landmark; ?>" /></td></tr>
					
					<tr><td colspan="3"><br/></td></tr>
					
					<input type="hidden" name="floor" value="<?php echo $floor; ?>"/>
					<input type="hidden" name="bldg" value="<?php echo $bldg; ?>"/>
					<input type="hidden" name="street" value="<?php echo $street; ?>"/>
					<input type="hidden" name="area" value="<?php echo $area; ?>"/>
					<input type="hidden" name="city" value="<?php echo $city; ?>"/>
					<input type="hidden" name="landmark" value="<?php echo $landmark; ?>"/>
					<input type="hidden" name="payment" value="<?php echo $payment; ?>"/>
					
					
					<tr><td>Subtotal</td><td colspan="2"><?php echo $_SESSION["subtotal"]; ?></td></tr>
					<tr><td>Payment</td><td colspan="2"><?php echo $payment; ?></td></tr>
					<tr><td>Change</td><td colspan="2"><?php echo $payment - $_SESSION["subtotal"]; ?></td></tr>
					
					<tr><td colspan="3"><br/></td></tr>
					
					<?php if($_SESSION["id"]!=null){ ?>
					<tr><td class="title">Email</td><td colspan="2"><input type="text" name="email" disabled="true" value="<?php echo $email; ?>" /></td></tr>
					<tr><td class="title">Password</td><td colspan="2"><input type="password" name="password"/><?php if($error!='') echo $error; ?></td></tr>
					<?php }else{ ?>
					<tr><td class="title">Name</td><td colspan="2"><input type="text" name="guestname" value="<?php echo $gname; ?>" /></td></tr>
					<tr><td class="title">Contact no</td><td colspan="2"><input type="number" name="guestcontact" value="<?php echo $gcontact; ?>"/><?php if($error!='') echo "<br/>".$error; ?></td></tr>
					<?php } ?>
					<tr><td></td><td><br/><input type="submit" name="submit3" value="Confirm Transaction"/></td><td></td></tr>
				
				</table>
			</form>
			<?php } ?>
			
			<?php if($msg!='') echo $msg; ?>
			
		</div>
	
	</div>
	</center>

</body>

</html>