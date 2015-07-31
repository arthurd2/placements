<?php
class Combinations
{

    static function generateAllCombinations(&$arrays, $i = 0) {
        if ($i == count($arrays) - 1) {
            return $arrays[$i];
        }
        
        // get combinations from subsequent arrays
        $tmp = Combinations::GenerateAllCombinations($arrays, $i + 1);
        $result = array();
        
        // concat each array from tmp with each element from $arrays[$i]
        foreach ($arrays[$i] as $v) 
            foreach ($tmp as $t) 
                $result[] = is_array($t) ? array_merge(array($v), $t) : array($v, $t);
        return $result;
    }

    static function generateAllCombinationsMaxVM(&$arrays, $max, $i = 0, &$hash_vm = array()) {
        $slice = array_slice( $arrays, $i , 1 );
        $news = array_pop( $slice );

        if ($i == count($arrays) - 1) {
            $resp = array();
            foreach ($news as $v) {
                list($vmName , $pmName) = explode(':', $v);
                $resp[] = array($v,'stat' => array($pmName => 1));
            }
            return $resp;
        }
        
        // get combinations from subsequent arrays
        $tmp = Combinations::GenerateAllCombinationsMaxVM($arrays, $max, $i + 1,$hash_vm);
        $result = array();
        
        // concat each array from tmp with each element from $arrays[$i]
        foreach ( $news as $v) {
            list($vmName , $pmName) = explode(':', $v);
            foreach ($tmp as $t) {
                if( !isset($t['stat'][$pmName]) or $t['stat'][$pmName] < $max ){
                    $state = array_merge(array($v), $t) ;
                    $state['stat'][$pmName] = isset($state['stat'][$pmName])? $state['stat'][$pmName]+1 : 1 ;
                    $result[] = $state;
                }
            }
        }
        if($i == 0) 
            foreach ($result as &$value) 
                unset($value['stat']);
        
        return $result;
    }

    static function willOverload($v,$t,$max){
        if (!is_array($t))
            return false;
        list($null , $pmName_new) = explode(':', $v);
        $count = 1;
        foreach ($t as $place) {
            list($null,$pmName) = explode(':', $place);
            //checar se a performance melhora com um array de counters
            if ($pmName_new == $pmName)
                $count++;
        }
        return ($count > $max);
    }

    static function filterCombinationsByMaxVm($combinations, $max) {
        foreach ($combinations as $key => $combination) {
            list($v, $p) = Combinations::montaVeP(array($combination));
            if (Combinations::hasMoreThen($p, $max)) {
                unset($combinations[$key]);
            }
        }
        return $combinations;
    }
    
    static function montaVeP($matrix) {
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
    static function hasMoreThen($vms,$max){
        foreach ($vms as $v) if($v > $max) return true;
        return false;
    }
}
