<?php

	session_start();

	//Connect to database
	$dbconn = pg_connect("host=localhost port=5432 dbname=TBP user=postgres password=password");
	$error = '';
	
	$_SESSION["login"]=0;
	$_SESSION["id"]=null;
	$_SESSION["tray"] = array();
	$_SESSION["traycontents"] = 0;
	$_SESSION["subtotal"] = 0;
	
	//LOG IN
	if(isset($_POST["log"])){
	
		$email = $_POST["loginemail"];
		$password = md5($_POST["loginpword"]);
		
		//Validation
		if(empty($email)){
			$error = "* Invalid login";
		}
		else{
			
			$query = "select email, pw from member where email='".$email."' and pw like '".$password."'";
			$result = pg_query($dbconn, $query) or die('Query failed: ' . pg_last_error());
			$row = pg_fetch_row($result);
		
			//Validation
			if($email==$row[0] and $password==$row[1]){
				$_SESSION["login"] = 1; //log in status
				$_SESSION["id"] = $email; //user id
				$_SESSION["tray"] = array(); //user tray
				$_SESSION["traycontents"] = 0; //number of items in tray
				$_SESSION["subtotal"] = 0; //total price due
				header("Location: home.php");
			}
			else{
				$error ="* Invalid login";
			}
			
		}
	}
	
?>
<html>

<head>
	<title>BRGR: The Burger Project Online</title>
	<link rel="stylesheet" href="style.css" type="text/css"/>
	<script type="text/javascript" src="jquery.js"></script>
	<script type="text/javascript" src="jquery.jcarousel.min.js"></script>
	<script type="text/javascript">	
		$(function(){
			$('#mycarousel img:gt(0)').hide();
				setInterval(function() {
				$('#mycarousel :first-child') .fadeOut(1000)
				.next('img').fadeIn(1000)
				.end().appendTo('#mycarousel');
			}, 3000);
		});
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
						else echo '<a href="tray.php">Tray ('.$_SESSION["traycontents"].')</a> | ';
					?>
					<a href="logout.php">Log out</a><br/>
				<?php }
					else{
						echo 'Welcome guest! ';
						if(isset($_SESSION["traycontents"]) && isset($_SESSION["traycontents"])) echo '| <a href="tray.php">Tray ('.$_SESSION["traycontents"].') | ';
						echo '<a href="index.php">Log in</a> or <a href="register.php">Sign up</a>';
					}
				?>
			</div>
			
			<!--content starts here-->
			
			<br/>
			
			<div id="mycarousel">
				<img src="images/carousel/1.jpg"/>
				<img src="images/carousel/2.jpg"/>
				<img src="images/carousel/3.jpg"/>
				<img src="images/carousel/4.jpg"/>
				<img src="images/carousel/5.jpg"/>
			</div>
			
			<br/>
			
			<!--DESCRIPTION-->
			What do you want in your burger? Create your own at "BRGR: The Burger Project!" <br/><br/>
			
			<!--FORM-->
			<form name="loginform" action="index.php" method="POST">
				<input type="text" name="loginemail" placeholder="Email" value="<?php if(!empty($email)) echo $email; ?>"/>
				<input type="password" name="loginpword" placeholder="Password"/> 
				<input type="submit" name="log" value="Log in"/>
			</form>
			<?php if($error!='') echo $error.'<br/><br/>'; ?>
			<a href="register.php">Register</a> <a href="home.php">Proceed as guest</a>
			
		</div>
	
	</div>
	</center>

</body>

</html>