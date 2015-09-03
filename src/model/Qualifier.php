<?php

class Qualifier extends Singleton {

	protected $weight = 1;


	public static function getWeight(){
		$class = get_called_class();
		$instance = $class::getInstance();
		return $instance->weight;
	}

}