<?php
	$mensaje = "<!DOCTYPE html><html><head><style>".$_POST["css"]."</style></head><body>".$_POST["html"]."</body></html>";
	//API URL
	$url = 'http://venoot-stage.us-west-2.elasticbeanstalk.com/api/events/'.$_POST["eventoID"].'/testEmail';
	
	//create a new cURL resource
	$ch = curl_init($url);
	$data = array(
		'email' => $_POST["mail"],
		'body' => $mensaje
	);
	$payload = json_encode($data);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$result = curl_exec($ch);

	

	



?>