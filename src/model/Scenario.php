<?php
class Scenario
{
    public $apr;
    public $nvms;
    public $npms;
    public $scenario;

    private function __construct(){}

    static function  geraScenario( $apr,  $nvm,  $npm) {
        
        //Bloco que gera um array com a distribuição inicial de Placements 
        //igual a $apr - Ex. 0.75 e 100PM geram 75 placements possiveis
        $numPlacements = floor($npm * $apr);
        $allow = array_fill(0, $numPlacements, true);
        $denied = array_fill($numPlacements, $npm - $numPlacements, false);
        $places = array_merge($allow, $denied);
        $r_pm = array();
        $r_vm = array();
        $result = array();

        foreach (range(0, $nvm - 1) as $vm) {
        	shuffle($places);
        	foreach ($places as $key => $place_allowed) {
        		if ($place_allowed){
        			$result["v$vm"][] = "v$vm:p$key";
                    $r_pm["p$key"] = isset($r_pm["p$key"])? $r_pm["p$key"]+1 : 1;
                    $r_vm["v$vm"] = isset($r_vm["v$vm"])? $r_vm["v$vm"]+1 : 1;;
        		}
        	}
        }
        return array($result,$r_vm,$r_pm);
    }
    
    static function geraScenarios($apr, $nvms, $npms) {
        $retorno = array();
        foreach ($nvms as $nvm) {
            foreach ($npms as $npm) {
                $scenario = new Scenario();
                $scenario->apr = $apr;
                $scenario->nvms = $nvm;
                $scenario->npms = $npm;
                list($places, $r_vm, $r_pm) = Scenario::geraScenario($apr, $nvm, $npm);
                $scenario->placements = $places;
                //Resumo: array cuja key é o nome da VM e o valor é o numero de placements possiveis que ela pode estas
                $scenario->rvm = $r_vm;
                //Resumo: array cuja key é o nome da PM e o valor é o numero de VM que ela teoricamente pode hospedar
                $scenario->rpm = $r_pm;
                $retorno["r-$apr,v-$nvm,p-$npm"] = $scenario;
            }
        }
        return $retorno;
    }
}
