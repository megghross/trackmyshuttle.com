<?php
$user_role = "";
if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) { die('Access denied'); };

if(isset($_SESSION['user_role'])){		
	$user_role =  $_SESSION['user_role'];
} 
else{
	header("location:index.php");
}

$pagename = $_SERVER["PHP_SELF"];	

$pagename = str_replace("/trackmyshuttle/admin/","",$pagename);
$pagename = str_replace("/","",$pagename);

?>		

<div class="page-sidebar" id="main-menu">
	<div class="page-sidebar-wrapper" id="main-menu-wrapper"> 
		<p class="menu-title m-b-30" style="margin-bottom: 0px;"><span class="pull-right"></span></p> 
		<ul>	
			<li <?php if ($pagename=="org.php"){echo "class='active'";}?>> <a href="org.php"> <i class="fa fa-building-o"></i><span class="title">Organizations</a></li>
			<li <?php if ($pagename=="inventory.php"){echo "class='active'";}?>> <a href="inventory.php"> <i class="fa fa-hdd-o"></i><span class="title">Trackers</a></li>
			<li <?php if ($pagename=="users.php") echo "class='active'";?>> <a href="users.php"> <i class="fa fa-users"></i> <span class="title">Users</span></a></li>				
		</ul>
	</div>	
</div>
<a href="#" class="scrollup">Scroll</a>
