<?php
class Rule
{
    protected $status = true;
    protected $weight = 1;
    protected static $instance = null;

    protected function __construct(){ }

    public static function getInstance()
    {
        if ( Rule::$instance === null)
            Rule::$instance = new Rule();
        
        return Rule::$instance;
    }
    //TODO Regra não tem peso porra!
    public static function getWeight() {
        return Rule::getInstance()->weight;
    }
    public static function isEnable() {
        return Rule::getInstance()->status;
    }
    public static function enable() {
        Rule::getInstance()->status = true;
    }
    public static function disable() {
        Rule::getInstance()->status = false;
    }
    public static function load(){}
}
