<?php
class Rule
{
    protected $matrix;
    
    function __construct($matrix = null) {
        $this->matrix = $matrix;
    }
    
    function setMatrix($matrix) {
        if (!is_null($this->matrix)) return false;
        $this->matrix = $matrix;
        return true;
    }
    
    function isPermited($host, $vm) {
        if (is_null($this->matrix)) throw new Exception("TestingWithoutMatrix", 1);
        
        return isset($this->matrix[$host][$vm]) ? $this->matrix[$host][$vm] : false;
    }
    
    static function mergeRules(&$hosts, &$VMs, &$rules) {
        $mergedRules = array();
        foreach ($hosts as $host) {
            foreach ($VMs as $vm) {
                $mergedRules[$host->getName() ][$vm->getName() ] = Rule::_mergeRule($host, $vm, $rules);
            }
        }
        return new Rule($mergedRules);
    }
    static private function _mergeRule(&$host, &$vm, &$rules) {
        foreach ($rules as $rule) {
            if (!$rule->isPermited($host->getName(), $vm->getName())) {
                return false;
            }
        }
        return true;
    }
}
