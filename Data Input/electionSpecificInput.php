<!--Copyright ©2015 Anouk Stein, MD-->
<html>
 <head>
<link rel="stylesheet" type="text/css" href="pr.MyStylePlain.css" />
  <title>Election</title>
 </head>
 <body style="background-color: #F3E2A9">
 <?php
 include "SaveElection.php";
 connect();
 
 echo "<br><br><center><h1>Enter Results</h1><h2> District: {$_POST['district']},  Machine: {$_POST['machine']}</h2></center>";
 echo "<form action = './saveInputs.php'  method ='post'>";

 
 //get categories
 $categoriesResult = getCategories();
 $count = 0;
 $categoryCount = 0;
 echo "<table><tr><td>";
 while ($category = mysql_fetch_array($categoriesResult)){
  //get candidates
  $id = $category['category_id']; //echo " ID = {$id} ";
  
  $candidates = getCandidates($id);
  echo "<h1> {$category['category_name']}</h1><table>";
  
  
  while ($candidate = mysql_fetch_array($candidates)){
    
    $hidden = "candidateID" . $count++;
    echo "<tr><td width = 25px></td><td>{$candidate['candidate_name']}</td><td> <input type ='text' name = '{$candidate['candidate_id']}' value = ";
    echo getElectionResults($_POST['district'], $_POST['machine'], $candidate['candidate_id']);
    echo "></td></tr>";
    echo "<input type = hidden name = $hidden METHOD = 'POST' value = '{$candidate['candidate_id']}'>";
  }
  echo "</table>";

  if ($categoryCount % 3 == 2){
   echo "</td><td style='padding-left:45px'>";
  }
  $categoryCount++;
 }
 echo "</td></tr></table>";
 
 echo "<INPUT type = hidden name = 'district' METHOD = 'POST' value = '{$_POST['district']}'>";
 echo "<INPUT type = hidden name = 'machine' METHOD = 'POST' value = '{$_POST['machine']}'>";
 echo "<input type = hidden name = 'maxCount' METHOD = 'POST' value = '{$count}'>";
 echo "<br><br><input type=submit name='output' value=' Save '>";
 echo "<br><br><input type=submit name='output' value=' Save and Download CSV '>";
 echo "<br><br><input type=submit name='output' value=' Save and Download Json '>";
 ?>
 
 </form>
 </body>
</html>
