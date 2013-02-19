<?php

	session_start();
	
	$dbconn = pg_connect("host=localhost port=5432 dbname=TBP user=postgres password=password");
	$update = false;
	$showeditform = false;
	
	if(isset($_POST["addsubmit"])){
	
		$name = $_POST["name"];
		$price = $_POST["price"];
		$type = $_POST["type"];
	
		$query = "insert into product values('$name', $price, '$type');";
		pg_query($dbconn, $query) or die('Query failed: ' . pg_last_error());
		$update = true;
	
	}
	
	if(isset($_POST["editsubmit"])){
	
		$pname = $_POST["pname"];
		$pprice = $_POST["pprice"];
		$ptype = $_POST["ptype"];
		$p = $_POST["oldname"];
	
		$query = "update product set pname='$pname', price=$pprice, ptype='$ptype' where pname='$p';";
		pg_query($dbconn, $query) or die('Query failed: ' . pg_last_error());
		$update = true;
	
	}
	
	$query = 'select * from product';
	$result = pg_query($dbconn, $query) or die('Query failed: ' . pg_last_error());
		
	while($row=pg_fetch_row($result)){
		if(isset($_POST["delete$row[0]"])){
			$q = "delete from product where pname='".$row[0]."'";
			$r = pg_query($dbconn, $q) or die('Query failed: ' . pg_last_error());
			$update = true;
		}
		
		if(isset($_POST["edit$row[0]"])){
			$showeditform = true;
			$q = "select * from product where pname='$row[0]'";
			$r = pg_query($dbconn, $q) or die('Query failed: ' . pg_last_error());
			$p = $row[0];
			$pname = $row[0];
			$pprice = $row[1];
			$ptype = $row[2];
		}
		
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
			
			<?php if($update) echo 'Products list updated<br/><br/>'; ?>	
			
			Add Product<br/><br/>
			<table class="addprodtable">
			<form name="addform" action="admin.php" method="POST">
				<tr><td style="text-align: right; font-weight: bold;">Product name</td><td><input type="text" name="name" placeholder="Name"/></td></tr>
				<tr><td style="text-align: right; font-weight: bold;">Price</td><td><input type="number" name="price" placeholder="Price"/></td></tr>
				<tr><td style="text-align: right; font-weight: bold;">Type</td><td><input type="text" name="type" placeholder="Type"/></td></tr>
				<tr><td colspan="2"><center><input type="submit" name="addsubmit" value="Submit"/></center></td></tr>
			</form>
			</table>
			
			<br/><br/><br/>
			
			<?php if($showeditform){ ?>
				Edit Product<br/>
				
				<table class="addprodtable">
				<form name="editform" action="admin.php" method="POST">
					<tr><td style="text-align: right; font-weight: bold;">Product name</td><td><input type="text" name="pname" placeholder="Name" <?php if($pname!=null) echo 'value="'.$pname.'"'; ?>/></td></tr>
					<tr><td style="text-align: right; font-weight: bold;">Price</td><td><input type="number" name="pprice" placeholder="Price" <?php if($pprice!=null) echo 'value="'.$pprice.'"'; ?>/></td></tr>
					<tr><td style="text-align: right; font-weight: bold;">Type</td><td><input type="text" name="ptype" placeholder="Type" <?php if($ptype!=null) echo 'value="'.$ptype.'"'; ?>/></td></tr>
					<tr><td colspan="2"><center><input type="submit" name="editsubmit" value="Submit"/></center></td></tr>
					<input type="hidden" name="oldname" value="<?php echo $p; ?>"/>
				</form>
				</table>
				
				<br/><br/><br/>
			<?php } ?>
			
			Products List<br/><br/>
			
			<table class="prodtable">
			<form name="prodform" action="admin.php" method="POST">
			<?php
			
				$query = 'select * from product';
				$result = pg_query($dbconn, $query) or die('Query failed: ' . pg_last_error());
		
				while($row=pg_fetch_row($result)){
					echo '<tr>';
					echo '<td><b>'.$row[0].'</b><br/>Php '.$row[1].'<br/>'.$row[2].'</td>';
					echo '<td><input type="submit" name="edit'.$row[0].'" value="Edit"/><br/>';
					echo '<input type="submit" name="delete'.$row[0].'" value="Delete"/></td>';
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