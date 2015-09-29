<?php
//Copyright ©2015 Anouk Stein, M.D. 

include "SaveElection.php";

//Can also save as json depending on which is better for Tableau
download('csv');
echo "Results saved for Map View!";

?>
