<!--Copyright ©2015 Anouk Stein, MD-->
<?php

global $variables; 

$variables['username'] = "root"; //insert database username
$variables['password'] = "mattmark123"; //insert database password
$variables['database'] = "elections";

//---------------------------------------------------------------------------------
function saveElectionResults($district, $machine_number, $candidateID, $votes){
    if (!$votes || !is_numeric($votes)){
        if (!is_numeric($votes) && $votes != ""){
            echo "Invalid data: " . $votes . " is not a number";
        }
        return;
    }
    $votes = strip_tags($votes);   
    $table = "results";
    $query = "SELECT * FROM $table WHERE district = $district AND machine = $machine_number AND candidateID = $candidateID";
       // echo $query;
    $result = mysql_query($query) or die(" Find saveElectionResults query Failed!".mysql_error());
    $rows = mysql_num_rows($result);
    
    if ($rows == 0){
        $query = "INSERT INTO $table (district, machine, candidateID, votes) VALUES ($district, $machine_number, $candidateID, $votes)";
    }else{
        $query = "UPDATE $table SET ";
        $query .= "votes = $votes ";
        $query .= "WHERE district = $district AND machine = $machine_number AND candidateID = $candidateID";  											
    }
    //echo $query;
    $result = mysql_query($query) or die("Save saveElectionResults query failed".mysql_error());
}
//---------------------------------------------------------------------------------
function getElectionResults($district, $machine_number, $candidateID){
    
    $table = "results";
    $query = "SELECT * FROM $table WHERE district = $district AND machine = $machine_number AND candidateID = $candidateID";
       // echo $query;
    $result = mysql_query($query) or die(" Find saveElectionResults query Failed!".mysql_error());
    //echo $query;
    $votesArray = mysql_fetch_array($result);
    $votes = $votesArray["votes"];
    return $votes;
}

//-------------------------------------------------------------------------------------------------------------
function getCandidates($category_id)
{
    $query = "SELECT * FROM `candidates` WHERE `category_id`  = {$category_id}"; 
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
    $query = "SELECT * FROM categories";   //echo $query;
    $result = mysql_query($query) or die(" getCategories query Failed!"); 

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
    $sql = mysql_query($query) or die(" Join query Failed!");
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
    $sql = mysql_query($query) or die(" Join query Failed!"); ;
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
function download(){
    $output = getResultsOutputCsv();
// Download the file
    $handle = fopen("/Users/Anouk/testdata/election_results.csv", "w");
    if($handle){
        fwrite($handle, $output);
        fclose($handle);
    }
    echo $output;
    exit;

}
//-------------------------------------------------------------------------------------------------------------
function dataEntered($district, $machine){
    $query ="select votes from results where district={$district} and machine={$machine} LIMIT 1";
    $result = mysql_query($query) or die("dataEntered failed".mysql_error());
    $rows = mysql_num_rows($result);
    
    if ($rows > 0)
        return true;
    else
        return false;
}

?>
