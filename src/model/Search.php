<?php
class Search
{
    
    function getPossibilities(&$hosts, &$vms, &$rules) {
        $mergedRule = Rule::mergeRules($hosts, $vms, $rules);
        $status = new Status($hosts, $vms, $mergedRule);
        $places = $status->getSpecialVMs();
        foreach ($places as $place) {
            $this->recursion($status, $place->host, $place->vm);
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
        $new_status = $status->getStatusAfterStore($host, $vm);
        $places = $new_status->getSpecialVMs();
        if (empty($place)) $this->storeStatus($new_status);
        foreach ($places as $place) {
            $this->recursion($status, $place->host, $place->vm);
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
