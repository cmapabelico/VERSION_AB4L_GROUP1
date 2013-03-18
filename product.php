<?php

	session_start();
	
	//Connect to database
	$dbconn = pg_connect("host=localhost port=5432 dbname=TBP user=postgres password=password");
	$update = false;
	$showeditform = false;
	
	//If user is not admin, redirect to home page
	if($_SESSION["id"]!='theburgerproject@gmail.com'){
		header("Location:index.php");
	}

	$error1 = "";
	$error2 = "";
	$error3 = "";
	
	//Add product form
	if(isset($_POST["addsubmit"])){
	
		$error1="";
		$error2="";
		$error3="";
	
		$update = true;
		$name = $_POST["name"];
		$price = $_POST["price"];
	
		//Check name
		if(empty($name)){
			$update = false;
			$error1 = "* Name required";
		}
		else{
			$n = pg_query($dbconn, "select * from product where pname='$name'");
			if(pg_num_rows($n)>0){
				$update = false;
				$error1 = "* Product already exists";
			}
		}
		//Check price
		if(empty($price)){
			$update = false;
			$error2 = "* Price required";
		}
		else if($price <= 0){
			$update = false;
			$error2 = "* Invalid price";
		}
		//Check type
		if(!isset($_POST["ptype"])){
			$update = false;
			$error3 = "* Type required";
		}
		else{
			$type = $_POST["ptype"];
		}
	
		//Add product to database
		if($update){
			$query = "insert into product values('$name', $price, '$type');";
			pg_query($dbconn, $query) or die('Query failed: ' . pg_last_error());	
			$name="";$price="";$type="";
		}
	
	}

	//Edit product form
	if(isset($_POST["editsubmit"])){
	
		$error1="";
		$error2="";
		$error3="";
	
		$pname = $_POST["pname"];
		$pprice = $_POST["pprice"];
		$p = $_POST["oldname"];
	
		//Check name
		if(empty($pname)){
			$update = false;
			$error1 = "* Name required";
		}
		else{
			$n = pg_query($dbconn, "select * from product where pname='$pname'");
			if(pg_num_rows($n)>0){
				$update = false;
				$error1 = "* Product already exists";
			}
		}
		//Check price
		if(empty($pprice)){
			$update = false;
			$error2 = "* Price required";
		}
		else if($pprice <= 0){
			$update = false;
			$error2 = "* Invalid price";
		}
		///Check type
		if(!isset($_POST["eptype"])){
			$update = false;
			$error3 = "* Type required";
		}
		else{
			$ptype = $_POST["eptype"];
		}
		
		
		//Update product info in database
		$query = "update product set pname='$pname', price=$pprice, ptype='$ptype' where pname='$p';";
		pg_query($dbconn, $query) or die('Query failed: ' . pg_last_error());
		$update = true;
	
	}
	
	$query = 'select * from product';
	$result = pg_query($dbconn, $query) or die('Query failed: ' . pg_last_error());		
	while($row=pg_fetch_row($result)){
		$temp = str_replace(" ","",$row[0]);
		//Delete product
		if(isset($_POST["delete$temp"])){
			$q = "delete from product where pname='".$row[0]."'";
			$r = pg_query($dbconn, $q) or die('Query failed: ' . pg_last_error());
			$update = true;
		}
		//Check which product to edit
		if(isset($_POST["edit$temp"])){
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
	<title>BRGR: The Burger Project Online - Products List</title>
	<link rel="stylesheet" href="style.css" type="text/css"/>
	<script type="text/javascript">
		function deleteAlert(){
			var c = confirm("Are you sure you want to delete this product?");
			return c;
		}
	</script>
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
			
			<br/><br/><br/>
			
			<?php if($update) echo '<b>PRODUCTS LIST UPDATED</b><br/><br/>'; ?>	
			
			<!--ADD PRODUCT FORM-->
			Add Product<br/><br/>
			<table class="addprodtable">
			<form name="addform" action="product.php" method="POST">
				<tr><td style="text-align: right; font-weight: bold;">Product name</td><td><input type="text" name="name" placeholder="Name" value="<?php if(isset($name))echo $name;?>"/></td>
					<?php
							if($error1!=""){
								echo "<td>".$error1."</td>";
							}
						?>
				</tr>
				<tr><td style="text-align: right; font-weight: bold;">Price</td><td><input type="number" name="price" placeholder="Price" value="<?php if(isset($price))echo $price;?>"/></td>
					<?php
						if($error2!=""){
							echo "<td>".$error2."</td>";
						}
					?>
				</tr>
				<tr>
					<td style="text-align: right; font-weight: bold;vertical-align:top;">Type</td>
					<td>
						<?php 
							//Print all types from database
							$query = 'select ptype from product group by ptype';
							$result = pg_query($dbconn, $query) or die('Query failed: ' . pg_last_error());
							while($row=pg_fetch_row($result)){
								echo '<input type="radio" name="ptype" value="'.$row[0].'"/>'.$row[0]."<br/>";
							}
						?>
						<br/>
					</td>
					<?php
						if($error3!=""){
							echo "<td>".$error3."</td>";
						}
					?>
				</tr>
				<tr><td colspan="2"><center><input type="submit" name="addsubmit" value="Submit"/></center></td></tr>
			</form>
			</table>
			
			<br/><br/><br/>
			
			<!--EDIT PRODUCT FORM-->
			<?php if($showeditform){ ?>
				Edit Product<br/>
				<table class="addprodtable">
				<form name="editform" action="product.php" method="POST">
					<tr><td style="text-align: right; font-weight: bold;">Product name</td><td><input type="text" name="pname" placeholder="Name" <?php if($pname!=null) echo 'value="'.$pname.'"'; ?>/></td>
						<?php
							if($error1!=""){
								echo "<td>".$error1."</td>";
							}
						?>
					</tr>
					<tr><td style="text-align: right; font-weight: bold;">Price</td><td><input type="number" name="pprice" placeholder="Price" <?php if($pprice!=null) echo 'value="'.$pprice.'"'; ?>/></td>
						<?php
							if($error2!=""){
								echo "<td>".$error2."</td>";
							}
						?>
					</tr>
					<tr>
					<td style="text-align: right; font-weight: bold;vertical-align:top;">Type</td>
					<td>
						<?php 
							//Print all types from database
							$query = 'select ptype from product group by ptype';
							$result = pg_query($dbconn, $query) or die('Query failed: ' . pg_last_error());
							while($row=pg_fetch_row($result)){
								if($ptype==$row[0]) echo '<input type="radio" checked="true" name="eptype" value="'.$row[0].'"/>'.$row[0]."<br/>";
								else echo '<input type="radio" name="eptype" value="'.$row[0].'"/>'.$row[0]."<br/>";
							}
						?>
						<br/>
					</td>
					<?php
						if($error3!=""){
							echo "<td>".$error3."</td>";
						}
					?>
				</tr>
					<tr><td colspan="2"><center><input type="submit" name="editsubmit" value="Submit"/></center></td></tr>
					<input type="hidden" name="oldname" value="<?php echo $p; ?>"/>
				</form>
				</table>
				
				<br/><br/><br/>
			<?php } ?>
			
			<!--PRODUCTS LIST-->
			Products List<br/><br/>
			
			<table class="prodtable">
			<form name="prodform" action="product.php" method="POST">
			<?php
				//Print all products from database
				$query = 'select * from product order by pname';
				$result = pg_query($dbconn, $query) or die('Query failed: ' . pg_last_error());
				while($row=pg_fetch_row($result)){
					echo '<tr>';
					echo '<td><b>'.$row[0].'</b><br/>Php '.$row[1].'<br/>'.$row[2].'</td>';
					$temp = str_replace(" ","",$row[0]);
					echo '<td><input type="submit" name="edit'.$temp.'" value="Edit"/><br/>';
					echo '<input type="submit" name="delete'.$temp.'" value="Delete" onclick="return deleteAlert()"/></td>';
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