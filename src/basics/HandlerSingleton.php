<?php
class HandlerSingleton {
	protected $classes;
	protected static $instance = [];

	protected function __construct(){
		$this->classes = [];
	}

    public static function getInstance()
    {
    	$called = get_called_class();
        if ( !isset($called::$instance[$called]))
            $called::$instance[$called] = new $called();
        
        return $called::$instance[$called];
    }

	static function add($class){
		$called = get_called_class();
		$instance = $called::getInstance();
		if (class_exists($class))
			$instance->classes[] = $class;
		else
			throw new Exception("Class '$class' does not exists.", 1);
	}

	static function getClasses(){
		$called = get_called_class();
		$instance = $called::getInstance();
		return $instance->classes;
	}
}