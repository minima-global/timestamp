<html>
<head>
	
	<title>Mini Stamp</title>
		
</head>

<body>
<br>

<center>

<h1>Time Stamp Service</h1>
	
<?php

	function generateRandomHash($length = 20) {
    	$characters = '0123456789ABCDEF';
    	$charactersLength = strlen($characters);
    	$randomString = '';
    	for ($i = 0; $i < $length; $i++) {
    	    $randomString .= $characters[random_int(0, $charactersLength - 1)];
    	}
    	return "0x".$randomString;
	}

	//Connect to DB
	$pdo = new PDO('mysql:dbname=tutorial;host=mysql', 'tutorial', 'secret', [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

	//create a random string..
	$uid  = generateRandomHash();
	$data = $_POST["tsdata"];
	
	//SQL Insert into the DB..
	$sql = 'INSERT INTO timestampdata (uid,datahash) VALUES (?,?)';

	//Prepare the SQL.. removes possibility of injection 
	$stmt = $pdo->prepare($sql);
	
	//Run the SQL
	$stmt->execute([$uid,$data]);
	
	//Close the connection
	$pdo = null;

	echo "Data added to DB uid:".$uid." data:".$data;
?>	
	
	Write down your UID!<br>
	<BR>
	You will  need this to check when the data has actually been added to the chain<br>
	<Br>
	THEN - you will be given the proof of it's existence.<br>
	<br>
	After that you can check the data yourself on your own Minima node..<br>
	<Br>
	You will not need to use this service to check the data again in future.. :)
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



