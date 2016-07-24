<!DOCTYPE html>
<html>
	<head>
		<title>Super Magical Love Party Forum</title>
		<link rel="stylesheet" type="text/css" href="pinkMaterial.css">
		<link rel="icon" type="image/png" href="resources/RainbowCupcake.png"/>
	</head>
	<body>
		<?php
			require 'navbar.php';
		?>
		<h2 style="display: inline">Forum</h2>		
		<a href="forum.php" style = "margin-left: 20px; display: inline"><img width=32, height = 32, src="resources/Sun.png" title = "Refresh"/></a> 

		<h3>Feel free to post <i>whatever</i> you want!</h3>
		<form action='forum.php' method='post' style = "padding: 10px; padding-left: 30px">
			Title: <INPUT TYPE = "TEXT" NAME='name'>
			Message: <textarea NAME='text' cols = "40" rows = "1"></textarea>
			Your Name: <INPUT TYPE = "TEXT" NAME='author'>
		<INPUT TYPE = "Submit" Name = "Submit1" VALUE = "Submit">
		</form>
		
		<?
			$name = trim(strip_tags($_POST['name']));
			$text = trim(strip_tags(nl2br($_POST['text'])));
			$author = trim(strip_tags($_POST['author']));
			$date = time();
   			$ipaddress = '';
   			if ($_SERVER['HTTP_CLIENT_IP'])
   			    	$ipaddress = $_SERVER['HTTP_CLIENT_IP'];
  			else if($_SERVER['HTTP_X_FORWARDED_FOR'])
  		      		$ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
 			else if($_SERVER['HTTP_X_FORWARDED'])
  				$ipaddress = $_SERVER['HTTP_X_FORWARDED'];
 			else if($_SERVER['HTTP_FORWARDED_FOR'])
 				$ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
 			else if($_SERVER['HTTP_FORWARDED'])
 			       $ipaddress = $_SERVER['HTTP_FORWARDED'];
			else if($_SERVER['REMOTE_ADDR'])
 			       $ipaddress = $_SERVER['REMOTE_ADDR'];
			else
  				$ipaddress = 'UNKNOWN';
			if($author == null)
			{
				$author = "anonymous";
			}
			if($name == null)
			{
				$name = "A Forum Post";
			}
			if($text != null)
			{
				$mysql_host = "127.0.0.1";
				$mysql_database = "smlp";
				$mysql_user = "root";
				$mysql_password = "mrsdonlon2015";
				$connection = mysql_connect($mysql_host, $mysql_user, $mysql_password);
				mysql_select_db($mysql_database);
				mysql_query("INSERT INTO forum (name, text, author, date, client) VALUES (\"". $name . "\", \"" . $text . "\", \"" . $author . "\", \"" . $date . "\", \"" . $ipaddress . "\")");
				mysql_close($connection);
			}

		?>
		
		<?
			$mysql_host = "127.0.0.1";
			$mysql_database = "smlp";
			$mysql_user = "root";
			$mysql_password = "mrsdonlon2015";
			$connection = mysql_connect($mysql_host, $mysql_user, $mysql_password);
			mysql_select_db($mysql_database);
			
			$result = mysql_query("SELECT * from forum ORDER BY date DESC");
			
			while($row = mysql_fetch_array($result, MYSQL_ASSOC))
			{
				$date = date('F d, Y g:i A', $row["date"]);
				echo "<div class=\"card\"> 
				<h3>" . $row["name"] . "</h3>
				<h5>" . $date . "</h5>
				<hr>
					" . $row["text"] .
				"<hr>
				<h5>" . $row["author"] . "</h5>
				</div>";
			}
			/*
			$servers = array();
			while($row = mysql_fetch_array($result, MYSQL_ASSOC))
			{
				$servers[] = $row;
			}
			rsort($servers);
			foreach($servers as $row)
			{
				echo "<div class=\"card\"> 
				<h3>" . $row["name"] . "</h3>
				<h5>" . $row["date"] . "</h5>
				<hr>
					IP: " . $row["ip"] .
				"<hr>
				<h5>" . $row["author"] . "</h5>
				</div>";
			}
			*/
			mysql_free_result($result);
			mysql_close($connection);
		?>
		<?php
			require 'footer.php';
		?>
	</body>
</html>