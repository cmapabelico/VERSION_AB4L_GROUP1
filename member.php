<?php

	session_start();
	
	//Connect to database
	$dbconn = pg_connect("host=localhost port=5432 dbname=TBP user=postgres password=password");
	$update = false;
	$viewmember = false;
	
	$email = '';
	$points = '';
	$fname = '';
	$lname = '';
	$contact = '';
	$bday = '';
	$gender = '';
	$floor = '';
	$bldg = '';
	$street = '';
	$area = '';
	$city = '';
	$landmark = '';	
	$floor2 = '';
	$bldg2 = '';
	$street2 = '';
	$area2 = '';
	$city2 = '';
	$landmark2 = '';
	
	//If user is not admin, redirect to home page
	if($_SESSION["id"]!='theburgerproject@gmail.com'){
		header("Location:index.php");
	}
	
	$query = 'select * from member';
	$result = pg_query($dbconn, $query) or die('Query failed: ' . pg_last_error());		
	while($row=pg_fetch_row($result)){
	
		$temp = str_replace(".","",$row[0]);
	
		//Delete member
		if(isset($_POST["delete$temp"])){
			$q = "delete from member where email='".$row[0]."'";
			$r = pg_query($dbconn, $q) or die('Query failed: ' . pg_last_error());
			$update = true;
		}
		//View member
		if(isset($_POST["view$temp"])){

			$q = "select * from member where email='$row[0]'";
			$r = pg_query($dbconn, $q) or die('Query failed: ' . pg_last_error());

			//Get user details
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
			$points = $row[19];

			$viewmember = true;
		}
	}
	
	if(isset($_POST["view@viewcom"])){
		$viewmember = true;
	}
	
	pg_free_result($result);
	
?>

<html>

<head>
	<title>BRGR: The Burger Project Online - Members List</title>
	<link rel="stylesheet" href="style.css" type="text/css"/>
	<script type="text/javascript">
		function deleteAlert(){
			var c = confirm("Are you sure you want to delete this member?");
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
			
			<?php if($viewmember){  ?>
			
				<table>
					<tr><td>Email</td><td><?php echo $email; ?></td></tr>
					<tr><td>Points</td><td><?php echo $points; ?></td></tr>
					<tr><td>First name</td><td><?php echo $fname; ?></td></tr>
					<tr><td>Last name</td><td><?php echo $lname; ?></td></tr>
					<tr><td>Contact</td><td><?php echo $contact; ?></td></tr>
					<tr><td>Birthday</td><td><?php echo $bday; ?></td></tr>
					<tr><td>Gender</td><td><?php echo $gender; ?></td></tr>
					<tr><td>Floor/Dept/House No</td><td><?php echo $floor; ?></td></tr>
					<tr><td>Building</td><td><?php echo $bldg; ?></td></tr>
					<tr><td>Street</td><td><?php echo $street; ?></td></tr>
					<tr><td>Area</td><td><?php echo $area; ?></td></tr>
					<tr><td>City</td><td><?php echo $city; ?></td></tr>
					<tr><td>Landmark</td><td><?php echo $landmark; ?></td></tr>
					<tr><td>Floor/Dept/House No</td><td><?php echo $floor2; ?></td></tr>
					<tr><td>Building</td><td><?php echo $bldg2; ?></td></tr>
					<tr><td>Street</td><td><?php echo $street2; ?></td></tr>
					<tr><td>Area</td><td><?php echo $area2; ?></td></tr>
					<tr><td>City</td><td><?php echo $city2; ?></td></tr>
					<tr><td>Landmark</td><td><?php echo $landmark2; ?></td></tr>
				</table>
			
				<br/><br/><br/>
			
			<?php } ?>
			
			<?php if($update) echo "<b>MEMBERS LIST UPDATED</b><br/><br/>"; ?>
			
			<!--MEMBERS LIST-->
			Members List<br/><br/>
			
			<table class="prodtable">
			<form name="memberform" action="member.php" method="POST">
			<?php
				//Print all members from database
				$query = 'select * from member';
				$result = pg_query($dbconn, $query) or die('Query failed: ' . pg_last_error());
				while($row=pg_fetch_row($result)){
					if($row[0]!="theburgerproject@gmail.com"){
						echo '<tr>';
						echo '<td><b>'.$row[0].'</b><br/>'.$row[2].' '.$row[3].'</td>';
						$temp = str_replace(".","",$row[0]);
						echo '<td><input type="submit" name="view'.$temp.'" value="View"/><br/>';
						echo '<input type="submit" name="delete'.$temp.'" value="Delete" onclick="return deleteAlert()"/></td>';
						echo '</tr>';
					}
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