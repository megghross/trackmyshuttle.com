<?php
/*
*	!!! THIS IS JUST AN EXAMPLE !!!, PLEASE USE ImageMagick or some other quality image processing libraries
*/

require("../../../plus/php/config.php");
$user_id = mysql_real_escape_string($_SESSION['userid']);

$imgUrl = $_POST['imgUrl'];
$imgInitW = $_POST['imgInitW'];
$imgInitH = $_POST['imgInitH'];
$imgW = $_POST['imgW'];
$imgH = $_POST['imgH'];
$imgY1 = $_POST['imgY1'];
$imgX1 = $_POST['imgX1'];
$cropW = $_POST['cropW'];
$cropH = $_POST['cropH'];
$mode = $_POST['mode'];
	
$jpeg_quality = 100;

list($txt, $ext) = explode(".", $imgUrl);
$filename = "cropped_".str_replace('content/img_profile_and_cover/','',$txt);

file_put_contents("crop.txt",$filename);

/* Condition Path Names */
$imgUrl_orig = $imgUrl;
$imgUrl = str_replace('content','../../../content',$imgUrl);
$output_filename = "content/img_profile_and_cover/".$filename;

$save = "";
if($imgInitH==$imgH && $imgInitW==$imgW){
	$response = array(
		"status" => 'success',
		"url" => $imgUrl_orig,
		"mode" => $mode
	);
	$save = $imgUrl_orig;
}
else{
	$what = getimagesize($imgUrl);
	switch(strtolower($what['mime']))
	{
		case 'image/png':
			$img_r = imagecreatefrompng($imgUrl);
			$source_image = imagecreatefrompng($imgUrl);
			$type = '.png';
			break;
		case 'image/jpeg':
			$img_r = imagecreatefromjpeg($imgUrl);
			$source_image = imagecreatefromjpeg($imgUrl);
			$type = '.jpeg';
			break;
		case 'image/gif':
			$img_r = imagecreatefromgif($imgUrl);
			$source_image = imagecreatefromgif($imgUrl);
			$type = '.gif';
			break;
		default: die('image type not supported');
	}
	
	$resizedImage = imagecreatetruecolor($imgW, $imgH);
	imagecopyresampled($resizedImage, $source_image, 0, 0, 0, 0, $imgW, $imgH, $imgInitW, $imgInitH);	
	
	$dest_image = imagecreatetruecolor($cropW, $cropH);
	imagecopyresampled($dest_image, $resizedImage, 0, 0, $imgX1, $imgY1, $cropW, $cropH, $cropW, $cropH);	
	
	imagejpeg($dest_image, '../../../'.$output_filename.$type, $jpeg_quality);
	
	$response = array(
		"status" => 'success',
		"url" => $output_filename.$type,
		"mode" => $mode
	);
	
	$save = $output_filename.$type;
}

if($save!=""){
	if($mode=="profile"){mysql_query("UPDATE user SET user_img_profile ='$save' WHERE user_id = '$user_id'");}
	elseif($mode=="cover"){mysql_query("UPDATE user SET user_img_cover ='$save' WHERE user_id = '$user_id'");}
}

print json_encode($response);

?>