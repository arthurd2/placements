<?php
class Costs extends HandlerSingleton
{
    protected $mainCostClass = null;
    protected $interfaceClass = 'InterfaceCost';
    protected $extendsClass = 'Cost';

    static function getCost(&$cvmp) {
        $costs = Costs::getInstance();
        $class = $costs->mainCostClass;
        $cost = (is_null($class))? 1 : $class::getCost($cvmp) ;

        return $cost;
        
    }

    static function add($class) {
        parent::add($class);
        $costs = Costs::getInstance();
        $costs->mainCostClass = $class;
        return true;
    }
    static function del($class) {
        parent::del($class);
        $costs = Costs::getInstance();
        $costs->mainCostClass = array_pop(Costs::getClasses());
        return true;
    }
}
