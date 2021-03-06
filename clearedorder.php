 
<?php

	session_start();
	
	//Connect to database
	$dbconn = pg_connect("host=localhost port=5432 dbname=TBP user=postgres password=password");
	$update = false;
	$vieworder = false;
	$order_id = '';
	$date_created = '';
	$date_cleared = '';
	$otype = '';
	$email = '';
	$items = '';
	$num_items = '';
	$subtotal = '';
	$cash = '';
	$status = '';

	
	//If user is not admin, redirect to home page
	if($_SESSION["id"]!='theburgerproject@gmail.com'){
		header("Location:index.php");
	}
	
	$query = 'select * from cleared_orders';
	$result = pg_query($dbconn, $query) or die('Query failed: ' . pg_last_error());
	
	while($row=pg_fetch_row($result)){
		$temp = str_replace(".","",$row[0]);
		
		//View order
		if(isset($_POST["view$temp"])){

			$q = "select * from cleared_orders where order_id='$row[0]'";
			$r = pg_query($dbconn, $q) or die('Query failed: ' . pg_last_error());
			
			//Get user details
			$order_id = $row[0];
			$date_created = $row[1];
			$date_cleared = $row[2];
			$otype = $row[3];
			$email = $row[4];
			$items = $row[5];
			$num_items = $row[6];
			$subtotal = $row[7];
			$cash = $row[8];
			$status = $row[9];
						
			$vieworder = true;
		}
	}
	
	if(isset($_POST["view@viewcom"])){
		$viewmember = true;
	}
	
	pg_free_result($result);
	
?>

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
			
			<?php if($vieworder){  ?>
			
				<h2>Viewing information for order id <?php echo $order_id; ?></h2>
				<table class="viewtable">
					<tr><td class="viewtitle">Order id</td><td><?php echo $order_id; ?></td></tr>
					<tr><td class="viewtitle">Date created</td><td><?php echo $date_created; ?></td></tr>
					<tr><td class="viewtitle">Date cleared</td><td><?php echo $date_cleared; ?></td></tr>
					<tr><td class="viewtitle">Order type</td><td><?php echo $otype; ?></td></tr>
					<tr><td class="viewtitle">Customer email</td><td><?php echo $email; ?></td></tr>
					<tr><td class="viewtitle">Items</td><td><?php echo $items; ?></td></tr>
					<tr><td class="viewtitle">Total number of items</td><td><?php echo $num_items; ?></td></tr>
					<tr><td class="viewtitle">Subtotal</td><td><?php echo $subtotal; ?></td></tr>
					<tr><td class="viewtitle">Cash</td><td><?php echo $cash; ?></td></tr>
					<tr><td class="viewtitle">Status</td><td><?php echo $status; ?></td></tr>
				</table>
				<br/>
			
			<?php } ?>
			
			<!--ORDER LIST-->
			<h2>List of Cleared Orders</h2>
			
			<table class="prodtable">
			<form name="orderform" action="clearedorder.php" method="POST">
			<?php
				//Print all members from database
				$query = "select * from cleared_orders where order_status ='cleared'";
				$result = pg_query($dbconn, $query) or die('Query failed: ' . pg_last_error());
				while($row=pg_fetch_row($result)){
					echo '<tr>';
					echo '<td><b>Order #'.$row[0].'</b><br/>Date cleared:'.$row[1].'<br/>Status:'.$row[9].'<br/>Ordered by:'.$row[4].'</td>';
					$temp = str_replace(".","",$row[0]);
					echo '<td><input type="submit" name="view'.$temp.'" value="View"/><br/>';
					echo '</tr>';
				}
				pg_free_result($result);
			?>
			</form>
			</table>
			
		</div>
	
	</div>
	</center>
	
</body>

</html>