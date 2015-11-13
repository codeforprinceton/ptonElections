<?php
//Copyright �2015 Anouk Stein, MD
include "SaveElection.php";
connect();
session_start();
if (isset($_POST['maxCount'])){
  $maxCount = $_POST['maxCount'];
  for ($i=0; $i<$maxCount; $i++){
      $hidden = "candidateID" . $i;
      $candidateID = $_POST[$hidden];
      $votes = $_POST[$candidateID];
  saveElectionResults($_POST['district'], $_POST['machine'], $candidateID, $votes);
  }
}

if ($_POST['output'] == " Download CSV "){
    $filename = "electionResults.csv";
    header('Content-type: application/csv');
    header('Content-Disposition: attachment; filename='.$filename);
    $date = date("m_d_Y");
    echo download('csv', $date);
}
 if  ($_POST['output'] == " Download Json "){
    $filename = "electionResults.json";
    header('Content-type: application/json');
    header('Content-Disposition: attachment; filename='.$filename);
    $date = date("m_d_Y");
    echo download('json', $date);
}

if  ($_POST['output'] == " Get Spreadsheet "){
  $filename = date("m_d_Y_") . "electionResults.html";
  header('Content-type: application/html');
  header('Content-Disposition: attachment; filename='.$filename);
  echo "<html><style>
      .overviewOuter{
      border: 1px solid gray;
      border-collapse: collapse;
      text-align: center;
  }
  .overviewNoWrap{
      border: 1px solid gray;
      border-collapse: collapse;
      white-space: nowrap;
      text-align: left;
      color: Black;
      font-weight: bold;
      padding-left: 3px;
      padding-right: 3px;
      min-width: 200px;
  }
  .overviewNoWrapRight{
    border: 1px solid gray;
    border-collapse: collapse;
    white-space: nowrap;
    text-align: right;
    color: black;
    padding-left: 3px;
    padding-right: 3px;
    min-width: 200px;
  }
  .overview{
      border: 1px solid gray;
      border-collapse: collapse;
      text-align: center;
      padding: 4px;
      color:black;
      font-size:14px;
      font-weight: lighter;
  }
  .tally{
      border: 1px solid gray;
      border-collapse: collapse;
      text-align: center;
      padding:3px;
      color:DarkBlue;
      font-size:16px;
  }</style><body>";
  /*
  echo "<html><style>
      .overviewOuter{
      border: 1px solid black;
      border-collapse: collapse;
      text-align: center;
  }
  .overviewNoWrap{
      border: 1px solid gray;
      border-collapse: collapse;
      white-space: nowrap;
      text-align: left;
      color: DimGray;
      padding-left: 3px;
      padding-right: 3px;
      min-width: 200px;
  }
  .overviewNoWrapRight{
    border: 1px solid gray;
    border-collapse: collapse;
    white-space: nowrap;
    text-align: right;
    color: DimGray;
    padding-left: 3px;
    padding-right: 3px;
    min-width: 200px;
  }
  .overview{
      border: 1px solid gray;
      border-collapse: collapse;
      text-align: center;
      padding: 4px;
      color:DarkGray;
      font-size:14px;
      font-weight: lighter;
  }
  .tally{
      border: 1px solid gray;
      border-collapse: collapse;
      text-align: center;
      padding:3px;
      color:DarkBlue;
      font-size:16px;
  }</style><body>";
  */
  //  style='background-color: #F3E2A9'

  /*
  echo '<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
  <input type="button" id="print-button" value="Make this table printable">
  <script>
  //   * HTML: Print Wide HTML Tables
  //   * http://salman-w.blogspot.com/2013/04/printing-wide-html-tables.html

   $(function() {
     $("#print-button").on("click", function() {
       var table = $("#table1"),
         tableWidth = table.outerWidth(),
         pageWidth = 600,
         pageCount = Math.ceil(tableWidth / pageWidth),
         printWrap = $("<div></div>").insertAfter(table),
         i,
         printPage;
       for (i = 0; i < pageCount; i++) {
         printPage = $("<div></div>").css({
           "overflow": "hidden",
           "width": pageWidth,
           "page-break-before": i === 0 ? "auto" : "always"
         }).appendTo(printWrap);
         table.clone().removeAttr("id").appendTo(printPage).css({
           "position": "relative",
           "left": -i * pageWidth
         });
       }
       table.hide();
       $(this).prop("disabled", true);
     });
   });
 </script>';
 */
  echo createOverviewTable();
  echo "</body></html>";
}
mysql_close();
?>
