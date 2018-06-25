<?php
//CHECK USER SESSION
if(!(isset($_SESSION['userkey'])))
{
	header("location:index.php");
}			
?>