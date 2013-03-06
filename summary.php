<?php

	session_start();
	
	//Connect to database
	$dbconn = pg_connect("host=localhost port=5432 dbname=TBP user=postgres password=password");
	
	$print =false;
	//If user is not admin, redirect to home page
	if($_SESSION["id"]!='theburgerproject@gmail.com'){
		header("Location:home.php");
	}
	//Gross Income
	if(isset($_POST["hdsubmit"])){
						
	//Getting gross income
		$query = "select * from store_summary where total_income = (select max(total_income) from store_summary) ;";
		$result = pg_query($dbconn, $query) or die('Query failed: ' . pg_last_error());
		while($row = pg_fetch_row($result)){
							
		$value = $row[3];
		$date = $row[0];
		}
		$print = true;
	}
	
	//Edit product form
	if(isset($_POST["editsubmit"])){
	
		$pname = $_POST["pname"];
		$pprice = $_POST["pprice"];
		$ptype = $_POST["ptype"];
		$p = $_POST["oldname"];
	
		//Form validation
		
		
		//Update product info in database
		$query = "update product set pname='$pname', price=$pprice, ptype='$ptype' where pname='$p';";
		pg_query($dbconn, $query) or die('Query failed: ' . pg_last_error());
		$update = true;
	
	}
	
	
?>

<html>

<head>
	<title>BRGR: The Burger Project Online</title>
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
			
			<!--VIEW GROSS INCOME-->
			Gross Income<br/><br/>
			<table class="addprodtable">
				<form name="addform" action="summary.php" method="POST">
					<tr><td style="text-align: right; font-weight: bold;">Daily</td><td>
						<select>
								<option name = "date" value = "1">January</option>
								<option name = "date" value = "2">February</option>
								<option name = "date" value = "3">March</option>
								<option name = "date" value = "4">April</option>
								<option name = "date" value = "5">May</option>
								<option name = "date" value = "6">June</option>
								<option name = "date" value = "7">July</option>
								<option name = "date" value = "8">August</option>
								<option name = "date" value = "9">September</option>
								<option name = "date" value = "10">October</option>
								<option name = "date" value = "11">November</option>
								<option name = "date" value = "12">December</option>
						</select>
					</td><td colspan="2"><center><input type="submit" name="dsubmit" value="Submit"/></center></td></tr>
					
					<tr><td style="text-align: right; font-weight: bold;">Monthly</td><td></td><td colspan="2"><center><input type="submit" name="msubmit" value="Submit"/></center></td></tr>
					
						<?php
							/*
							$query = 'select * from store_summary extract';
							$result = pg_query($dbconn, $query) or die('Query failed: ' . pg_last_error());
							while($row=pg_fetch_row($result)){
								echo '<tr>';
								echo '<td><b>'.$row[0].'</b><br/>Php '.$row[1].'<br/>'.$row[2].'</td>';
								echo '</tr>';
							}
							pg_free_result($result);	
							*/
						?>
						
					<tr><td style="text-align: right; font-weight: bold;">Yearly</td></td></td><td><td colspan="2"><center><input type="submit" name="hsubmit" value="Submit"/></center></td></tr>
					<tr><td style="text-align: right; font-weight: bold;">Highest Day</td></td><td><td colspan="2"><center><input type="submit" name="hdsubmit" value="Submit"/></center></td></tr>
					<tr><td style="text-align: right; font-weight: bold;">Highest Month</td></td><td><td colspan="2"><center><input type="submit" name="hmsubmit" value="Submit"/></center></td></tr>
					<tr><td style="text-align: right; font-weight: bold;">Highest Year</td></td><td><td colspan="2"><center><input type="submit" name="hysubmit" value="Submit"/></center></td></tr>
					<tr><td style="text-align: right; font-weight: bold;">Income: </td></td><td><p id="change1">
					<?php
						
						if($print){
							echo "<br/>{$value}";
							echo "<br/>{$date}";
						
						}
					?>
					</p></tr>
				</form>
			</table>
			
			<br/><br/><br/>
			
			<!--VIEW CUSTOMER COUNT-->
			Customer Count<br/><br/>
			<table class="addprodtable">
				<form name="addform" action="summary.php" method="POST">
					<tr><td style="text-align: right; font-weight: bold;">Daily</td><td>
						<select>
								<option name = "date" value = "1">January</option>
								<option name = "date" value = "2">February</option>
								<option name = "date" value = "3">March</option>
								<option name = "date" value = "4">April</option>
								<option name = "date" value = "5">May</option>
								<option name = "date" value = "6">June</option>
								<option name = "date" value = "7">July</option>
								<option name = "date" value = "8">August</option>
								<option name = "date" value = "9">September</option>
								<option name = "date" value = "10">October</option>
								<option name = "date" value = "11">November</option>
								<option name = "date" value = "12">December</option>
						</select>
					</td><td colspan="2"><center><input type="submit" name="dsubmitc" value="Submit"/></center></td></tr>
					<tr><td style="text-align: right; font-weight: bold;">Monthly</td><td>
						<?php
							/*
							$query = 'select * from store_summary';
							$result = pg_query($dbconn, $query) or die('Query failed: ' . pg_last_error());
							while($row=pg_fetch_row($result)){
								echo '<tr>';
								echo '<td><b>'.$row[0].'</b><br/>Php '.$row[1].'<br/>'.$row[2].'</td>';
								echo '</tr>';
							}
							*/
						?></td></td><td colspan="2"><center><input type="submit" name="msubmitc" value="Submit"/></center></td></tr>
					<tr><td style="text-align: right; font-weight: bold;">Yearly</td><td></td><td colspan="2"><center><input type="submit" name="ysubmitc" value="Submit"/></center></td></tr>
					
					<tr><td style="text-align: right; font-weight: bold;">Highest Day</td><td></td><td colspan="2"><center><input type="submit" name="hdsubmitc" value="Submit"/></center></td></tr>
					<tr><td style="text-align: right; font-weight: bold;">Highest Month</td><td></td><td colspan="2"><center><input type="submit" name="hmsubmitc" value="Submit"/></center></td></tr>
					<tr><td style="text-align: right; font-weight: bold;">Highest Year</td><td></td><td colspan="2"><center><input type="submit" name="hysubmitc" value="Submit"/></center></td></tr>
					<tr><td style="text-align: right; font-weight: bold;">Visitors: </td><td></td><td style="text-align: right; font-weight: bold;"><p id="change2"></p></td></tr>

				</form>
			</table>
			
			
			
		</div>
	
	</div>
	</center>
	
</body>

</html>