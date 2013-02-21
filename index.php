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
				$_SESSION["login"] = 1;
				$_SESSION["id"] = $email;
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
</head>

<body>

	<center>
	<br/>
	
	<!--LOGO-->
	<img src="images/logo.png" width="500"/><br/><br/>
	
	<!--FLASH-->
	<div class="flash">Flash/JCarousel thing here</div><br/>
	
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

</body>

</html>