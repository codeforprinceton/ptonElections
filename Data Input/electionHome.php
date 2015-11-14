<!--Copyright 2015 Anouk Stein, MD-->

<html>
 <head>
  <title>Election</title>
 <link rel="stylesheet" type="text/css" href="./elections.css" />
 <script src="elections.js"></script>
 <script src="http://code.jquery.com/jquery-latest.js"></script>

  <div id='debug'></div>

 <?php
 $signedIn = false;
 include "SaveElection.php";

 session_start();
 if (isset($_POST['signIn'])){
   if ($_POST['signIn'] == "yes"){
     $signedIn = true;
   }
 }
  if (isset($_POST['username']) && isset($_POST['password'])){
      $signedIn = checkUser($_POST['username'], $_POST['password']);
  }
  if ($signedIn){
    $_SESSION['election_id'] = $_POST['election'];

    //set editable status of election
    $result = getCurrentElectionInfo();
    $_SESSION['is_active'] = $result['is_active'];


    echo "<script>
       $(document).ready(function() {
         $('#districts').load('sidebar.php');
         var auto_refresh = setInterval(
             function ()
             {
                 $('#districts').load('sidebar.php').fadeIn('slow');
             }, 5000); // refresh every 5000 milliseconds
         $.ajaxSetup({ cache: true });
   });
   </script>";
 }else{
   echo "<br><br><br><h2>Invalid username or password. Hit back button on browser to try again.</h2>";
 }

 ?>
 </head>
 <body style="text-align: center; background-color: #F3E2A9">
  <table><tr><td id="main">
 <div class="sidebar">

 <?php
 //   <button class='link' onclick='saveAllForMap()'>Save For Map</button>

 if($signedIn){
   echo "  <h2>Choose Districts and Machines:</h2>
     <div id='districts'></div>
     <hr>
     <button class='link' onclick='showSpreadsheet()'>Spreadsheet Overview</button>

     <form action = './saveInputs.php' name='choices' method ='post'>
        <input type=submit class='input' name='output' value=' Download CSV '><br>
        <input type=submit class='input' name='output' value=' Download Json '><br>
        <input type=submit class='input' name='output' value=' Get Spreadsheet '><br>
     </form>";
     if (!(stripos($_SESSION['privilege'], 'admin') === false)){
     echo "
     <form action = 'administrativeGUI.php'method ='post'>
        <input type=submit class='input' value=' Edit/Create Election Template '>
     </form>";
   }
   echo "
     <form action = 'start.php'method ='post'>
        <input type=submit class='signout' value=' Sign Out '>
     </form>
    <hr>
    </div></td>
    <td id='main'>
     <div class='mainBox'>
        <br>
         <div id='input'>
          <center><!--<img src='http://www.princetonnj.gov/images/masthead-bw-seal-702.jpg'>-->
          <br><h1 class='title'>Enter Election Data</h1>Select a District and Machine to input Election data.
          <br><br><br>
    <img src='https://images.duckduckgo.com/iu/?u=http%3A%2F%2Fgreenbaywi.gov%2Fwp-content%2Fuploads%2F2013%2F05%2Felection-vote.jpg&f=1'>
         </center>
         </div>
         <div id='info'></div>
     </div>
      </td>
      </tr></table>";
 }

 function checkUser($username, $password){
   $password = md5($password);
   $query = "SELECT * FROM users WHERE username = '{$username}' and password = '{$password}'";
   $result = runQuery($query);
   if ($result->num_rows > 0){
     $_SESSION['username'] = $username;
     $user = $result->fetch_assoc();
     $_SESSION['privilege'] = $user['privilege'];
     return true;
   }else{
     $_SESSION['username'] = "";
     return false;
   }
 }
 ?>

 </body>
</html>
