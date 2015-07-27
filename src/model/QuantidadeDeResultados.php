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

    function calcularComRegrasMaxVMProd($scenario, $maxVM) {
        $todas = 1;
        foreach ($scenario['rpm'] as $pmName => $vms) {
            $combinacao = QuantidadeDeResultados::calcCombination($vms, $maxVM);
            //echo "Desejados da PM($pmName): $combinacao\n ";
            $todas *= $combinacao;
        }
        return $todas;
    }

    function calcularComRegrasMaxVMSum($scenario, $maxVM) {
        $todas = 0;
        foreach ($scenario['rpm'] as $pmName => $vms) {
            $combinacao = QuantidadeDeResultados::calcCombination($vms, $maxVM);
            //echo "Desejados da PM($pmName): $combinacao\n ";
            $todas += $combinacao;
        }
        return $todas;
    }

    function calcularComRegrasMaxVMSub($scenario, $maxVM) {

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
  
    function fact($a) {
        if ($a <= 1) return 1;
        else return $a * QuantidadeDeResultados::fact(($a - 1));
    }
    
    function calcCombination($n, $s) {
        $resp = QuantidadeDeResultados::fact($n) / (QuantidadeDeResultados::fact($s) * QuantidadeDeResultados::fact($n - $s));
        return $resp;
    }
    function getOtherMultiplier($pm, $scenario) {
        $outsider = 1;
        $insider = 0;
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
            $outsider *= $flag? count($places): 1 ;
            $insider += $flag? 0 : count($places)-1;
        }
        return array( $outsider, $insider);
    }
    
    function calcularComRegrasMaxVMOutIn($scenario, $maxVM) {
        $indesejado = 0;
        $todas = array_product($scenario['rvm']);

        foreach ($scenario['rpm'] as $pmName => $pm) {
            $pm_tmp = 0;
            if ($pm > $maxVM) {
                list($out,$in) = QuantidadeDeResultados::getOtherMultiplier($pmName,$scenario);
                for ($i = $maxVM+1; $i <= $pm; $i++) {
                    $expo = $pm - $i;
                    //echo "\nPM($pm) | O($out) I($in) | POW($expo) <br>\n";
                    $pm_tmp+= $out*pow($in,$expo);
                }
            }
            $indesejado+= $pm_tmp;
        }
        return $todas - $indesejado;
    }

    function calcularComRegrasMaxVMSubProdOthers($scenario, $maxVM) {

        $indesejado = 0;

        $todas = array_product($scenario['rvm']);
        foreach ($scenario['rpm'] as $pmName => $pm) {
            $pm_tmp = 0;
            if ($pm > $maxVM) {
                list($multi,$null) = QuantidadeDeResultados::getOtherMultiplier($pmName,$scenario);
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


}
