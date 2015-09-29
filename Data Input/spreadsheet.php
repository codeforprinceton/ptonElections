<?php
//Copyright ©2015 Anouk Stein, M.D. 

include "SaveElection.php";
connect();

echo createOverviewTable();

mysql_close();
?>