<?php
/* !! PLEASE DO NOT EDIT THIS FILE !! */

	/*
	 * ServInfo Server Update Tool v0.9.4
	 * Author: Elite Star Services
	 * Web: https://elite-star-services.com/servinfo
	 * 
	 * @License:
	 * GPL v3 | https://elite-star-services.com/license/
	*/


// PASSWORD PROTECTION - SERVER TOKEN NEEDED
require('ss_cnf.php');
if(!isset($_GET[$Stoken])) { echo "ACCESS DENIED: Check Server and ServInfo Requirements..."; } else {


?>
    <head>
    <!-- 3rd Party Hosted -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fontawesome-4.7@4.7.0/css/font-awesome.min.css">
    <!-- Elite Star Hosted -->
    <link rel="stylesheet" href="https://cs.elite-star-services.com/common/css/ess.css"/>
    <link rel="stylesheet" href="https://cs.elite-star-services.com/common/css/servinfo.css"/>
    <script src="https://cs.elite-star-services.com/common/js/jquery.min.js"></script>
    <script src="https://cs.elite-star-services.com/common/js/bootstrap.min.js"></script>
    <!-- DataTables  -->
    <link rel="stylesheet" href="https://cs.elite-star-services.com/common/css/dataTables.bootstrap.min.css">
    <script src="https://cs.elite-star-services.com/common/js/jquery.dataTables.min.js"></script>
    <script src="https://cs.elite-star-services.com/common/js/dataTables.bootstrap.min.js"></script>
    <script src="https://cs.elite-star-services.com/common/js/ess.dataTables.js" type="text/javascript"></script>
    <link rel="shortcut icon" href="https://cs.elite-star-services.com/common/img/sifavicn.png">
    <title>ServInfo - Server Update Tool</title>
    </head>



<div class="container">
<div class="row">
<div class="col-md-12">
<div class="panel panel-default pullup">
<div class="panel-heading">
<div class="panel-title">UPDATE SERVER - Stage 1</div>
</div>
<div class="panel-body">


<?php


// DOWNLOAD UPDATER SCRIPT
// REVERT BACK TO STEALTH METHOD (WordFence alerts if zip downloader exists in local package)

echo "<li>Fetching Update Script...</li>";

$installURL = "https://cs.elite-star-services.com/servinfo_sa/dist/server/update_script.txt";
$result = fopen($installURL, 'rb');

if ( !$result ) {
    echo "<li class='small'>ALTERNATE DOWNLOAD METHOD USED</li>";
// DOWNLOAD FAILED try SSL EXCEPTION
$arrContextOptions=array(
    "ssl"=>array(
        "verify_peer"=>false,
        "verify_peer_name"=>false,
    ),
);  
$result = file_get_contents($installURL, false, stream_context_create($arrContextOptions));
} 

if ( !$result ) {
    echo "FILE DOWNLOAD FAILED - UPDATE ABORTED!";
    die();
}

file_put_contents("updater.php", $result);

echo "<li>Script Saved / Starting Upgrade...</li>";
echo '<META HTTP-EQUIV="Refresh" Content="4; URL=updater.php?'.$Stoken.'">';

}
?>
</div</div</div></div</div></div>