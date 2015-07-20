<?php
class Combinations
{
    static function GenerateAllCombinations($arrays, $i = 0) {
        if (!isset($arrays[$i])) {
            return array();
        }
        if ($i == count($arrays) - 1) {
            return $arrays[$i];
        }
        
        // get combinations from subsequent arrays
        $tmp = Combinations::GenerateAllCombinations($arrays, $i + 1);
        $result = array();
        
        // concat each array from tmp with each element from $arrays[$i]
        foreach ($arrays[$i] as $v) {
            foreach ($tmp as $t) {
                $result[] = is_array($t) ? array_merge(array($v), $t) : array($v, $t);
            }
        }
        return $result;
    }

    static function GenerateAllCombinationsMaxVM(&$arrays, $max, $i = 0) {
        //$arrays = ($i == 0)? array_values($arrays1) : $arrays1;

        if (!isset($arrays[$i])) 
            return array();
        
        if ($i == count($arrays) - 1) 
            return $arrays[$i];
        
        // get combinations from subsequent arrays
        $tmp = Combinations::GenerateAllCombinationsMaxVM($arrays, $max, $i + 1);
        $result = array();
        
        // concat each array from tmp with each element from $arrays[$i]
        foreach ($arrays[$i] as $v) 
            foreach ($tmp as $t) 
                //Checks if the new possibility will overload the PM
                if(count($t) >= $max and !Combinations::WillOverload($v,$t,$max))
                    $result[] = is_array($t) ? array_merge(array($v), $t) : array($v, $t);

        return $result;
    }

    static function WillOverload(&$v,&$t,&$max){
        if (!is_array($t))
            return false;
        list($null , $pm_name_new) = explode(':', $v);
        $count = 1;
        foreach ($t as $place) {
            list($null,$pm_name) = explode(':', $place);
            //checar se a performance melhora com um array de counters
            if ($pm_name_new == $pm_name)
                $count++;
        }
        return $count > $max;
    }

    static function FilterCombinationsByMaxVm($combinations, $max) {
        foreach ($combinations as $key => $combination) {
            list($v, $p) = Combinations::montaVeP(array($combination));
            if (Combinations::hasMoreThen($p, $max)) {
                unset($combinations[$key]);
            }
        }
        return $combinations;
    }
    
    function montaVeP($matrix) {
        $resp['vms'] = array();
        $resp['pms'] = array();
        foreach ($matrix as $vm_places) {
            foreach ($vm_places as $place) {
                list($vmName, $pmName) = explode(':', $place);
                @$resp['pms']["$pmName"]+= 1;
                @$resp['vms']["$vmName"]+= 1;
            }
        }
        return array($resp['vms'], $resp['pms']);
    }
    function hasMoreThen($vms,$max){
        foreach ($vms as $v) if($v > $max) return true;
        return false;
    }
}
