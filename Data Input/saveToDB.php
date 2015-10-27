<!--Copyright �2015 Anouk Stein, MD-->
<html>
 <head>
<link rel="stylesheet" type="text/css" href="./elections.css" />
  <title>Election</title>
 </head>
 <body style="background-color: #F3E2A9; margin-left: 20px;">
<?php

//include "SaveElection.php";
include "administrativeFunctions.php";
connect();
//var_dump($_POST);
$election_id = $_POST['election'];
//get ballotItem
$maxCategoryCount = $_POST['maxCategoryCount'];
for ($i=0; $i<$maxCategoryCount; $i++){
    $hidden = "category" . $i;
    $info = explode('_', $_POST[$hidden]);

    $ballotItemID = $info[0];
    $order = $info[1];
    $index = $ballotItemID . "-question";
    $ballotItem = $_POST[$index];

saveQuestion($ballotItemID, $election_id, $ballotItem, $order);
}

$maxCount = $_POST['maxCount'];
for ($i=0; $i<$maxCount; $i++){
    $hidden = "candidateID" . $i;

    $info = explode('_', $_POST[$hidden]);

    $responseID = $info[0];
    $questionID = $info[1];
    $candidateName = $_POST[$responseID];
    $order = $info[2];
    //$categoryID
    if($questionID < 1){
      $index = $info[3];
      $questionName = $_POST[$index];
      //get id from name
      $questionID = getQuestionIDFromQuestion($questionName, $election_id);
    }
saveCandidate($responseID, $election_id, $candidateName, $questionID, $order);
}

//Machine count
//get all districts
$districts = getAllDistricts($election_id);
while ($d = mysql_fetch_array($districts)){
  $index = $d['district_id'] . "_district";
  $m = $_POST[$index];
  saveMachineCount($d['district_id'], $m, $election_id);
}
echo "Saved.";

mysql_close();
 ?>
 </body>
</html>
