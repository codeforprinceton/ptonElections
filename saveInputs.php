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

if ($_POST['output'] == " Save and Download CSV "){
    $filename = "electionResults.csv";
    header('Content-type: application/csv');
    header('Content-Disposition: attachment; filename='.$filename);
    download();
}
 if  ($_POST['output'] == " Save and Download Json "){
    $filename = "electionResults.json";
    header('Content-type: application/json');
    header('Content-Disposition: attachment; filename='.$filename);
    getResultsOutputJsn();
}



echo "<br><br><br><center><h1>Results Saved!</h1></center>";

//echo "<form action = './output.php'  method ='post'>";
//echo "Download results:<br><br><input type='radio' name='output' value='csv' checked='true'/>Save results as csv file<br><br> or<br>";
//echo "<br><input type='radio' name='output' value='json' />Save results as Json";
//echo "<br><br><input type=submit value=' Download '>"
mysql_close();
?>

</form>
 </body>
</html>