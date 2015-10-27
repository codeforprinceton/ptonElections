<?php
//Copyright �2015 Anouk Stein, MD
include "SaveElection.php";
connect();
if (isset($_POST['maxCount'])){
  $maxCount = $_POST['maxCount'];
  for ($i=0; $i<$maxCount; $i++){
      $hidden = "candidateID" . $i;
      $candidateID = $_POST[$hidden];
      $votes = $_POST[$candidateID];
  saveElectionResults($_POST['district'], $_POST['machine'], $candidateID, $votes);
  }
}

if ($_POST['output'] == " Download CSV "){
    $filename = "electionResults.csv";
    header('Content-type: application/csv');
    header('Content-Disposition: attachment; filename='.$filename);
    $date = date("m_Y");
    echo download('csv', $date);
}
 if  ($_POST['output'] == " Download Json "){
    $filename = "electionResults.json";
    header('Content-type: application/json');
    header('Content-Disposition: attachment; filename='.$filename);
    $date = date("m_Y");
    echo download('json', $date);
}
mysql_close();
?>
