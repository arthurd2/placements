<?php
$fmt_charts = '
<html>
  <head>
  <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
  <script src="//code.jquery.com/jquery-1.10.2.js"></script>
  <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
  <link rel="stylesheet" href="//jqueryui.com/resources/demos/style.css">

    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">
      google.load("visualization", "1.1", {packages:["table"]});
      google.setOnLoadCallback(drawTable);

      function drawTable() {
        var data = new google.visualization.DataTable();
		%s
        %s
        var table = new google.visualization.Table(document.getElementById(\'table_div\'));

        table.draw(data, {showRowNumber: true, width: \'200px\', height: \'50%%\'});
      }
    </script>

  <script>
  $(function() {
    $( "#accordion" ).accordion({
      heightStyle: "content"
    });
  });
  </script>

  </head>
  <body>
    <div id="table_div" style="float:left"></div>
    <div id="accordion" style="margin-left:20px; float:left" >
    %s
    </div>
  </body>
</html>
';
$fmt_accordion_title = 'MaxVMs[%s] VM(%s) PM(%s) Real(%s) UpperBound(%s) RFC(%s) NoRules(%s)';
$fmt_accordion_body = '%s';