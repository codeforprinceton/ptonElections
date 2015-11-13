<?php
//Copyright 2015 Anouk Stein, MD
include "variables.php";

global $variables;
$conn = new mysqli($variables['port'], $variables['username'], $variables['password'], $variables['database']);
if ($conn->connect_error){
  trigger_error('Database connection failed: '  . $conn->connect_error, E_USER_ERROR);
}


function runQuery($query){
  global $conn;
  $result = $conn->query($query);
  if($result === false) {
    trigger_error('Wrong SQL: ' . $query . ' Error: ' . $conn->error, E_USER_ERROR);
  }
  return $result;
}

//---------------------------------------------------------------------------------
function saveElectionResults($district, $machine_number, $candidateID, $votes){
    global $variables;  //echo "Votes are $votes";
    global $conn;
    if ($votes == "" || !is_numeric($votes)){
        if (!is_numeric($votes) && $votes != ""){
            echo "Invalid data: " . $votes . " is not a number";
        }
        return;
    }
    $votes = strip_tags($votes);

    $table = $variables['resultsTableName'];
    $d = $variables['district_results']; //election_district_id
    $m = $variables['machine_results'];
    $c_id = $variables['candidateID_results'];
    $v = $variables['votes_results'];

    //get id FROM district
    $election_district_id = getElectionDistrictID($district);

    // $query = "SELECT * FROM $table WHERE $d = $election_district_id AND $m = $machine_number AND $c_id = $candidateID";
    //     //echo $query;
    // $result = $conn->$query; //mysql_query($query) or die(" Find saveElectionResults query Failed!".$query);
    // $rows = $result->num_rows; //mysql_num_rows($result);
//ON DUPLICATE KEY
  //  if ($rows == 0){
        $query = "INSERT INTO $table ($d, $m, $c_id, $v) VALUES ($election_district_id, $machine_number, $candidateID, $votes)";
    //}else{
        $query .= " ON DUPLICATE KEY UPDATE $table SET ";
        $query .= "$v = $votes ";
        $query .= "WHERE $d = $election_district_id AND $m = $machine_number AND $c_id = $candidateID";
  //  }
    $result = runQuery($query);
  }

function getElectionDistrictID($district){
  global $conn;
  //TODO fix
  if($district > 24){
    return $district;
  }
  $electionID = getCurrentElectionID();
  $query = "SELECT id FROM election_districts WHERE district_id = $district AND election_id = $electionID";
  $result = runQuery($query);
$district = $result->fetch_assoc(); //mysql_fetch_array($result);
  return $district['id'];
}
//---------------------------------------------------------------------------------
function getElectionResults($district, $machine_number, $candidateID){
    global $variables;
    global $conn;
    $v = $variables['votes_results'];

    //get id FROM district
    $election_district_id = getElectionDistrictID($district);

    $query = "SELECT * FROM results WHERE election_district_id = $election_district_id AND machine_number = $machine_number AND response_id = $candidateID";
    //$result = mysql_query($query) or die(" getElectionResults query Failed!".$query);
    //echo $query;
    $result = runQuery($query);
    $votesArray = $result->fetch_assoc();//mysql_fetch_array($result);
    $votes = $votesArray[$v];
    return $votes;
}

//---------------------------------------------------------------------------------
//Spreadsheet
function createOverviewTable(){
    global $variables;

    $election_id = getCurrentElectionID();
    $table = $variables['categoriesTableName'];
    $query = "SELECT * FROM $table WHERE election_id = $election_id";
    //$result = mysql_query($query) or die(" Find createOverviewTable query Failed!".mysql_error());
    $result = runQuery($query);

    $election = getCurrentElectionInfo();
    $date = new DateTime($election['election_date']);
    $d = date_format($date, "M d, Y");
    $text = "<h1>Unofficial Results -  {$election['name']} {$d} {$election['location']}</h1>";
    while ($category = $result->fetch_assoc()){
      //mysql_fetch_array($result)){
        $text .= createOverviewTableForCategory($category['id']);
        $text .= "<br>";
    }
    return $text;
}

function createOverviewTableForCategory($category_id){
    global $variables;
    global $conn;
    $table = $variables['categoriesTableName'];
    $query = "SELECT * FROM $table WHERE id = $category_id";
    $result = runQuery($query);

    $category = $result->fetch_assoc(); //mysql_fetch_array($result);

    $text = "<table class='overviewOuter' id='table1'>";
    $text .= createDistrictMachineHeader($category['question']);

    //election id
    $election_id = getCurrentElectionID();
    //get each candidate
    $candidates = getCandidates($category_id);
    while ($candidate = $candidates->fetch_assoc()){
    //mysql_fetch_array($candidates)){
        $text .= "<tr><td class='overviewNoWrap'>{$candidate['response']}</td>";
        // get machines and districts
        $machineArray = getArrayOfMachinesForElection($election_id);
        foreach ($machineArray as $machineInfo){
            $district = $machineInfo[0]; //echo "District $district <br>";
            $machine = $machineInfo[1];
            $text .= "<td  class='tally'>";
            $text .= getElectionResults($district, $machine, $candidate['id']);
            $text .= "</td>";
        }
        $text .= "<td class='tally'>" . getTotalTally($category_id, $candidate['response'], $election_id) . "</td>";
        $text .= "</tr>";
    }
    /*
    if(is_numeric(stripos($category['question'], "Machine"))){
       $text .= createDistrictVotesRow($category_id);
       $text .= createRegisteredVotersRow($category_id);
       $text .= createPercentageRow($category_id);
    }
    */
    $text .= createMachineSumRow($category_id);
    $text .= createDistrictVotesRow($category_id);
    $text .= createRegisteredVotersRow($category_id);
    $text .= createPercentageRow($category_id);
    $text .= "</table>";
    return $text;
}

function createDistrictMachineHeader($categoryName){
    $header = "<tr><th>{$categoryName}</th>"; //class='overview'
    $machineArray = getArrayOfMachines();
    foreach ($machineArray as $machineInfo){
        $district = $machineInfo[0];
        $machine = $machineInfo[1];
        $name = getDistrictName($district);
        $header .= "<th  class='overview'>";
        if(is_numeric($name)){
          $header .= "D{$district}  M{$machine}";
        }else{
          $header .= $name;
        }
        $header .= "</th>";
    }
    $header .= "<th class='overview'>Total</th></tr>";
    return $header;
}

function createMachineSumRow($category_id){
    $row = "<tr><td class='overviewNoWrapRight'>(Machine Totals)</td>";
    $machineArray = getArrayOfMachines();
    $total = 0;
    foreach ($machineArray as $machineInfo){
        $district = $machineInfo[0];
        $machine = $machineInfo[1];
        $name = getDistrictName($district);
        $machine_total = getMachineTotal($category_id, $district, $machine);
        $total += $machine_total;

        $row .= "<td  class='overview'>";
        $row .= $machine_total;
        $row .= "</td>";
    }
    $row .= "<td class='overview'>$total</td></tr>";
    return $row;
}
function createRegisteredVotersRow($category_id){

    $row = "<tr><td class='overviewNoWrapRight'>(Registered Voters)</td>";
    $election_id = getCurrentElectionID();
    $result = getAllDistricts($election_id);
    $total = 0;
    while($district = $result->fetch_assoc()){
      //mysql_fetch_array($result)){
      $reg_voters = getRegisteredVoters($district['id']);
      $total += $reg_voters;
      $machineCount = $district['machine_count'];
      $row .= "<td  class='overview' colspan = {$machineCount}>";
      $row .= $reg_voters;
      $row .= "</td>";
    }
    $row .= "<td class='overview'>$total</td></tr>";
    return $row;
}
function getRegisteredVoters($election_district_id){


  $query = "SELECT reg_voters FROM election_districts WHERE id = $election_district_id";
  $result = runQuery($query);
  $info = mysql_fetch_array($result);
  return $info['reg_voters'];
}
function createDistrictVotesRow($category_id){
    $row = "<tr><td class='overviewNoWrapRight'>(District Total Voted)</td>";
    $election_id = getCurrentElectionID();
    $result = getAllDistricts($election_id);
    while($district = $result->fetch_assoc()){
    //mysql_fetch_array($result)){
      $machineCount = $district['machine_count'];
      $districtVotes = 0;
      for($m=1; $m<= $machineCount;$m++){
        $districtVotes += getMachineTotal($category_id, $district['id'], $m);
      }

      $row .= "<td  class='overview' colspan = {$machineCount}>";
      $row .= $districtVotes;
      $row .= "</td>";
    }
    $row .= "<td class='overview'></td></tr>";
    return $row;
}
function createPercentageRow($category_id){
    $row = "<tr><td class='overviewNoWrapRight'>(Percent)</td>";
    $election_id = getCurrentElectionID();
    $result = getAllDistricts($election_id);
    while($district = $result->fetch_assoc()){
    //mysql_fetch_array($result)){
      $machineCount = $district['machine_count'];
      $districtVotes = 0;
      for($m=1; $m<= $machineCount;$m++){
        $districtVotes += getMachineTotal($category_id, $district['id'], $m);
      }

      $reg_voters = getRegisteredVoters($district['id']);

      $percent = 0;
      if($reg_voters > 0){
        $percent = round(100 * $districtVotes/$reg_voters, 1);
      }

      $row .= "<td  class='overview' colspan = {$machineCount}>";
      $row .= $percent;
      $row .= "</td>";
    }
    $row .= "<td class='overview'></td></tr>";
    return $row;
}

//-------------------------------------------------------------------------------------------------------------

function getAllDistricts($electionID){

  $query = "SELECT * FROM election_districts WHERE election_id = $electionID";
  $result = runQuery($query);
  return $result;
}

function getDistrictName($election_district_id){

  $query = "SELECT name FROM districts JOIN election_districts WHERE election_districts.id = $election_district_id";
    $query .= " AND districts.id = election_districts.district_id";
  $result = runQuery($query);
  $info = $result->fetch_assoc();
  //mysql_fetch_array($result);
  return $info['name'];
}

function getArrayOfMachines(){
    $electionID = getCurrentElectionID();
  return getArrayOfMachinesForElection($electionID);
}
function getArrayOfMachinesForElection($electionID){
  $machines = Array();
//get districts
$result = getAllDistricts($electionID);
while ($district = mysql_fetch_array($result)){
  $machineCount = $district['machine_count'];
  for ($m=1; $m<= $machineCount; $m++){
    $machines[] = [$district['id'],$m]; //election_district_id
  }
}
    return $machines;
}
//-------------------------------------------------------------------------------------------------------------
function getCurrentElectionID(){
    // get election chosen

  $id = $_SESSION['election_id'];
  return $id;
}
function getActiveElectionID(){

  $query = "SELECT id FROM elections WHERE active='true'";
  $result = runQuery($query);
  $election = $result->fetch_assoc();//mysql_fetch_array($result);
  return $election['id'];
}

function setActiveElection($election_id, $isActive){
  $query = "UPDATE elections SET active = $isActive WHERE id = $election_id";
  $result = runQuery($query);
}

function getCurrentElectionInfo(){
  $id = getCurrentElectionID();
  $query = "SELECT * FROM elections WHERE id=$id";
  $result = runQuery($query);
  $election = $result->fetch_assoc();//mysql_fetch_array($result);
  return $election;
}

//-------------------------------------------------------------------------------------------------------------
function getCandidates($category_id)
{
    global $variables;
    $table = $variables['candidatesTableName'];

    $query = "SELECT * FROM $table WHERE {$variables['category_id_categories']}  = {$category_id} ORDER BY response_order";
    $result = runQuery($query);
    return $result;
}
//--------------------------------------------------------------------------------------------------------------

function getTotalTally($category_id, $candidate_name, $election_id){
  $query = "SELECT SUM(results.tally) FROM results JOIN responses WHERE responses.question_id = $category_id ";
  $query .= " AND responses.response = '{$candidate_name}' AND results.response_id = responses.id";
  $result = runQuery($query);
  $info = $result->fetch_assoc();//mysql_fetch_array($result);
  return $info['SUM(results.tally)'];
}

function getMachineTotal($category_id, $d, $m){

  //get all response id for question
  $query = "SELECT id FROM responses WHERE question_id = $category_id";
  $result = runQuery($query);

  $response_id_string = "";
   while ($info = $result->fetch_assoc()){
   //mysql_fetch_array($result)){
     $response_id_string .= $info['id'] . ",";
   }
   $response_id_string = substr($response_id_string,0,-1);

  $query = "SELECT SUM(results.tally) FROM results WHERE election_district_id = $d ";
  $query .= " AND machine_number = $m AND results.response_id IN ({$response_id_string})";
  $result = runQuery($query);
  $info = $result->fetch_assoc();//mysql_fetch_array($result);
  return $info['SUM(results.tally)'];
}
//--------------------------------------------------------------------------------------------------------------
 function connect()
{
    global $variables;
    $conn = new mysqli($variables['port'], $variables['username'], $variables['password'], $variables['database']);
    if ($conn->connect_error){
      trigger_error('Database connection failed: '  . $conn->connect_error, E_USER_ERROR);
    }
    return $conn;
  //  $connection = mysql_connect("{$variables['port']}", "{$variables['username']}", "{$variables['password']}") or die("Unable to connect to SQL server"  . mysql_error());
    //mysql_SELECT_db($variables['database']) or die("Unable to SELECT database FROM connect()" . mysql_error());
}

//-------------------------------------------------------------------------------------------------------------
//Questions
function getCategories($electionID){
//  $electionID = getCurrentElectionID();
    global $variables;
    $table = $variables['categoriesTableName'];
    $query = "SELECT * FROM $table WHERE election_id = $electionID ORDER BY question_order";
    $result = runQuery($query);
    return $result;
}

//-------------------------------------------------------------------------------------------------------------
function getJoinQuery(){
  //only include districts completed
  //get all districts
  $election_id = getCurrentElectionID();
  $result = getAllDistricts($election_id);
  $completedDistricts = "";
  while ($district = $result->fetch_assoc()){
  //mysql_fetch_array($result)){ *
    if (districtComplete($district['id']) == true){
      $completedDistricts .= $district['id'] . ",";
    }
  }
  $completedDistricts = substr($completedDistricts, 0, -1);

if (strlen($completedDistricts) > 0){
    $query = "SELECT responses.response, questions.question, district_id, machine_number, tally, election_districts.reg_voters";
    $query .= " FROM questions,responses, results, election_districts WHERE results.response_id=responses.id AND responses.question_id=questions.id ";
    $query .= " AND election_districts.id = results.election_district_id AND election_districts.election_id = $election_id";
    if (strlen($completedDistricts) > 0){
      $query .= " IN ({$completedDistricts})";
    }
    $query .= " ORDER BY questions.question, responses.response";
    return $query;
  }else {
    return "";
  }
}
function getJoinQueryPrior(){
  //TODO
  //only include districts completed
  //get all districts
  $election_id = getPriorElectionID();
  $result = getAllDistricts($election_id);
    $query = "SELECT responses.response, questions.question, district_id, machine_number, tally, election_districts.reg_voters";
    $query .= " FROM questions,responses, results, election_districts WHERE results.response_id=responses.id AND responses.question_id=questions.id ";
    $query .= "AND election_districts.id = results.election_district_id AND election_districts.election_id = $election_id";
    $query .= " ORDER BY questions.question, responses.response";

    return $query;
}
function getPriorElectionID(){
  //TODO
  return 2;
}
//-------------------------------------------------------------------------------------------------------------

function getResultsOutputCsv(){
  return getResultsOutputWithDelimiter(",");
}

function getResultsOutputTsv(){
  return getResultsOutputWithDelimiter("\t");
}
function getResultsOutputWithDelimiterWithJoinQuery($delimiter, $joinQuery, $d, $needsHeader){

    $output = "";
    $query = $joinQuery;
    if(strlen($joinQuery) == 0){
      return "";
    }
    $result = runQuery($query);
    $columns_total = $result->num_fields();//mysql_num_fields($result);

    //Election date
    $election = getCurrentElectionInfo();
    // $date = new DateTime($election['election_date']);
    // $d = date_format($date, "M d, Y");
    //headers
    if ($needsHeader == "true"){
      $output .= "Election Date" . $delimiter;

      // Get The Field Name
      for ($i = 0; $i < $columns_total; $i++) {
          $heading = mysqli_fetch_field_direct($result, $i);
          $output .= $heading . $delimiter;
      }
      $output = trim($output);
      $output .="\n";
    }

    // Get Records FROM the table
    while ($row = $result->fetch_assoc()){
    //mysql_fetch_array($result)) {
        $output .= $d  . $delimiter;
        for ($i = 0; $i < $columns_total; $i++) {
            $output .= $row[$i] . $delimiter;
        }
        $output = trim($output);
        $output .="\n";
    }
    return $output;
}

function getResultsOutputWithDelimiter($delimiter){
  //Prior
  $joinQueryPrior = getJoinQueryPrior();
  //TODO
  $dPrior = "Nov 04, 2014";
  // $date = new DateTime($election['election_date']);
  // $d = date_format($date, "M d, Y");

  $output = getResultsOutputWithDelimiterWithJoinQuery($delimiter, $joinQueryPrior,$dPrior, "true");

//Current
  $joinQuery = getJoinQuery();
  $election = getCurrentElectionInfo();
  $date = new DateTime($election['election_date']);
  $d = date_format($date, "M d, Y");
  $output .= getResultsOutputWithDelimiterWithJoinQuery($delimiter, $joinQuery, $d, "false");
    return $output;
}

//-------------------------------------------------------------------------------------------------------------
function getResultsOutputJsn(){

    $output = "{";
    $query = getJoinQuery();
    $result = runQuery($query);

    // Get Records FROM the table
    while ($row = $result->fetch_assoc()){
    //mysql_fetch_array($result, MYSQL_ASSOC)) {
        $output.= "item:" . json_encode($row) . ",\n";
    }
    $output = rtrim($output, ",");
    $output.= "}";
    echo stripcslashes(json_encode($output));

}
//-------------------------------------------------------------------------------------------------------------
function download($type, $date){
    global $variables;
    $year = $date;
    $path = $variables['pathForCSVandJson'];

    $output = "";
    if ($type == 'csv'){
        //$output .= getResultsOutputCsv();
        $output .= getResultsOutputWithDelimiter("\t");
    }elseif($type == 'tsv'){
        $output .= getResultsOutputTsv();
    }elseif($type == 'json'){
        $output .= getResultsOutputJsn();
    }elseif($type == 'xls'){
        $output .= getResultsOutputXls();
    }


    $file = @fopen($path . "electionResults" . "." . $type, "w");
    if($file){
        fwrite($file, $output);
        fclose($file);
    }
    return $output;
}

//-------------------------------------------------------------------------------------------------------------
function dataEntered($district, $machine){

    $query ="SELECT tally FROM results WHERE election_district_id = {$district} AND machine_number = {$machine}";
    $result = runQuery($query);
    $rows = mysql_num_rows($result);
          //echo $query;
    $categoryCount = getTotalCandidateCount();

    //echo "Rows = $rows and count = $categoryCount";
    if ($rows >= $categoryCount){
        return true;
      }
    else{ //echo "false";
        return false;
      }
}
function districtComplete($districtID){

  //get machines for district
  //$election_id = getCurrentElectionID();
  $election_district_id = getElectionDistrictID($districtID);
  $query = "SELECT machine_count FROM election_districts WHERE id = $election_district_id"; //echo $query;
  $result = runQuery($query);

  $district = $result->fetch_assoc();
    //mysql_fetch_array($result);
  $m = $district['machine_count'];
  while ($m > 0){
    $isDone = dataEntered($election_district_id, $m);
    if($isDone == false){
      return false;
    }
    $m--;
  }
  return true;
}
//-------------------------------------------------------------------------------------------------------------

function getTotalCandidateCount(){
    //getCandidates
    $election_id = getCurrentElectionID();
    $categoryResult = getCategories($election_id);
    $count = 0;
    while ($category = $categoryResult->fetch_assoc()){
    //mysql_fetch_array($categoryResult)){
        $id = $category['id'];
        $result = getCandidates($id);
        $count += mysql_numrows($result);
    }
    return $count;
}
//-------------------------------------------------------------------------------------------------------------
function saveSpreadSheetToPDF(){

$data = createOverviewTable();
$myfile = fopen("Spreadsheet.html", "w") or die("Unable to open file!");

fwrite($myfile, $data);
fclose($myfile);

}
//-------------------------------------------------------------------------------------------------------------

?>
