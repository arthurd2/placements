<?php
class Combinations
{

    static function GenerateAllCombinations(&$arrays, $i = 0) {
        $news = array_pop( array_slice( $arrays , $i , 1 ));

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

    static function GenerateAllCombinationsMaxVM(&$arrays, $max, $i = 0, &$hash_vm = array()) {
        
        $news = array_pop( array_slice( $arrays , $i , 1 ));

        if ($i == count($arrays) - 1) {
            $resp = array();
            foreach ($news as $v) {

                list($vm_name , $pm_name) = explode(':', $v);
                $resp[] = array($v,'stat' => array($pm_name => 1));
            }
            return $resp;
        }
        
        // get combinations from subsequent arrays
        $tmp = Combinations::GenerateAllCombinationsMaxVM($arrays, $max, $i + 1,$hash_vm);
        $result = array();
        
        // concat each array from tmp with each element from $arrays[$i]
        //$news = array_slice($arrays, $i,1);
        foreach ( $news as $v) {
            list($vm_name , $pm_name) = explode(':', $v);
            foreach ($tmp as $t) {
                if( !isset($t['stat'][$pm_name]) or $t['stat'][$pm_name] < $max ){
                    $state = array_merge(array($v), $t) ;
                    $state['stat'][$pm_name] = isset($state['stat'][$pm_name])? $state['stat'][$pm_name]+1 : 1 ;
                    $result[] = $state;
                }
            }
        }
        if($i == 0) 
            foreach ($result as &$value) 
                unset($value['stat']);
        
        return $result;
    }

    static function WillOverload($v,$t,$max){
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
        return ($count > $max);
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
