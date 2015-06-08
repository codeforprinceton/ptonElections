<!--Copyright ©2015 Anouk Stein, MD-->
<html>
 <head>
<link rel="stylesheet" type="text/css" href="pr.MyStylePlain.css" />
  <title>Election</title>
 </head>
 <body style="background-color: #F3E2A9; margin-left: 20px;">

<?php
include "SaveElection.php";
connect();
$maxCount = $_POST['maxCount'];
for ($i=0; $i<$maxCount; $i++){
    $hidden = "candidateID" . $i;
    $candidateID = $_POST[$hidden];
    $votes = $_POST[$candidateID];
saveElectionResults($_POST['district'], $_POST['machine'], $candidateID, $votes);
}

echo "<br><br><br><center><h1>Results Saved!</h1></center>";

echo "<form action = './output.php'  method ='post'>";
echo "Download results:<br><br><input type='radio' name='output' value='csv' checked='true'/>Save results as csv file<br><br> or<br>";
echo "<br><input type='radio' name='output' value='json' />Save results as Json";

mysql_close();
?>
<br><br><input type=submit value=' Download '>
</form>
 </body>
</html>