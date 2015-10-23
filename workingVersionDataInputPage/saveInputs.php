<!--Copyright ©2015 Anouk Stein, MD-->
<html>
 <head>
<link rel="stylesheet" type="text/css" href="./elections.css" />
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

if ($_POST['output'] == " Download CSV "){
    $filename = "electionResults.csv";
    header('Content-type: application/csv');
    header('Content-Disposition: attachment; filename='.$filename);
    echo download('csv');
}
 if  ($_POST['output'] == " Download Json "){
    $filename = "electionResults.json";
    header('Content-type: application/json');
    header('Content-Disposition: attachment; filename='.$filename);
    echo download('json');
}

echo "<br><br><br><center><h1>Results Saved!</h1></center>";
mysql_close();
?>

</form>
 </body>
</html>