<?php

	include("prodclass.php");
	session_start();
	$dbconn = pg_connect("host=localhost port=5432 dbname=TBP user=postgres password=password");

	$showform1 = true;
	$showform2 = false;
	$showform3 = false;
	$showform4 = false;
	$showform5 = false;
	$showform6 = false;
	
	$brgr='';
	$bun='';
	$cheese='';
	$premium='';
	$basic='';
	$sauce='';
	
	$price = 0;
	
	if(isset($_POST["submit1"])){
		
		if(isset($_POST["brgr"])){
			$brgr = $_POST["brgr"];
			
			$q = "select * from product where pname='".$brgr."';";
			$r = pg_query($dbconn, $q);
			$row = pg_fetch_row($r);
			$price += $row[1];
			
			$showform1 = false;
			$showform2 = true;
		}
		else{
			$showform1 = true;
		}

	}
	
	if(isset($_POST["submit2"])){
		$price = $_POST["price"];
		$brgr = $_POST["brgr"];
		
		if(isset($_POST["bun"])){
			$bun = $_POST["bun"];
			
			$q = "select * from product where pname='".$bun."';";
			$r = pg_query($dbconn, $q);
			$row = pg_fetch_row($r);
			$price += $row[1];

			$showform1 = false;
			$showform2 = false;
			$showform3 = true;
		}
		else{
			$showform1 = false;
			$showform2 = true;
		}
	}
	
	if(isset($_POST["submit3"])){
		$price = $_POST["price"];
		$brgr = $_POST["brgr"];
		$bun = $_POST["bun"];
		if(isset($_POST["cheese"])){
			$cheese = $_POST["cheese"];
			if(count($cheese) > 0){
				for($i=0;$i < count($cheese);$i++){
					//get item details
					$q = "select * from product where pname='".$cheese[$i]."';";
					$r = pg_query($dbconn, $q);
					$row = pg_fetch_row($r);
					$cheese.= $row[0]." ";
					$price += $row[1];
				}
			}
		}
		else{
			$cheese = null;
		}
		$showform1 = false;
		$showform2 = false;
		$showform3 = false;
		$showform4 = true;
	}
	
	if(isset($_POST["submit4"])){
		$price = $_POST["price"];
		$brgr = $_POST["brgr"];
		$bun = $_POST["bun"];
		$cheese = $_POST["cheese"];
		if(isset($_POST["premium"])){
			$premium = $_POST["premium"];
			if(count($premium) > 0){
				for($i=0;$i < count($premium);$i++){
					//get item details
					$q = "select * from product where pname='".$premium[$i]."';";
					$r = pg_query($dbconn, $q);
					$row = pg_fetch_row($r);
					$premium.= $row[0]." ";
					$price += $row[1];
				}
			}
		}
		else{
			$premium = null;
		}
		$showform1 = false;
		$showform2 = false;
		$showform3 = false;
		$showform4 = false;
		$showform5 = true;
	}
	
	if(isset($_POST["submit5"])){
		$price = $_POST["price"];
		$brgr = $_POST["brgr"];
		$bun = $_POST["bun"];
		$cheese = $_POST["cheese"];
		$premium = $_POST["premium"];
		if(isset($_POST["basic"])){
			$basic = $_POST["basic"];
			if(count($basic) > 0){
				for($i=0;$i < count($basic);$i++){
					//get item details
					$q = "select * from product where pname='".$basic[$i]."';";
					$r = pg_query($dbconn, $q);
					$row = pg_fetch_row($r);
					$basic.= $row[0]." ";
					$price += $row[1];
				}
			}
		}
		else{
			$basic = null;
		}
		$showform1 = false;
		$showform2 = false;
		$showform3 = false;
		$showform4 = false;
		$showform5 = false;
		$showform6 = true;

	}
	
	if(isset($_POST["submit6"])){
		$price = $_POST["price"];
		$brgr = $_POST["brgr"];
		$bun = $_POST["bun"];
		$cheese = $_POST["cheese"];
		$premium = $_POST["premium"];
		$basic = $_POST["basic"];
		if(isset($_POST["sauce"])){
			$sauce = $_POST["sauce"];
			if(count($sauce) > 0){
				for($i=0;$i < count($sauce);$i++){
					//get item details
					$q = "select * from product where pname='".$sauce[$i]."';";
					$r = pg_query($dbconn, $q);
					$row = pg_fetch_row($r);
					$sauce.= $row[0]." ";
					$price += $row[1];
				}
			}
		}
		else{
			$sauce = null;
		}
		
		$b = new Burger($brgr, $bun, $cheese, $premium, $basic, $sauce, 1, $price);
		array_push($_SESSION["tray"], $b);
		$_SESSION["traycontents"]++;
		$_SESSION["subtotal"]+=$price;
		
		$showform1 = false;
		$showform2 = false;
		$showform3 = false;
		$showform4 = false;
		$showform5 = false;
		$showform6 = false;
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
			
			<?php if($showform1){ ?>
			<form name="choosebrgr" action="create.php" method="POST">
			<h1>STEP 1: CHOOSE A BRGR</h1><br/>
			<?php
				$query = "select * from product where ptype='brgr'";
				$result = pg_query($dbconn, $query) or die('Query failed: ' . pg_last_error());	
				while($row=pg_fetch_row($result)){
					echo '<input type="radio" name="brgr" value="'.$row[0].'"/> '.$row[0].'<br/>';
				}
			?>	
			<br/>
			<input type="submit" name="submit1" value="Next"/>
			</form>
			<?php } ?>
			
			<?php if($showform2){ ?>
			<form name="choosebun" action="create.php" method="POST">
			<h1>STEP 2: CHOOSE A BUN</h1><br/>
			<?php
				$query = "select * from product where ptype='bun'";
				$result = pg_query($dbconn, $query) or die('Query failed: ' . pg_last_error());	
				while($row=pg_fetch_row($result)){
					echo '<input type="radio" name="bun" value="'.$row[0].'"/> '.$row[0].'<br/>';
				}
			?>
			<br/>
			<input type="hidden" name="price" value="<?php echo $price; ?>"/>
			<input type="hidden" name="brgr" value="<?php echo $brgr; ?>"/>
			<input type="submit" name="submit2" value="Next"/>
			</form>
			<?php } ?>
		
			<?php if($showform3){ ?>
			<form name="choosecheese" action="create.php" method="POST">
			<h1>STEP 3: CHOOSE A CHEESE</h1><br/>
			<?php
				$query = "select * from product where ptype='cheese'";
				$result = pg_query($dbconn, $query) or die('Query failed: ' . pg_last_error());	
				while($row=pg_fetch_row($result)){
					echo '<input type="checkbox" name="cheese[]" value="'.$row[0].'"/> '.$row[0].'<br/>';
				}
			?>
			<br/>
			<input type="hidden" name="price" value="<?php echo $price; ?>"/>
			<input type="hidden" name="brgr" value="<?php echo $brgr; ?>"/>
			<input type="hidden" name="bun" value="<?php echo $bun; ?>"/>
			<input type="submit" name="submit3" value="Next"/>
			</form>
			<?php } ?>
			
			<?php if($showform4){ ?>
			<form name="choosepremium" action="create.php" method="POST">
			<h1>STEP 4: CHOOSE YOUR PREMIUM TOPPINGS</h1><br/>
			<?php
				$query = "select * from product where ptype='premium'";
				$result = pg_query($dbconn, $query) or die('Query failed: ' . pg_last_error());	
				while($row=pg_fetch_row($result)){
					echo '<input type="checkbox" name="premium[]" value="'.$row[0].'"/> '.$row[0].'<br/>';
				}
			?>
			<br/>
			<input type="hidden" name="price" value="<?php echo $price; ?>"/>
			<input type="hidden" name="brgr" value="<?php echo $brgr; ?>"/>
			<input type="hidden" name="bun" value="<?php echo $bun; ?>"/>
			<input type="hidden" name="cheese" value="<?php echo $cheese; ?>"/>
			<input type="submit" name="submit4" value="Next"/>
			</form>
			<?php } ?>
			
			<?php if($showform5){ ?>
			<form name="choosebasic" action="create.php" method="POST">
			<h1>STEP 5: CHOOSE YOUR BASIC TOPPINGS</h1><br/>
			<?php
				$query = "select * from product where ptype='basic'";
				$result = pg_query($dbconn, $query) or die('Query failed: ' . pg_last_error());	
				while($row=pg_fetch_row($result)){
					echo '<input type="checkbox" name="basic[]" value="'.$row[0].'"/> '.$row[0].'<br/>';
				}
			?>
			<br/>
			<input type="hidden" name="price" value="<?php echo $price; ?>"/>
			<input type="hidden" name="brgr" value="<?php echo $brgr; ?>"/>
			<input type="hidden" name="bun" value="<?php echo $bun; ?>"/>
			<input type="hidden" name="cheese" value="<?php echo $cheese; ?>"/>
			<input type="hidden" name="premium" value="<?php echo $premium; ?>"/>
			<input type="submit" name="submit5" value="Next"/>
			</form>
			<?php } ?>
			
			<?php if($showform6){ ?>
			<form name="choosesauce" action="create.php" method="POST">
			<h1>STEP 6: CHOOSE A SAUCE</h1><br/>
			<?php
				$query = "select * from product where ptype='sauce'";
				$result = pg_query($dbconn, $query) or die('Query failed: ' . pg_last_error());	
				while($row=pg_fetch_row($result)){
					echo '<input type="checkbox" name="sauce[]" value="'.$row[0].'"/> '.$row[0].'<br/>';
				}
			?>			
			<br/>
			<input type="hidden" name="price" value="<?php echo $price; ?>"/>
			<input type="hidden" name="brgr" value="<?php echo $brgr; ?>"/>
			<input type="hidden" name="bun" value="<?php echo $bun; ?>"/>
			<input type="hidden" name="cheese" value="<?php echo $cheese; ?>"/>
			<input type="hidden" name="premium" value="<?php echo $premium; ?>"/>
			<input type="hidden" name="basic" value="<?php echo $basic; ?>"/>
			<input type="submit" name="submit6" value="Finish"/>
			</form>
			<?php } ?>
			
			<?php 
				if(!$showform1 && !$showform2 && !$showform3 && !$showform4 && !$showform5 && !$showform6){
					echo "Custom burger added to tray";
				}
			?>
			
		</div>
	
	</div>
	</center>

</body>

</html>