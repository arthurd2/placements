<?php

final class RulesFreeOfContext extends HandlerSingleton
{
    static function add($class) {
        $interfaceClass = 'RuleFreeOfContext';
        $extendsClass = 'Rule';

        $implements = class_implements($class);
        if (in_array($interfaceClass, $implements) and is_subclass_of($class, $extendsClass)) parent::add($class);
        else throw new Exception("Class '$class' does not: implements '$interfaceClass' or extends '$extendsClass'", 1);
    }
    
    public static function isAllowed($vm, $pm) {
        $rules = RulesFreeOfContext::getClasses();
        
        foreach ($rules as $class) {
        	$allowed = $class::isAllowed($vm, $pm);
        	$isEnable = $class::isEnable();
            if ($isEnable and !$allowed) return false;
        }
        return true;
    }
}
