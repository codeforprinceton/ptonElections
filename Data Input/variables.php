<?php
global $variables;
$variables['pathForCSVandJson'] = "";//"/Users/Guest/tmp/";
//Database
$variables['username'] = "root"; //insert database username
$variables['password'] = "mattmark123"; //insert database password
$variables['database'] = "ptonElections"; //database name
//$variables['server'] = "localhost"; //server name
$variables['port'] = "localhost"; //port   (was 8888) or 3306
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
?>
