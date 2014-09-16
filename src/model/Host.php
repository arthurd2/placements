<?php
class Host
{
    private $freeDimensions;
    private $dimensions;
    private $VMs = array();
    
    function __construct($dimensions) {
        $this->dimensions = $dimensions;
        $this->freeDimensions = $dimensions;
    }

    public function getVMs() {
        return $this->VMs;
    }
    
    public function getFreeDimensions() {
        return $this->freeDimensions;
    }
    
    public function getDimensions() {
        return $this->dimensions;
    }

    public function fitVM(VM $VM) {
        foreach ($this->freeDimensions as $dimension => $freeSpace) {
            if ($freeSpace < $VM->getDimension($dimension)) {
                return false;
            }
        }
        return true;
    }
    
    public function storeVM($VM) {
        if ($this->fitVM($VM)) {
            try {
                $this->substractVMDimesions($VM);
                $this->VMs[] = $VM;
                return true;
            }
            catch(Exception $e) {
                return false;
            }
        }
        return false;
    }
    
    private function substractVMDimesions($VM) {
        foreach ($this->freeDimensions as $dimension => $freeSpace) {
            $substract = $VM->getDimension($dimension);
            if ($freeSpace < $substract) {
                throw new Exception("VMTooBigToStore");
            } else {
                $this->freeDimensions[$dimension]-= $substract;
            }
        }
    }
}
