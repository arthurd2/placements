<?php
class Status
{
    
    /**
     * TODO
     * Construtor
     * Recebe os dados e deve montar a matrix de placement
     * @param [type] $hosts
     * @param [type] $vms
     * @param [type] $mergedRule
     */
    function Status($hosts, $vms, $mergedRule) {
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
     * getSpecialVMs
     * 	Return the VMs with least possibilities
     * @return VM[]
     */
    private function getSpecialVMs(&$hosts, &$vms, &$rule) {
        foreach ($hosts as $host) {
            foreach ($vms as $vm) {
                            
                
            }
        }
    }
    
    /**
     * TODO
     * Retorna o mapeamento Hosts x VMs
     * @return Hosts[]
     */
    function getPlacements() {
    }
}
