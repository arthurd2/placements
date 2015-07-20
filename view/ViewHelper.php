<?php

class ViewHelper {
	
	static function printState($states){
		$resp = '';
		$fmt = '%s<br>';
		$i = 1;
		foreach ($states as $key => $value) {
			$resp .= sprintf($fmt,"Possibility ".$i++.":  => ".implode(' | ', $value));
		}
		return $resp;
	}
}