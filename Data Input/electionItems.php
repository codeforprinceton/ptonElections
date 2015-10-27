<!--Copyright �2015 Anouk Stein, MD-->
<html>
 <head>
<link rel="stylesheet" type="text/css" href="./elections.css" />
  <title>Election</title>
  <!-- <script src="elections.js"></script> -->
 </head>
 <body style="background-color: #F3E2A9; margin-left: 20px;">
   <div id="input"></div>
<form action="saveToDB.php" method="POST">
<?php
include "administrativeFunctions.php";
connect();
$election_id = $_POST['election'];


$result = getItemFromTable('elections', 'id', $election_id);
$election = mysql_fetch_array($result);
$date = date_create($election['election_date']);

echo "<h3>Election: {$election['name']} " . date_format($date,"m/d/Y") . " (" . $election['location'] . ")</h3>";

//list ballot items
//get categories
$categoriesResult = getCategories();
$count = 0;
$categoryCount = 0;
$newCount = -1;

while ($category = mysql_fetch_array($categoriesResult)){
  echo "<table><tr><td class='category'>";
  $hiddenCategory = "category" . $categoryCount++;
 //get candidates
 $ballotItemID = $category['id']; //echo " ID = {$ballotItemID} ";
 $unique = $ballotItemID . "-question";
 $candidates = getCandidates($ballotItemID);
 $ballotItem = $category['question']; //echo "Ballot item is " . $ballotItem;
 //
 echo "<h4>Ballot Item: <input type='text' name='{$unique}' value='{$ballotItem}'";
 //echo " oninput='jsBallotItem(this.value, this.name)'";
 echo "></td></tr>";
   echo "<input type=hidden name = $hiddenCategory METHOD='POST' value='{$ballotItemID}_{$categoryCount}'>";

$choiceCount = 1;
 while ($candidate = mysql_fetch_array($candidates)){
  $candidate_id = $candidate['id'];
  $name = $candidate['response'];
   $hidden = "candidateID" . $count++;
   echo "<tr><td width = 25px></td><td>";
   echo "Choice $choiceCount: <input type ='text' name='{$candidate_id}' value='{$name}'";
   //id='$choiceCount,$ballotItemID'
   //echo " oninput='jsCandidate(this.value, this.name, this.id)'
   echo "></td></tr>";

   echo "<input type=hidden name = '{$hidden}' METHOD='POST' value='{$candidate_id}_{$ballotItemID}_{$choiceCount}_'>";
   //echo "<input type=hidden name = 'category_id' METHOD='POST' value='{$ballotItemID}'>";
   $choiceCount++;
 }
 //Add More choices
 $limit = 4;
 while ($limit > 0){
   $hidden = "candidateID" . $count++;
   $candidate_id = $newCount--;
   echo "<tr><td width = 25px></td><td>";
   echo "Choice $choiceCount: <input type ='text' name='{$candidate_id}'";
   //" oninput='jsCandidate(this.value, this.name, this.id)'></td></tr>";
   echo "></td></tr>";

   echo "<input type=hidden name = $hidden METHOD='POST' value='{$candidate_id}_{$ballotItemID}_{$choiceCount}_'>";
   $choiceCount++;
   $limit--;
 }

echo "</table>";
}

//Add more ballot items
$ballotItemLimit = 0;
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
 $limit = 5;
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
echo createMachineCountColumn($election_id);
echo "</td><td>";
echo createRegVotersColumn($election_id);
echo "</td></tr></table>";
/*
echo "<h4>Set Number of Machines per District</h4>";
//get districts
$query = "Select * from election_districts JOIN districts where election_districts.election_id = $election_id and ";
$query .= "election_districts.district_id = districts.id";
$result = mysql_query($query) or die("Machine Query Failed!"  . $query);
while ($district = mysql_fetch_array($result)){
  $machineCount = $district['machine_count'];
  $name = $district['name'];
  $district_id = $district['id'] . "_district";
  echo "District $name: <input type='number' name=$district_id value=$machineCount> <br>";
}
*/

echo "<input type='hidden' name='election' value='{$election_id}'>";
echo "<input type=hidden name = 'maxCount' value='{$count}'>";
echo "<input type=hidden name = 'maxCategoryCount' value='{$categoryCount}'>";

?>
<input type='submit' value='Save'>
</form>
<?php mysql_close(); ?>

 </body>
</html>
