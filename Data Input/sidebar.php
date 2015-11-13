<?php
include "SaveElection.php";

session_start();
$election_id = getCurrentElectionID();
 //get all districts
 $query = "Select * from election_districts JOIN districts where election_districts.election_id = $election_id and ";
 $query .= "election_districts.district_id = districts.id";
 $result = runQuery($query);
 while ($district = $result->fetch_assoc()){
   //mysql_fetch_array($result)){
   $machineCount = $district['machine_count'];
   $name = $district['name'];
   $district_id = $district['id'];
   if(districtComplete($district_id) == true){
     echo "<br><div class='highlight'>";
   }else{
     echo "<br><div class='lowlight'>";
   }
   echo "District $name</div>";

 $election_district_id = getElectionDistrictID($district_id);
   for ($m=1; $m<=$machineCount; $m++){
     $districtMachineString = "$district_id.$m";  //echo "$districtMachineString <br>";
     echo"<button class='link' onclick='showInfo($districtMachineString)'>";
      if (dataEntered($election_district_id, $m) == true){
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
