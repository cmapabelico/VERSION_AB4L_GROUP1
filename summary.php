<?php

	session_start();
	
	//Connect to database
	$dbconn = pg_connect("host=localhost port=5432 dbname=TBP user=postgres password=password");
	
	$print = '';
	$print2 = '';
	$date = '';
	$value = '';
	
	//If user is not admin, redirect to home page
	if($_SESSION["id"]!='theburgerproject@gmail.com'){
		header("Location:index.php");
	}
	
	//Gross Income
	
	//highestday
	if(isset($_POST["hdsubmit"])){
						
	//Getting gross income
		$query = "select extract(month from store_date),extract(day from store_date),extract(year from store_date),total_income as output from store_summary where total_income = (select max(total_income) from store_summary)";
		$result = pg_query($dbconn, $query) or die('Query failed: ' . pg_last_error());
		while($row = pg_fetch_row($result)){
							
		$date = 'Date: '.$row[0]." - ".$row[1]." - ".$row[2];
		$value = 'Income: '.$row[3];
		$print = 'go';
		}
	}
	
	//highestmonth
	if(isset($_POST["hmsubmit"])){
						
	//Getting gross income
		$max = 0;
		for($i = 1;$i<=12;$i++){
			$query = "select sum(total_income) from store_summary where (select extract(month from store_date) = {$i} )";
			$result = pg_query($dbconn, $query) or die('Query failed: ' . pg_last_error());
			while($row = pg_fetch_row($result)){
				if($row[0] > $max){
				$max = $row[0];
				$value = 'Income: '.$max;
				$date = 'Month: '.$i;
				$print = 'go';
				}
			}
		}
	}

	//highestyear
	if(isset($_POST["hysubmit"])){
						
	//Getting gross income
		$max = 0;
			
			$years = "select extract(year from store_date) from store_summary group by extract(year from store_date)";
			$res = pg_query($dbconn, $years) or die('Query failed: ' . pg_last_error());
			while($row = pg_fetch_row($res)){
				foreach($row as $index){
				
				$query = "select sum(total_income) as output from store_summary where extract(year from store_date)={$index}";
				$result = pg_query($dbconn, $query) or die('Query failed: ' . pg_last_error());
					while($row = pg_fetch_row($result)){
						if($row[0] > $max){
						$max = $row[0];
						$value = "Income: ".$max;
						$date = "Year: ".$index;
						$print = 'go';
						}
					}
				}
			}
	}

	
	
	//CUSTOMER COUNT
	
	if(isset($_POST["hdsubmitc"])){
	
	}
	
	//highestday
	if(isset($_POST["hdsubmitc"])){
						
		$query = "select extract(month from store_date),extract(day from store_date),extract(year from store_date),total_num_member_orders as output from store_summary where total_num_member_orders = (select max(total_num_member_orders) from store_summary)";
		$result = pg_query($dbconn, $query) or die('Query failed: ' . pg_last_error());
		while($row = pg_fetch_row($result)){
							
		$value = "Visitor Count: ".$row[3];
		$date = "Date: ".$row[0]." - ".$row[1]." - ".$row[2];
		$print2 = 'go';
		}
	}
	
	//highestmonth
	if(isset($_POST["hmsubmitc"])){
						
		$max = 0;
		for($i = 1;$i<=12;$i++){
			$query = "select sum(total_num_member_orders) from store_summary where (select extract(month from store_date) = {$i} )";
			$result = pg_query($dbconn, $query) or die('Query failed: ' . pg_last_error());
			while($row = pg_fetch_row($result)){
				if($row[0] > $max){
				$max = $row[0];
				$value = "Visitor Count: ".$max;
				$date = "Month: ".$i;
				$print2 = 'go';
				}
			}
		}
	}
	
	//highestyear
	if(isset($_POST["hysubmitc"])){
						
		$max = 0;
			
			$years = "select extract(year from store_date) from store_summary group by extract(year from store_date)";
			$res = pg_query($dbconn, $years) or die('Query failed: ' . pg_last_error());
			while($row = pg_fetch_row($res)){
				foreach($row as $index){
				
				$query = "select sum(total_num_member_orders) as output from store_summary where extract(year from store_date)={$index}";
				$result = pg_query($dbconn, $query) or die('Query failed: ' . pg_last_error());
					while($row = pg_fetch_row($result)){
						if($row[0] > $max){
						$max = $row[0];
						$value = "Visitor Count: ".$max;
						$date = "Year: ".$index;
						$print2 = 'go';
						}
					}
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
					<tr><td style="text-align: right; font-weight: bold;">Daily</td><td><input type='date' name='dailydate_income'/></td><td colspan="2"><center><input type="submit" name="dsubmit" value="Submit"/></center></td></tr>	
					<tr><td style="text-align: right; font-weight: bold;">Monthly</td><td></td><td colspan="2"><center><input type="submit" name="msubmit" value="Submit"/></center></td></tr>						
					<tr><td style="text-align: right; font-weight: bold;">Yearly</td></td><td>
					<select>
					<?php
						
					
					?>
					</select>
					</td><td colspan="2"><center><input type="submit" name="hsubmit" value="Submit"/></center></td></tr>
					<tr><td style="text-align: right; font-weight: bold;">Highest Day</td><td></td><td colspan="2"><center><input type="submit" name="hdsubmit" value="Submit"/></center></td></tr>
					<tr><td style="text-align: right; font-weight: bold;">Highest Month</td><td></td><td colspan="2"><center><input type="submit" name="hmsubmit" value="Submit"/></center></td></tr>
					<tr><td style="text-align: right; font-weight: bold;">Highest Year</td><td></td><td colspan="2"><center><input type="submit" name="hysubmit" value="Submit"/></center></td></tr>
					<tr><td style="text-align: right; font-weight: bold;"></td><td></td><td colspan="2"><center><input type="submit" name="clear" value="Clear"/></center></td></tr>
				</form>
					<tr><td style="text-align: right; font-weight: bold;"></td><td><p id="change1"><?php if($print != '')echo "<br/>{$value}<br/>{$date}";?></p></td></tr>
			</table>
			
			<br/><br/><br/>
			
			<!--VIEW CUSTOMER COUNT-->
			Customer Count<br/><br/>
			<table class="addprodtable">
				<form name="addform" action="summary.php" method="POST">
					<tr><td style="text-align: right; font-weight: bold;">Daily</td><td><input type='date' name='dailydate_visitors'/></td><td colspan="2"><center><input type="submit" name="dsubmitc" value="Submit"/></center></td></tr>
					<tr><td style="text-align: right; font-weight: bold;">Monthly</td><td></td></td><td colspan="2"><center><input type="submit" name="msubmitc" value="Submit"/></center></td></tr>
					<tr><td style="text-align: right; font-weight: bold;">Yearly</td><td></td><td colspan="2"><center><input type="submit" name="ysubmitc" value="Submit"/></center></td></tr>				
					<tr><td style="text-align: right; font-weight: bold;">Highest Day</td><td></td><td colspan="2"><center><input type="submit" name="hdsubmitc" value="Submit"/></center></td></tr>
					<tr><td style="text-align: right; font-weight: bold;">Highest Month</td><td></td><td colspan="2"><center><input type="submit" name="hmsubmitc" value="Submit"/></center></td></tr>
					<tr><td style="text-align: right; font-weight: bold;">Highest Year</td><td></td><td colspan="2"><center><input type="submit" name="hysubmitc" value="Submit"/></center></td></tr>
					<tr><td style="text-align: right; font-weight: bold;"></td><td></td><td colspan="2"><center><input type="submit" name="clear" value="Clear"/></center></td></tr>

				</form>
					<tr><td style="text-align: right; font-weight: bold;"></td><td><p id="change2"><?php if($print2 != '')echo "<br/>{$value}<br/>{$date}";?></p></td></tr>
			</table>
			
			
			
		</div>
	
	</div>
	</center>
	
</body>

</html>