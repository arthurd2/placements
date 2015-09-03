<?php
class Costs extends HandlerSingleton
{
    
    protected $mainCostClass = null;
    
    static function getCost(&$cvmp) {
        $costs = Costs::getInstance();
        $class = $costs->mainCostClass;
        $cost = (is_null($class))? 1 : $class::getCost($cvmp) ;

        return $cost;
        
    }
    
//TODO Passar todos os add para o Singleton

    static function add($class) {
        $costs = Costs::getInstance();
        $interfaceClass = 'InterfaceCost';
        $extendsClass = 'Cost';
        
        $implements = class_implements($class);
        if (in_array($interfaceClass, $implements) and is_subclass_of($class, $extendsClass)) {
        	$costs->mainCostClass = $class;
            parent::add($class);
        } 
        else {
            throw new Exception("Class '$class' does not: implements '$interfaceClass' or extends '$extendsClass'", 1);
        }
    }
}
