<?php
/* ServInfo Server Information Manager */

	/*
	 * @Project: 
	 *   ServInfo Server Information Manager
	 *   Author: DigTek (Elite Star Services)
	 *   Web: https://elite-star-services.com/servinfo
	 *   Copyright © 2022 Elite Star Services
	 * 
	 * @Changelog:
	 * https://servinfo.elite-star-services.com/sc-changelog/
	 * 
	 * @License:
	 * GPL v3 | https://elite-star-services.com/license/
	*/

require('head.php');
?>

<title>ServInfo - Configuration & Capabilities</title>
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-heading"><center><b>SERVINFO SYSTEM INFORMATION</b></center></div>
					<div class="panel-body">



ServInfo is designed to collect Software and System Information for Linux Based Servers running php.
<br>


<br>* PROJECT STILL IN PUBLIC BETA - PROPER DOCUMENTATION TO FOLLOW *
<br>


<br>The machine hostname can be changed in the file 'etc/hostname' and should be a single unique name such as "MyServer" (this is also used as the machine prompt).
<br>


<br>To reset the Machine ID, run these two commands in a terminal as root / sudo:<br>
    <b>rm -f /etc/machine-id<br>
    dbus-uuidgen --ensure=/etc/machine-id</b>
<br>


<br><b>ServInfo Client System Requirements:</b>
<li>Database on localhost</li>
<li>Must have Shell Access</li>
<hr>
Systems Tested:
<li>Debian</li>
<li>Ubuntu</li>


				</div>
			</div>
		</div>
	</div>
</div>


<?php
include('foot.php');
?>