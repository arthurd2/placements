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

      var table = \'\';
      var data = \'\';

      function drawTable() {
        data = new google.visualization.DataTable(%s);
        table = new google.visualization.Table(document.getElementById(\'table_div\'));
        draw_table();
        //google.visualization.events.addListener(table, \'select\', changeValue ); 

      }

      function draw_table(){
        table.draw(data, {showRowNumber: true});
      }

      function changeValue(col) {
        var row = table.getSelection()[0].row;
        var resp = data.getValue(row, col);
        if (resp == true){
          var new_value = false;
        }else{
          var new_value = true;
        }
        data.setCell(row, col, new_value);
        draw_table();

        
  }  

      function newscenario() {
        var max = document.getElementById(\'maxvm\').value;
        var nvm = document.getElementById(\'nvm\').value;
        var npm = document.getElementById(\'npm\').value;
        var apr = document.getElementById(\'apr\').value;
        var newURL = "max=" + max + "&nvm=" + nvm+ "&npm=" + npm+ "&apr=" + apr;
        //alert(newURL);

        window.location.search = newURL;
      }

      function submit() {
        var json =  data.toJSON();
        var max = document.getElementById(\'maxvm\').value;
        var newURL = "max=" + max + "&state=" + json;
        //alert(newURL);

        window.location.search = newURL;
      }


    </script>

  <script>
  $(function() {
    $( "#accordion" ).accordion({ heightStyle: "content" });
  });
  </script>

  </head>
  <body>
    <div id="dados_div" style="float:left">
      <div id="genScenario_div" style="width:300px,text-align:center,float:left" > %s </div>
      <div id="accordion" style="width:550px,float:left"> %s </div>
    </div>
    <div id="table_div" style="wifth:600px,float:right">
      <div id="pmcontrol_div"> %s </div>
      <div id="table_div"></div>
    </div>
  </body>
</html>
';
$fmt_accordion_title = 'MaxVMs:%s VM:%s NoRules:%s RFC:%s | UpperBound1:%s  UpperBound2:%s | Real:%s   ';
$fmt_accordion_body = '%s';
$fmt_genscenario = '
<button onclick="submit()">Re-calculate Table&Max!</button><br>
Max:<input type="text" id="maxvm" value="%s"><br>
#VM:<input type="text" id="nvm" value="%s"><br>
#PM:<input type="text" id="npm" value="%s"><br>
APR:<input type="text" id="apr" value="%s"><br>
<button onclick=\'newscenario()\'>Generate New Scenario!</button>';