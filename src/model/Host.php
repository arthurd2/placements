<?php
class Host {
    private $freeDimensions;
    private $dimensions;
    private $vms = array();
    private $name;

    function __construct($name, array $dimensions) {
        $this->name = $name;
        $this->dimensions = $dimensions;
        $this->freeDimensions = $dimensions;
    }

    public function getVMs() {
        return $this->vms;
    }

    public function getName() {
        return $this->name;
    }

    public function getFreeDimensions() {
        return $this->freeDimensions;
    }

    public function getDimensions() {
        return $this->dimensions;
    }

    public function fitVM(VM $vm) {
        foreach ($this->freeDimensions as $dimension => $freeSpace) {
            if ($freeSpace < $vm->getDimension($dimension)) {
                return false;
            }
        }
        return true;
    }

    public function storeVM($vm) {
        if ($this->fitVM($vm)) {
            try {
                $this->substractVMDimesions($vm);
                $this->vms[] = $vm;
                return true;
            }
            catch(Exception $e) {
                return false;
            }
        }
        return false;
    }

    private function substractVMDimesions($vm) {
        foreach ($this->freeDimensions as $dimension => $freeSpace) {
            $substract = $vm->getDimension($dimension);
            if ($freeSpace < $substract) {
                throw new Exception("VMTooBigToStore");
            } else {
                $this->freeDimensions[$dimension]-= $substract;
            }
        }
    }
}
