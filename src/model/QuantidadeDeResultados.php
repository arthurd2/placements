<?php
class QuantidadeDeResultados
{
    static function calcularSemRegras($scenario) {
        return pow($scenario->npms, $scenario->nvms);
    }
    
    static function calcularComRegras($scenario) {
        $retorno = 1;
        foreach ($scenario->placements as $vm) {
            $retorno*= count($vm);
        }
        return $retorno;
    }
    static function calcularComRegrasMaxVM($scenario, $maxVM) {
    	//TODO Implementar!!!
    }
    
    function calcBountMax($matrix, $max) {
        list($V, $P) = montaVeP($matrix);
        $indesejado = 0;
        $todas = array_product($V);
        foreach ($P as $pmName => $pm) {
            $pm_tmp = 0;
            if ($pm > $max) {
                for ($i = $max + 1; $i <= $pm; $i++) {
                    $tmp = calcCombination($pm, $i);
                    $pm_tmp+= $tmp;
                }
            }
            echo "Indesejados da PM($pmName): $pm_tmp\n ";
            $indesejado+= $pm_tmp;
        }
        return $todas - $indesejado;
    }

    function montaVeP($matrix) {
        $resp['vms'] = array();
        $resp['pms'] = array();
        foreach ($matrix as $vm_places) {
            foreach ($vm_places as $place) {
                
                //echo "Analisando $place \n";
                list($vmName, $pmName) = explode(':', $place);
                @$resp['pms']["$pmName"]+= 1;
                @$resp['vms']["$vmName"]+= 1;
            }
        }
        return array($resp['vms'], $resp['pms']);
    }
    
    function fact($a) {
        if ($a <= 1) return 1;
        else return $a * fact(($a - 1));
    }
    
    function calcCombination($n, $s) {
        $resp = fact($n) / (fact($s) * fact($n - $s));
        return $resp;
    }




}
