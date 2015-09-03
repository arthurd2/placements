<?php
interface RuleSensitiveToTheContext  {
	static function isAllowed(& $cvmp);
    static function isEnable();
    static function enable();
    static function disable();
}