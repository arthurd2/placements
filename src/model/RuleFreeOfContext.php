<?php
interface RuleFreeOfContext {
	static function isAllowed(& $vm , & $pm);
    static function getWeight();
    static function isEnable();
    static function enable();
    static function disable();
}