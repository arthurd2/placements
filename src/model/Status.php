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
            $this->hosts[$host->getId() ] = $host;
            foreach ($vms as $vm) {
                $this->vms[$vm->getId() ] = $vm;
                if ($rule->isPermited($host->getId(), $vm->getId())) {
                    $vm->setPossibleHost($host);
                    $host->setPossibleVM($vm);
                }
            }
        }
    }
    
    /**
     * getSpecialVMs
     *  Return the VMs with least possibilities
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
     * getStatusAfterStore
     * Considerando que a VM passada será armazenada no Host passado, deve retornar um novo status atualizado.
     * Para cada VM que poderia ser alocado no host, ele verifica se ainda serve no mesmo.
     * @param  Host $host
     * @param  VM $vm
     * @return Array[]
     */
    function getStatusAfterStore(&$host, &$vm) {
        $vm->setHost($host);
        $vm->removePossibleHost($host);
        $host->storeVM($vm);
        $host->updatePossibleVMs();
        $this->placedVMs[ $vm->getId() ] = $vm;
        $this->placedHosts[ $host->getId() ] = $host;
        unset($this->vms[ $vm->getId() ]);
        unset($this->hosts[ $host->getId() ]);
        return clone ($this);
    }
    
    /**
     * Retorna o mapeamento Hosts x VMs
     * @return Hosts[]
     */
    function getPlacements() {
        return $this->placedHosts;
    }
}
