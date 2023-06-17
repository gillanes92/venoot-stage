<?php

	error_reporting(E_ALL);
	ini_set('display_errors', '1');



	$url = "http://venoot-stage.us-west-2.elasticbeanstalk.com/api/events/".$_GET["event"]."/images";
	$contents = file_get_contents($url);
	$contents = utf8_encode($contents);
	$results = json_decode($contents,true); 
	$cadena = "";
	$jsondata = array();
	//var_dump($results["images"]["extra_images"]);
	$imagenes= array();
	foreach ($results["images"]["extra_images"] as $product) {
		$nombre = explode("/",$product);
		//echo $nombre[count($nombre)-1];
		if ($cadena!="") $cadena.=",";
		$cadena.="'".$nombre[count($nombre)-1]."'";
		$imagenes[] = $nombre[count($nombre)-1];
	}
	$imagenes[] = $_GET["image_new"];
	$jsondata["extra_images"] = $imagenes;
	//$jsondata["extra_images2"] = $imagenes;
	//$jsondata["extra_images"] = substr($cadena,0,strlen($cadena)-1);
	//header('Content-type: application/json; charset=utf-8');
	//echo json_encode($jsondata, JSON_FORCE_OBJECT);
	//die();
	
	
		//API URL
	$url = 'http://venoot-stage.us-west-2.elasticbeanstalk.com/api/events/'.$_GET["event"].'/updateExtraImages';
	
	//create a new cURL resource
	
	//$jsondata["extra_images"] = array("xzzIqq2brL6p3MmoTAxDv60Ryiay479BHgJQclKD.jpeg","demo.jpg","claudio.jpg","pruebas.jpg");
	
	$ch = curl_init($url);
	$payload = json_encode($jsondata, JSON_FORCE_OBJECT);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	header('Content-type: application/json; charset=utf-8');
	$result = curl_exec($ch);
	echo $result;
	die();
	
	
?>