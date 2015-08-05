<?php
class QuantidadeDeResultados
{
    static function calcularSemRegras($scenario) {
        return pow($scenario['npms'], $scenario['nvms']);
    }
    
    static function calcularComRegras($scenario) {
        $retorno = 1;
        foreach ($scenario['placements'] as $vm) {
            $retorno*= count($vm);
        }
        return $retorno;
    }

    static function calcularComRegrasMaxVMProd($scenario, $maxVM) {
        $todas = 1;
        foreach ($scenario['rpm'] as $pmName => $vms) {
            $combinacao = QuantidadeDeResultados::calcCombination($vms, $maxVM);
            $todas *= $combinacao;
        }
        return $todas;
    }

    static function calcularComRegrasMaxVMSum($scenario, $maxVM) {
        $todas = 0;
        foreach ($scenario['rpm'] as $pmName => $vms) {
            $combinacao = QuantidadeDeResultados::calcCombination($vms, $maxVM);
            $todas += $combinacao;
        }
        return $todas;
    }

    static function calcularComRegrasMaxVMSub($scenario, $maxVM) {

        $indesejado = 0;

        $todas = array_product($scenario['rvm']);
        foreach ($scenario['rpm'] as $pmName => $pm) {
            $localUnwanted = 0;
            if ($pm > $maxVM) {
                for ($i = $maxVM + 1; $i <= $pm; $i++) {
                    $tmp = QuantidadeDeResultados::calcCombination($pm, $i);
                    $localUnwanted+= $tmp;
                }
            }
            $indesejado+= $localUnwanted;
        }
        return $todas - $indesejado;
    }
  
    static function fact($a) {
        if ($a <= 1) return 1;
        else return $a * QuantidadeDeResultados::fact(($a - 1));
    }
    
    static function calcCombination($n, $s) {
        $resp = QuantidadeDeResultados::fact($n) / (QuantidadeDeResultados::fact($s) * QuantidadeDeResultados::fact($n - $s));
        return $resp;
    }

    static function getOutsidersInsiders($pm, $scenario) {
        $outsider = 1;
        $insider = array();
        foreach ($scenario['placements'] as $vm => $places) {
            $flag = true;
            foreach ($places as $place) {
                $pm_name = explode(':', $place)[1];
                //If the current VM can be host in the evaluated PM, do not multiply
                if($pm == $pm_name){
                    $flag = false;
                    break;
                }
            }
            if($flag)
                $outsider *= count($places);
            else
                $insider[] = count($places)-1;
        }
        return array( $outsider, $insider);
    }
    
    static function calcularComRegrasMaxVMOutIn($scenario, $maxVM) {
        $unwanted = 0;
        $all = array_product($scenario['rvm']);

        foreach ($scenario['rpm'] as $pmName => $numOfVMsInPM) {
            $unwantedLocal = 0;
            if ($numOfVMsInPM > $maxVM) {
                list($out,$in) = QuantidadeDeResultados::getOutsidersInsiders($pmName,$scenario);
                for ($i = $maxVM+1; $i <= $numOfVMsInPM; $i++) {
                    $inConsolidated = QuantidadeDeResultados::getInsidersCombinations($in, $numOfVMsInPM-$i);
                    $unwantedLocal += $out * $inConsolidated;
                }
            }
            $unwanted += $unwantedLocal;
        }
        return $all - $unwanted;
    }

    static function calcularComRegrasMaxVMSubProdOthers($scenario, $maxVM) {

        $indesejado = 0;

        $todas = array_product($scenario['rvm']);
        foreach ($scenario['rpm'] as $pmName => $pm) {
            $localUnwanted = 0;
            if ($pm > $maxVM) {
                $multi = QuantidadeDeResultados::getOutsidersInsiders($pmName,$scenario)[0];
                for ($i = $maxVM+1; $i <= $pm; $i++) {
                    $tmp = QuantidadeDeResultados::calcCombination($pm, $i)*$multi;
                    $localUnwanted += $tmp;
                }
            }
            $indesejado += $localUnwanted;
        }
        return $todas - $indesejado;
    }
    static function getInsidersCombinations(&$insiders, $size){
        if($size <= 0) return 1;
        $subtrees = QuantidadeDeResultados::_getInsidersCombinations( $insiders, $size);
        return array_sum($subtrees);
    }

    static function _getInsidersCombinations(&$insiders, $size,$pos = 0){
        if($size <= 0) return array(1);

        $return = array();
        $end = count($insiders)-$size;
        
        for ( $i = $pos ; $i <= $end ; $i++ ){
            $subtree = QuantidadeDeResultados::_getInsidersCombinations( $insiders, $size-1, $i+1);
            foreach ($subtree as $value) 
                $return[] = $insiders[$i]*$value;
        }
        return $return;
    }
    static function calculateAvgCombSplitterApproach($scenario, $maxVM) {
        $quantities = QuantidadeDeResultados::getCombinatorialSliceQuantities($scenario, $maxVM);
        return array_sum($quantities)/count($quantities);
    }
    static function calculateSumCombSplitterApproach($scenario, $maxVM) {
        $quantities = QuantidadeDeResultados::getCombinatorialSliceQuantities($scenario, $maxVM);
        return array_sum($quantities);
    }
    static function calculateProdSequencialSplitterApproach($scenario, $maxVM) {
        $quantities = QuantidadeDeResultados::getSequencialSliceQuantities($scenario, $maxVM);
        return array_product($quantities);
    }
    static function calculateSumSequencialSplitterApproach($scenario, $maxVM) {
        $quantities = QuantidadeDeResultados::getSequencialSliceQuantities($scenario, $maxVM);
        return array_sum($quantities);
    }
    static function getSequencialSliceQuantities($scenario, $maxVM) {
        $slice_size = ($maxVM*2) + 1 ;
        $quatities = array();

        $p = 0;
        while ( $p < $scenario['nvms']){
            $slice = array_slice($scenario['placements'], $p, $slice_size);
            $slice_scenario = Scenario::buildScenarioByPlacements($slice);
            $quatities[] = QuantidadeDeResultados::calcularComRegrasMaxVMOutIn($slice_scenario,$maxVM);
            $p += $slice_size;
        }

        return $quatities;
    }
    static function getCombinatorialSliceQuantities($scenario, $maxVM) {
        require_once 'libs/Combinatorics.php';
        $combinatorics = new Math_Combinatorics;
        $sliceSize = ($maxVM*2) + 1 ;

        $input = array_keys($scenario['placements']);
        $combinations = $combinatorics->combinations($input, $sliceSize);

        $quatities = array();

        foreach ($combinations as $combination) {
            $slice = array();
            foreach ($combination as $index) {
                $slice[] = $scenario['placements'][$index];
            }
            $sliceScenario = Scenario::buildScenarioByPlacements($slice);
            $quatities[] = QuantidadeDeResultados::calcularComRegrasMaxVMOutIn($sliceScenario,$maxVM);
        }

        return $quatities;
    }

    //TODO Arrumar casos de VMs sem places!!!
    static function treeSearchApproach(&$scenario, &$maxVM, &$level = 0 , &$usageVector = array(), &$stateCounter = 0) {
        //// Select the array of placements of VM => $level
        $key = array_keys($scenario['rvm'])[$level];
        $vmPlaces = $scenario['placements'][$key];

        //Interects foreach possible placements of that VM
        foreach ( $vmPlaces as $p) {
            $pmName = explode(':', $p)[1];

            //Checks if the PM is not full
            if(!isset($usageVector[$pmName]) || $usageVector[$pmName] < $maxVM){
                //Check if the last VM to host
                if( $level >= $scenario['nvms']-1 ){
                    //Just count one state
                    $stateCounter++;
                }else{
                    //Prepare to drilldown
                    $level++;
                    $usageVector[$pmName] = isset($usageVector[$pmName])? $usageVector[$pmName]+1 : 1;
                    QuantidadeDeResultados::treeSearchApproach( $scenario, $maxVM, $level , $usageVector, $stateCounter);
                    $level--;
                    $usageVector[$pmName]--;
                }
            }
        }
        return $stateCounter;
    }
}