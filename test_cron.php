<?php
	
	function updateDB($db, $root, $proof, $data ){
		
		//Create the UPDATE sql..
		$upsql = "UPDATE timestampdata".
				" SET roottreehash=?, proof=?, hasbeenadded=1, datestamped=CURRENT_TIMESTAMP".
				" WHERE hasbeenadded=0 AND datahash=?";

		
		//Prepare the SQL.. removes possibility of injection 
		$stmt = $db->prepare($upsql);
	
		//Run the SQL
		$stmt->execute([$root,$proof,$data]);
		
	}
	
	//Connect to DB
	$pdo = new PDO('mysql:dbname=tutorial;host=mysql', 'tutorial', 'secret', [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

	//SQL Insert into the DB..
	$sql = "SELECT * FROM timestampdata WHERE hasbeenadded=0";

	//Prepare the SQL.. removes possibility of injection 
	$stmt = $pdo->prepare($sql);
	
	//Run the SQL
	$stmt->execute();

	if ($stmt->rowCount() == 0) {
		//No Data to timestamp..
		//echo "No Data to time stamp..";
	}else{
		echo "Time stamping ".$stmt->rowCount();

		//Create the MMR Create function..
		$mmrcreate = "mmrcreate nodes:[";
		while ($row = $stmt->fetch()){
    		$mmrcreate .= "\"".$row['datahash']."\",";
		}

		//Remove final,
		$mmrcreate = substr($mmrcreate,0,strlen($mmrcreate)-1); 

		//Add closing bracket
		$mmrcreate.="]";

		//Run it on Minima..
		$ch = curl_init("minima:9005/".urlencode($mmrcreate));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$output = curl_exec($ch); 
		
		//Convert to a JSON object
		$resp = json_decode($output,false);		
		//$json_pretty = json_encode($resp, JSON_PRETTY_PRINT); 
		//echo "<pre style='text-align:left;max-width:800;'>" . $json_pretty . "<pre/>"; 

		//And now get the txn ready..
		if(!$resp->status){
			//echo "Something went wrong..";
		}else{
			$root = $resp->response->root->data;
			$txncmd = "send amount:0.000001 address:0xFFEEDD state:{\"99\":\"".$root."\"}";
			echo "ADD TIMESTAMP:".$txncmd."<br><br>";		
			
			//Run on Minima..
			$ch = curl_init("minima:9005/".urlencode($txncmd));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			$output = curl_exec($ch); 

			//And now Update the DB..
			$nodes = $resp->response->nodes;
			foreach($nodes as $node){
				//Get the data and proof..
				$nodedata  = $node->data;
				$nodeproof = $node->proof;
				updateDB($pdo, $root, $nodeproof, $nodedata );	
			}
		}	
	}

	//Close the connection
	$pdo = null;
?>