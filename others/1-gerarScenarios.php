<?php
require_once "../src/model/Scenario.php";

//Seta APR
$apr = 0.75;

//Seta intervalos de VMs
$nvms = array(10,12,14,16,18,20);

//Seta intervalos de PMs
$npms = array(4);

//Gerar Scenarios
$scenarios = Scenario::geraScenarios($apr,$nvms,$npms);


//Converver para JSON
$json = json_encode($scenarios);

//Criar o nome do Arquivo
$filename = sprintf("Scenarios_APR(%s)_VMs(%s)_PMs(%s).json", $apr ,implode(',',$nvms),implode(',',$npms));

//Salvar no arquivo
file_put_contents($filename, $json);
echo "$filename";