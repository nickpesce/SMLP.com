<?
	error_reporting(E_ALL);

	echo "Updating Servers\n";

	$mysql_host = "127.0.0.1";
	$mysql_database = "smlp";
	$mysql_user = "root";
	$mysql_password = "mrsdonlon2015";
	$connection = mysqli_connect($mysql_host, $mysql_user, $mysql_password, $mysql_database);
	
	$result = mysqli_query($connection, "SELECT * from servers ORDER BY date DESC");
	while($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
	{
		echo $row["ip"]. ":" . $row["port"];

		$ip = $row["ip"];
		$port = $row["port"];
		echo "-------";
		echo $ip . " " . $port;
		$socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
		if ($socket === false) {
			echo "socket_create() failed: reason: " . 
			socket_strerror(socket_last_error()) . "\n";
		}
		$connect_result = socket_connect($socket, $ip, $port);

		if ($connect_result === false) {
			mysqli_query($connection, "UPDATE servers SET status=0 WHERE ip=" . $row["ip"]);
			echo "socket_connect() failed.\nReason: ($connect_result) " . 
			socket_strerror(socket_last_error($socket)) . "\n";
		}
		socket_set_timeout($socket, 5);

		$msg = "What up?";
		socket_write($socket, $msg, strlen($msg));
		

		$response = socket_read($socket, 2048, PHP_NORMAL_READ);
		echo $response;
		$data = stream_get_meta_data($response);
		if($data['timed_out']) {
			echo " is offline.\n";
			mysqli_query($connection, "UPDATE servers SET status=0 WHERE ip=".$ip);
		}else {
			echo " is online.\n";
			mysqli_query($connection, "UPDATE servers SET status=1 WHERE ip=".$ip);
			mysqli_query($connection, "UPDATE servers SET players=" . $response . " WHERE ip=".$ip);
		}
		socket_close($socket);

	}
	mysqli_free_result($result);
	mysqli_close($connection);
?>