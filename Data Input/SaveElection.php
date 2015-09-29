<!--Copyright ©2015 Anouk Stein, MD-->
<?php

global $variables; 
//Database
$variables['username'] = "root"; //insert database username
$variables['password'] = "mattmark123"; //insert database password
$variables['database'] = "elections"; //database name
//Tables
$variables['resultsTableName'] = 'results';
$variables['categoriesTableName'] = 'categories';
$variables['candidatesTableName'] = 'candidates';
//Columns
$variables['votes_results'] = 'votes';
$variables['district_results'] = 'district';
$variables['machine_results'] = 'machine';
$variables['candidateID_results'] = 'candidateID';
$variables['category_id_categories'] = 'category_id';


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
    $v = $variables['votes_results'] = 'votes';
    
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
    $v = $variables['votes_results'] = 'votes';
    
    $query = "SELECT * FROM $table WHERE $d = $district AND $m = $machine_number AND $c_id = $candidateID";
       // echo $query;
    $result = mysql_query($query) or die(" Find saveElectionResults query Failed!".mysql_error());
    //echo $query;
    $votesArray = mysql_fetch_array($result);
    $votes = $votesArray['{$v}'];
    return $votes;
}

//---------------------------------------------------------------------------------
function createOverviewTable(){
    global $variables;
    $table = $variables['categoriesTableName'];
    $query = "SELECT * FROM $table";
    $result = mysql_query($query) or die(" Find createOverviewTable query Failed!".mysql_error());

    $text = "";
    while ($category = mysql_fetch_array($result)){
        $text .= createOverviewTableForCategory($category['category_id']);
        $text .= "<br>";
    }
    return $text;
}

function createOverviewTableForCategory($category_id){
    global $variables;
    $table = $variables['categoriesTableName'];
    $query = "SELECT * FROM $table WHERE {$variables['category_id_categories']} = $category_id";
    $result = mysql_query($query) or die(" Find createOverviewTableForCategory query Failed!".mysql_error());
    $category = mysql_fetch_array($result);
    $text = "<table class='overviewOuter'>";
    $text .= createDistrictMachineHeader($category['category_name']);
    
    //get each candidate
    $candidates = getCandidates($category_id);
    while ($candidate = mysql_fetch_array($candidates)){
        $text .= "<tr><td class='overviewNoWrap'>{$candidate['candidate_name']}</td>";
        

//TODO get machines and districts
//data entered
        $machineArray = getArrayOfMachines();
        foreach ($machineArray as $machineInfo){
            $district = $machineInfo[0];
            $machine = $machineInfo[1];
            $text .= "<td  class='overview'>";
            $text .= getElectionResults($district, $machine, $candidate['candidate_id']);
            $text .= "</td>";
        }
        $text .= "</tr>";
    }
    //get votes for each cell
    $text .= "</table>";
    return $text;
}

function createDistrictMachineHeader($categoryName){
    $header = "<tr><th  class='overview'>{$categoryName}</th>";
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
    
    //TODO get from db
    $number_of_districts = 22;
$maximum_number_machines = 3;
$machines = Array();
for ($d=1; $d<= $number_of_districts; $d++){
    for ($m=1; $m<=$maximum_number_machines; $m++){
        $tmp = [$d,$m];
        $machines[] = $tmp;
    }
}
    return $machines;
}

//-------------------------------------------------------------------------------------------------------------
function getCandidates($category_id)
{
    global $variables;
    $table = $variables['candidatesTableName'];
    
    $query = "SELECT * FROM $table WHERE {$variables['category_id_categories']}  = {$category_id}"; 
    $result = mysql_query($query) or die("Student getCandidates Failed!");
    return $result;
}

//--------------------------------------------------------------------------------------------------------------
 function connect()
{    
    global $variables;

    $connection = mysql_connect("localhost", $variables['username'], $variables['password']) or die("Unable to connect to SQL server");
    mysql_select_db($variables['database']) or die("Unable to select database from connect()"); 
}

//-------------------------------------------------------------------------------------------------------------
function getCategories(){
    global $variables;
    $table = $variables['categoriesTableName'];
    $query = "SELECT * FROM $table";
    $result = mysql_query($query) or die(" getCategories query Failed!" . mysql_error()); 

    return $result;
}

//-------------------------------------------------------------------------------------------------------------
function getJoinQuery(){
    $query = "select candidates.candidate_name, categories.category_name, district, machine, votes from candidates,categories, results where results.candidateID=candidates.candidate_id and candidates.category_id=categories.category_id order by categories.category_name, candidates.candidate_name";
    return $query;
}
//-------------------------------------------------------------------------------------------------------------

function getResultsOutputCsv(){
     connect();
    
    $output = "";
    $query = getJoinQuery();
    $sql = mysql_query($query) or die(" Join query Failed!".mysql_error());
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
    
    mysql_close();
    return $output;
}

//-------------------------------------------------------------------------------------------------------------
function getResultsOutputJsn(){
     connect();
    
    $output = "{";
    $query = getJoinQuery();
    $sql = mysql_query($query) or die(" Join query Failed!".mysql_error()); ;
    $columns_total = mysql_num_fields($sql);

    // Get Records from the table
    while ($row = mysql_fetch_array($sql, MYSQLI_ASSOC)) {
        $output.= "item:" . json_encode($row) . ",";
    }
    $output = rtrim($output, ",");
    $output.= "}";
    echo stripcslashes(json_encode($output));

    mysql_close();

}
//-------------------------------------------------------------------------------------------------------------
function download($type){
    $year = 2015; //TODO getElectionYear
    $output = "";
    
    if ($type == 'csv'){
        $output .= getResultsOutputCsv();
    }elseif($type == 'json'){
        $output .= getResultsOutputJsn();

    $file = fopen("./ptonElections_".  $year. "." . $type,"w");
    if($file){
        fwrite($file, $output);
        fclose($file);
    }
    
    return $output;
}

//-------------------------------------------------------------------------------------------------------------
function dataEntered($district, $machine){
    //TODO check if all fields entered not just one
    global $variables;
    $table = $variables['resultsTableName'];
    $votes = $variables['votes_results'];
    $d = $variables['district_results'];
    $m = $variables['machine_results'];
    
    $query ="Select $votes from $table where $d = {$district} and $m = {$machine} LIMIT 1";
    $result = mysql_query($query) or die("dataEntered failed".mysql_error());
    $rows = mysql_num_rows($result);
    
    if ($rows > 0)
        return true;
    else
        return false;
}

?>