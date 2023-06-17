<?php
	error_reporting(E_ALL);
	ini_set('display_errors', '1');
	$url = "http://venoot-stage.us-west-2.elasticbeanstalk.com/api/events/".$_POST["event"]."/images";
	$contents = file_get_contents($url);
	$contents = utf8_encode($contents);
	$results = json_decode($contents,true); 
	$cadena = "";
	foreach ($results as $product) {
		foreach ($product as $products) {
		if (gettype($products)!='array') 
		{
			$cadena .=$products.",";
		}
		else
		{
			foreach ($products as $galeria) 
			{
				$cadena .=$galeria.",";
			}
		}

		}
	}
	$jsondata = array();
	$jsondata["images"] = substr($cadena,0,strlen($cadena)-1);
	header('Content-type: application/json; charset=utf-8');
	echo json_encode($jsondata, JSON_FORCE_OBJECT);
?>