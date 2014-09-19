<?php
class VM
{
    private $name;
    private $dimensions;
    private $possibleHost;
    
    function __construct($name, $dimensions) {
        $this->name = $name;
        $this->dimensions = $dimensions;
    }
    
    function getName() {
        return $this->name;
    }
    
    function setName($newName) {
        $this->name = $newName;
    }
    
    function getDimensions() {
        return $this->dimensions;
    }
    
    function setDimensions($newDim) {
        $this->dimensions = $newDim;
    }
    
    function getDimension($dimensionName) {
        return isset($this->dimensions[$dimensionName]) ? $this->dimensions[$dimensionName] : 0;
    }
    
    function setDimension($name, $value) {
        if (is_float($value) || is_int($value)) {
            $this->dimensions[$name] = $value;
        } else {
            throw new Exception("InvalidDimension");
        }
    }
    function setPossibleHost(&$host) {
        $this->possibleHost[$host->getName() ] = $host;
    }

    function getQtdHosts(){
        return count($this->possibleHost);
    }

    function getPossibleHosts(){
        return $this->possibleHost;
    }

}

