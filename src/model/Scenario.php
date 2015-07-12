<?php
class Scenario
{
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
                $key = "r-$apr,v-$nvm,p-$npm";
                $retorno[$key]["apr"] = $apr;
                $retorno[$key]["nvm"] = $nvm;
                $retorno[$key]["npm"] = $npm;
                $retorno[$key]["scenario"] = Scenario::geraScenario($apr, $nvm, $npm);
            }
        }
        return $retorno;
    }
}
