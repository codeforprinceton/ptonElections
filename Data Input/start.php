<!-- Copyright 2015 Anouk Stein, M.D.-->
<html>
 <head>
  <title>Princeton Election LogIn</title>
 </head>
<body style="text-align: center; background-color: #F3E2A9">
 <h1>Princeton Elections LogIn</h1>

<FORM action="electionHome.php" method='post'>

<h3><br>User name: <input type="text" name="username" /></br>
<br>Password: <input type="password" name="password" /></br>
<br><br>Choose election:</h3>

<?php
include "administrativeFunctions.php";
connect();

//choose election
//get all election

$result = getAllElections();
$count = 0;
while ($election = mysql_fetch_array($result)){
  $d = new DateTime($election['election_date']);
  $id = $election['id'];
  echo '<input type="radio" name="election" value=' . $id;
  if ($count++ == 0){
    echo " checked";
  }
  echo '> ';
  echo $election['name'] . " " . date_format($d, "M d, Y");
  echo " <i>(" . $election['location'] . ")</i><br>";
}

?>

<br><input type=submit value="LogIn">
</center>
</FORM>
</body>
</html>
