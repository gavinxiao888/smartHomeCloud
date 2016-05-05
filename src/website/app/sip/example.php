<?php
require_once('PhpSIP.class.php');

/* Sends NOTIFY to reset Linksys phone */

try
{
  $api = new PhpSIP();
  $api->setUsername('1001'); // authentication username
  $api->setPassword('123456'); // authentication password
  // $api->setProxy('some_ip_here'); 
  $api->addHeader('Event: resync');
  $api->setMethod('NOTIFY');
  $api->setFrom('sip:wangzh@221.0.91.34:55060');
  $api->setUri('sip:wangzh@221.0.91.34:55060');
  $res = $api->send();

  echo "response: $res\n";
  
} catch (Exception $e) {
  
  echo $e;
}

?>
