<?php
$response = json_decode(file_get_contents("php://input"), true);
$data = array();

if(isset($response) && isset($response['system'])) {
	$system = $response['system'];
	
	if($system == "tbweb" || $system == "todos") {
		$data["tbweb"] = "not_found";
	} 

	if($system == "sinan" || $system == "todos") {
		$data["sinan"] = "not_found";
	} 

	if($system == "sitetb"|| $system == "todos" ) {
		$data["sitetb"] = "not_found";
	} 

	if($system == "gal" || $system == "todos") {
		$data["gal"] = "not_found";
	}

	$data["status"] = 200;
	$data["message"] = "OK";
} else {
	$data["status"] = 401;
	$data["message"] = "Unauthorized";
}
echo json_encode($data);
?>