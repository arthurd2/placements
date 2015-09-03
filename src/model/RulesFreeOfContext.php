<?php

final class RulesFreeOfContext extends HandlerSingleton
{
    protected $interfaceClass = 'RuleFreeOfContext';
    protected $extendsClass = 'Rule';

  
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
