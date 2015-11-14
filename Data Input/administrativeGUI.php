<!--Copyright 2015 Anouk Stein, MD-->
<?php  ?>
<html>
 <head>
<link rel="stylesheet" type="text/css" href="./elections.css" />
<script src="elections.js"></script>

  <title>Election</title>
 </head>
 <body style="background-color: #F3E2A9; margin-left: 20px;">
   <div id="debug"></div>
  <?php
  include "administrativeFunctions.php";
  //connect();
//create/edit elections
//get all election

?>
<h1>Edit/Create Election</h1>
<br>
<?php
//Set editable
////TODO set active state of elections
echo "<h3>Set Editable Status of Elections</h3>";
echo "<form action= '{$_SERVER['PHP_SELF']}' method='POST'>";
echo "<table><th>Election</th><th>Can Edit</th></tr>";


if(isset($_POST['submit']))
{
  //var_dump($_POST);
  // foreach($_POST['election'] as $check) {
  //           //echo $check;
  //           setActiveElection($check, true);
  //         }
          $result = getAllElections();
          while ($election = $result->fetch_assoc()){
            $id = $election['id'];
            if(in_array($id, $_POST['election'])){
              setActiveElection($id, 1);
            }else{
              setActiveElection($id, 0);
            }
          }
}

$result = getAllElections();
while ($election = $result->fetch_assoc()){
  $d = new DateTime($election['election_date']);
  $id = $election['id'];
  echo "<tr><td>";
  echo $election['name'] . " " . date_format($d, "M d, Y");
  echo " <i>(" . $election['location'] . ")</i></td>";
  $election_id = $election['id'];
  echo "<td align='center'> <input type='checkbox' name='election[]' value='{$election_id}'";
  if($election['is_active'] == 1){
    echo " checked";
  }
  //OnChangeCheckbox (this)
  //echo " onclick='OnChangeCheckbox(this)'> </td></tr>";
  //echo " oninput ='getActiveStatus(this, this.name)'> </td></tr>";
echo "> </td></tr>";
}
echo "</table>";
echo "<br><input type='submit' name='submit' value='Save'>";

echo "<br><hr>";
echo "<h3>Choose Election</h3>";
echo "<form action='./electionItems.php' method='POST'>";
$result->data_seek(0);
while ($election = $result->fetch_assoc()){
  $d = new DateTime($election['election_date']);
  $id = $election['id'];
  echo '<input type="radio" name="election" value=' . $id . '> ';
  echo $election['name'] . " " . date_format($d, "M d, Y");
  echo " <i>(" . $election['location'] . ")</i><br>";
}
echo "<br><input type='submit' value='Edit'></form>";

//echo "<br><input type='submit' value='Save'></form>";
//add election
?>
<hr>
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
<div id="input"></div>
</body></html>
