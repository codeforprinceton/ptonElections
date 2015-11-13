<!--Copyright �2015 Anouk Stein, MD-->
<html>
 <head>
<link rel="stylesheet" type="text/css" href="./elections.css" />
  <title>Election</title>
 </head>
 <body style="background-color: #F3E2A9; margin-left: 20px;">
   <form action="saveToDB.php" method="POST">
<?php
include "administrativeFunctions.php";
//connect();
//var_dump($_POST);
//New election
//echo $_POST['month'] . " " . $_POST['year'];
$m = $_POST['month'];
$d = $_POST['day'];
$y = $_POST['year'];

if ($m<1 || $m>12 || $y<2015 || $y>3000  || $d<1 || $d>31){
  echo "<h3>Invalid date. Hit back button on browser and reenter.</h3>";
}else if(!isset($_POST['items']) || $_POST['items'] < 1){
    echo "<h3>Please set number of ballot items. Hit back button on browser and reenter.</h3>";
}else{
  $date ="$y-$m-$d 00:00:00";
  //2015-11-02 00:00:00
  $election_id = createNewElection($date, $_POST['location'], $_POST['name']);
  //echo "id is $election_id";

  //get last election data
  $result = getAllElections();
  $election = $result->fetch_assoc();//mysql_fetch_array($result);
  //$election = mysql_fetch_array($result);
  $priorElection_id = $election['id'];
  //echo "priorElection_id is  $priorElection_id";


  $count = 0;
  $categoryCount = 0;
  $newCount = -1;
  echo "<h3>Election: {$_POST['name']} $m/$d/$y Location: {$_POST['location']}</h3>";
  //Add more ballot items
  $ballotItemLimit = $_POST['items'];
  while ($ballotItemLimit > 0){
    echo "<table><tr><td class='category'>";
    $hiddenCategory = "category" . $categoryCount++;
   //get candidates
   $ballotItemID = $newCount--;
   $unique = $ballotItemID . "-question";

   echo "<h4>Ballot Item: <input type='text' name='{$unique}'";
   echo "></td></tr>";
  echo "<input type=hidden name = $hiddenCategory METHOD='POST' value='{$ballotItemID}_{$categoryCount}'>";

  $choiceCount = 1;
   //Add More choices
   $limit = 10;
   while ($limit > 0){
     $hidden = "candidateID" . $count++;
     $candidate_id = $newCount--;
     echo "<tr><td width = 25px></td><td>";
     echo "Choice $choiceCount: <input type ='text' name='{$candidate_id}'";
     echo "></td></tr>";

     echo "<input type=hidden name = $hidden METHOD='POST' value='{$candidate_id}_{$ballotItemID}_{$choiceCount}_{$unique}'>";
     $choiceCount++;
     $limit--;
   }
  echo "</table>";
    $ballotItemLimit--;
  }
  //machines and reg voters
  echo "<table><tr><td>";
  echo createMachineCountColumn($priorElection_id);
  echo "</td><td>";
  echo createRegVotersColumn($priorElection_id);
  echo "</td></tr></table>";

  echo "<input type=hidden name='election' value='{$election_id}'>";
  echo "<input type=hidden name = 'maxCount' value='{$count}'>";
  echo "<input type=hidden name = 'maxCategoryCount' value='{$categoryCount}'>";
  echo "<input type='submit' value='Save'>";
}
 ?>
 </form>
 </body>
</html>
