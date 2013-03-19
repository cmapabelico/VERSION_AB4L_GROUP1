<?php

	session_start();
	
	//Connect to database
	$dbconn = pg_connect("host=localhost port=5432 dbname=TBP user=postgres password=password");
	
	if(!isset($_SESSION['date_income']))
		$_SESSION['date_income'] = '';
	
	if(!isset($_SESSION['value_income']))
		$_SESSION['value_income'] = '';
	
	if(!isset($_SESSION['date_customer']))
		$_SESSION['date_customer'] = '';
	
	if(!isset($_SESSION['value_customer']))
		$_SESSION['value_customer'] = '';
	
	//array of months to be traversed
	$month = array('January','February','March','April','May','June','July','August','September','October','November','December');
	
	$_SESSION['print_type']='';
	
	//If user is not admin, redirect to home page
	if($_SESSION["id"]!='theburgerproject@gmail.com'){
		header("Location:home.php");
	}
	
	//SESSIONS FOR DATE
	if(!isset($_SESSION['defaultdateincome']))
		$_SESSION['defaultdateincome'] = date('Y-m-d');
	if(!isset($_SESSION['defaultmonthincome']))
		$_SESSION['defaultmonthincome'] = '';
	if(!isset($_SESSION['defaultyearincome']))
		$_SESSION['defaultyearincome'] = '';
	
	//SESSIONS FOR ORDERS
	if(!isset($_SESSION['defaultdatecount']))
		$_SESSION['defaultdatecount'] = date('Y-m-d');
	if(!isset($_SESSION['defaultmonthcount']))
		$_SESSION['defaultmonthcount'] = '';
	if(!isset($_SESSION['defaultyearcount']))
		$_SESSION['defaultyearcount'] = '';
	////////////////////////////////////////////////////////
	
	//Gross Income
	
	//day
	if(isset($_POST["dsubmit"])){
		$date = $_POST['dailydate_income'];
		$query = "select total_income from store_summary where store_date = date '{$date}'";
		
		$result = pg_query($dbconn, $query) or die('Query failed: ' . pg_last_error());
		while($row = pg_fetch_row($result)){

			$_SESSION['date_income'] = '<b>Date: </b>'.$month[date("n",strtotime($date))-1].' - '.date("d",strtotime($date)).' - '.date("Y",strtotime($date));
			$_SESSION['value_income'] = '<b>Income: </b>'.$row[0];
		}
		$_SESSION['defaultdateincome'] = $date;
		if($row[0]===null)
			$_SESSION['value_income'] = '<b>No data available.</b>';			
			$_SESSION['date_income'] = '<b>Date: </b>'.$month[date("n",strtotime($date))-1].' - '.date("d",strtotime($date)).' - '.date("Y",strtotime($date));
		
	}

	//month
	if(isset($_POST["msubmit"])){
		$query = "select sum(total_income) from store_summary where date_part('Month',store_date) = {$_POST['monthly']}";

		$result = pg_query($dbconn, $query) or die('Query failed: ' . pg_last_error());
		while($row = pg_fetch_row($result)){
			
			$_SESSION['date_income'] = '<b>Month: </b>'.$month[$_POST['monthly']-1];
			$_SESSION['value_income'] = '<b>Income: </b>'.$row[0];
		}
		$_SESSION['defaultmonthincome'] = $_POST['monthly'];

	}

	//year
	if(isset($_POST["hsubmit"])){
		$query = "select sum(total_income) from store_summary where date_part('Year',store_date) = {$_POST['yearly']}";

		$result = pg_query($dbconn, $query) or die('Query failed: ' . pg_last_error());
		while($row = pg_fetch_row($result)){
		
			$_SESSION['date_income'] = '<b>Year: </b>'.$_POST['yearly'];
			$_SESSION['value_income'] = '<b>Income: </b>'.$row[0];
		}
		$_SESSION['defaultyearincome'] = $_POST['yearly'];
	}
	
	//highestday
	if(isset($_POST["hdsubmit"])){
						
		$query = "select extract(month from store_date) ,extract(day from store_date),extract(year from store_date),total_income as output from store_summary where total_income = (select max(total_income) from store_summary)";
		$result = pg_query($dbconn, $query) or die('Query failed: ' . pg_last_error());
		while($row = pg_fetch_row($result)){
				
		$_SESSION['date_income'] = '<b>Date: </b>'.$month[$row[0] - 1]." - ".$row[1]." - ".$row[2];
		$_SESSION['value_income'] = '<b>Income: </b>'.$row[3];
		}
	}
	
	//highestmonth
	if(isset($_POST["hmsubmit"])){
						
		$max = 0;
		for($i = 1;$i<=12;$i++){
			$query = "select sum(total_income) from store_summary where (select extract(month from store_date) = {$i} )";
			$result = pg_query($dbconn, $query) or die('Query failed: ' . pg_last_error());
			while($row = pg_fetch_row($result)){
				if($row[0] > $max){
				$max = $row[0];
				$_SESSION['date_income'] = '<b>Month: </b>'.$month[$i-1];
				$_SESSION['value_income'] = '<b>Income: </b>'.$max;
				}
			}
		}
	}

	//highestyear
	if(isset($_POST["hysubmit"])){
						
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
						$_SESSION['date_income'] = "<b>Year: </b>".$index;
						$_SESSION['value_income'] = "<b>Income: </b>".$max;
						}
					}
				}
			}
	}

	
	//clear income
	if(isset($_POST["clear"])){
		$_SESSION['value_income'] = '';
		$_SESSION['date_income'] = '';
	}
	
	if( isset($_POST["dsubmitc"]) || isset($_POST["msubmitc"]) || isset($_POST["ysubmitc"]) || isset($_POST["hdsubmitc"]) || isset($_POST["hmsubmitc"]) || isset($_POST["hysubmitc"]) )
		$type = 'member';
	else
		$type = 'guest';
		
	//CUSTOMER COUNT
		//day
	if(isset($_POST["dsubmitc"]) || isset($_POST["dsubmitcu"])){
		$date = $_POST['dailydate_visitors'];
		
		$query = "select total_num_".$type."_orders from store_summary where store_date = date '{$date}'";
		$result = pg_query($dbconn, $query) or die('Query failed: ' . pg_last_error());
		
		$query = "select count(distinct (select total_num_".$type."_orders from store_summary where store_date = date '{$date}')) from store_summary";
		$number = pg_query($dbconn, $query) or die('Query failed: ' . pg_last_error());
		
		while($row = pg_fetch_row($result)){
			$_SESSION['print_type'] = '<b>Type: </b>'.ucfirst($type);
			$_SESSION['value_customer'] = '<b>Order Count: </b>'.$row[0];
		}
		$_SESSION['date_customer'] = '<b>Date: </b>'.$month[date("n",strtotime($date))-1].' - '.date("d",strtotime($date)).' - '.date("Y",strtotime($date));
		$_SESSION['defaultdatecount'] = $date;
		while($count = pg_fetch_row($number)){
			if($count[0] == 0)
				$_SESSION['value_customer'] = '<b>No data available.</b>';
		}
		
	
	}
	
	//month
	if(isset($_POST["msubmitc"]) || isset($_POST["msubmitcu"])){
		$query = "select sum(total_num_".$type."_orders) from store_summary where date_part('Month',store_date) = {$_POST['monthlyc']}";
		$result = pg_query($dbconn, $query) or die('Query failed: ' . pg_last_error());
		while($row = pg_fetch_row($result)){
			$_SESSION['print_type'] = '<b>Type: </b>'.ucfirst($type);
			$_SESSION['value_customer'] = '<b>Order Count: </b>'.$row[0];
			$_SESSION['date_customer'] = '<b>Month: </b>'.$month[$_POST['monthlyc']-1];
		}
		$_SESSION['defaultmonthcount'] = $_POST['monthlyc'];
	}

	//year
	if(isset($_POST["ysubmitc"]) || isset($_POST["ysubmitcu"])){
		$query = "select sum(total_num_".$type."_orders) from store_summary where date_part('Year',store_date) = {$_POST['yearlyc']}";
		$result = pg_query($dbconn, $query) or die('Query failed: ' . pg_last_error());
		while($row = pg_fetch_row($result)){
			$_SESSION['print_type'] = '<b>Type: </b>'.ucfirst($type);
			$_SESSION['value_customer'] = '<b>Order Count: </b>'.$row[0];
			$_SESSION['date_customer'] = '<b>Year: </b>'.$_POST['yearlyc'];
				
		}
		$_SESSION['defaultyearcount'] = $_POST['yearlyc'];
	}
	
	//highestday
	if(isset($_POST["hdsubmitc"]) || isset($_POST["hdsubmitcu"])){
						
		$query = "select extract(month from store_date),extract(day from store_date),extract(year from store_date),total_num_".$type."_orders as output from store_summary where total_num_".$type."_orders = (select max(total_num_".$type."_orders) from store_summary)";
		$result = pg_query($dbconn, $query) or die('Query failed: ' . pg_last_error());
		while($row = pg_fetch_row($result)){	
			$_SESSION['print_type'] = '<b>Type: </b>'.ucfirst($type);
			$_SESSION['date_customer'] = "<b>Date: </b>".$month[$row[0] - 1]." - ".$row[1]." - ".$row[2];
			$_SESSION['value_customer'] = "<b>Order Count: </b>".$row[3];
		}
	}
	
	//highestmonth
	if(isset($_POST["hmsubmitc"]) || isset($_POST["hmsubmitcu"])){
						
		$max = 0;
		for($i = 1;$i<=12;$i++){
			$query = "select sum(total_num_".$type."_orders) from store_summary where (select extract(month from store_date) = {$i} )";
			$result = pg_query($dbconn, $query) or die('Query failed: ' . pg_last_error());
			while($row = pg_fetch_row($result)){
				if($row[0] > $max){
				$max = $row[0];
				$_SESSION['print_type'] = '<b>Type: </b>'.ucfirst($type);
				$_SESSION['date_customer'] = "<b>Month: </b>".$month[$i - 1];
				$_SESSION['value_customer'] = "<b>Order Count: </b>".$max;
				}
			}
		}
	}
	
	//highestyear
	if(isset($_POST["hysubmitc"]) || isset($_POST["hysubmitcu"])){
						
		$max = 0;
			
			$years = "select extract(year from store_date) from store_summary group by extract(year from store_date)";
			$res = pg_query($dbconn, $years) or die('Query failed: ' . pg_last_error());
			while($row = pg_fetch_row($res)){
				foreach($row as $index){
				
				$query = "select sum(total_num_".$type."_orders) as output from store_summary where extract(year from store_date)={$index}";
				$result = pg_query($dbconn, $query) or die('Query failed: ' . pg_last_error());
					while($row = pg_fetch_row($result)){
						if($row[0] > $max){
						$max = $row[0];
						$_SESSION['print_type'] = '<b>Type: </b>'.ucfirst($type);
						$_SESSION['value_customer'] = "<b>Order Count: </b>".$max;
						$_SESSION['date_customer'] = "<b>Year: </b>".$index;
						}
					}
				}
			}
	}
	
	//clear customer
	if(isset($_POST["clearc"])){
		$_SESSION['print_type'] = '';
		$_SESSION['value_customer'] = '';
		$_SESSION['date_customer'] = '';
	}

?>

<html>

<head>
	<title>BRGR: The Burger Project Online</title>
	<link rel="stylesheet" href="style.css" type="text/css"/>
</head>

<!--TO REMOVE RIGHT CLICK-->
<body oncontextmenu="return false">

	<center>
	<div class="body">

		<div class="nav" >
			<img src="images/logo.png" width="500" /><br/><br/>
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
			
			<br/><br/><br/><br/>
			
	
			
			<!--VIEW GROSS INCOME-->
			<div id="gross_income" style="float:left;">
				Gross Income<br/><br/>
				<table class="addprodtable">
					<form name="addform" action="summary.php" method="POST">
						<tr>
							<td style="text-align: right; font-weight: bold;">Daily</td>
							<td><input type='date' name='dailydate_income' value="<?php echo "{$_SESSION['defaultdateincome']}"; ?>"/></td>
							<td colspan="2">
								<center>
								
								<input type="submit" name="dsubmit" value="Submit"/>
								</center>
							</td>
						</tr>
						
						<tr>
						<td style="text-align: right; font-weight: bold;">Monthly</td>
							<td>
									<?phpecho $_SESSION['defaultmonthincome'];?>
									<select name="monthly">
									<?php
									//QUERY GETS ALL MONTHS IN DBASE
									$query = "select date_part('Month',store_date) from store_summary group by date_part('Month',store_date) order by date_part";
									$result = pg_query($dbconn, $query) or die('Query failed: ' . pg_last_error());
									while($row = pg_fetch_row($result)){										
										if( "{$_SESSION['defaultmonthincome']}" == $row[0])
											echo '<option value="'.$row[0].'" selected="selected">'.$month[$row[0] - 1].'</option>';
										else
											echo '<option value='.$row[0].'>'.$month[$row[0] - 1].'</option>';
											
									}
									
									?>
									</select>
							</td>
						<td colspan="2">
							<center>
							<input type="submit" name="msubmit" value="Submit"/>
							</center>
						</td>
						</tr>						
						
						<tr>
							<td style="text-align: right; font-weight: bold;">Yearly</td>
							<td>
								<select name="yearly">
									<?php
									//QUERY GETS ALL YEARS IN DBASE
									$query = "select date_part('Year',store_date) from store_summary group by date_part('Year',store_date) order by date_part";
									$result = pg_query($dbconn, $query) or die('Query failed: ' . pg_last_error());
									while($row = pg_fetch_row($result)){
										if( "{$_SESSION['defaultyearincome']}" == $row[0])
											echo '<option value="'.$row[0].'" selected="selected">'.$row[0].'</option>';
										else
											echo '<option value='.$row[0].'>'.$row[0].'</option>';
											
									}
									
									?>
								</select>
							</td>
							<td colspan="2"><center><input type="submit" name="hsubmit" value="Submit"/>
							</center>
							</td>
						</tr>
						
						<tr><td style="text-align: right; font-weight: bold;">Highest Day</td><td></td><td colspan="2"><center><input type="submit" name="hdsubmit" value="Submit"/></center></td></tr>
						<tr><td style="text-align: right; font-weight: bold;">Highest Month</td><td></td><td colspan="2"><center><input type="submit" name="hmsubmit" value="Submit"/></center></td></tr>
						<tr><td style="text-align: right; font-weight: bold;">Highest Year</td><td></td><td colspan="2"><center><input type="submit" name="hysubmit" value="Submit"/></center></td></tr>
						<tr><td style="text-align: right; font-weight: bold;"></td><td></td><td colspan="2"><center><input type="submit" name="clear" value="Clear"/></center></td></tr>
					</form>
						<tr><td style="text-align: right; font-weight: bold;"></td><td><p id="change1"><?php echo "<br/>{$_SESSION['value_income']}<br/>{$_SESSION['date_income']}";?></p></td></tr>
				</table>
			</div>
			
			<!--VIEW CUSTOMER COUNT-->
			<div id="customer_count" style="float:right;">
				Customer Count<br/><br/>
				<table class="addprodtable">
					<form name="addform" action="summary.php" method="POST">
						<tr>
							<td style="text-align: right; font-weight: bold;">Daily</td>
							<td><input type='date' name='dailydate_visitors' value="<?php echo "{$_SESSION['defaultdatecount']}"; ?>"/></td>
							<td colspan="2">
								<center>
								<input type="submit" name="dsubmitc" value="Registered"/>
								<input type="submit" name="dsubmitcu" value="Unregistered"/>
								</center>
							</td>
						</tr>
						
						<tr>
							<td style="text-align: right; font-weight: bold;">Monthly</td>
							<td>
								<select name="monthlyc">
									<?php
									//QUERY GETS ALL MONTHS IN DBASE
									$query = "select date_part('Month',store_date) from store_summary group by date_part('Month',store_date) order by date_part";
									$result = pg_query($dbconn, $query) or die('Query failed: ' . pg_last_error());
									while($row = pg_fetch_row($result)){
										if( "{$_SESSION['defaultmonthcount']}" == $row[0])
											echo '<option value="'.$row[0].'" selected="selected">'.$month[$row[0] - 1].'</option>';
										else
											echo '<option value='.$row[0].'>'.$month[$row[0] - 1].'</option>';
										
									}
									
									?>
								</select>
							</td>
							<td colspan="2">
								<center>
								<input type="submit" name="msubmitc" value="Registered"/>
								<input type="submit" name="msubmitcu" value="Unregistered"/>
								</center>
							</td>
						</tr>
						
						<tr>
							<td style="text-align: right; font-weight: bold;">Yearly</td>
							<td>
								<select name="yearlyc">
									<?php
									//QUERY GETS ALL YEARS IN DBASE
									$query = "select date_part('Year',store_date) from store_summary group by date_part('Year',store_date) order by date_part";
									$result = pg_query($dbconn, $query) or die('Query failed: ' . pg_last_error());
									while($row = pg_fetch_row($result)){
										if( "{$_SESSION['defaultyearcount']}" == $row[0])
											echo '<option value="'.$row[0].'" selected="selected">'.$row[0].'</option>';
										else
											echo '<option value='.$row[0].'>'.$row[0].'</option>';
									
									}
									
									?>
								</select>
							</td>
							<td colspan="2">
								<center>
								<input type="submit" name="ysubmitc" value="Registered"/>
								<input type="submit" name="ysubmitcu" value="Unregistered"/>
								</center>
							</td>
						</tr>	
						
						<tr>
						<td style="text-align: right; font-weight: bold;">Highest Day</td>
						<td></td>
						<td colspan="2">
						<center>
						<input type="submit" name="hdsubmitc" value="Registered"/>
						<input type="submit" name="hdsubmitcu" value="Unregistered"/>
						</center></td>
						</tr>
						
						<tr>
						<td style="text-align: right; font-weight: bold;">Highest Month</td>
						<td></td>
						<td colspan="2">
						<center>
						<input type="submit" name="hmsubmitc" value="Registered"/>
						<input type="submit" name="hmsubmitcu" value="Unregistered"/>
						</center></td>
						</tr>
						
						<tr>
						<td style="text-align: right; font-weight: bold;">Highest Year</td>
						<td></td>
						<td colspan="2">
						<center>
						<input type="submit" name="hysubmitc" value="Registered"/>
						<input type="submit" name="hysubmitcu" value="Unregistered"/>
						</center></td>
						</tr>

						<tr><td style="text-align: right; font-weight: bold;"></td><td></td><td colspan="2"><center><input type="submit" name="clearc" value="Clear"/></center></td></tr>

					</form>
						<tr><td style="text-align: right; font-weight: bold;"></td><td><p id="change2"><?php echo "{$_SESSION['print_type']}<br/>{$_SESSION['value_customer']}<br/>{$_SESSION['date_customer']}";?></p></td></tr>
				</table>
			</div>

		</div>
	
	</div>
	</center>
	
</body>

</html>