<?php

	include("prodclass.php");
	session_start();
	$dbconn = pg_connect("host=localhost port=5432 dbname=TBP user=postgres password=password");

	//UPDATE QUANTITY
	for($i=0;$i<count($_SESSION["tray"]);$i++){
		if(isset($_POST["update$i"])){
			$newqty = $_POST["quantity$i"];
			$prevqty = $_POST["prevquantity$i"];
			$price = $_POST["prodprice$i"];
			if($newqty>0){
				//Set new quantity
				$_SESSION["tray"][$i]->set_qty($newqty);
				//Update number of tray contents
				$_SESSION["traycontents"]-=$prevqty;
				$_SESSION["traycontents"]+=$newqty;
				//Update subtotal
				$_SESSION["subtotal"]-=($prevqty * $price);
				$_SESSION["subtotal"]+=($newqty * $price);
			}
			else if($newqty==0){	
				//Remove item from tray
				unset($_SESSION["tray"][$i]);
				//Update indices
				$_SESSION["tray"] = array_values($_SESSION["tray"]);
				//Update number of items in tray
				$_SESSION["traycontents"]-=$prevqty;
				//Update subtotal
				$_SESSION["subtotal"]-=($prevqty * $price);
			}
		}
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
						echo 'Welcome guest! <a href="index.php">Log in</a> or <a href="register.php">Sign up</a>';
					}
				?>
				
			</div>
			
			<br/><br/><br/>
			
			<?php if($_SESSION["traycontents"]>0){ ?>
				
					<table class="traytable" cellspacing="0" cellpadding="0">
					<form name="form" action="tray.php" method="post">
						
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
							<td>
								<?php

									if($_SESSION["tray"][$i]->name == "Custom burger"){
										echo "Custom burger";
										echo "<ul>";
										echo "<li>".$_SESSION["tray"][$i]->brgr."</li>";
										echo "<li>".$_SESSION["tray"][$i]->bun."</li>";
										echo "<li>".$_SESSION["tray"][$i]->cheese."</li>";
										echo "<li>".$_SESSION["tray"][$i]->basic."</li>";
										echo "<li>".$_SESSION["tray"][$i]->premium."</li>";
										echo "<li>".$_SESSION["tray"][$i]->sauce."</li>";
										echo "</ul>";
									}
									else echo $_SESSION["tray"][$i]->name;
								?>
							</td>
							<td>
								<input type="number" min="0" name="quantity<?php echo $i; ?>" value="<?php echo $_SESSION["tray"][$i]->qty; ?>"/> 
								<input type="hidden" name="prevquantity<?php echo $i; ?>" value="<?php echo $_SESSION["tray"][$i]->qty; ?>"/>
								<input type="hidden" name="prodprice<?php echo $i; ?>" value="<?php echo $_SESSION["tray"][$i]->price; ?>"/>
								<input type="submit" name="update<?php echo $i; ?>" value="&#10003;"/>
							</td>
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
					
					<br/>
					
					<a href="checkout.php">Checkout</a>
			
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