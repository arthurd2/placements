<?php
require_once "../src/model/QuantidadeDeResultados.php";
require_once "../src/model/Combinations.php";
//Scenarios_APR(0.75)_VMs(10,20,40,80,100)_PMs(2,4,6,8,10).json
//
ini_set('xdebug.max_nesting_level', 700); 
//$filemane = 'Scenarios_APR(0.75)_VMs(10,20,40,80,100)_PMs(4,6,8,10,12)';
//$filemane = 'Scenarios_APR(0.75)_VMs(10,20,30,40,50,60,70,80,90,100)_PMs(10)';
//$filemane = 'Scenarios_APR(0.75)_VMs(10,20,40,60,80,100,120,140,160)_PMs(10)';
$filemane = 'Scenarios_APR(0.75)_VMs(4,5,6,7,8,9,10,11,12)_PMs(3)';
$file = file($filemane.'.json');
$scenarios = json_decode($file[0],true);

$formato = "%s %s %s\n";
$saida_sem   = '';
$saida_com   = '';
$saida_sum 	 = '';
$saida_sub 	 = '';
$saida_prod	 = '';
$saida_real	 = '';
$saida_2d	 = "VMs PMs Sem Com Sum Sub Prod Real\n";
$saida_delta = '';

$flag = null;
foreach ($scenarios as $key => $value) {
	printf("%s - VM(%s) - PM(%s) ",date(DATE_RFC2822), $value['nvms'], $value['npms']);

	$sem = QuantidadeDeResultados::calcularSemRegras($value);
	$com = QuantidadeDeResultados::calcularComRegras($value);
	$sum = QuantidadeDeResultados::calcularComRegrasMaxVMSum($value,3);
	$sub = QuantidadeDeResultados::calcularComRegrasMaxVMSub($value,3);
	$prod= QuantidadeDeResultados::calcularComRegrasMaxVMProd($value,3);
	$real_r = Combinations::GenerateAllCombinations(array_values($value['placements']));
	$real = count(Combinations::FilterCombinationsByMaxVm($real_r,3));
	echo " - Real($real) \n";
	$delta = 5;//100*($sum/$real);

	//Quebra a linha quando muda de VM. Necessario para o funcionamento do GnuPlot PM3D
	if (!is_null($flag) && $flag != $value['nvms']){
		$saida_sem 	.= "\n";
		$saida_com 	.= "\n";
		$saida_sum 	.= "\n";
		$saida_sub 	.= "\n";
		$saida_prod	.= "\n";
		$saida_real	.= "\n";
		$saida_delta.= "\n";
	}
	$flag = $value['nvms'];

	$saida_sem 	.= sprintf($formato, $value['nvms'], $value['npms'], $sem);
	$saida_com 	.= sprintf($formato, $value['nvms'], $value['npms'], $com);
	$saida_sum 	.= sprintf($formato, $value['nvms'], $value['npms'], $sum);
	$saida_sub 	.= sprintf($formato, $value['nvms'], $value['npms'], $sub);
	$saida_prod	.= sprintf($formato, $value['nvms'], $value['npms'], $prod);
	$saida_real	.= sprintf($formato, $value['nvms'], $value['npms'], $real);
	$saida_delta.= sprintf($formato, $value['nvms'], $value['npms'], $delta);
	$saida_2d	.= $value['nvms'].' '.$value['npms'].' '.$sem.' '.$com.' '.$sum.' '.$sub.' '.$prod.' '.$real."\n";
}

file_put_contents('filename.sem.csv', $saida_sem);
file_put_contents('filename.com.csv', $saida_com);
file_put_contents('filename.sum.csv', $saida_sum);
file_put_contents('filename.sub.csv', $saida_sub);
file_put_contents('filename.prod.csv', $saida_prod);
file_put_contents('filename.real.csv', $saida_real);
file_put_contents('filename.delta.csv', $saida_delta);
file_put_contents('filename.2d.csv', $saida_2d);