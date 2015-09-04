<?php

class Singleton {

	protected static $instance = [];

    public static function getInstance()
    {
    	$called = get_called_class();
        if ( !isset($called::$instance[$called])){
            $called::$instance[$called] = new $called();
        }
        
        return $called::$instance[$called];
    }
}