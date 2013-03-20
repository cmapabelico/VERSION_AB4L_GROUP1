
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
	
	$query = 'select * from orders';
	$result = pg_query($dbconn, $query) or die('Query failed: ' . pg_last_error());
	
	while($row=pg_fetch_row($result)){
		$temp = str_replace(".","",$row[0]);
	
		//clear order
		if(isset($_POST["clear$temp"])){
			$status = 'cleared';
			$query = "update cleared_orders set order_status = '$status'  where order_id = '$row[0]'";
			pg_query($dbconn, $query) or die('Query failed: ' . pg_last_error());
			$q = "delete from orders where order_id = $row[0]";
			$r = pg_query($dbconn, $q) or die('Query failed: ' . pg_last_error());
			$update = true;
			 
			
		}
		//View order
		if(isset($_POST["view$temp"])){

			$q = "select * from orders where order_id='$row[0]'";
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
			$status = $row[10];
						
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
					<a href="edit.php">Edit</a> | 
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
			
			<br/><br/><br/>
			
			<?php if($vieworder){  ?>
			
				<table>
					<tr><td>Order id</td><td><?php echo $order_id; ?></td></tr>
					<tr><td>Date created</td><td><?php echo $date_created; ?></td></tr>
					<tr><td>Date cleared</td><td><?php echo $date_cleared; ?></td></tr>
					<tr><td>Order type</td><td><?php echo $otype; ?></td></tr>
					<tr><td>Customer email</td><td><?php echo $email; ?></td></tr>
					<tr><td>Items</td><td><?php echo $items; ?></td></tr>
					<tr><td>Total number of items</td><td><?php echo $num_items; ?></td></tr>
					<tr><td>Subtotal</td><td><?php echo $subtotal; ?></td></tr>
					<tr><td>Cash</td><td><?php echo $cash; ?></td></tr>
					<tr><td>Status</td><td><?php echo $status; ?></td></tr>
				</table>
			
				<br/><br/><br/>
			
			<?php } ?>
			
			<?php if($update){
				echo "<b>ORDER LIST UPDATED</b><br/><br/>"; 
				echo "<b>CLEARED ORDER LIST UPDATED</b><br/><br/>";
				}
			?>
			
			<!--ORDER LIST-->
			ORDERS List<br/><br/>
			
			<table class="prodtable">
			<form name="orderform" action="order.php" method="POST">
			<?php
				//Print all members from database
				$query = 'select * from orders';
				$result = pg_query($dbconn, $query) or die('Query failed: ' . pg_last_error());
				while($row=pg_fetch_row($result)){
					echo '<tr>';
					echo '<td><b>Order #'.$row[0].'</b><br/>Date created:'.$row[2].'<br/>Status:'.$row[10].'<br/>Ordered by:'.$row[4].'</td>';
					$temp = str_replace(".","",$row[0]);
					echo '<td><input type="submit" name="view'.$temp.'" value="View"/><br/>';
					echo '<input type="submit" name="clear'.$temp.'" value="Clear"/></td>';
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