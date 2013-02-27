<?php

	session_start();

	//Connect to database
	$dbconn = pg_connect("host=localhost port=5432 dbname=TBP user=postgres password=password");
	$error = '';
	
	$_SESSION["login"]=0;
	$_SESSION["id"]=null;
	
	//LOG IN
	if(isset($_POST["log"])){
		
		$email = $_POST["loginemail"];
		$password = md5($_POST["loginpword"]);
		
		//Validation
		if(empty($email)){
			$error = "* Invalid login";
		}
		else{
			
			$query = "select email, pword from member where email='".$email."' and pword like '".$password."'";
			$result = pg_query($dbconn, $query) or die('Query failed: ' . pg_last_error());
			$row = pg_fetch_row($result);
		
			//Validation
			if($email==$row[0] and $password==$row[1]){
				$_SESSION["login"] = 1; //log in status
				$_SESSION["id"] = $email; //user id
				$_SESSION["tray"] = array(); //user tray
				$_SESSION["traycontents"] = 0; //number of items in tray
				header("Location: home.php");
			}
			else{
				$error ="* Invalid login";
			}
			
		}
	}
	
?>
<!DOCTYPE html>
<html>
<head>
	<title>BRGR: The Burger Project Online</title>
	<link rel="stylesheet" href="style.css" type="text/css"/>
	<link rel="stylesheet" href="bootstrap.css/bootstrap.min.css" type="text/css"/>
	<link rel="stylesheet" href="bootstrap.css/bootstrap-responsive.min.css" type="text/css"/>
</head>

<body>

	<center>
	<br/>
	
	<!--LOGO-->
	<img src="images/logo.png" width="500"/><br/><br/>
	
	<!--FLASH-->
	<div class="flash">
			<div id="myCarousel" class="carousel slide">
			  <ol class="carousel-indicators">
				<li data-target="#myCarousel" data-slide-to="0" class="active"></li>
				<li data-target="#myCarousel" data-slide-to="1"></li>
				<li data-target="#myCarousel" data-slide-to="2"></li>
			  </ol>
			  <!-- Carousel items -->
			  <div class="carousel-inner">
				<div class="active item"><img src="http://www.yadig.com/uploads/biz-logo/burger_ph_alb_091020114032.jpg" alt></div>
				<div class="item"><img src="http://upload.wikimedia.org/wikipedia/commons/9/91/Burger_King_Whopper_Combo.jpg" alt></div>
				<div class="item"><img src="http://images4.fanpop.com/image/photos/23800000/BK-burger-king-23892437-263-336.jpg" alt></div>
			  </div>
			  <!-- Carousel nav -->
			  <a class="carousel-control left" href="#myCarousel" data-slide="prev"><</a>
			  <a class="carousel-control right" href="#myCarousel" data-slide="next">></a>
			</div>
	</div>
	
	<br/>
	<br/>
	<br/>
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
	
	</center>
	
	
	<script src="bootstrapjs/jquery-1.9.1.js"></script>
	<script src="bootstrapjs/bootstrap-carousel.js"></script>
	<script src="bootstrapjs/bootstrap.min.js"></script>
</body>

</html>