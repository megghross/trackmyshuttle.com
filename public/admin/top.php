<?php
if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) { die('Access denied'); };

$user_key = "";
$org_key = "";
$orgName = "";
//CHECK USER SESSION
if(!(isset($_SESSION['userkey'])))
{
	header("location:index.php");
}
else {
	$user_key = $_SESSION['userkey'];
	$org_key = $_SESSION['orgkey'];
	$orgName = $_SESSION['org_name'];
	//IF ORGKEY FOUND IN URL THEN USE THIS ORGKEY
	if (isset($_GET["org"]))
	{
		if ($_SESSION['user_role']=="Platform-Admin") {
			$org_key = $_GET["org"];	
			include(SITEPATH . "/plus/php/class.organization.php");
			$objOrg = new organization();
			$orgData = $objOrg->getData($org_key);
			if ($orgData!=null){					
				$orgName = $orgData["org_name"];
			}
			else{
				header("location:".$_SERVER["PHP_SELF"]);
			}
		}
		else{
			header("location:".$_SERVER["PHP_SELF"]);
		}		
	}
	
	$loc_key = "0";
	if (isset($_GET["loc"]))
	{
		$loc_key = $_GET["loc"];	
		include(SITEPATH . "/plus/php/class.location.php");
		$objLoc = new location();
		$locData = $objLoc->getData($loc_key);
		if ($locData!=null){					
			$loc_key = $locData["loc_key"];
		}
		else{
			header("location:".$_SERVER["PHP_SELF"]);
		}
	}
}
?>

<!-- BEGIN HEADER -->
<div class="header navbar navbar-inverse "> 
  <div class="navbar-inner">
	<div class="header-seperation">
	  <ul class="nav pull-left notifcation-center" id="main-menu-toggle-wrapper" style="display:none">	
		<li class="dropdown"> <a id="main-menu-toggle" href="#main-menu"  class="" > <div class="iconset top-menu-toggle-white"></div> </a> </li>		 
	  </ul>
	  <a href="/dashboard"><img src="plus/img/logo3.png" class="logo" alt=""  data-src="plus/img/logo3.png" data-src-retina="plus/img/logo3.png" width="180" height="35" style="margin-left: 30px; margin-top: 10px;"/></a>
	</div>
	<div class="header-quick-nav" >
	<div class="pull-left"> 
	  <ul class="nav quick-section">
		<li class="quicklinks"> <a href="#" class="" id="layout-condensed-toggle" >
		  <div class="iconset top-menu-toggle-dark"></div>
		  </a></li>
	  </ul>	 
	</div>
	<div class="pull-right"> 
		<ul class="nav quick-section" style="margin-top:8px">
		  <h4 style="color:#ee0707" ><?php echo $orgName; ?></h4>
		</ul>
	    <ul class="nav quick-section ">
		  <li class="quicklinks"> 
			  <a data-toggle="dropdown" class="dropdown-toggle  pull-right " href="#" id="user-options">						
				  <div class="iconset top-settings-dark "></div> 	
			  </a>
			  <ul class="dropdown-menu  pull-right" role="menu" aria-labelledby="user-options">
			  	<li><a href="changepwd.php"><i class="fa fa-key"></i>&nbsp;&nbsp;Update Password</a></li>
				<li><a href="../logout.php"><i class="fa fa-power-off"></i>&nbsp;&nbsp;Log Out</a></li>
			 </ul>
		  </li> 
	  </ul>
	</div>
	</div>
  </div>
</div>
<!-- END HEADER -->