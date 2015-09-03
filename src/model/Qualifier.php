<?php

class Qualifier {

	protected $weight = 1;

	static function getWeight(){
		$class = get_called_class();
		$instance = $class::getInstance();
		return $instance->weight;
	}

}