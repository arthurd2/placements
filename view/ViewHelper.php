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
	static function getPmControlButtons($num){
		$resp = "";
		$fmt = "<button onclick='changeValue(%s)'>%s</button>\n";
		$range = range(1, $num);
		foreach ($range as $key => $value) {
			$resp .= sprintf($fmt, $value, "PM $value");
		}
		return $resp;
	}
}