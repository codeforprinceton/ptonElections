<?php
//Copyright ©2015 Anouk Stein, M.D. 
$q=$_GET["q"];
include "SaveElection.php";
connect();

echo '<script src="elections.js"></script>';

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
 
 //echo "<br><br><table class='buttons'><tr>";
 ////echo "<td><input type=submit name='output' value=' Save '></td>";
 //echo "<td><input type=submit name='output' value=' Download CSV '>";
 //echo "</td><td><input type=submit name='output' value=' Download Json '>";
 //echo "</td></tr></table>";
 
 
 
mysql_close();
?>