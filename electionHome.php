<!--Copyright ©2015 Anouk Stein, MD-->
<!--
//TODO
//Load saved results
//All one page
// machines for district
-->
<html>
 <head>
<link rel="stylesheet" type="text/css" href="./elections.css" />
  <title>Election</title>
 </head>
 <body style="text-align: center; background-color: #F3E2A9">
    <br><br>
    <h1>Choose Election District and Machine</h1>
 <?php
include "SaveElection.php";
connect();


echo "Select the district: ";
echo "<form action = './electionSpecificInput.php' name='choices' method ='post'> ";
echo "<select name='district'>";

$number_of_districts = 22;
for ($i=1; $i<=$number_of_districts; $i++){
echo '<OPTION VALUE="' . $i . '">' . $i ;
}

echo "</select><br><br>Select the machine: ";
echo "<select name='machine'>";


$maximum_number_machines = 3;
for ($i=1; $i<=$maximum_number_machines; $i++){
echo '<OPTION VALUE="' . $i . '">' . $i ;
}
?>
</select><br><p><input type=submit></p>
<br><br><h2>Districts entered:</h2>

<?php
//data entered
for ($d=1; $d<= $number_of_districts; $d++){
    for ($m=1; $m<=$maximum_number_machines; $m++){
        if (dataEntered($d,$m) == true){
            echo"<div class='highlight'><b>District $d, Machine $m</b> </div> ";
        }else{
          echo"<div class='lowlight'>District $d, Machine $m </div> ";
        }
    }
}
mysql_close();
?>

</form>
 </body>
</html>