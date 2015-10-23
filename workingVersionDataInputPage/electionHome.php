<!--Copyright ©2015 Anouk Stein, MD-->

<html>
 <head>
  <title>Election</title>
 <link rel="stylesheet" type="text/css" href="./elections.css" />
 <script src="elections.js"></script>
 </head>
 <body style="text-align: center; background-color: #F3E2A9">
  <table><tr><td id="main">
 <div class="sidebar">
  <br>
  <h2>Choose Districts and Machines:</h2>
  
<?php
include "SaveElection.php";
connect();

     $machineArray = getArrayOfMachines();
     foreach ($machineArray as $machineInfo){
            $d = $machineInfo[0];
            $m = $machineInfo[1];
       $districtMachineString = "$d.$m";
       echo"<button class='link' onclick='showInfo($districtMachineString)'>";
        if (dataEntered($d,$m) == true){
          echo "<div class='highlight'>";
        }else{
          echo "<div class='lowlight'>";
        }
        echo "District $d, Machine $m</div></button>";
    }

?>
  <hr>
  <button class='link' onclick='showSpreadsheet()'>Spreadsheet Overview</button>
  <button class='link' onclick='saveAllForMap()'>Save All</button>
  <form action = './saveInputs.php' name='choices' method ='post'>
 <input type=submit class='input' name='output' value=' Download CSV '><br>
 <input type=submit class='input' name='output' value=' Download Json '>
 </form>
 <hr>
  
 </div>
 
 </td><td id="main">
 
 <div class="mainBox">
    <br>
     <div id="input">
      <center>
       <!--
       <img src="http://www.princetonnj.gov/images/masthead-bw-seal-702.jpg">-->
      <br>
      <h1 class="title">Enter Election Data</h1>
      Select a District and Machine to input Election data.
      <br>
       <br>
        <br>
<img src="https://images.duckduckgo.com/iu/?u=http%3A%2F%2Fgreenbaywi.gov%2Fwp-content%2Fuploads%2F2013%2F05%2Felection-vote.jpg&f=1">
     </center>
     </div>
     
     <div id="info"></div>
        
 </div>
 
 <!--<form action = './saveInputs.php' name='choices' method ='post'>
 <br><br><table class='buttons'><tr>
 <td><input type=submit name='output' value=' Download CSV '></td>
 <td><input type=submit name='output' value=' Download Json '></td>
 </tr></table>
 </form>-->
 
  </td>
  </tr></table>
<?php mysql_close(); ?>


 </body>
</html>