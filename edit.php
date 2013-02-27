<?php

	session_start();

	//If there is no logged in user, redirect to home page
	if($_SESSION["login"]!=1){
		header("Location:home.php");
	}
	
	//Connect to database
	$dbconn = pg_connect("host=localhost port=5432 dbname=TBP user=postgres password=password");
	
	//Get user details from database
	$query = "select * from member where email='".$_SESSION["id"]."'";
	$result = pg_query($dbconn, $query) or die('Query failed: ' . pg_last_error());
	$row = pg_fetch_row($result);
	
	$email = $row[0];
	$fname = $row[2];
	$lname = $row[3];
	$contact = $row[4];
	$bday = $row[5];
	$gender = $row[6];
	
	$floor = $row[7];
	$bldg = $row[8];
	$street = $row[9];
	$area = $row[10];
	$city = $row[11];
	$landmark = $row[12];

	$floor2 = $row[13];
	$bldg2 = $row[14];
	$street2 = $row[15];
	$area2 = $row[16];
	$city2 = $row[17];
	$landmark2 = $row[18];
	
	$update = false;
	
	//When edit form is submitted
	if(isset($_POST["submit"])){
	
		$fname = $_POST["fname"];
		$lname = $_POST["lname"];
		$contact = $_POST["contact"];
		$floor = $_POST["floor"];
		$bldg = $_POST["bldg"];
		$street = $_POST["street"];
		$area = $_POST["area"];
		$city = $_POST["city"];
		$landmark = $_POST["landmark"];
		$floor2 = $_POST["floor2"];
		$bldg2 = $_POST["bldg2"];
		$street2 = $_POST["street2"];
		$area2 = $_POST["area2"];
		$city2 = $_POST["city2"];
		$landmark2 = $_POST["landmark2"];
		
		//Form validation
		if(empty($fname) || empty($lname) || empty($contact) || empty($floor) || empty($street) || empty($area) || empty($city)){
			$update = false;
		}
		else{
			$update = true;
		}
		
		//If form is valid, update database
		if($update){
			$query= "UPDATE member SET fname='$fname', lname='$lname', contact='$contact', floor='$floor', bldg='$bldg', street='$street', area='$area', city='$city', landmark='$landmark', floor2='$floor2', bldg2='$bldg2', street2='$street2', area2='$area2', city2='$city2', landmark2='$landmark2' where email='$email';";
			pg_query($dbconn, $query) or die('Query failed: ' . pg_last_error());
		}
	
	}
	
?>

<html>

<head>
	<title>BRGR: The Burger Project Online - Edit user profile</title>
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
			
			<br/><br/>
			
			<?php if(!empty($_SESSION["id"])){ ?>
	
				<table class="editformtable">
				<form name="editform" action="edit.php" method="POST">
				
					<tr><td class="title">Email</td><td><input type="text" name="email" placeholder="Email" disabled="true" value="<?php echo $_SESSION["id"]; ?>"/></td></tr>
					<tr><td class="title">First name</td><td><input type="text" name="fname" placeholder="First name" value="<?php echo $fname; ?>"/></td></tr>
					<tr><td class="title">Last name</td><td><input type="text" name="lname" placeholder="Last name" value="<?php echo $lname; ?>" /></td></tr>
					<tr><td class="title">Contact no</td><td><input type="number" name="contact" placeholder="Contact no" value="<?php echo $contact; ?>" /></td></tr>
					<tr><td class="title">Birthday</td><td><input type="text" disabled="true" value="<?php echo $bday; ?>" /></td></tr>
					<tr><td class="title">Gender</td><td><input type="text" disabled="true" value="<?php echo $gender; ?>" /></td></tr>
					
					<tr><td colspan="2"><br/><br/></td></tr>
					
					<tr><td class="title">Floor/Dept/House no</td><td><input type="text" name="floor" placeholder="Floor/Dept/House no" value="<?php echo $floor; ?>" /></td></tr>
					<tr><td class="title">Building</td><td><input type="text" name="bldg" placeholder="Building" value="<?php echo $bldg; ?>" /></td></tr>
					<tr><td class="title">Street</td><td><input type="text" name="street" placeholder="Street" value="<?php echo $street; ?>" /></td></tr>
					<tr><td class="title">Area</td><td><input type="text" name="area" placeholder="Area" value="<?php echo $area; ?>" /></td></tr>
					<tr><td class="title">City</td><td><input type="text" name="city" placeholder="City" value="<?php echo $city; ?>" /></td></tr>
					<tr><td class="title">Landmark/s</td><td><input type="text" name="landmark" placeholder="Landmark" value="<?php echo $landmark; ?>" /></td></tr>
				
					<tr><td colspan="2"><br/><br/></td></tr>
			
					<tr><td class="title">Floor/Dept/House no</td><td><input type="text" name="floor2" placeholder="Floor/Dept/House no" value="<?php echo $floor2; ?>" /></td></tr>
					<tr><td class="title">Building</td><td><input type="text" name="bldg2" placeholder="Building" value="<?php echo $bldg2; ?>" /></td></tr>
					<tr><td class="title">Street</td><td><input type="text" name="street2" placeholder="Street" value="<?php echo $street2; ?>" /></td></tr>
					<tr><td class="title">Area</td><td><input type="text" name="area2" placeholder="Area" value="<?php echo $area2; ?>" /></td></tr>
					<tr><td class="title">City</td><td><input type="text" name="city2" placeholder="City" value="<?php echo $city2; ?>" /></td></tr>
					<tr><td class="title">Landmark/s</td><td><input type="text" name="landmark2" placeholder="Landmark" value="<?php echo $landmark2; ?>" /></td></tr>
					
					<tr><td colspan="2"><br/><br/></td></tr>
					<tr><td colspan="2"><center><input type="submit" name="submit"/></center></td></tr>
			
				</form>
				</table>
			
			<?php } ?>
			
			<?php if($update) echo 'Account settings saved'; ?>
			
		</div>
	
	</div>
	</center>	

</body>

</html>