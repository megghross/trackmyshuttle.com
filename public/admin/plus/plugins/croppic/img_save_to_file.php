<?php
/*
*	!!! THIS IS JUST AN EXAMPLE !!!, PLEASE USE ImageMagick or some other quality image processing libraries
*/
$mode = $_POST['mode'];

$imagePath = "../../../content/img_profile_and_cover/";

$allowedExts = array("gif", "jpeg", "jpg", "png", "GIF", "JPEG", "JPG", "PNG");
$temp = explode(".", $_FILES["img"]["name"]);
$extension = end($temp);

if ( in_array($extension, $allowedExts))
{
	if ($_FILES["img"]["error"] > 0)
	{
		$response = array(
		   "status" => 'error',
		   "message" => 'ERROR Return Code: '. $_FILES["img"]["error"],
		);
		echo "Return Code: " . $_FILES["img"]["error"] . "<br>";
	}
	else
	{
		$filename = $_FILES["img"]["tmp_name"];
		list($width, $height) = getimagesize( $filename );

		
		list($txt, $ext) = explode(".", $_FILES["img"]["name"]);
		$newfilename = uniqid().".".$ext;
		
		move_uploaded_file($filename, $imagePath.$newfilename);
		
		$response = array(
		  "status" => 'success',
		  "url" => 'content/img_profile_and_cover/'.$newfilename,
		  "width" => $width,
		  "height" => $height
		);
	}
}
else
{
	$response = array(
		"status" => 'error',
		"message" => 'something went wrong',
	);
}
  
print json_encode($response);

?>
