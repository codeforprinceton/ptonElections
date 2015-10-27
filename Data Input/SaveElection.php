<?php
//Copyright �2015 Anouk Stein, MD
global $variables;
$variables['pathForCSVandJson'] = "/Users/Guest/tmp";
//Database
$variables['username'] = "root"; //insert database username
$variables['password'] = "mattmark123"; //insert database password
$variables['database'] = "ptonElections"; //database name
//Tables
$variables['resultsTableName'] = 'results';
$variables['categoriesTableName'] = 'questions';
$variables['candidatesTableName'] = 'responses';
//Columns
$variables['votes_results'] = 'tally';
$variables['district_results'] = 'election_district_id';
$variables['machine_results'] = 'machine_number';
$variables['candidateID_results'] = 'response_id';
$variables['category_id_categories'] = 'question_id';



//---------------------------------------------------------------------------------
function saveElectionResults($district, $machine_number, $candidateID, $votes){
    global $variables;
    if (!$votes || !is_numeric($votes)){
        if (!is_numeric($votes) && $votes != ""){
            echo "Invalid data: " . $votes . " is not a number";
        }
        return;
    }
    $votes = strip_tags($votes);

    $table = $variables['resultsTableName'];
    $d = $variables['district_results'];
    $m = $variables['machine_results'];
    $c_id = $variables['candidateID_results'];
    $v = $variables['votes_results'];

    $query = "SELECT * FROM $table WHERE $d = $district AND $m = $machine_number AND $c_id = $candidateID";
       // echo $query;
    $result = mysql_query($query) or die(" Find saveElectionResults query Failed!".mysql_error());
    $rows = mysql_num_rows($result);

    if ($rows == 0){
        $query = "INSERT INTO $table ($d, $m, $c_id, $v) VALUES ($district, $machine_number, $candidateID, $votes)";
    }else{
        $query = "UPDATE $table SET ";
        $query .= "$v = $votes ";
        $query .= "WHERE $d = $district AND $m = $machine_number AND $c_id = $candidateID";
    }
    //echo $query;
    $result = mysql_query($query) or die("Save saveElectionResults query failed".mysql_error());
}
//---------------------------------------------------------------------------------
function getElectionResults($district, $machine_number, $candidateID){
    global $variables;
    $table = $variables['resultsTableName'];

    $d = $variables['district_results'];
    $m = $variables['machine_results'];
    $c_id = $variables['candidateID_results'];
    $v = $variables['votes_results'];

    $query = "SELECT * FROM $table WHERE $d = $district AND $m = $machine_number AND $c_id = $candidateID";
       // echo $query;
    $result = mysql_query($query) or die(" Find saveElectionResults query Failed!".mysql_error());
    //echo $query;
    $votesArray = mysql_fetch_array($result);
    $votes = $votesArray[$v];
    return $votes;
}

//---------------------------------------------------------------------------------
//Spreadsheet
function createOverviewTable(){
    global $variables;
    $table = $variables['categoriesTableName'];
    $query = "SELECT * FROM $table";
    $result = mysql_query($query) or die(" Find createOverviewTable query Failed!".mysql_error());

    $text = "<h1>Unofficial Results</h1>";
    while ($category = mysql_fetch_array($result)){
        $text .= createOverviewTableForCategory($category['id']);
        $text .= "<br>";
    }
    return $text;
}

function createOverviewTableForCategory($category_id){
    global $variables;
    $table = $variables['categoriesTableName'];
    $query = "SELECT * FROM $table WHERE id = $category_id";
    $result = mysql_query($query) or die(" Find createOverviewTableForCategory query Failed!".mysql_error());
    $category = mysql_fetch_array($result);
    $text = "<table class='overviewOuter'>";
    $text .= createDistrictMachineHeader($category['question']);

    //get each candidate
    $candidates = getCandidates($category_id);
    while ($candidate = mysql_fetch_array($candidates)){
        $text .= "<tr><td class='overviewNoWrap'>{$candidate['response']}</td>";


// get machines and districts
//data entered
        $machineArray = getArrayOfMachines();
        foreach ($machineArray as $machineInfo){
            $district = $machineInfo[0];
            $machine = $machineInfo[1];
            $text .= "<td  class='tally'>";
            $text .= getElectionResults($district, $machine, $candidate['id']);
            $text .= "</td>";
        }
        $text .= "</tr>";
    }
    //get votes for each cell
    $text .= "</table>";
    return $text;
}

function createDistrictMachineHeader($categoryName){
    $header = "<tr><th>{$categoryName}</th>"; //class='overview'
    $machineArray = getArrayOfMachines();
    foreach ($machineArray as $machineInfo){
        $district = $machineInfo[0];
        $machine = $machineInfo[1];

        $header .= "<th  class='overview'>";
        $header .= "D{$district}  M{$machine}";
        $header .= "</th>";
    }
    $header .= "</tr>";
    return $header;
}
function getArrayOfMachines(){
    $electionID = getCurrentElectionID();
  return getArrayOfMachinesForElection($electionID);
}
function getAllDistricts($electionID){
  $query = "Select * from election_districts where election_id = $electionID";
  $result = mysql_query($query) or die("Machine Query Failed!"  . $query);
  return $result;
}
function getArrayOfMachinesForElection($electionID){
  $machines = Array();
//get districts
$result = getAllDistricts($electionID);
while ($district = mysql_fetch_array($result)){
  $machineCount = $district['machine_count'];
  // get district name from districts table
  $machinequery = "Select name from districts where id = {$district['district_id']}";
  $machineresult = mysql_query($machinequery) or die("Query Failed!"  . mysql_error());
  $d = mysql_fetch_array($machineresult);
  for ($m=1; $m<= $machineCount; $m++){
    $machines[] = [$d['name'],$m];
  }
}
    return $machines;
}
function getCurrentElectionID(){
  //static $currentElectionID;
//  if ($currentElectionID != NULL){
    // get most recent
    $query = "Select id from elections order by election_date desc";
    $result = mysql_query($query) or die("Current election id Query Failed!"  . $query);
    $id = mysql_fetch_array($result);
    $currentElectionID = $id[0];
    //debug
  //  echo "Election id generated";
//  }

  return $currentElectionID;
}

//-------------------------------------------------------------------------------------------------------------
function getCandidates($category_id)
{
    global $variables;
    $table = $variables['candidatesTableName'];

    $query = "SELECT * FROM $table WHERE {$variables['category_id_categories']}  = {$category_id} Order By response_order";
    //echo $query;
    $result = mysql_query($query) or die(" getCandidates Failed!"  . mysql_error() . $query);
    return $result;
}

//--------------------------------------------------------------------------------------------------------------
 function connect()
{
    global $variables;

    $connection = mysql_connect("localhost:8888", $variables['username'], $variables['password']) or die("Unable to connect to SQL server"  . mysql_error());
    mysql_select_db($variables['database']) or die("Unable to select database from connect()" . mysql_error());
}

//-------------------------------------------------------------------------------------------------------------
//Questions
function getCategories(){
  $electionID = getCurrentElectionID();
    global $variables;
    $table = $variables['categoriesTableName'];
    $query = "SELECT * FROM $table where election_id = $electionID order by question_order";
    $result = mysql_query($query) or die(" getCategories query Failed!" . mysql_error());

    return $result;
}

//-------------------------------------------------------------------------------------------------------------
function getJoinQuery(){
  //only include districts completed
  //get all districts
  $election_id = getCurrentElectionID();
  $result = getAllDistricts($election_id);
  $completedDistricts = "";
  while ($district = mysql_fetch_array($result)){
    if (districtComplete($district['id'])){
      $completedDistricts .= $district['id'] . ","; //echo "Done: " . $completedDistricts;
    }
  }
  $completedDistricts = strstr($completedDistricts, 0, -1);

    $query = "Select responses.response, questions.question, election_district_id, machine_number, tally";
    $query .= " from questions,responses, results where results.response_id=responses.id and responses.question_id=questions.id ";
    if (strlen($completedDistricts) > 0){
      $query .= " IN ({$completedDistricts})";
    }
    $query .= " ORDER BY questions.question, responses.response";

    return $query;
}
//-------------------------------------------------------------------------------------------------------------

function getResultsOutputCsv(){
    $output = "";
    $query = getJoinQuery();
    $sql = mysql_query($query) or die(" Join query Failed!". $query);
    $columns_total = mysql_num_fields($sql);

    // Get The Field Name
    for ($i = 0; $i < $columns_total; $i++) {
        $heading = mysql_field_name($sql, $i);
        $output .= '"'.$heading.'",';
    }
    $output .="\n";

    // Get Records from the table

    while ($row = mysql_fetch_array($sql)) {
        for ($i = 0; $i < $columns_total; $i++) {
            $output .='"'.$row["$i"].'",';
        }
        $output .="\n";
    }
    return $output;
}

//-------------------------------------------------------------------------------------------------------------
function getResultsOutputJsn(){
    $output = "{";
    $query = getJoinQuery();
    $sql = mysql_query($query) or die(" Join query Failed!".mysql_error()); ;
    $columns_total = mysql_num_fields($sql);

    // Get Records from the table
    while ($row = mysql_fetch_array($sql, MYsql_ASSOC)) {
        $output.= "item:" . json_encode($row) . ",";
    }
    $output = rtrim($output, ",");
    $output.= "}";
    echo stripcslashes(json_encode($output));

}
//-------------------------------------------------------------------------------------------------------------
function download($type, $date){
    global $variables;
    $year = $date; //2015; //TODO getElectionYear
    $path = $variables['pathForCSVandJson'];

    $output = "";

    if ($type == 'csv'){
        $output .= getResultsOutputCsv();
    }elseif($type == 'json'){
        $output .= getResultsOutputJsn();
    }

    $file = fopen($path . "/ptonElections_".  $year. "." . $type,"w");
    if($file){
        fwrite($file, $output);
        fclose($file);
    }
    return $output;
}

//-------------------------------------------------------------------------------------------------------------
function dataEntered($district, $machine){
    global $variables;
    $table = $variables['resultsTableName'];
    $votes = $variables['votes_results'];
    $d = $variables['district_results'];
    $m = $variables['machine_results'];

    $query ="Select $votes from $table where $d = {$district} and $m = {$machine}";
    $result = mysql_query($query) or die("dataEntered failed".$query);
    $rows = mysql_num_rows($result);

    $categoryCount = getTotalCandidateCount();

    //echo "Rows = $rows and count = $categoryCount";
    if ($rows >= $categoryCount)
        return true;
    else
        return false;
}
function districtComplete($districtID){
  //get machines for district
  $query = "Select machine_count from election_districts where district_id = $districtID";
  $result = mysql_query($query) or die("Query failed".$query);
  $district = mysql_fetch_array($result);
  $m = $district['machine_count'];
  while ($m > 0){
    if(dataEntered($districtID, $m) == false){
      return false;
    }
    $m--;
  }
  return true;
}
//-------------------------------------------------------------------------------------------------------------

function getTotalCandidateCount(){
    global $variables;
    //getCandidates
    $categoryResult = getCategories();
    $count = 0;
    while ($category = mysql_fetch_array($categoryResult)){
        $id = $category['id'];
        $result = getCandidates($id);
        $count += mysql_numrows($result);
    }
    return $count;
}
//-------------------------------------------------------------------------------------------------------------



//-------------------------------------------------------------------------------------------------------------

?>
