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
function createNewElection($date, $location){
  $query = "INSERT INTO elections (election_date,location) VALUES ('{$date}', '{$location}')";
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


?>
