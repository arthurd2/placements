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
        
        $result = array();

        foreach (range(0, $nvm - 1) as $vm) {
        	shuffle($places);
        	foreach ($places as $key => $place_allowed) {
        		if ($place_allowed){
        			$result["v$vm"][] = "v$vm:p$key";
        		}
        	}
        }
        return $result;
    }
    
    static function geraScenarios($apr, $nvms, $npms) {
        $retorno = array();
        foreach ($nvms as $nvm) {
            foreach ($npms as $npm) {
                $scenario->apr = $apr;
                $scenario->nvm = $nvm;
                $scenario->npm = $npm;
                $scenario->placements = Scenario::geraScenario($apr, $nvm, $npm);
                $retorno["r-$apr,v-$nvm,p-$npm"] = $scenario;
            }
        }
        return $retorno;
    }
}
