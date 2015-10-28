<?php
include "SaveElection.php";
connect();
session_start();
$election_id = getCurrentElectionID();
 //get all districts
 $query = "Select * from election_districts JOIN districts where election_districts.election_id = $election_id and ";
 $query .= "election_districts.district_id = districts.id";
 $result = mysql_query($query) or die("Query Failed!"  . $query);
 while ($district = mysql_fetch_array($result)){
   $machineCount = $district['machine_count'];
   $name = $district['name'];
   $district_id = $district['district_id'];
   if(districtComplete($district_id)){
     echo "<br><div class='highlight'>";
   }else{
     echo "<br><div class='lowlight'>";
   }
   echo "District $name</div>";

   for ($m=1; $m<=$machineCount; $m++){
     $districtMachineString = "$district_id.$m";  //echo "$districtMachineString <br>";
     echo"<button class='link' onclick='showInfo($districtMachineString)'>";
      if (dataEntered($district_id,$m) == true){
        echo "<div class='highlight'>";
      }else{
        echo "<div class='lowlight'>";
      }
      if(is_numeric($name)){
        echo " Machine $m";
      }else{
        echo " $name";
      }
      echo " </div></button><br>";
   }
}
//save for map
$date = date("m_d_Y");
download('csv', $date);
?>
