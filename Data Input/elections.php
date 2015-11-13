<?php
//Copyright �2015 Anouk Stein, M.D.
$getq=$_GET["q"];

include "administrativeFunctions.php";
echo '<script src="elections.js"></script>';
//connect();
session_start();

$parseQ = explode("-",$getq);
$q = $parseQ[0];
$functionName = $parseQ[1];

//echo "Name is " . $functionName;

switch ($functionName){
  case "showVoteInput":
    showVoteInput($q);
    break;
  case "saveVote":
    saveVote($q);
    break;
  case "saveAllForMap";
    saveAllForMap();
    break;
  case "showSpreadsheet";
    showSpreadsheet();
    break;
}

function showVoteInput($q){
  //TODO not editable if prior election
$info = explode(".", $q);
  $district = $info[0];
  $machine = $info[1];
  //get name of district
  $query = "Select * from districts where id = $district";
  //$result = mysql_query($query) or die("Query Failed!"  . $query);
  $result = runQuery($query);
  $districtInfo = $result->fetch_assoc();//mysql_fetch_array($result);
  $name = $districtInfo['name'];
 echo "<center><h1 class='title'>Enter Election Results for District: <span class='big'>$name</span>,  Machine: <span class='big'>$machine</span></h1></center>";
 echo "<form action = './saveInputs.php'  method ='post'>";

 //get categories
 $election_id = getCurrentElectionID();
 //TODO Refine
 $active = "true";
 if ($election_id != 1){
   $active = "false";
 }
 $categoriesResult = getCategories($election_id);
 $count = 0;
 $categoryCount = 0;
 echo "<table><tr><td class='category'>";
 while ($category = $categoriesResult->fetch_assoc()){
   //mysql_fetch_array($categoriesResult)){
  //get candidates
  $id = $category['id']; //echo " ID = {$id} ";

  $candidates = getCandidates($id);
  echo "<h1> {$category['question']}</h1><table>";

  while ($candidate = $candidates->fetch_assoc()){
    //mysql_fetch_array($candidates)){
  //  $info = "{$district},{$machine},{$candidate['candidate_id']},"; //echo $info;

    $hidden = "candidateID" . $count++;
    echo "<tr><td width = 25px></td><td>{$candidate['response']}</td><td>";
    echo "<input type ='number' id='{$candidate['id']}' name='{$candidate['id']}' value = ";
    echo "'";
    echo getElectionResults($district, $machine, $candidate['id']);
    echo "'";

    if ($active == "true"){
      echo " oninput='ajaxGetInfo(this.value, this.name)' min=0>";
    }else{
      //READONLY
      echo " READONLY>";
    }
    echo "</td></tr>";
    echo "<input type=hidden name = $hidden METHOD='POST' value='{$candidate['id']}'>";
   // echo "<input type=hidden name = 'candidateID' METHOD='POST' value='{$candidate['candidate_id']}'>";


  }
  echo "</table>";

  echo "<input type=hidden id = 'district' value='{$district}'>";
  echo "<input type=hidden id = 'machine' value='{$machine}'>";

  if ($categoryCount % 3 == 2){
   echo "</td><td style='padding-left:45px'>";
  }
  $categoryCount++;
 }
 echo "</td></tr></table>";
 echo "<input type=hidden name = 'maxCount' value='{$count}'>";
// echo "<input type=hidden name = 'maxCategoryCount' value='{$categoryCount}'>";
}
 //---------------------------------------------------------------------------------
 function saveVote($q){
  $info = explode(",", $q);
  $d = $info[0];
  $m = $info[1];
  $c = $info[2];
  $v = $info[3];

  saveElectionResults($d,$m,$c,$v);
 }
 //---------------------------------------------------------------------------------
 function saveAllForMap(){
  //Can also save as json depending on which is better for Tableau
  $date = date("m_d_Y");
  download('csv', $date);
  echo "Results saved for Map View as ptonElections_{$date}.csv!";
 }
 //---------------------------------------------------------------------------------
 function showSpreadsheet(){
    echo createOverviewTable();
 }
 //---------------------------------------------------------------------------------

mysql_close();
?>
