<?php

	session_start();

	$dbconn = pg_connect("host=localhost port=5432 dbname=TBP user=postgres password=password");
	
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
	
	if(isset($_POST["submit"])){
	
		$fname = $_POST["fname"];
		$lname = $_POST["lname"];
		$contact = $_POST["contact"];
		//$bday
		//$gender 
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
		
		$query= "UPDATE member SET fname='$fname', lname='$lname', contact='$contact', floor='$floor', bldg='$bldg', street='$street', area='$area', city='$city', landmark='$landmark', floor2='$floor2', bldg2='$bldg2', street2='$street2', area2='$area2', city2='$city2', landmark2='$landmark2';";
		pg_query($dbconn, $query) or die('Query failed: ' . pg_last_error());
		
		$update = true;
	
	}
	
?>

<html>

<head>
	<title>BRGR: The Burger Project Online</title>
</head>

<body>
	
	<?php
		if(!empty($_SESSION["id"])) echo "Hello ".$_SESSION["id"];
		else echo "Hello guest";
	?> 
	
	<br/><br/><br/>
	
	<?php if(!empty($_SESSION["id"])){ ?>
	
		<form name="editform" action="edit.php" method="POST">
		
			<input type="text" name="email" placeholder="Email" disabled="true" value="<?php echo $_SESSION["id"]; ?>"/><br/>
			<input type="text" name="fname" placeholder="First name" value="<?php echo $fname; ?>"/><br/>
			<input type="text" name="lname" placeholder="Last name" value="<?php echo $lname; ?>" /><br/>
			<input type="number" name="contact" placeholder="Contact no" value="<?php echo $contact; ?>" /><br/>
			<input type="number" name="bmonth" placeholder="MM"/> <input type="number" name="bday" placeholder="DD"/> <input type="number" name="byear" placeholder="YYYY"/><br/>
			<input type="radio" name="gender" value="Male"/> Male <input type="radio" name="gender" value="Female"/> Female<br/>
			
			<br/>
			
			<input type="text" name="floor" placeholder="Floor/Dept/House no" value="<?php echo $floor; ?>" /><br/>
			<input type="text" name="bldg" placeholder="Building" value="<?php echo $bldg; ?>" /><br/>
			<input type="text" name="street" placeholder="Street" value="<?php echo $street; ?>" /><br/>
			<input type="text" name="area" placeholder="Area" value="<?php echo $area; ?>" /><br/>
			<input type="text" name="city" placeholder="City" value="<?php echo $city; ?>" /><br/>
			<input type="text" name="landmark" placeholder="Landmark" value="<?php echo $landmark; ?>" /><br/>
		
			<br/>
	
			<input type="text" name="floor2" placeholder="Floor/Dept/House no" value="<?php echo $floor2; ?>" /><br/>
			<input type="text" name="bldg2" placeholder="Building" value="<?php echo $bldg2; ?>" /><br/>
			<input type="text" name="street2" placeholder="Street" value="<?php echo $street2; ?>" /><br/>
			<input type="text" name="area2" placeholder="Area" value="<?php echo $area2; ?>" /><br/>
			<input type="text" name="city2" placeholder="City" value="<?php echo $city2; ?>" /><br/>
			<input type="text" name="landmark2" placeholder="Landmark" value="<?php echo $landmark2; ?>" /><br/>
			
			<br/>
			<input type="submit" name="submit"/>
	
		</form>
	
	<?php } ?>
	
	<?php if($update) echo 'Account settings saved'; ?>
	
	<br/><br/><br/>
	
	<a href="index.php">Home</a><br/>
	<?php
		if(!empty($_SESSION["id"])) echo '<a href="logout.php">Log out</a>';
		else{
			echo '<a href="login.php">Log in</a><br/>';
			echo '<a href="register.php">Register</a><br/>';
		}
	?>
	
</body>

</html>