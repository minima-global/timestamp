<?php

	//This is JSON
	header('Content-Type: application/json; charset=utf-8');
	
	//Connect to DB
	$pdo = new PDO('mysql:dbname=tutorial;host=mysql', 'tutorial', 'secret', [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

	//create a random string..
	$uid = $_GET["tsuid"];
	
	//SQL Insert into the DB..
	$sql = "SELECT * FROM timestampdata WHERE uid=?";

	//Prepare the SQL.. removes possibility of injection 
	$stmt = $pdo->prepare($sql);
	
	//Run the SQL
	$stmt->execute([$uid]);

	if ($stmt->rowCount() == 0) {
		
		//UID not found
		$json['status'] = false;
		$json['uid']  = $uid;		
		$json['error']  = "UID not found";
		echo json_encode($json);
		exit(); 

	}else{

		//Get the data
		$row = $stmt->fetch();	

		//Start creation of JSON
		$json['status'] 		= true;
		$json['uid']  			= $uid;
		$json['data']  			= $row['datahash'];
		$json['number']  		= $row['idtimestampdata'];
		$json['datecreated']  	= $row['datecreated'];	
		
		if($row['hasbeenadded'] == 0){	
			//Not added to chain yet
			$json['onchain']  	= false;		
		}else{
			
			//Added on chain
			$json['onchain']  		= true;
			$json['datestamped']  	= $row['datestamped'];
			$json['root']  			= $row['roottreehash'];
			$json['proof']  		= $row['proof'];
			$json['address']  		= "0xFFEEDD";
		}
	}
	
	//Output JSON
	echo json_encode($json);
	
	//Close the connection
	$pdo = null;
?>	