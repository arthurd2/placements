<?php
$path = '../src/';
$filemane = 'Scenarios_APR(0.75)_VMs(4,5,6,7,8,9)_PMs(3)';
$file = file($path . $filemane . '.json');
$scenarios = json_decode($file[0], true);
$scenario = array_pop($scenarios);
echo "<pre>";
$anterior = $scenario;
$resp[] = $scenario;
$novo = $anterior;

function rpm($placements){
	$resp = array();
	foreach ($placements as $v) {
		foreach ($v as $p) {
			list($vm,$pm) = explode(':', $p);
			$resp[$pm] = isset($resp[$pm])? $resp[$pm]+1 : 1;
		}
	}
	ksort($resp);
	return $resp;
}

for ($i = 8; $i >= 4; $i--) {
    $novo['nvms'] = $i;
    unset($novo['placements']["v$i"]);
    unset($novo['rvm']["v$i"]);
    $novo['rpm'] = rpm($novo['placements']);
    $resp[] = $novo;
}
print_r($resp);


//Converver para JSON
$json = json_encode($resp);

//Salvar no arquivo
file_put_contents($path . $filemane . '.2.json', $json);