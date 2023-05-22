<?php
    /*
    * ServInfo Client Installer v0.9.7
    * Author: Elite Star Services
    * Web: https://elite-star-services.com/servinfo
    *
    * @Changelog:
    * https://servinfo.elite-star-services.com/sc-changelog/
    * 
     * @License:
	 * GPL v3 | https://elite-star-services.com/license/
    */
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
<title>ServInfo - Client Installer</title>
</head>

<!-- TITLE TABLE -->
<div class="container">
<div class="row">
<div class="col-md-12">
<div class="panel panel-default panel-navbar">
<div class='panel-heading center'><a class='si-page-title' href='https://elite-star-services.com/servinfo/'>ServInfo Server Information Manager</a></div>
<table class='table table-condensed'>
<tr class='i center bold-text'><td colspan='2'>-- CLIENT INSTALLER --</td><td style='display:none;'></td></tr></table>



<?php
// SET ERROR REPORTING
error_reporting(0); //off
//error_reporting(E_ALL); //on
//ini_set('display_errors', '1'); //on



echo '<div class="panel-body">';


// IF NO FORM DATA
if(!isset($_GET['token'])) {



    
// PRE INSTALL CHECKS
echo "<table class='table' width='934px' align='center'>";
echo '<thead><tr><th style="display:none;"></th><th style="display:none;"></th></tr></thead>';
echo "<tr class='si-heading'><td colspan='2'>PRE INSTALLATION CHECKS</td><td style='display:none;'></td></tr>";




// GET SERVER INFO / VERIFY LINUX
$vm = "";
$mach = 'hostnamectl status';
exec($mach, $macout);
foreach ($macout as $key => $value) { 
    $minf = explode(": ", $value);
    $minf[0] = trim($minf[0]);
// Get Individual Items Here 
    if ($minf[0] == "Machine ID") { $sid = $minf[1]; }
    if ($minf[0] == "Chassis") { $vm = trim($minf[1]); }
//    if ($minf[0] == "Static hostname") { $host = $minf[1]; }
//    if ($minf[0] == "Virtualization") { $vm = strtoupper($minf[1]); }
//    if ($minf[0] == "Kernel") { $ker = $minf[1]; }
}

// Backup Method to Get SID
if (!$sid) { $sid = trim(shell_exec('cat /etc/machine-id 2>/dev/null')); }


// WARN AND DIE IF NO SID
echo '<tr><td class="e">Linux Based Server</td>';
if (!$sid) {
    echo '<td class="v fb"><b>Cannot Install | Machine ID Not Found</b><br>ServInfo is for Linux Based Servers Only at this time.</td></tr>'; 
    die();
} else {
    echo '<td class="v">Server Check Passed ... OK!</td></tr>';
}




// CHECK WRITE PERMISSION
echo '<tr><td class="e">File Write Permission</td><td class="v">';
$is_writable = file_put_contents('test.txt', "test");
if ($is_writable > 0) { 
    echo "Folder is Writable ... OK!</td></tr>";
    unlink('test.txt');
} else { 

// GET WEB ROOT FOLDER
$www = $_SERVER['DOCUMENT_ROOT'];

// DETERMINE WEB USER
$user = "Could Not Determine Web User";
$user = getenv('APACHE_RUN_USER');
if ($user == "") { $user = getenv('USER'); }
if ($user == "") { $user = get_current_user(); }
    echo "<span class='fb'><b>Cannot Install | Folder is NOT writable</b><br>"; 
    echo "The Root of your Web Server: ".$www."<br>Must be Writable for the Web User: ".$user."</span>";
    die ();
}




// CHECK PHP VERSION
echo '<tr><td class="e">PHP Version 5 Minimum</td><td class="v">';
$php_version=phpversion();
if($php_version<5)
{ die('<font color="firebrick">Cannot Install | PHP v'.$php_version.' is EOL!<font color="firebrick">Cannot Install | </td></tr></table>'); } else { echo 'PHP @ v'.$php_version.' ... OK!</td></tr>'; }


// CHECK IF SAFE MODE OFF
echo '<tr><td class="e">PHP Safe Mode Off</td><td class="v">';
if (ini_get("safe_mode"))
{ die('<font color="firebrick">Cannot Install | PHP Safe Mode is On!</font></td></tr></table>'); } else { echo 'PHP Safe Mode is Off ... OK!</td></tr>'; }


// CHECK IF EXEC ENABLED
echo '<tr><td class="e">PHP exec() Enabled</td><td class="v">';
if (function_exists('exec'))
{ echo 'PHP exec() is Enabled ... OK!</td></tr>'; } else { die('<font color="firebrick">Cannot Install | PHP exec() Not Enabled!</font></td></tr></table>'); }


echo "</table>";




?>
<div class="row">
<div class="col-md-12">
<div class="panel panel-default pullup">
<div class="panel-heading">
<div class="panel-title">CONFIGURE CLIENT SETTINGS</div>
</div>
<div class="panel-body">


<form class="pullup" method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>">


<b>Create Client Password</b><br>
<input type="text" name="token" size="65"><br>
<small>This should be a secure password</small><br><br>

<b>Enter Server Root URL or IP</b> <i>including</i> http:// or https://<br>
<input type="text" name="url" size="65"><br>
<small>Example: http://10.0.10.11 | Leave Blank if StandAlone</small><br><br>

<b>Enter Database User Name</b><br>
<input type="text" name="dbuser" size="65"><br>
<small>This user only needs access to determine the SQL Server Version</small><br><br>

<b>Enter Database User Password</b><br>
<input type="text" name="dbpass" size="65"><br>
<small>If this Server has no local SQL Database, leave the name and password blank</small>


<?php
// CHECK IF VM
/*
$vm = "";
$cmd = 'hostnamectl status';
exec($cmd, $out);
foreach ($out as $key => $value) { 
    $inf = explode(": ", $value);
    $inf[0] = trim($inf[0]);
    if ($inf[0] == "Chassis") { $vm = trim($inf[1]); }
}
*/
if ($vm == "vm") { 
    echo '
    <hr><b>Virtual Machine Detected</b><br>
    <small>If a VM was cloned it may not have a unique Machine ID.<br>
    If that applies here, run these two commands in a terminal as root / sudo to reset the Machine ID:<br>
    <b>rm -f /etc/machine-id<br>
    dbus-uuidgen --ensure=/etc/machine-id</b><br>
    ';
    $sid = trim(shell_exec('cat /etc/machine-id 2>/dev/null'));
    echo "Your Current Machine ID: ".$sid."</small><hr>";
}
?>

<input class="btn btn-primary" type="submit" value="Install Client" name="submit">
</form>
</div>
</div>
</div>




<?php
// FORM DATA EXISTS
} else {
?>




<div class="row">
<div class="col-md-12">
<div class="panel panel-primary pullup">
<div class="panel-heading">
<div class="panel-title">INSTALLING CLIENT</div>
</div>
<div class="panel-body">



<?php
$token = $_GET['token'];
$url = $_GET['url'];
$dbuser = $_GET['dbuser'];
$dbpass = $_GET['dbpass'];


if ($token == "") { die('<font color="firebrick">Password Cannot be Blank!</font>'); }






/* CREATE CLIENT FOLDER
$dir = 'servinfo';

// Create servinfo Folder with 774 Permissions - Owner will be the PHP User
if (!file_exists($dir)) {
    mkdir ($dir, 0774);
}

// COPY FILES

file_put_contents ($dir.'/test.txt', 'Test');
*/




// CHECK IF CONFIG FILE ALREADY EXISTS
if (file_exists('servinfo/sc_cnf.php')){
    echo "CONFIG FILE EXISTS - ALREADY INSTALLED?";
    die();
}




// DOWNLOAD AND EXTRACT PACKAGE
//file_put_contents("install.zip", fopen("https://cs.elite-star-services.com/servinfo/client/install.zip", 'r'));
//file_put_contents("install.zip", file_get_contents("https://cs.elite-star-services.com/servinfo/client/install.zip"));

$installURL = "https://cs.elite-star-services.com/servinfo_sa/dist/client/install.zip";
$result = fopen($installURL, 'rb');

if ( !$result ) {
    echo "<li class='small'>ALTERNATE DOWNLOAD METHOD</li>";
// IF DOWNLOAD FAILED -> SSL EXCEPTION
$arrContextOptions=array(
    "ssl"=>array(
        "verify_peer"=>false,
        "verify_peer_name"=>false,
    ),
);  
$result = file_get_contents($installURL, false, stream_context_create($arrContextOptions));
} 

if ( !$result ) {
    echo "FILE DOWNLOAD FAILED - ABORT!";
    die();
}

file_put_contents("install.zip", $result);





    echo "<li>Install Package Downloaded...</li>";
 $zip = new ZipArchive;
if ($zip->open('install.zip') === TRUE) {
    $zip->extractTo('./servinfo');
$zip->close();
  echo "<li>Package Files Installed...</li>";
  unlink('install.zip');
  echo "<li>Install Package Deleted...</li>";
} else {
  echo "INSTALL FAILED - PACKAGE NOT FOUND";
  die();
}



// CREATE CNF FILE



echo "<li>Create Config File...</li>";
echo "<small>Client Password: ".$token."<br>";
if ($url == "") {
    echo "No ServInfo Server Set - Edit sc_cnf.php to set ServInfo Server IP<br>";
} else {
    echo "Server URL: ".$url."<br>";
}
if ($dbpass == "") {
    echo "No Database Server Set - Edit sc_cnf.php to set Database Server Login<br></small>";
} else {
    echo "Database User: ".$dbuser."<br>";
    echo "Database Pass: ".$dbpass."<br></small>";
}



$write_cnf = "<?php
/* SERVINFO CLIENT CONFIGURATION */

	/*
	 * @Project:
	 * ServInfo Server Information Manager
	 * Author: DigTek (Elite Star Services)
	 * Web: https://elite-star-services.com/servinfo
	 * 
	 * @Changelog:
	 * https://servinfo.elite-star-services.com/sc-changelog/
	 *
	 * @License:
	 * GPL v3 | https://elite-star-services.com/license/
	*/

// PASSWORD PROTECTION - CLIENT TOKEN NEEDED
\$token = '".$token."';
    
// ENTER YOUR SERVINFO SERVER ADDRESS
\$si_url = '".$url."';
    
// ENTER LOCAL DATABASE LOGIN INFO (Used to get Server Type & Version))
// * If no DB Server leave these blank *
\$dbUser = '".$dbuser."';
\$dbPass = '".$dbpass."';


// SET MODES TO TRUE ONLY IF REQUESTED BY SUPPORT
\$debugMode = 'FALSE';
\$devMode = 'FALSE';

// PLEASE DO NOT EDIT BELOW THIS LINE

// SET ERROR REPORTING
if (\$debugMode != 'TRUE') {
    error_reporting(0);
} else {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
}

?>";



// WRITE CNF FILE
$cnf = fopen('servinfo/sc_cnf.php', 'a');
fwrite($cnf, $write_cnf);
fclose($cnf);
    echo "<li>Config File Created...</li>";



// DELETE INSTALLER FILE
  unlink('client-installer.php');
    echo "<li>Install Package Deleted...</li>";
    echo "<li><strong>Install Complete</strong>...</li>";


    echo '&nbsp;<br>&nbsp;<a class="btn btn-primary button-link bold-text" href="./servinfo/servinfo.php?'.$token.'" role="button">Run ServInfo</a>';



echo '
</div>
</div>
</div>
';


}
?>
</div>
</div>
</div>
</div>