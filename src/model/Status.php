<?php
class Status extends rule
{
    
    /**
     * Construtor
     * Recebe os dados e deve montar a matrix de placement
     * @param [type] $hosts
     * @param [type] $vms
     * @param [type] $mergedRule
     */
    function __construct($hosts, $vms, $rule) {
        $this->matrix = $rule->matrix;
        foreach ($hosts as $host) {
            $this->hosts[$host->getName() ] = $host;
            foreach ($vms as $vm) {
                $this->vms[$vm->getName() ] = $vm;
                if ($rule->isPermited($host->getName(), $vm->getName())) {
                    $vm->setPossibleHost($host);
                    $host->setPossibleVM($vm);
                }
            }
        }
    }
    
    /**
     * getSpecialVMs
     * 	Return the VMs with least possibilities
     * @return VM[]
     */
    private function getSpecialVMs() {
        
        if (empty($this->vms)) return array();
        
        $index = array();
        $min = count($this->hosts);
        foreach ($this->vms as $vm) {
            $qtd = $vm->getQtdHosts();
            $index[$qtd][] = $vm;
            $min = ($qtd < $min && $qtd > 0) ? $qtd : $min;
        }
        return $index[$min];
    }
    
    /**
     * TODO
     * getStatusAfterStore
     * Considerando que a VM passada será armazenada no Host passado, deve retornar um novo status atualizado.
     * Para cada VM que poderia ser alocado no host, ele verifica se ainda serve no mesmo.
     * @param  Host $host
     * @param  VM $vm
     * @return Array[]
     */
    function getStatusAfterStore(&$host, &$vm) {
    }
    
    /**
     * TODO
     * Retorna o mapeamento Hosts x VMs
     * @return Hosts[]
     */
    function getPlacements() {
    }
}
