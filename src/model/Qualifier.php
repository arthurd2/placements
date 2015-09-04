<?php
class Qualifier extends Singleton
{
    
    protected $weight = 1;
    
    public static function getWeight() {
        $class = get_called_class();
        $instance = $class::getInstance();
        return $instance->weight;
    }
    
    final static function _evaluate(&$cvmp) {
        $class = get_called_class();
        $evaluation = $class::evaluate($cvmp);
        //TODO test values > 0 & <=2
        //TODO test existance of all vms 
        if ($cvmp['nvms'] == count($evaluation)) {
            //$cvmp['qualifications']['values'] = $evaluation;
            return $evaluation;
        } 
        else {
            Qualifiers::del($class);
            throw new Exception("Num of Evaluations from class '$class' is different from number of VMs.", 1);
        }
    }
}
