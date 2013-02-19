<?PHP
	
	session_start();

	require_once("./include/membersite_config.php");
	
	if($_SESSION["login"]==1){
		header("Location: home.php");
	}
	
	if(isset($_POST['submitted']))
	{
		
		$db = pg_connect("host=localhost port=5432 dbname=TBP user=postgres password=password");

		$email = pg_escape_string($_POST['email']);
		$password = pg_escape_string($_POST['password']);
		$fname = pg_escape_string($_POST['fname']);	
		$lname = pg_escape_string($_POST['lname']);
		$contact = pg_escape_string($_POST['contact']);
		$bday = pg_escape_string($_POST['bday']);
		$gender = pg_escape_string($_POST['gender']);
		$floor = pg_escape_string($_POST['floor']);
		$bldg = pg_escape_string($_POST['bldg']);
		$street = pg_escape_string($_POST['street']);
		$area = pg_escape_string($_POST['area']);
		$city = pg_escape_string($_POST['city']);
		$lmark = pg_escape_string($_POST['lmark']);
		$floor2 = pg_escape_string($_POST['floor2']);
		$bldg2 = pg_escape_string($_POST['bldg2']);
		$street2 = pg_escape_string($_POST['street2']);
		$area2 = pg_escape_string($_POST['area2']);
		$city2 = pg_escape_string($_POST['city2']);
		$lmark2 = pg_escape_string($_POST['lmark2']);


		$query = "INSERT INTO member(email, pword, fname, lname, contact, bday, 
		gender, floor, bldg, street, area, city, landmark, floor2, bldg2, street2,
		area2, city2, landmark2) VALUES ('".$email."','".$password."',
		'".$fname."','".$lname."','".$contact."',
		'".$bday."','".$gender."','".$floor."','".$bldg."','".$street."','".$area."','".$city."','".$lmark."',
		'".$floor2."','".$bldg2."','".$street2."','".$area2."','".$city2."','".$lmark2."')";
		$result = pg_query($query); 

		$fgmembersite->RedirectToURL("thankyou.html");
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US" lang="en-US">
	<head>
	    <meta http-equiv='Content-Type' content='text/html; charset=utf-8'/>
		<title>REGISTER</title>
		   <link rel="STYLESHEET" type="text/css" href="style/fg_membersite.css" />
		<script type='text/javascript' src='scripts/gen_validatorv31.js'></script>
		<link rel="stylesheet" type="text/css" href="style/pwdwidget.css"/>
		<link rel="stylesheet" type="text/css" href="style.css"/>
		<script src="scripts/pwdwidget.js" type="text/javascript"></script> 
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
			
			<br/><br/>
			
			<div id="fg_membersite">
			<form id="register" action="register.php" method="post" accept-charset='UTF-8'>
				<fieldset>
				<legend>REGISTER</legend>
				<input type="hidden" name="submitted" id="submitted" value="1"/>
				<div class="short_explanation">* required fields</div>
				<!--email-->
				<div class='container'>
					<label for='email' >Email Address*:</label><br/>
					<input type='text' name='email' maxlength="50" /><br/>
					<span id='register_email_errorloc' class='error'></span>
				</div>
				<!--password-->
				<div class='container' style='height:80px;'>
					<label for='password' >Password*:</label><br/>
					<div class='pwdwidgetdiv' id='thepwddiv' ></div>
					<noscript>
					<input type='password' name='password' id='password' maxlength="50" />
					</noscript>    
					<div id='register_password_errorloc' class='error' style='clear:both'></div>
				</div>
				
				<div><span class='error'><?php echo $fgmembersite->GetErrorMessage(); ?></span></div>
				<!--first name-->
				<div class="container">
					<label for="fname">First Name*:</label><br/>
					<input type="text" name="fname" id="fname" maxlength="50"/><br/>
					<span id='register_fname_errorloc' class='error'></span>
				</div>
				<!--last name-->
				<div class="container">
					<label for="lname">Last Name*:</label><br/>
					<input type="text" name="lname" id="lname" maxlength="50"/><br/>
					<span id='register_lname_errorloc' class='error'></span>
				</div>
				<!--contact number-->
				<div class="container">
					<label for="contact">Contact number*:</label><br/>
					<input type="text" name="contact" id="contact" maxlength="11"/><br/>
					<span id='register_contact_errorloc' class='error'></span>
				</div>
				<!--bday-->
				<div class="container">
					<label for="bday">Birthday*:</label><br/>
					<input type="date" name="bday" id="bday" /><br/>
					<span id='register_bday_errorloc' class='error'></span>
				</div>
				<!--gender-->
				<div class="container">
					<label for="gender">Gender*:</label><br/>
					<input type="radio" name="gender" id="gender">Male<br/>
					<input type="radio" name="gender" id="gender">Female<br/>
					<span id='register_gender_errorloc' class='error'></span>
				</div>
				<!--floor-->
				<div class="container">
					<label for="floor">Floor number/Department/House number:</label><br/>
					<input type="text" name="floor" id="floor" maxlength="2"/><br/>
					<span id='register_floor_errorloc' class='error'></span>
				</div>
				<!--bldg-->
				<div class="container">
					<label for="bldg">Building:</label><br/>
					<input type="text" name="bldg" id="bldg" maxlength="50"/><br/>
					<span id='register_bldg_errorloc' class='error'></span>
				</div>
				<!--street-->
				<div class="container">
					<label for="street">Street*:</label><br/>
					<input type="text" name="street" id="street" maxlength="50"/><br/>
					<span id='register_street_errorloc' class='error'></span>
				</div>
				<!--area-->
				<div class="container">
					<label for="area">Area*:</label><br/>
					<input type="text" name="area" id="area" maxlength="50"/><br/>
					<span id='register_area_errorloc' class='error'></span>
				</div>
				<!--city-->
				<div class="container">
					<label for="city">City*:</label><br/>
					<input type="text" name="city" id="city" maxlength="50"/><br/>
					<span id='register_city_errorloc' class='error'></span>
				</div>
				<!--landmark-->
				<div class="container">
					<label for="lmark">Landmark*:</label><br/>
					<input type="text" name="lmark" id="lmark" maxlength="50"/><br/>
					<span id='register_lmark_errorloc' class='error'></span>
				</div>
				<hr>
				<h3>Alternate address:</h3>
				<!--floor2-->
				<div class="container">
					<label for="floor2">Floor number:</label><br/>
					<input type="text" name="floor2" id="floor2" maxlength="2"/><br/>
					<span id='register_floor2_errorloc' class='error'></span>
				</div>
				<!--bldg2-->
				<div class="container">
					<label for="bldg2">Building:</label><br/>
					<input type="text" name="bldg2" id="bldg2" maxlength="50"/><br/>
					<span id='register_bldg2_errorloc' class='error'></span>
				</div>
				<!--street2-->
				<div class="container">
					<label for="street2">Street:</label><br/>
					<input type="text" name="street2" id="street2" maxlength="50"/><br/>
					<span id='register_street2_errorloc' class='error'></span>
				</div>
				<!--area2-->
				<div class="container">
					<label for="area2">Area:</label><br/>
					<input type="text" name="area2" id="area2" maxlength="50"/><br/>
					<span id='register_area2_errorloc' class='error'></span>
				</div>
				<!--city2-->
				<div class="container">
					<label for="city2">City:</label><br/>
					<input type="text" name="city2" id="city2" maxlength="50"/><br/>
					<span id='register_city2_errorloc' class='error'></span>
				</div>
				<!--landmark2-->
				<div class="container">
					<label for="lmark2">Landmark:</label><br/>
					<input type="text" name="lmark2" id="lmark2" maxlength="50"/><br/>
					<span id='register_lmark2_errorloc' class='error'></span>
				</div>
								
				<div class='container'>
					<input type='submit' name='Submit' value='Submit' />
				</div>
				
				</fieldset>
			</form>
			
		</div>
	
	</div>
	</center>
			
	<script type='text/javascript'>
	// <![CDATA[
		var pwdwidget = new PasswordWidget('thepwddiv','password');
		pwdwidget.MakePWDWidget();
		
		var frmvalidator  = new Validator("register");
		frmvalidator.EnableOnPageErrorDisplay();
		frmvalidator.EnableMsgsTogether();
		frmvalidator.addValidation("fname","req","Please provide your first name");
		frmvalidator.addValidation("lname","req","Please provide your last name");
		frmvalidator.addValidation("contact","req","Please provide your contact number");
		frmvalidator.addValidation("contact","num","Please enter valid contact");
		frmvalidator.addValidation("contact","cont","Please enter 11 digits");
		frmvalidator.addValidation("bday","req","Please provide your birthday");
		frmvalidator.addValidation("gender","req","Please choose your gender");
		frmvalidator.addValidation("street","req","Enter your street name");
		frmvalidator.addValidation("area","req","Enter your area name");
		frmvalidator.addValidation("city","req","Enter your city");
		frmvalidator.addValidation("lmark","req","Enter a nearby landmark");
		frmvalidator.addValidation("email","req","Please provide your email address");
		frmvalidator.addValidation("email","email","Please provide a valid email address");
		frmvalidator.addValidation("password","req","Please provide a password");
	// ]]>
	</script>


</body>
</html>



