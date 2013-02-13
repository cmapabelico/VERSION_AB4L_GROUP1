<?php

	session_start();

	//Connect to database
	$dbconn = pg_connect("host=localhost port=5432 dbname=tbp user=postgres password=secret");
	$error = '';
	
	//LOG IN
	if(isset($_POST["log"])){
		
		$email = $_POST["loginemail"];
		$password = $_POST["loginpword"];
		
		if(empty($email)){
			$error = "* Invalid login";
		}
		else{
			
			$query = "select email, pword from member where email='".$email."' and pword like '".$password."'";
			$result = pg_query($dbconn, $query) or die('Query failed: ' . pg_last_error());
			$row = pg_fetch_row($result);
		
			if($email==$row[0] and $password==$row[1]){
				$_SESSION["login"] = 1;
				$_SESSION["id"] = $email;
				header("Location: index.php");
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
</head>

<body>

	<form name="loginform" action="login.php" method="POST">
		<input type="text" name="loginemail" placeholder="Email" value="<?php if(!empty($email)) echo $email; ?>"/><br/>
		<input type="password" name="loginpword" placeholder="Password"/><br/>
		<input type="submit" name="log" value="Log in"/>
	</form>
	<?php if($error!='') echo $error; ?>


</body>

</html>