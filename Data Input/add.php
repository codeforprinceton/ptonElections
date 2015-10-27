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
connect();
//var_dump($_POST);
//New election
//echo $_POST['month'] . " " . $_POST['year'];
$m = $_POST['month'];
$y = $_POST['year'];
if ($m<1 || $m>12 || $y<2015 || $y>3000){
  echo "Invalid date. Hit back button on browser and reenter.";
}else{
  $date ="$y-$m-01 00:00:00";
  //2015-11-02 00:00:00
  $election_id = createNewElection($date, $_POST['location']);
  //echo "id is $election_id";

  //get last election data
  $result = getAllElections();
  $election = mysql_fetch_array($result);
  $election = mysql_fetch_array($result);
  $priorElection_id = $election['id'];
  echo "priorElection_id is  $priorElection_id";


  $count = 0;
  $categoryCount = 0;
  $newCount = -1;
  echo "<h3>Election Date: $m/$y Location: {$_POST['location']}</h3>";
  //Add more ballot items
  $ballotItemLimit = 8;
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
   $limit = 8;
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
  //machines
  echo "<h4>Set Number of Machines per District</h4>";
  //get districts
  $query = "Select * from election_districts JOIN districts where election_districts.election_id = $priorElection_id and ";
  $query .= "election_districts.district_id = districts.id";
  $result = mysql_query($query) or die("Machine Query Failed!"  . $query);
  while ($district = mysql_fetch_array($result)){
    $machineCount = $district['machine_count'];
    $name = $district['name'];
    $district_id = $district['district_id'] . "_district";
    //save first
    saveMachineCount($district['district_id'], $machineCount, $election_id);
    echo "District $name: <input type='number' name=$district_id value=$machineCount> <br>";
  }

  echo "<input type=hidden name='election' value='{$election_id}'>";
  echo "<input type=hidden name = 'maxCount' value='{$count}'>";
  echo "<input type=hidden name = 'maxCategoryCount' value='{$categoryCount}'>";
}
mysql_close();
 ?>

 <input type='submit' value='Submit'>
 </form>
 </body>
</html>
