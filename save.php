<?php
//Copyright ©2015 Anouk Stein, M.D. 
$q=$_GET["q"];
include "SaveElection.php";
connect();

$info = explode(",", $q);
$d = $info[0];
$m = $info[1];
$c = $info[2];
$v = $info[3];

saveElectionResults($d,$m,$c,$v);
 //echo "<br>Saved $q";
mysql_close();
?>
