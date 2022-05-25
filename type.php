<?php
	include "cnx.php";
	$request_method = $_SERVER["REQUEST_METHOD"];

	if (isset($_SERVER['HTTP_ORIGIN'])) {
        header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Max-Age: 86400');    // cache for 1 day
    }
	
    // Access-Control headers are received during OPTIONS requests
    if ($request_method == 'OPTIONS') {

        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
            header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");         

        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
            header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

        exit(0);
    }
		
	function getTacheType() {
		global $cnx;
		$sql = $cnx->prepare("SELECT idType, nomType, idParent FROM type");
		$sql->execute();
		$response = [];

		foreach($sql->fetchAll(PDO::FETCH_ASSOC) as $row) {
			$type = [
				'id' => $row['idType'],
				'name' => $row['nomType'],
				'idParent' => $row['idParent'],
			];
			$response[] = $type;
		}

		header('Content-Type: application/json');
		echo json_encode($response);
	}

	switch($request_method)
	{
		case 'GET':
			getTacheType();
			break;
		default:
			header("HTTP/1.0 405 Method Not Allowed");
			break;
	}
?>