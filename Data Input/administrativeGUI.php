<!--Copyright �2015 Anouk Stein, MD-->
<html>
 <head>
<link rel="stylesheet" type="text/css" href="./elections.css" />
  <title>Election</title>
 </head>
 <body style="background-color: #F3E2A9; margin-left: 20px;">
  <?php
  include "administrativeFunctions.php";
  //connect();
//create/edit elections
//get all election
$result = getAllElections();
?>
<h1>Edit/Create Election</h1>
<br><h3>Choose Election</h3>
<?php
echo "<form action='./electionItems.php' method='POST'>";
while ($election = mysql_fetch_array($result)){
  $d = new DateTime($election['election_date']);
  $id = $election['id'];
  echo '<input type="radio" name="election" value=' . $id . '> ';
  echo $election['name'] . " " . date_format($d, "M d, Y");
  echo " <i>(" . $election['location'] . ")</i><br>";
}
echo "<br><input type='submit' value='Edit'></form>";
//add election
?>
<br>
<h3>Or Add New Election</h3>
<form action="add.php" method='POST'>
Election Month: <input type='number' name='month' min="1" max="12"><br>
Election Day: <input type='number' name='day' min="1" max="31"><br>
Election Year: <input type='number' name='year' min="2015" max="3000"><br>
<br>
Name: <input type='text' name='name' value='Primary'><br>
Location: <input type='text' name='location' value='Princeton, NJ'><br>
<br>
Number of Ballot Items: <input type='number' name='items' value='0' min="0" max="25">
<br><br><input type="submit" value="Create">
</form>
<?php

//ballotItem
//20 ballot item choices

//Save
?>
</body></html>
