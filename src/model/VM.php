<?php
class VM
{
    public $name;
    public $dimensions;
    
    function __construct($name, $dimensions) {
        $this->name = $name;
        $this->dimensions = $dimensions;
    }
    
    public function getName() {
        return $this->name;
    }
    
    public function setName($newName) {
        $this->name = $newName;
    }
    
    public function getDimensions() {
        return $this->dimensions;
    }
    
    public function setDimensions($newDim) {
        $this->dimensions = $newDim;
    }
    
    public function getDimension($dimension_name) {
        return isset($this->dimensions[$dimension_name]) ? $this->dimensions[$dimension_name] : 0;
    }
    
    public function setDimension($name, $value) {
        if (is_float($value) || is_int($value)) {
            $this->dimensions[$name] = $value;
        } else {
            throw new Exception("InvalidDimension");
        }
    }
}

