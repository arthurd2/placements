<?php

final class RulesSensitiveToTheContext extends HandlerSingleton
{
    protected $interfaceClass = 'RuleSensitiveToTheContext';
    protected $extendsClass = 'Rule';
    
    public static function isAllowed($cvmp) {
        $rules = RulesSensitiveToTheContext::getClasses();
        
        foreach ($rules as $class) {
            $allowed = $class::isAllowed($cvmp);
            $isEnable = $class::isEnable();
            if ($isEnable and !$allowed) return false;
        }
        return true;
    }
}
