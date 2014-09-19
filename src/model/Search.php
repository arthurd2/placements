<?php
class Search
{
    
    function getPossibilities(&$hosts, &$vms, &$rules) {
        $mergedRule = Rule::mergeRules($hosts, $vms, $rules);
        $status = new Status($hosts, $vms, $mergedRule);
        $vms = $status->getSpecialVMs();
        foreach ($vms as $vm) {
            $hosts = $vm->getPossibleHosts();
            foreach ($hosts as $host) {
                $this->recursion($status, $host, $vm);
            }
        }
    }
    
    /**
     * Recursion search for the truth
     * @param  Status $status
     * @param  Host $host
     * @param  VM $vm
     * @return null
     */
    private function recursion(&$status, &$host, &$vm) {
        $newStatus = $status->getStatusAfterStore($host, $vm);
        $vms = $newStatus->getSpecialVMs();
        if (empty($place)) $this->storeStatus($newStatus);
        foreach ($vms as $vm) {
            $hosts = $vm->getPossibleHosts();
            foreach ($hosts as $host) {
                $this->recursion($status, $host, $vm);
            }
        }
    }
    
    /**
     * storeStatus armazena as possibilidades que existem de se organizar o datacenter
     * @param  Status $status
     */
    private function storeStatus(&$status) {
        $serialized = serialize($status->getPlacements());
        $md5 = md5($serialized);
        $this->possibilities[$md5] = $serialized;
    }
}
