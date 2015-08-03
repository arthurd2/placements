<?php
set_include_path('../');
require_once "view/formats.php";
require_once "src/model/Scenario.php";
require_once "src/model/QuantidadeDeResultados.php";
require_once "src/model/Combinations.php";
require_once "view/Accordion.php";
require_once "view/ViewHelper.php";

ini_set('xdebug.max_nesting_level', 1000);
$max = isset($_GET['max']) ? $_GET['max'] : 3;
$nvm = isset($_GET['nvm']) ? $_GET['nvm'] : 7;
$npm = isset($_GET['npm']) ? $_GET['npm'] : 3;
$apr = isset($_GET['apr']) ? $_GET['apr'] : 0.75;

if (isset($_GET['state'])) {
    $scenario = Scenario::getScenarioFromJSON($_GET['state']);
} 
else {
    $scenarios = Scenario::geraScenarios($apr, array($nvm), array($npm));
    $scenario = array_pop($scenarios);
}

$json = Scenario::toDataTableJSON($scenario);

//$memcache = memcache_connect('localhost', 11211);
//$accordion = isset($_GET['cache']) ? false : memcache_get($memcache, 'accordion');
//$accordion = false;
//if ($accordion == false) {
//echo "<script>alert('Not Cached');</script>";

$accordion = new Accordion();
$sem = QuantidadeDeResultados::calcularSemRegras($scenario);
$com = QuantidadeDeResultados::calcularComRegras($scenario);

$last = QuantidadeDeResultados::calcularComRegrasMaxVMSub($scenario, $max);
$tree = QuantidadeDeResultados::treeSearchApproach($scenario,$max);
$test1 = QuantidadeDeResultados::calcularComRegrasMaxVMOutIn($scenario, $max);
$test_c_a = QuantidadeDeResultados::calculateAvgCombSplitterApproach($scenario, $max);
$test_c_s = QuantidadeDeResultados::calculateSumCombSplitterApproach($scenario, $max);
$test_s_p = QuantidadeDeResultados::calculateProdSequencialSplitterApproach($scenario, $max);
$test_s_s = QuantidadeDeResultados::calculateSumSequencialSplitterApproach($scenario, $max);

$filtered = Combinations::GenerateAllCombinationsMaxVM($scenario['placements'], $max);
$real = count($filtered);

$title = sprintf($fmt_accordion_title, $max, $scenario['nvms'],$scenario['npms'], $sem, $com, $real, $tree, $last, $test1, 
	$test_c_a,$test_c_s,$test_s_p,$test_s_s);
//$body = sprintf($fmt_accordion_body, ViewHelper::printState($filtered));
$body = '&nbsp';
$accordion->add($title, $body);

//memcache_set($memcache, 'accordion', $accordion);
//}

$srt_accordion = $accordion->get();
$genScenario = sprintf($fmt_genscenario,$max,$nvm,$npm,$apr);

$buttons = ViewHelper::getPmControlButtons($scenario['npms']);
echo sprintf($fmt_charts, $json, $srt_accordion, $genScenario, $buttons);
