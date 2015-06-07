<!--Copyright ©2015 Anouk Stein, MD-->
 <?php
//Save to csv
include "SaveElection.php";


if ($_POST['output'] == "csv"){
    $filename = "electionResults.csv";
    header('Content-type: application/csv');
    header('Content-Disposition: attachment; filename='.$filename);
    download();
}
 if  ($_POST['output'] == "json"){
    $filename = "electionResults.json";
    header('Content-type: application/json');
    header('Content-Disposition: attachment; filename='.$filename);
    getResultsOutputJsn();
}

?>

