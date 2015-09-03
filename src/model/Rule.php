<?php
class Rule extends Singleton
{
    protected $status = true;
    protected $weight = 1;
    
    protected function __construct() {}
    
    public static function isEnable() {
        $class = get_called_class();
        return $class::getInstance()->status;
    }
    public static function enable() {
        $class = get_called_class();
        $class::getInstance()->status = true;
    }
    public static function disable() {
        $class = get_called_class();
        $class::getInstance()->status = false;
    }
    public static function load() {}
}
