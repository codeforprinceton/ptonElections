<?php
//Copyright ©2015 Anouk Stein, M.D. 
$getq=$_GET["q"];

include "SaveElection.php";
echo '<script src="elections.js"></script>';
connect();

$parseQ = explode("-",$getq);
$q = $parseQ[0];
$functionName = $parseQ[1];

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
$info = explode(".", $q);
  $district = $info[0];  
  $machine = $info[1]; 
 echo "<center><h1 class='title'>Enter Election Results for District: <span class='big'>$district</span>,  Machine: <span class='big'>$machine</span></h1></center>";
 echo "<form action = './saveInputs.php'  method ='post'>";

 //get categories
 $categoriesResult = getCategories();
 $count = 0;
 $categoryCount = 0;
 echo "<table><tr><td class='category'>";
 while ($category = mysql_fetch_array($categoriesResult)){
  //get candidates
  $id = $category['category_id']; //echo " ID = {$id} ";
  
  $candidates = getCandidates($id);
  echo "<h1> {$category['category_name']}</h1><table>";
  
  while ($candidate = mysql_fetch_array($candidates)){
    $info = "{$district},{$machine},{$candidate['candidate_id']},"; //echo $info;
    
    $hidden = "candidateID" . $count++;
    echo "<tr><td width = 25px></td><td>{$candidate['candidate_name']}</td><td>";
    echo "<input type ='number' id='{$candidate['candidate_id']}' name='{$candidate['candidate_id']}' value = ";
    echo "'";
    echo getElectionResults($district, $machine, $candidate['candidate_id']);
    echo "'";
    //$functionStr = "ajaxGetInfo({$info}, this.value)"; // . $info . ")";

    echo " oninput='ajaxGetInfo(this.value, this.name)' min=0></td></tr>";
    echo "<input type=hidden name = $hidden METHOD='POST' value='{$candidate['candidate_id']}'>";
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
  download('csv');
  echo "Results saved for Map View!";
 }
 //---------------------------------------------------------------------------------
 function showSpreadsheet(){
    echo createOverviewTable();
 }
 //---------------------------------------------------------------------------------
mysql_close();
?>