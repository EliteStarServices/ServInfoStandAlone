<?php
	/*
	 * ServInfo Server Information Manager
	 * Author: Elite Star Services
	 * Web: https://servinfo.elite-star-services.com/
	 * 
	 * @Changelog:
	 * https://servinfo.elite-star-services.com/sc-changelog/
	 * 
	 * @License:
	 * GPL v3 | https://elite-star-services.com/license/
	*/


require('head.php');


// NEEDED FOR TOKEN AND DATABASE PAGES - REQUIRED IN HEAD NOW
//require('ss_cnf.php');




// GET CLIENT REMOTE VERSION
$remoteVersion = file_get_contents('https://cs.elite-star-services.com/servinfo_sa/dist/client/version.txt');




// GET SERVER DATA
$sqh = "select * from servdata";
if (!$result = $db->query($sqh)) { die('There was an error running the query [' . $db->error . ']'); }


?>
<link rel="stylesheet" href="https://cs.elite-star-services.com/common/css/wide.css"/>
<title>ServInfo - Show All Servers</title>
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default">
			<div class="panel-heading center bold-text">ServInfo Monitored Servers</div>
			<div class="panel-body">
			<table id="serv_info" class="table table-hover table-small" data-order='[[ 0, "asc" ]]'>
<?php
echo '<thead>';
echo "<tr><th>SERVER URL | ID</th><th>NAME | TOKEN</th><th>OPERATING SYSTEM</th><th>WEB USER | IP</th><th>WEB SW | ROOT</th><th>PHP | SQL</th><th>CERT | UPTIME</th><th>CPU | MEMORY INFO</th><th>VM | GUI</th><th>UPDATED</th><th><font size='1'>UP</font></th><th><font size='1'>VIEW</font></th><th><font size='1'>UPD</font></th><th><font size='1'>DEL</font></th></tr>";
echo '</thead>';

	while ($row = $result->fetch_assoc()) {


// ASSEMBLE SERVER DATA
$ip = $row["ip_db"];
$sid = $row["sid_db"];
$ssl = $row["ssl_db"];
$url = $row["url_db"];
$token = $row["pass_db"];



// patch if client < v0.9.4
$chkU = substr($url, -1);
if ($chkU != '/') {

$baseURL = $url;
//$post_url = '/servinfo/servinfo.php?'.$token;
$view = $url.'/servinfo/servinfo.php?'.$token;
$ug_url = $url.'/servinfo/';
$clientVersion = $row["ver_db"];
$clientVersion = str_replace(',', '', $clientVersion);

} else {
// GET BASE URL FROM SCRIPT URL
$tmp_url = explode('://', $url);
$exPath = strchr($tmp_url[1], '/');
$baseURL = str_replace($exPath, '', $url);
//$post_url = 'servinfo.php?'.$token;
$view = $url.'servinfo.php?'.$token;
$ug_url = $url;
$allVer = $row["ver_db"];
$getVer = explode(',', $allVer);
$clientVersion = $getVer[0];
$wp_version = $getVer[1];
//echo $baseURL;
}



// CONVERT URL TO DOMAIN
$url2dom = explode("://", $baseURL);
$domain = $url2dom[1];






//$view = $url.'/servinfo/servinfo.php?'.$token;

$upd_serv = 'sgl_srv.php?server='.$sid.'&update';
$del_serv = 'sgl_srv.php?server='.$sid.'&delete';

$preup = explode(":", $row["run_db"]);
$upTime = $preup[0].' Days, '.$preup[1].' Hrs, '.$preup[2].' Min';

$precpu = explode(":", $row["cpu_db"]);
// DISABLES CLOCK RATE DISPLAY - PHP8+ DOES NOT SHOW IT ANYMORE
// if ($precpu[2] != 0) { $cpuInf = $precpu[0].' x '.$precpu[1].' @ '.$precpu[2].'MHz'; } else { $cpuInf = $precpu[0].' x '.$precpu[1]; }
$cpuInf = $precpu[0].' x '.$precpu[1];

$premem = explode(":", $row["mem_db"]);
$memInf = 'Total: '.$premem[0].'MB / Available: '.$premem[1].'MB';





// CHECK FOR CLIENT UPGRADE IF STAND ALONE
if (version_compare($remoteVersion, $clientVersion) ==  1) {
	if ($wp_version == '0') {
		$cliVer = '<a href="'.$ug_url.'sc_upd.php?'.$token.'&return='.$sid.'" title="Update Client to v'.$remoteVersion.'"><font color="red"><b>Client v'.$clientVersion.'</b></font></a>';
	} else { 
		$cliVer = '<span title="Update Client in WP Dashboard"><font color="orange"><b>Client v'.$clientVersion.'</b></font></span>'; 
	}
} else { $cliVer = 'Client v'.$clientVersion; }





//CHECK SERVER STATUS USING PING
$online = "ONLINE";
unset($output);
exec("ping -c 1 -w 1 $domain", $output);
foreach($output as $response){
//  echo $response."<br>";
  if (strpos($response, "100%") !== false) { $online = "OFFLINE"; } /* !== */
}



// TABLE OUTPUT
$wpc = '';
$my_cm = '';
if ($wp_version != "0") {
	if ( version_compare( $wp_version, '4.7', 'lt' ) ) { 
		$wpc = "cp";
		$my_cm = 'CP v'.$wp_version;
	}
	if ( version_compare( $wp_version, '4.8', 'gt' ) ) { 
		$wpc = "wp";
		$my_cm = 'WP v'.$wp_version;
	}
}
echo "<tr class='".$wpc."'><td>";
echo '<a href="'.$baseURL.'"><small><strong>'.$baseURL.'</strong></a><br><small>'.$sid.'</small></small></td><td>';
echo '<small>'.$row["host_db"].'<br>'.$row["pass_db"].'</small></td><td>';

// SHOW xPress version if found
if ($my_cm != "") {
	$KorP = $my_cm;
} else {
	$KorP = $row["ker_db"];
}
echo '<small>'.$row["os_db"].'<br>'.$KorP.'</small></td><td>';

echo '<small>'.$row["user_db"].'<br>'.$ip.'</small></td><td>';
$www_in = $row["www_db"];
$web_out = explode(' (', $row["web_db"]);
$www_out = (strlen($www_in) > 21) ? '<span  title="'.$www_in.'">'.substr($www_in,0,18).'...' : $www_in;
echo '<small>'.$web_out[0].'<br>'.$www_out.'</small></td><td>';
$php_out = explode('-', $row["php_db"]);
$sql_out = explode('-', $row["sql_db"]);
$pma_link = $row["xdb1"];
if ($pma_link != "") {
echo '<small><b><a href="'.$pma_link.'" title="PHP MyAdmin Link">PHP '.$php_out[0].'</a></b><br>'.$sql_out[0].'</small></td><td>';
} else {
echo '<small>PHP '.$php_out[0].'<br>'.$sql_out[0].'</small></td><td>';
}


// DETERMINE HOW TO SHOW CERT INFO
if ($ssl == "No Certificate Found") {
	echo '<small>'.$ssl.'<br>'.$upTime.'</small></td><td>';
} else {
	$ext_ssl = explode(" | ", $ssl);
	echo '<small><span  title="'.$ext_ssl[0].'">Cert '.$ext_ssl[1].'</span><br>'.$upTime.'</small></td><td>';
}


echo '<small>'.$cpuInf.'<br>'.$memInf.'</small></td><td>';
echo '<small>'.$row["vm_db"].'<br>'.$row["gui_db"].'</small></td><td>';
echo '<small>'.$row["last_update"].'<br>'.$cliVer.'</small></td><td>';


// ICON AREA
$iconURL = "https://cs.elite-star-services.com/common/img/";
if ($online == "OFFLINE") { $ol_img = "offline-icon.png"; } else { $ol_img = "online-icon.png"; }
echo '<img src="'.$iconURL.$ol_img.'" width="32" title="SERVER '.$online.'"></td><td>';

// ONLY SHOW VIEW & UPDATE LINKS IF SERVER ONLINE
if ($online == "OFFLINE") { echo '---</td><td>---</td><td>'; } else { 
echo '<a href="'.$view.'"><img src="'.$iconURL.'view-icon.png" width="32" title="VIEW DETAILED INFO"></a></td><td>';
echo '<a href="'.$upd_serv.'"><img src="'.$iconURL.'update-icon.png" width="32" title="UPDATE SERVER"></a></td><td>';
}


// DELETE CONFIRMATION ADDED
echo '<a href="'.$del_serv.'" onclick="return confirm(`Are you sure you want to delete the entry '.$domain.'?`);"><img src="'.$iconURL.'delete-icon.png" width="32" title="DELETE SERVER"></a></td></tr>';
//echo '<a href="'.$del_serv.'"><img src="'.$iconURL.'delete-icon.png" width="32" title="DELETE SERVER"></a></td></tr>';


	}
				echo "</table>";

?>
		</div>
	</div>
</div>


<?php
include('foot.php');
?>