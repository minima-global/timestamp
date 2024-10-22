<html>
<head>
	
	<title>Mini Stamp</title>
		
</head>

<body>
<br>

<center>

<h1>Check UID</h1>
	
<?php
	
	//Connect to DB
	$pdo = new PDO('mysql:dbname=tutorial;host=mysql', 'tutorial', 'secret', [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

	//create a random string..
	$uid = $_POST["tsuid"];
	
	//SQL Insert into the DB..
	$sql = "SELECT * FROM timestampdata WHERE uid=?";

	//Prepare the SQL.. removes possibility of injection 
	$stmt = $pdo->prepare($sql);
	
	//Run the SQL
	$stmt->execute([$uid]);

	if ($stmt->rowCount() == 0) {
		echo "No Data found! for that UID :".$uid; 
	}else{

		//Get the data
		$row = $stmt->fetch();	

		echo "<table>";
		echo "<tr><td>Number:</td> <td>".$row['idtimestampdata']."</td></tr>";
		echo "<tr><td>UID:</td> <td>".$row['uid']."</td></tr>";
		echo "<tr><td>Request Created:</td> <td>".$row['datecreated']."</td></tr>";

		if($row['hasbeenadded'] == 0){	
			echo "<tr><td>On Chain Yet:</td> <td>NO</td></tr></table>";
		}else{
			echo "<tr><td>On Chain Yet:</td> <td>YES</td></tr>";
			echo "<tr><td>Date Stamped:</td> <td>".$row['datestamped']."</td></tr>";
		
			//Show more details..
			echo "<tr><td>&nbsp;</td> </tr>";
			echo "<tr><td>DATA:</td> <td>".$row['datahash']."</td></tr>";
			echo "<tr><td>ROOT:</td> <td>".$row['roottreehash']."</td></tr>";
			echo "<tr><td>PROOF:</td> <td style='max-width:300;word-wrap:break-word;'>".$row['proof']."</td></tr>";
			echo "</table><br><br>";
			
			echo "<div style='text-align:left;max-width:570;word-wrap:break-word;'>";
			echo "<b>To check this on your Minima node</b><br><br>";
			echo "First run this to find the coin.. and the date created<br><br>";
			echo "<code>archive action:addresscheck address:0xFFEEDD statecheck:".$row['roottreehash']."</code><br><br>";
			echo "Then run this to check it is in the hash..<br><br>";
			echo "<code>mmrproof data:\"".$row['datahash']."\" root:".$row['roottreehash']." proof:".$row['proof']."</code><br><br>";
			echo "If both functions return true.. your data is Forever time-stamped on Minima!";
			echo "</div>";			
		}
	}
	
	//Close the connection
	$pdo = null;
?>	
	
	<br>
	<Br>
	<a href="index.php">Back Home</a>

</center>





<?php

	//phpinfo();

	/*$pdo = new PDO('mysql:dbname=tutorial;host=mysql', 'tutorial', 'secret', [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
	$query = $pdo->query('SELECT * FROM testable');
	while ($row = $query->fetch()){
    	echo $row['testablecol']."-".$row['testablecol1']."<br>";
	}*/

	/*$ch = curl_init("minima:9005/status");
	$output = curl_exec($ch); 
	echo $output;

	echo "Hello World!";*/ 
?>

</body>

</html>



