<?php
include "SaveElection.php";



function getItemFromTable($table, $item, $id){

  $query = "Select * FROM $table WHERE $item = $id";
  //$result = mysql_query($query) or die("Query Failed!"  . $query);
  $result = runQuery($query);
  return $result;
}
//create/edit elections
function getAllElections(){

  $query = "Select * FROM elections order by election_date desc";
  //$result = mysql_query($query) or die("Current election id Query Failed!"  . $query);
  $result = runQuery($query);
  return $result;
}
function createNewElection($date, $location, $name){

  $query = "INSERT INTO elections (election_date,location, name) VALUES ('{$date}', '{$location}', '{$name}')";
  //$result = mysql_query($query) or die("Query Failed!".mysql_error());
  $result = runQuery($query);
  global $conn;
  return $conn->insert_id; //mysql_insert_id();
}
function editElection($date, $electionID, $location){

  $query = "UPDATE elections SET location = '{$location}', election_date = $date WHERE $id = $electionID";
  //$result = mysql_query($query) or die("Query Failed!".mysql_error());
  $result = runQuery($query);
}

function saveQuestion($ballotItemID, $election_id, $ballotItem, $order){
  global $conn;
  //check
  $checkQuery = "Select * FROM questions WHERE id=$ballotItemID";
  //$checkResult = mysql_query($checkQuery) or die("Query Failed!"  . $checkQuery);
  $checkResult = $conn->query($checkQuery);
  if($checkResult === false) {
    trigger_error('Wrong SQL: ' . $checkQuery . ' Error: ' . $conn->error, E_USER_ERROR);
  }
  $rows = $checkResult->num_rows; //mysql_num_rows($checkResult);
  $query = "";
  if ($rows > 0){
    if (strlen($ballotItem) == 0){
      //delete
      $query = "DELETE FROM questions WHERE id = $ballotItemID";
    }else{
      //$query = "INSERT INTO questions (election_id, question, question_order) VALUES ($election_id, '{$ballotItem}', $order)";
      $query = "UPDATE questions SET question ='{$ballotItem}' WHERE id = $ballotItemID";
    }
  //   $result = mysql_query($query) or die("Query failed" .$query);
  }else if(strlen($ballotItem) > 0){
    //insert
    $query = "Insert into questions (election_id, question, question_order) VALUES ($election_id, '{$ballotItem}', $order)";
  }
  $result = runQuery($query);

}

function saveCandidate($responseID, $election_id, $response, $questionID, $order){

  //check
  // $checkQuery = "Select * FROM responses WHERE id=$responseID"; //echo $checkQuery;
  // $checkResult = mysql_query($checkQuery) or die("Query Failed!"  . $checkQuery);
  // $rows = mysql_num_rows($checkResult);
  // $query = "";
  // if ($rows > 0){
    if (strlen($response) == 0){
      //delete
      $query = "DELETE FROM responses WHERE id = $responseID"; //echo $query;
    }else{
      $query = "INSERT INTO responses (question_id, response, response_order) VALUES ($questionID, '{$response}', $order)";
      if ($responseID >= 0){
      $query .= " ON DUPLICATE KEY UPDATE responses SET response ='{$response}' WHERE id = $responseID";
      }
    }
    $result = runQuery($query);

  //   $result = mysql_query($query) or die("Query failed" . $query);
  // }else if(strlen($response) > 0){
  //   //insert
  //   $query = "Insert into responses (question_id, response, response_order) VALUES ($questionID, '{$response}', $order)";
  //   //$result = mysql_query($query) or die("Query failed" . $query);
  //
  // }
  //echo $query . "<br>";

  //echo "$responseID, $election_id, $response, $questionID, $order<br>";
}

function saveMachineCount($district_id, $machine_count, $electionID){

  $checkQuery = "Select * FROM election_districts WHERE district_id = $district_id AND election_id = $electionID";
  $checkResult = runQuery($checkQuery);
  $rows = $checkResult->num_rows;
  if ($rows > 0){
  //$query = "INSERT INTO  election_districts (election_id, district_id, machine_count) VALUES ($electionID, $district_id, $machine_count)";
  $query = "UPDATE election_districts SET machine_count ='{$machine_count}' WHERE district_id = $district_id  AND  election_id = $electionID";
}else{
  $query = "Insert into election_districts (election_id, district_id, machine_count) VALUES ($electionID, $district_id, $machine_count)";
}
  $result = runQuery($query);
}

function saveRegVoters($district_id, $reg_voters, $electionID){
  $checkQuery = "Select * FROM election_districts WHERE district_id = $district_id AND election_id = $electionID";
  $checkResult = runQuery($checkQuery);
  $rows = $checkResult->num_rows;
  if ($rows > 0){
  //$query = "INSERT INTO  election_districts (election_id, district_id, reg_voters) VALUES ($electionID, $district_id, $reg_voters)";
  $query = "UPDATE election_districts SET reg_voters ='{$reg_voters}' WHERE district_id = $district_id AND election_id = $electionID";
  }else{
    $query = "Insert into election_districts (election_id, district_id, reg_voters) VALUES ($electionID, $district_id, $reg_voters)";
  }
  $result = runQuery($query);
}


function getQuestionIDFROMQuestion($question, $electionID){
  global $conn;
  $query = "SELECT id FROM questions WHERE question='{$question}' AND election_id = $electionID";
  //$result = mysql_query($query) or die("Query failed" . $query);
  $result = runQuery($query);
  $id = $result->fetch_assoc(); //mysql_fetch_array($result);
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
  $query = "Select * FROM election_districts JOIN districts WHERE election_districts.election_id = $electionID AND ";
  $query .= "election_districts.district_id = districts.id";
  $result = runQuery($query); //echo $query;

  while ($district = $result->fetch_assoc()){
  //mysql_fetch_array($result)){
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
  $query = "SELECT * FROM election_districts JOIN districts WHERE election_districts.election_id = $electionID and ";
  $query .= "election_districts.district_id = districts.id";
  $result = runQuery($query);

  while ($district = $result->fetch_assoc()){
    //mysql_fetch_array($result)){
    $voters = $district['reg_voters'];
    $name = $district['name'];
    $district_id = $district['district_id'] . "_districtVoters";
    //save first
    saveRegVoters($district['district_id'], $voters, $electionID);
    $text .= "District $name: <input type='number' name=$district_id value=$voters> <br>";
  }
  return $text;
}

function getUsername(){

  return $_SESSION['username'];
}
?>
