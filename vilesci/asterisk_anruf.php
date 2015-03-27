<?php
/* Copyright (C) 2006 Technikum-Wien
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as
 * published by the Free Software Foundation; either version 2 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307, USA.
 *
 * Authors: Gerald Raab <gerald.raab@technikum-wien.at>,
 *          Andreas Oesterreicher <andreas.oesterreicher@technikum-wien.at>
 */
require_once('../config.inc.php');
require_once('../../../config/vilesci.config.inc.php');
require_once('../../../include/functions.inc.php');
require_once('../../../include/person.class.php');
require_once('../../../include/benutzerberechtigung.class.php');
require_once('../../../include/mitarbeiter.class.php');

error_reporting(E_ALL);
$user = get_uid();

$ma = new mitarbeiter();
$ma->load($user);
//$ma->load('relgner');
$meldung = '';
if (isset($_REQUEST['nummer']) && $ma->telefonklappe){
	$nummer = $_REQUEST["nummer"];
	$nummer = preg_replace('/[^0-9]/','',$nummer);
        
	// callfile bauen:
	$sipselber = $ma->telefonklappe;
	$callfile = "Channel: SIP/".$sipselber."\n";
	$callfile .= "Callerid: ".$sipselber."\n";
	//$callfile .= "MaxRetries: 2\n";
	//$callfile .= "RetryTime: 60\n";
	$callfile .= "WaitTime: 30\n";
	$callfile .= "Context: test\n";
	$callfile .= "Extension:".$nummer."\n";
	//echo $callfile;
	$filename='callfile_'.$sipselber.'.call';
	$handle = fopen('/tmp/'.$filename,'w');
	fwrite($handle, $callfile);
	fclose($handle);

	$conn = ssh2_connect(ASTERISK_SERVER_IP, ASTERISK_SERVER_PORT);
	ssh2_auth_password($conn, ASTERSIK_SSH_USER, ASTERSIK_SSH_PWD);
	ssh2_scp_send($conn, '/tmp/'.$filename, '/var/spool/asterisk/outgoing/'.$filename);
	$conn = null;
	$meldung = 'Nummer '.$nummer." wird angerufen!";
	unlink('/tmp/'.$filename);
	if (!isset($_REQUEST["debug"]))
		die();
}


?>

<html>
<body>
<form name="numform" action="asterisk_anruf.php" method="POST">
<input type="text" name="nummer"><input type="submit" name="submit" value="Anruf">
<input type="hidden" name="debug" value="1">
</form>
<?php
	echo $meldung;
?>
<br>
<?php
	echo $ma->telefonklappe;
?>
</body>
</html>
