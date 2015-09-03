<?php

final class RulesSensitiveToTheContext extends HandlerSingleton
{
    static function add($class) {
        $interfaceClass = 'RuleSensitiveToTheContext';
        $extendsClass = 'Rule';

        $implements = class_implements($class);
        if (in_array($interfaceClass, $implements) and is_subclass_of($class, $extendsClass)) parent::add($class);
        else throw new Exception("Class '$class' does not: implements '$interfaceClass' or extends '$extendsClass'", 1);
    }
    
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
