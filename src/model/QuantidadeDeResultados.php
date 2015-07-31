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
            //echo "Desejados da PM($pmName): $combinacao\n ";
            $todas *= $combinacao;
        }
        return $todas;
    }

    static function calcularComRegrasMaxVMSum($scenario, $maxVM) {
        $todas = 0;
        foreach ($scenario['rpm'] as $pmName => $vms) {
            $combinacao = QuantidadeDeResultados::calcCombination($vms, $maxVM);
            //echo "Desejados da PM($pmName): $combinacao\n ";
            $todas += $combinacao;
        }
        return $todas;
    }

    static function calcularComRegrasMaxVMSub($scenario, $maxVM) {

        $indesejado = 0;

        $todas = array_product($scenario['rvm']);
        foreach ($scenario['rpm'] as $pmName => $pm) {
            $pm_tmp = 0;
            if ($pm > $maxVM) {
                for ($i = $maxVM + 1; $i <= $pm; $i++) {
                    $tmp = QuantidadeDeResultados::calcCombination($pm, $i);
                    $pm_tmp+= $tmp;
                }
            }
            //echo "Indesejados da PM($pmName): $pm_tmp\n ";
            $indesejado+= $pm_tmp;
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
                list($vm_name,$pm_name) = explode(':', $place);
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
                //error_log(print_r($in));
                for ($i = $maxVM+1; $i <= $numOfVMsInPM; $i++) {
                    $in_consolidated = QuantidadeDeResultados::getInsidersCombinations($in, $numOfVMsInPM-$i);
                    $unwantedLocal += $out * $in_consolidated;
                }
            }
            //error_log("PM($pmName) - Unwanted($unwantedLocal)<br>");
            $unwanted += $unwantedLocal;
        }
        //echo "<br>----------------------------<br>";
        return $all - $unwanted;
    }

    static function calcularComRegrasMaxVMSubProdOthers($scenario, $maxVM) {

        $indesejado = 0;

        $todas = array_product($scenario['rvm']);
        foreach ($scenario['rpm'] as $pmName => $pm) {
            $pm_tmp = 0;
            if ($pm > $maxVM) {
                list($multi,$null) = QuantidadeDeResultados::getOutsidersInsiders($pmName,$scenario);
                for ($i = $maxVM+1; $i <= $pm; $i++) {
                    $tmp = QuantidadeDeResultados::calcCombination($pm, $i)*$multi;
                    $pm_tmp+= $tmp;
                }
            }
            //echo "Indesejados da PM($pmName): $pm_tmp\n ";
            $indesejado+= $pm_tmp;
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
    static function calculateProdSequencialSplitterApproach($scenario, $maxVM) {
        $quantities = QuantidadeDeResultados::getSequencialSliceQuantities($scenario, $maxVM);
        return array_product($quantities);
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
        $slice_size = ($maxVM*2) + 1 ;

        $input = array_keys($scenario['placements']);
        $combinations = $combinatorics->combinations($input, $slice_size); // 5 is the subset size

        $quatities = array();

        foreach ($combinations as $combination) {
            $slice = array();
            foreach ($combination as $index) {
                $slice[] = $scenario['placements'][$index];
            }
            $slice_scenario = Scenario::buildScenarioByPlacements($slice);
            $quatities[] = QuantidadeDeResultados::calcularComRegrasMaxVMOutIn($slice_scenario,$maxVM);
        }

        return $quatities;
    }
}