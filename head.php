<?php
/* 
 * !! PLEASE DO NOT EDIT THIS FILE !! 
 * Read "How To Use System" (file "info.php") @ MENU -> Information -> How To Use System
 * You can include "userMenu.php" to add a Custom Menu that will not be affected by updates.
*/

	/*
	 * @Project: 
	 *   ServInfo Server Information Manager
	 *   Author: Elite Star Services
	 *   Web: https://elite-star-services.com/servinfo
	 * 
	 * @Changelog:
	 * https://servinfo.elite-star-services.com/sc-changelog/
	 * 
	 * @License:
	 *   GPL v3 | https://elite-star-services.com/license/
	*/

// SET CURRENT SERVER VERSION
$serverVersion = "0.9.5";
require('ss_cnf.php');

?>
<head>
<!DOCTYPE html>
<html lang="en">
<meta charset="utf-8">
<!-- 3rd Party Hosted -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fontawesome-4.7@4.7.0/css/font-awesome.min.css">
<!-- Elite Star Hosted -->
<link rel="stylesheet" href="https://cs.elite-star-services.com/common/css/bootstrap-datetimepicker.min.css"/>
<link rel="stylesheet" href="https://cs.elite-star-services.com/common/css/ess.css"/>
<script src="https://cs.elite-star-services.com/common/js/jquery.min.js"></script>
<script src="https://cs.elite-star-services.com/common/js/bootstrap.min.js"></script>
<script src="https://cs.elite-star-services.com/common/js/moment-with-locales.js"></script>
<script src="https://cs.elite-star-services.com/common/js/bootstrap-datetimepicker.min.js"></script>
<!-- DataTables  -->
<link rel="stylesheet" href="https://cs.elite-star-services.com/common/css/dataTables.bootstrap.min.css">
<script src="https://cs.elite-star-services.com/common/js/jquery.dataTables.min.js"></script>
<script src="https://cs.elite-star-services.com/common/js/dataTables.bootstrap.min.js"></script>
<script src="https://cs.elite-star-services.com/common/js/ess.dataTables.js" type="text/javascript"></script>
<link rel="shortcut icon" href="https://cs.elite-star-services.com/common/img/sifavicn.png">
<!-- Scripts Not Needed? 
	<script src="js/bootstrapValidator.min.js" type="text/javascript"></script>
	<script src="js/jquery.confirm.min.js" type="text/javascript"></script>
	<script src="https://cdn.datatables.net/plug-ins/1.10.12/sorting/numeric-comma.js"></script>
-->
</head>
<body>

	<div class="container">
	<nav class="navbar navbar-inverse" role="navigation">
		<div class="navbar-header">
			<a class="navbar-brand" href="./index.php"><b><i class="wpmi-icon wpmi-position-before wpmi-align-top wpmi-size-1.4 fa fa-info-circle"></i>&nbsp;&nbsp;ServInfo</b></a>
		</div>
		<div id="navbar" class="navbar-collapse collapse">
			<ul class="nav navbar-nav">
				<li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="glyphicon glyphicon-cog"></i> <?php echo "Main Menu"; ?>
						<span class="caret"></span></a>
					<ul class="dropdown-menu">
					<li><a href="./sho_srv.php"><i class="fa fa-eye fa-fw"> </i>View All Servers</a></li>
					<li><a href="./sgl_srv.php"><i class="fa fa-h-square fa fa-plus-square fa-fw"> </i>Add New Server</a></li>
					<li><a href="./upd_srv.php"><i class="fa fa-database fa-fw"> </i>Update All Servers</a></li>
					<li><a href="https://elite-star-services.com/dev/?smd_process_download=1&download_id=41"><i class="fa fa-download fa-fw"> </i>Download Client</a></li>
					</ul>
				</li>
<?php
// OPTIONAL MENU SPAWNS HERE
$cus = "userMenu.php";
if (file_exists($cus)) { include $cus; }
?>
				<li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#">
						<i class="fa fa-info fa-fw"></i> <?php echo 'Information'; ?><span class="caret"></span></a>
					<ul class="dropdown-menu">
					<li><a href="./info.php"><i class="fa fa-file-text-o fa-fw"></i> <?php echo 'How To Use System'; ?></a></li>
					<li><a href="https://elite-star-services.com"><i class="fa fa-user-o fa-fw"></i> <?php echo 'Contact Us'; ?></a></li>
					</ul>
				</li>
			</ul>

		</div>
		</nav>



<?php

// CHECK IF UPDATE AVAILABLE
$latestVersion = file_get_contents('https://cs.elite-star-services.com/servinfo_sa/dist/server/version.txt');

  if ( !$latestVersion ) {
    $noUp = "Yes";
  }

  if (version_compare($latestVersion, $serverVersion) ==  1) {
    $hasUpdate = "Yes";
  }


// SHOW VERSION / UPGRADE INFORMATION
if ($hasUpdate) {
	echo "<table class='table table-condensed alert-table'>";
    echo "<tr class='w center'><td colspan='2'>** SERVER UPDATE AVAILABLE -- New Version: ".$latestVersion." ~ ";
    echo "Your Version: ".$serverVersion." -- <a class='bold-text' href='ss_upd.php?".$Stoken."'>UPGRADE SERVER NOW</a> **</td><td style='display:none;'></td></tr></table>";
} elseif ($noUp) {
    echo "<table class='table table-condensed alert-table'><tr class='i center'><td colspan='2'>* UNABLE TO CHECK FOR UPDATE * (Current Server Version ".$serverVersion.")</td><td style='display:none;'></td></tr></table>";
} else {
    // Maybe just display nothing if no update needed?
    //echo "<table class='table table-condensed alert-table'><tr class='i center'><td colspan='2'>-- ServInfo Version ".$serverVersion." --</td><td style='display:none;'></td></tr></table>";
}
?>		



	</div>
</div>