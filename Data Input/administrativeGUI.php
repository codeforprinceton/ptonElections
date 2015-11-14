<!--Copyright 2015 Anouk Stein, MD-->
<?php  ?>
<html>
 <head>
<link rel="stylesheet" type="text/css" href="./elections.css" />
<script src="elections.js"></script>
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
  <title>Election</title>
 </head>
 <body style="background-color: #F3E2A9; margin-left: 20px;">
   <div id="debug"></div>
   <div class="container">
  <?php
  include "administrativeFunctions.php";
  //connect();
//create/edit elections
//get all election

?>
<h1>Edit/Create Election</h1>
<br>
<div class="row">
    <div class="col-sm-6">
      <h3>Set Editable Status of Elections</h3>
<?php
//Set editable
////TODO set active state of elections
echo "<form action= '{$_SERVER['PHP_SELF']}' method='POST'>";
echo "<table><th>Election</th><th>Can Edit</th></tr>";


if(isset($_POST['submit'])){
  //var_dump($_POST);
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
if(isset($_POST['submitUser'])){
  //var_dump($_POST);
  $f=$_POST['first'];
  $l=$_POST['last'];
  $u=$_POST['usr'];
  $p=$_POST['pwd'];
  $a=$_POST['access'];

  if($f && $l && $u && $p && $a){
    addUser($f, $l, $u, $p, $a, true);
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
?>
</table>
<br><input type='submit' name='submit' value='Save'>
</form>
</div>
<div class="col-sm-6">
<h3>Choose Election</h3>
<form action='./electionItems.php' method='POST'>

  <?php
$result->data_seek(0);
while ($election = $result->fetch_assoc()){
  $d = new DateTime($election['election_date']);
  $id = $election['id'];
  echo '<input type="radio" name="election" value=' . $id . '> ';
  echo $election['name'] . " " . date_format($d, "M d, Y");
  echo " <i>(" . $election['location'] . ")</i><br>";
}
?>
<br><input type='submit' value='Edit'>
</form>
</div></div>
<br>
<hr>
<h3>Add New User</h3>
<br>
<form class="form-inline" role="form" action= '<?php echo $_SERVER['PHP_SELF']; ?>' method='POST'>
   <div class="form-group">
     <label for="first">First:</label>
     <input type="text" class="form-control" name="first">
   </div>
   <div class="form-group">
     <label for="last"> Last:</label>
     <input type="text" class="form-control" name="last">
   </div>
   <div class="form-group">
     <label for="usr"> Username:</label>
     <input type="text" class="form-control" name="usr">
   </div>
   <div class="form-group">
     <label for="pwd"> Password:</label>
     <input type="password" class="form-control" name="pwd">
   </div>
   <br>
   <label class="radio-inline">
     <input type="radio" name="access" value="admin">Administrator
   </label>
   <label class="radio-inline">
     <input type="radio" name="access" value="user" checked>User
   </label>
   <label class="radio-inline">
     <input type="radio" name="access" value="readonly">Read Only access
   </label>
   <br><br>

<input type="submit" value="Add New User" name="submitUser">
</form>
<br><br>
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
</div>
</body>
</html>
