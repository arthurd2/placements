<?php
class Rule
{
    protected $matrix;
    
    function __construct(&$matrix = null) {
        $this->matrix = $matrix;
    }
    
    function setMatrix(&$matrix) {
        
        if (!is_null($this->matrix)) return false;
        $this->matrix = $matrix;
        return true;
    }
    
    function isPermited(Host &$host, VM &$vm) {
        
        if (is_null($this->matrix)) throw new Exception("TestingWithoutMatrix", 1);
        
        return isset($this->matrix[$host->getId()][$vm->getId()]) ? $this->matrix[$host->getId()][$vm->getId()] : false;
    }
    
    static function mergeRules(&$hosts, &$vms, &$rules) {
        $mergedRules = array();
        foreach ($hosts as &$host) {
            foreach ($vms as &$vm) {
                $mergedRules[ $host->getId() ][ $vm->getId() ] = Rule::mergeRule($host, $vm, $rules);
            }
        }
        return new Rule($mergedRules);
    }
    
    private static function mergeRule(&$host, &$vm, &$rules) {
        foreach ($rules as &$rule) {
            if (!$rule->isPermited($host, $vm)) {
                return false;
            }
        }
        return true;
    }
}
