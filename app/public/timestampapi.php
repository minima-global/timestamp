<?php
	
	//This is JSON
	header('Content-Type: application/json; charset=utf-8');

	//Get the data
	$data = $_GET["tsdata"];
	
	//Check Length
	if(strlen($data) > 66){
		$json['status'] = false;
		$json['error']  = "Hash data wrong length";
		echo json_encode($json);
		exit();
	}

	//Generate a random UID..
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
	
	//SQL Insert into the DB..
	$sql = 'INSERT INTO timestampdata (uid,datahash) VALUES (?,?)';

	//Prepare the SQL.. removes possibility of injection 
	$stmt = $pdo->prepare($sql);
	
	//Run the SQL
	$stmt->execute([$uid,$data]);
	
	//Close the connection
	$pdo = null;
	
	//Output the JSON data
	$json['status'] = true;
	$json['data']   = $data;
	$json['uid']    = $uid;
	echo json_encode($json);	
?>	