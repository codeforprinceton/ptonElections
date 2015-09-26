<!--Copyright ©2015 Anouk Stein, MD-->

<html>
 <head>
<link rel="stylesheet" type="text/css" href="./elections.css" />
  <title>Election</title>
  <script src="elections.js"></script>
  
 </head>
 <body style="text-align: center; background-color: #F3E2A9">
 <div class="sidebar">
  <br><br><h2>Choose Districts and Machines:</h2>

<?php
include "SaveElection.php";
connect();

$number_of_districts = 22;
$maximum_number_machines = 3;

//TODO get machines and districts
//data entered
for ($d=1; $d<= $number_of_districts; $d++){
    for ($m=1; $m<=$maximum_number_machines; $m++){
       $districtMachineString = "$d.$m";
       echo"<button class='link' onclick='showInfo($districtMachineString)'>";
        if (dataEntered($d,$m) == true){
          echo "<div class='highlight'>";
        }else{
          echo "<div class='lowlight'>";
        }
        echo "District $d, Machine $m</div></button>";
    }
}
?>
 </div>
 <div class="mainBox">
    <br>
     <div id="input">
      <center><h1 class="title">Enter Election Data</h1></center>
      Select a District and Machine to input Election data.
     </div>
     <div id="info">Debug</div>
        
 </div>
<?php mysql_close(); ?>

</form>
 </body>
</html>