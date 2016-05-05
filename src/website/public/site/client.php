<?php

require_once dirname(__FILE__)."/sip.base.php";
error_reporting(E_ALL^E_NOTICE);

debug_output("Starting ...");

// Try registering
try {

	$sipClient = new BaseSipClass("55060", "221.0.91.34:55060");
	$sipClient->debug = TRUE;
	$sipClient->register();
	$sipClient->close();

} catch (Exception $e) {

	die("Caught exception ".$e->getMessage."\n");

}


debug_output("Ending ...");

?>
