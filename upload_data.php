<?php
 
// Connection data
$serveur = "xxx";
$login = "xxx";
$base = "xxx";
$pass = "xxx";
$table = "teleinfo";
 
// Check password
if(isset($_GET["PASSWD"])) {
	$passwd = $_GET["PASSWD"];
	$realPasswd = "123456";
	if(strcmp($passwd,$realPasswd)==0) {
 
		// Get data from GET
		if(!isset($_GET["LOAD"])) {
			$load = "NULL";
		}else {
			$load = $_GET["LOAD"];
		}
		if(!isset($_GET["HPHC"])) {
			$hphc = "NULL";
			$price = 0;
		}else {
			$hphc= $_GET["HPHC"];
			if(strcmp($hphc,"HP")==0) {
				$price = 1;
			} else {
				$price = 0;
			}
		}
 
		// Connect to server
		$con = mysql_connect($serveur, $login, $pass);
		if (!$con) {
			die('Could not connect: ' . mysql_error());
		}
		mysql_select_db($base) or die("Error connecting to $base");
		mysql_query("SET NAMES 'utf8'");
 
		// Add data to table
		$req = "INSERT INTO $table(time,papp,ptec,price) VALUES(NOW(),$load,'{$hphc}',$price)";
		$requete = mysql_query($req);
 
		// If an error occurs, save it to a log file
		if (!$requete) {
			$myfile = fopen("logfail.txt", "w");
			fwrite($myfile, "res = " . $requete . "---req = " . $req . "---load = " . $load . "---hphc = " . $hphc . "---error = " . mysql_error());
			fclose($myfile);
		}
	}
}
?>
