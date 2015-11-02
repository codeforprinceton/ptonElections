<?php
include "SaveElection.php";

function getItemFromTable($table, $item, $id){
  $query = "Select * from $table where $item = $id";
  $result = mysql_query($query) or die("Query Failed!"  . $query);
  return $result;
}
//create/edit elections
function getAllElections(){
  $query = "Select * from elections order by election_date desc";
  $result = mysql_query($query) or die("Current election id Query Failed!"  . $query);
  return $result;
}
function createNewElection($date, $location, $name){
  $query = "INSERT INTO elections (election_date,location, name) VALUES ('{$date}', '{$location}', '{$name}')";
  $result = mysql_query($query) or die("Query Failed!".mysql_error());
  return mysql_insert_id();
}
function editElection($date, $electionID, $location){
  $query = "UPDATE elections SET location = '{$location}', election_date = $date WHERE $id = $electionID";
  $result = mysql_query($query) or die("Query Failed!".mysql_error());
}

function saveQuestion($ballotItemID, $election_id, $ballotItem, $order){
  //check
  $checkQuery = "Select * FROM questions where id=$ballotItemID";
  $checkResult = mysql_query($checkQuery) or die("Query Failed!"  . $checkQuery);
  $rows = mysql_num_rows($checkResult);
  if ($rows > 0){
    if (strlen($ballotItem) == 0){
      //delete
      $query = "DELETE from questions where id = $ballotItemID";
    }else{
      //update
      $query = "Update questions set question ='{$ballotItem}' where id = $ballotItemID";
    }
    $result = mysql_query($query) or die("Query failed" .$query);
  }else if(strlen($ballotItem) > 0){
    //insert
    $query = "Insert into questions (election_id, question, question_order) values ($election_id, '{$ballotItem}', $order)";
    $result = mysql_query($query) or die("Query failed" .$query);
  }
  //echo $query;

}

function saveCandidate($responseID, $election_id, $response, $questionID, $order){

  //check
  $checkQuery = "Select * FROM responses where id=$responseID"; //echo $checkQuery;
  $checkResult = mysql_query($checkQuery) or die("Query Failed!"  . $checkQuery);
  $rows = mysql_num_rows($checkResult);
  $query = "";
  if ($rows > 0){
    if (strlen($response) == 0){
      //delete
      $query = "DELETE from responses where id = $responseID"; //echo $query;
    }else{
      //update
      $query = "Update responses set response ='{$response}' where id = $responseID";
    }
    $result = mysql_query($query) or die("Query failed" . $query);
  }else if(strlen($response) > 0){
    //insert
    $query = "Insert into responses (question_id, response, response_order) values ($questionID, '{$response}', $order)";
    $result = mysql_query($query) or die("Query failed" . $query);
  }
  //echo $query . "<br>";

  //echo "$responseID, $election_id, $response, $questionID, $order<br>";
}

function saveMachineCount($district_id, $machine_count, $electionID){
  $checkQuery = "Select * from election_districts where district_id = $district_id and election_id = $electionID";
  $checkResult = mysql_query($checkQuery) or die("Query Failed!"  . $checkQuery);
  $rows = mysql_num_rows($checkResult);
  if ($rows > 0){
  $query = "Update election_districts set machine_count ='{$machine_count}' where district_id = $district_id and election_id = $electionID";
}else{
  $query = "Insert into election_districts (election_id, district_id, machine_count) values ($electionID, $district_id, $machine_count)";
}
  $result = mysql_query($query) or die("Query failed" . $query);  //echo $query;
}
function saveRegVoters($district_id, $reg_voters, $electionID){
  $checkQuery = "Select * from election_districts where district_id = $district_id and election_id = $electionID";
  $checkResult = mysql_query($checkQuery) or die("Query Failed!"  . $checkQuery);
  $rows = mysql_num_rows($checkResult);
  if ($rows > 0){
  $query = "Update election_districts set reg_voters ='{$reg_voters}' where district_id = $district_id and election_id = $electionID";
}else{
  $query = "Insert into election_districts (election_id, district_id, reg_voters) values ($electionID, $district_id, $reg_voters)";
}
  $result = mysql_query($query) or die("Query failed" . $query);  //echo $query;
}


function getQuestionIDFromQuestion($question, $electionID){
  $query = "Select id from questions where question='{$question}' and election_id = $electionID";
  $result = mysql_query($query) or die("Query failed" . $query);
  $id = mysql_fetch_array($result);
  return $id['id'];
}

function createNewBallotItem($electionID, $itemName, $orderNumber){

}
function editBallotItem($ballotItemID, $itemName, $orderNumber){

}
function createNewChoice($electionID, $itemName, $orderNumber){

}
function editChoice($choiceID, $itemName, $orderNumber){

}
function setPassword($password){
}

function createMachineCountColumn($electionID){
  $text =  "<h4>Number of Machines per District</h4>";
  //get districts
  $query = "Select * from election_districts JOIN districts where election_districts.election_id = $electionID and ";
  $query .= "election_districts.district_id = districts.id";
  $result = mysql_query($query) or die("Machine Query Failed!"  . $query);
  while ($district = mysql_fetch_array($result)){
    $machineCount = $district['machine_count'];
    $name = $district['name'];
    $district_id = $district['district_id'] . "_district";
    //save first
    saveMachineCount($district['district_id'], $machineCount, $electionID);
    $text .= "District $name: <input type='number' name=$district_id value=$machineCount> <br>";
  }
  return $text;
}
function createRegVotersColumn($electionID){
  $text =  "<h4>Registered Voters per District</h4>";
  //get districts
  $query = "Select * from election_districts JOIN districts where election_districts.election_id = $electionID and ";
  $query .= "election_districts.district_id = districts.id";
  $result = mysql_query($query) or die("Query Failed!"  . $query);
  while ($district = mysql_fetch_array($result)){
    $voters = $district['reg_voters'];
    $name = $district['name'];
    $district_id = $district['district_id'] . "_districtVoters";
    //save first
    saveRegVoters($district['district_id'], $voters, $electionID);
    $text .= "District $name: <input type='number' name=$district_id value=$voters> <br>";
  }
  return $text;
}
?>
