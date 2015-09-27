<?php
//Copyright ©2015 Anouk Stein, M.D. 

//$q=$_GET["q"];

include "SaveElection.php";
connect();

echo createOverviewTable();

mysql_close();
?>