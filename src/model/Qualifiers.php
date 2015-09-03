<?php
class Qualifiers extends HandlerSingleton {
	static function add($class){
        $costs = Qualifiers::getInstance();
        $interfaceClass = 'InterfaceQualifier';
        $extendsClass = 'Qualifier';
        
        $implements = class_implements($class);
        if (in_array($interfaceClass, $implements) and is_subclass_of($class, $extendsClass))
            parent::add($class);
        else 
            throw new Exception("Class '$class' does not: implements '$interfaceClass' or extends '$extendsClass'", 1);
        
	}

    static function getBenefit(& $cvmp){
    	//TODO Test Me
    	if (!isset($cvmp['qualifications']['benefit'])){
    		$evaluations = Qualifiers::getEvaluation($cvmp);
    		$cvmp['qualifications']['benefit'] = array_sum($evaluations);
    	}
  		return $cvmp['qualifications']['benefit'];
    }
    static function getEvaluation(& $cvmp){
        //TODO Test Me
        if (!isset($cvmp['qualifications']['values'])){
        	$qualifiers = Qualifiers::getClasses();
            foreach ($cvmp['vmp'] as $vm => $pm) 
                $cvmp['qualifications']['values'][$vm] =  1 ;
        	foreach ( $qualifiers as $class) {
        		$normEval = $class::evaluate($cvmp);
        		$w = $class::getWeight();
        		foreach ($normEval as $vm => $eval) {
                    $cvmp['qualifications']['values'][$vm] *=  pow($eval, $w);
                }
        	}
        }
        return $cvmp['qualifications']['values'];
    }

    static function getCostBenefit(& $cvmp){
    	//TODO Test Me
    	global $realCvmp;
    	if (!isset($cvmp['qualifications']['cb'])){
    		$candBen = Qualifiers::getBenefit($cvmp);
    		$realBen = Qualifiers::getBenefit($realCvmp);
    		$cvmp['qualifications']['cb'] = ($candBen-$realBen)/Costs::getCost($cvmp);
    	}
       	return $cvmp['qualifications']['cb']  ;
    }
}