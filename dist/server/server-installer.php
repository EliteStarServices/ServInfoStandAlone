<?php
    /*
    * ServInfo Server Installer v0.9.4
    * Author: Elite Star Services
    * Web: https://elite-star-services.com/servinfo
    *
    * @Changelog:
    * https://servinfo.elite-star-services.com/ss-changelog/
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
<title>ServInfo - Server Installer</title>
</head>

<!-- TITLE TABLE -->
<div class="container">
<div class="row">
<div class="col-md-12">
<div class="panel panel-default panel-navbar">
<div class='panel-heading center'><a class='si-page-title' href='https://elite-star-services.com/servinfo/'>ServInfo Server Information Manager</a></div>
<table class='table table-condensed'>
<tr class='i center bold-text'><td colspan='2'>-- SERVER INSTALLER --</td><td style='display:none;'></td></tr></table>



<?php
// SET ERROR REPORTING
error_reporting(0); //off
//error_reporting(E_ALL); //on
//ini_set('display_errors', '1'); //on




echo '<div class="panel-body">';


// IF NO FORM DATA
if(!isset($_GET['dbpass'])) {



    
// PRE INSTALL CHECKS
echo "<table class='table' width='934px' align='center'>";
echo '<thead><tr><th style="display:none;"></th><th style="display:none;"></th></tr></thead>';
echo "<tr class='si-heading'><td colspan='2'>PRE INSTALLATION CHECKS</td><td style='display:none;'></td></tr>";




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
    echo "<font color='firebrick'><b>Cannot Install | Folder is NOT writable</b></font><br>"; 
    echo "The Root of your Web Server: ".$www."<br>Must be Writable for the Web User: ".$user;
    die ();
}



// CHECK PHP VERSION
echo '<tr><td class="e">PHP Version 5 Minimum</td><td class="v">';
$php_version=phpversion();
if($php_version<5)
{ die('<font color="firebrick">Cannot Install | PHP v'.$php_version.' is EOL!</font></td></tr></table>'); } else { echo 'PHP @ v'.$php_version.' ... OK!</td></tr>'; }


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
<div class="panel-title">CONFIGURE SERVER SETTINGS</div>
</div>
<div class="panel-body">



<form class="pullup" method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>">


<b>Create Password</b><br>
<input type="text" name="token" size="65"><br>
<small>This should be a secure password</small><br><br>

<b>Enter Database User Name</b><br>
<input type="text" name="dbuser" size="65"><br>
<small>This user needs access to create and write database files</small><br><br>

<b>Enter Database User Password</b><br>
<input type="text" name="dbpass" size="65"><br><br>

<b>Enter Database Host</b><br>
<input type="text" name="dbhost" value="localhost" size="65"><hr>


<input class="btn btn-primary" type="submit" value="Install Server" name="submit">
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
<div class="panel-title">INSTALL SERVER</div>
</div>
<div class="panel-body">



<?php
$token = $_GET['token'];
$url = $_GET['url'];
$dbhost = $_GET['dbhost'];
$dbuser = $_GET['dbuser'];
$dbpass = $_GET['dbpass'];


if ($dbpass == "") { die('<font color="firebrick">Password Cannot be Blank!</font>'); }



// CHECK IF CONFIG FILE ALREADY EXISTS
if (file_exists('servinfo/ss_cnf.php')){
    echo "CONFIG FILE EXISTS - ALREADY INSTALLED?";
    die();
}



// Connect to DB Server
$pre = new mysqli('localhost', $dbuser, $dbpass);

if (mysqli_connect_errno()) {
  exit('Unable to Connect to MySQL Server: '. mysqli_connect_error());
}


// CREATE DATABASE
$sql = "CREATE DATABASE servinfo DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci";

if ($pre->query($sql) === TRUE) {
  echo '<li>Database Successfully Created...</li>';
}
else {
 echo 'Unable to Create Database: '. $pre->error;
 die();
}

$pre->close();


// CREATE TABLE
$con = new mysqli('localhost', $dbuser, $dbpass, 'servinfo');

//
$mySql = "CREATE TABLE IF NOT EXISTS servdata (
          id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
          sid_db VARCHAR(55) NOT NULL,
          url_db VARCHAR(255) NOT NULL,
          ip_db VARCHAR(25) NOT NULL,
          pass_db VARCHAR(155) NOT NULL,
          host_db VARCHAR(155) NOT NULL,
          run_db VARCHAR(55) NOT NULL,
          os_db VARCHAR(255) NOT NULL,
          ker_db VARCHAR(155) NOT NULL,
          gui_db VARCHAR(155) NOT NULL,
          vm_db VARCHAR(155) NOT NULL,
          cpu_db VARCHAR(155) NOT NULL,
          mem_db VARCHAR(55) NOT NULL,
          web_db VARCHAR(255) NOT NULL,
          www_db VARCHAR(255) NOT NULL,
          user_db VARCHAR(155) NOT NULL,
          php_db VARCHAR(55) NOT NULL,
          ssl_db VARCHAR(155) NOT NULL,
          sql_db VARCHAR(255) NOT NULL,
          git_db VARCHAR(155) NULL,
          ver_db VARCHAR(25) NOT NULL,
          xdb1 VARCHAR(255) NULL,
          xdb2 VARCHAR(255) NULL,
          xdb3 VARCHAR(255) NULL,
          last_update VARCHAR(25) NOT NULL
)";


if ($con->query($mySql) === TRUE) {
  echo '<li>Required Tables Successfully Created...</li>';
}
else {
 echo '<br>Unable to Create Tables: '. $con->error;
 die();
}
//


$con->close();

echo "<li>Database Setup Complete...</li>";





// DOWNLOAD AND EXTRACT PACKAGE


$installURL = "https://cs.elite-star-services.com/servinfo_sa/dist/server/install.zip";
$result = fopen($installURL, 'rb');

if ( !$result ) {
    echo "<li class='small'>ALTERNATE DOWNLOAD METHOD</li>";
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
    echo "FILE DOWNLOAD FAILED - ABORT!";
    die();
}

file_put_contents("install.zip", $result);





    echo "<li>Install Package Downloaded...</li>";
 $zip = new ZipArchive;
if ($zip->open('install.zip') === TRUE) {
    $zip->extractTo('./');
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
    echo "<small>Server Password: ".$token."<br>";
    echo "Database Host: ".$dbhost."<br>";
    echo "Database User: ".$dbuser."<br>";
    echo "Database Pass: ".$dbpass."<br></small>";


$write_cnf = "<?php
/* SERVINFO SERVER CONFIGURATION */

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


// EDIT ENTRIES BELOW AS NEEDED TO MATCH YOUR SETUP
// Please read How To Use System @ MENU -> Information -> How To Use System
    
// DATABASE LOGIN
\$dbHost = '".$dbhost."';
\$dbDatabase = 'servinfo';
\$dbUser = '".$dbuser."';
\$dbPass = '".$dbpass."';

/* Connect to Database */
\$db = new mysqli(\$dbHost, \$dbUser, \$dbPass, \$dbDatabase);
\$db->set_charset('utf8');
if (\$db->connect_errno > 0) { die('Unable to connect to database [' . \$db->connect_error . ']'); }

// PASSWORD PROTECTION - SERVER TOKEN
\$Stoken = '".$token."';


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
$cnf = fopen('ss_cnf.php', 'a');
fwrite($cnf, $write_cnf);
fclose($cnf);
    echo "<li>Config File Created...</li>";



// DELETE INSTALLER FILES
  unlink('server-installer.php');
    echo "<li>Install Package Deleted...</li>";
    echo "<li><strong>Install Complete</strong>...</li>";


//    echo '&nbsp;<br>&nbsp;<a class="btn btn-primary button-link bold-text" href="sho_srv.php?'.$token.'" role="button">Run ServInfo</a>';
    echo '&nbsp;<br>&nbsp;<a class="btn btn-primary button-link bold-text" href="sho_srv.php" role="button">Run ServInfo</a>';



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