<?php

global $valid_fan;

## LMSensors Fanspeeds
if ($device['os'] == "linux") 
{
  $oids = snmp_walk($device, "lmFanSensorsDevice", "-OsqnU", "LM-SENSORS-MIB");
  if ($debug) { echo($oids."\n"); }
  $oids = trim($oids);
  if ($oids) echo("LM-SENSORS ");
  $precision = 1;
  $type = 'lmsensors';
  foreach(explode("\n", $oids) as $data) 
  {
    $data = trim($data);
    if ($data) 
    {
      list($oid,$descr) = explode(" ", $data,2);
      $split_oid = explode('.',$oid);
      $index = $split_oid[count($split_oid)-1];
      $oid  = "1.3.6.1.4.1.2021.13.16.3.1.3.". $index;
      $current = snmp_get($device, $oid, "-Oqv", "LM-SENSORS-MIB");
      $descr = trim(str_ireplace("fan-", "", $descr));
      if($current > '0' && $current < '500') {
        discover_fan($valid_fan,$device, $oid, $index, $type, $descr, $precision, NULL, NULL, $current);
      }
    }
  }
}

?>