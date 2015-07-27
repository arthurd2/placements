<?php
require_once "../view/formats.php";
require_once "../src/model/Scenario.php";
require_once "../src/model/QuantidadeDeResultados.php";
require_once "../src/model/Combinations.php";
require_once "../view/Accordion.php";
require_once "../view/ViewHelper.php";

$path = '../src/';
$filemane = 'Scenarios_APR(0.75)_VMs(4,5,6,7,8,9)_PMs(3).2';

$file = file($path . $filemane . '.json');
$scenarios = json_decode($file[0], true);
$scenarios = array_reverse($scenarios);
$scenario = array_pop($scenarios);
$scenarios[] = $scenario ;
$header = Scenario::toGoogleTableHeader($scenario);
$lines = Scenario::toGoogleTableLines($scenario);
$memcache = memcache_connect('localhost', 11211);

$max = isset($_GET['max'])?$_GET['max']:3;

$accordion = isset($_GET['cache']) ? false : memcache_get($memcache, 'accordion');
$accordion = false;



if ($accordion == false) {
	//echo "<script>alert('Not Cached');</script>";
    $resultados = array();
    $accordion = new Accordion();
    foreach ($scenarios as $key => $value) {
        $sem = QuantidadeDeResultados::calcularSemRegras($value);
        $com = QuantidadeDeResultados::calcularComRegras($value);
        
        $last = QuantidadeDeResultados::calcularComRegrasMaxVMSub($value, $max);
        $test = QuantidadeDeResultados::calcularComRegrasMaxVMOutIn($value, $max);
        
        $filtered = Combinations::GenerateAllCombinationsMaxVM($value['placements'],$max);
        $real = count($filtered);
        
        $title = sprintf($fmt_accordion_title, $max, $value['nvms'], $sem, $com, $last, $test, $real );
        $body = sprintf($fmt_accordion_body, ViewHelper::printState($filtered));
        $accordion->add($title, $body);
    }
    memcache_set($memcache, 'accordion',$accordion);
}

$srt_accordion = $accordion->get();
echo sprintf($fmt_charts, $header, $lines, $srt_accordion);
