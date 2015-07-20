<?php
$fmt_charts = '
<html>
  <head>
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
  </head>
  <body>
    <div id="table_div"></div>
  </body>
</html>
';
