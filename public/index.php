<?php
require_once "../view/formats.php";
require_once "../src/model/Scenario.php";
$path = '../src/';
$filemane = 'Scenarios_APR(0.75)_VMs(4,5,6,7,8,9)_PMs(3)';
$file = file($path.$filemane.'.json');
$scenarios = json_decode($file[0],true);
$scenario = array_pop($scenarios);

$header = Scenario::toGoogleTableHeader($scenario);
$lines = Scenario::toGoogleTableLines($scenario);

echo sprintf($fmt_charts,$header,$lines);