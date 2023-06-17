<?php
if($_FILES)
{
$resultArray = array();
	foreach ( $_FILES as $file){
             	$fileName = $file['name'];
             	$tmpName = $file['tmp_name'];
             	$fileSize = $file['size'];
             	$fileType = $file['type'];
             	if ($file['error'] != UPLOAD_ERR_OK)
             	{
                 		error_log($file['error']);
                 		echo JSON_encode($file['error']);
             	}
		
				$fileNameCmps = explode(".", $fileName);
				$fileExtension = strtolower(end($fileNameCmps));
				$newFileName = md5(time() . $fileName) . '.' . $fileExtension;
				$dest_path = $newFileName;
				move_uploaded_file($tmpName, $dest_path);
					
				$fileTmpPath = $file['tmp_name'];

				$url = "http://localhost/editor/grapesjs/uploads/";			
		
	
             	$fp = fopen($tmpName, 'r');
             	$content = fread($fp, filesize($tmpName));
             	fclose($fp);
            	$result=array(
                 		'name'=>$newFileName,
                 		'type'=>'image',
                 		'src'=>$url.$newFileName,
                 		'height'=>350,
                 		'width'=>250
             	); 
		// we can also add code to save images in database here.
              	array_push($resultArray,$result);
 	}    
$response = array( 'data' => $resultArray );
echo json_encode($response);
}
?>