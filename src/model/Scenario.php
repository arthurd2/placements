<?php
class Scenario
{
    public $apr;
    public $nvms;
    public $npms;
    public $scenario;
    
    private function __construct() {
    }
    
    static function geraPlacements($apr, $nvm, $npm) {
        
        //Bloco que gera um array com a distribuição inicial de Placements
        //igual a $apr - Ex. 0.75 e 100PM geram 75 placements possiveis
        $numPlacements = floor($npm * $apr);
        $allow = array_fill(0, $numPlacements, true);
        $denied = array_fill($numPlacements, $npm - $numPlacements, false);
        $places = array_merge($allow, $denied);
        $rPM = array();
        $rVM = array();
        $result = array();
        
        foreach (range(0, $nvm - 1) as $vm) {
            shuffle($places);
            foreach ($places as $key => $place_allowed) {
                if ($place_allowed) {
                    $nvm = "v$vm";
                    $npm = "p$key";
                    $result[$nvm][] = "$nvm:$npm";
                    $rPM[$npm] = isset($rPM[$npm]) ? $rPM[$npm] + 1 : 1;
                    $rVM[$nvm] = isset($rVM[$nvm]) ? $rVM[$nvm] + 1 : 1;
                }
            }
        }
        return array($result, $rVM, $rPM);
    }
    
    static function geraScenarios($apr, $nvms, $npms) {
        $retorno = array();
        foreach ($nvms as $nvm) {
            foreach ($npms as $npm) {
                $scenario = array();
                $scenario['apr'] = $apr;
                $scenario['nvms'] = $nvm;
                $scenario['npms'] = $npm;
                list($places, $r_vm, $r_pm) = Scenario::geraPlacements($apr, $nvm, $npm);
                $scenario['placements'] = $places;
                
                //Resumo: array cuja key é o nome da VM e o valor é o numero de placements possiveis que ela pode estas
                $scenario['rvm'] = $r_vm;
                
                //Resumo: array cuja key é o nome da PM e o valor é o numero de VM que ela teoricamente pode hospedar
                $scenario['rpm'] = $r_pm;
                $retorno["r-$apr,v-$nvm,p-$npm"] = $scenario;
            }
        }
        return $retorno;
    }
    
    static function buildScenarioByPlacements(&$placements) {
        $scenario['rpm'] = array();
        $scenario['rvm'] = array();
        
        foreach ($placements as $vms) {
            foreach ($vms as $place) {
                list($nameVM, $namePM) = explode(':', $place);
                $scenario['rpm'][$namePM] = isset($scenario['rpm'][$namePM]) ? $scenario['rpm'][$namePM] + 1 : 1;
                $scenario['rvm'][$nameVM] = isset($scenario['rvm'][$nameVM]) ? $scenario['rvm'][$nameVM] + 1 : 1;
                $scenario['placements'][$nameVM][] = "$nameVM:$namePM";
            }
        }
        $scenario['nvms'] = count($scenario['rvm']);
        $scenario['npms'] = count($scenario['rpm']);
        return $scenario;
    }
    
    static function getFilledArrayWithTrue($placements) {
        $resp = array();
        foreach ($placements as $vm => $places) foreach ($places as $place) {
            list($nvm, $npm) = explode(':', $place);
            $resp[$nvm][$npm] = true;
        }
        return $resp;
    }
    
    static function toDataTableJSON($scenario) {
        $resp = array();
        $pms = array_keys($scenario['rpm']);
        $vms = array_keys($scenario['rvm']);
        $places = Scenario::getFilledArrayWithTrue($scenario['placements']);
        $resp['cols'][] = array('label' => 'VMs', 'type' => 'string');
        
        foreach ($pms as $pmName) {
            $resp['cols'][] = array('label' => $pmName, 'type' => 'boolean');
        }
        foreach ($vms as $v) {
            $rows = array();
            $rows[] = array('v' => $v);
            foreach ($pms as $p) $rows[] = array('v' => isset($places[$v][$p]));
            $resp['rows'][] = array('c' => $rows);
        }
        return json_encode($resp);
    }
    static function getScenarioFromJSON($json) {
        $table = json_decode($json, true);
        unset($table['cols'][0]);
        foreach ($table['cols'] as $k => $v) {
            $pms[] = $v['label'];
        }
        $placements = array();
        foreach ($table['rows'] as $r => $row) {
            $tmp = array_shift($row['c']);
            $vm = $tmp['v'];
            foreach ($row['c'] as $k => $c) {
                if ($c['v'] == 1) {
                    $pm = $pms[$k];
                    $placements[$r][] = "$vm:$pm";
                }
            }
        }
        
        return Scenario::buildScenarioByPlacements($placements);
    }
    
    static function getScenarioFromVMWare() {
    }
    
    static function loadVMWareTree() {
        //https://www.vmware.com/support/developer/vc-sdk/visdk41pubs/ApiReference/vim.VirtualMachine.html
        //http://pubs.vmware.com/vsphere-60/index.jsp?topic=/com.vmware.wssdk.apiref.doc/index.html&single=true&__utma=207178772.1811249502.1438681066.1438681066.1438681066.1&__utmb=207178772.0.10.1438681066&__utmc=207178772&__utmx=-&__utmz=207178772.1438681066.1.1.utmcsr=(direct)|utmccn=(direct)|utmcmd=(none)&__utmv=-&__utmk=104578819
        require_once "libs/vmwarephp/Bootstrap.php";

        $cache = memcache_connect('localhost', 11211);
        $vmware = new \Vmwarephp\Vhost(getenv('VMHOST'), getenv('VMUSER'), getenv('VMPASS'));
        $vms = $cache->get('vms');
        if ($vms == false) { 
            //'name', 'summary','network','datastore', 'config'
            $virtualMachines = array();
            $virtualMachines = $vmware->findAllManagedObjects('VirtualMachine', array('name', 'network', 'datastore'));
            foreach ($virtualMachines as $vm) {
                $newVM['name'] = $vm->name;
                
                //$newVM->cpus = $vm->summary->config->numCpu
                $newVM['memory'] = $vm->config->hardware->memoryMB;
                
                //$newVM->annotation = $vm->config->annotation
                $newVM['uuid'] = $vm->config->uuid;
                
                $newVM['networks'] = array();
                foreach ($vm->network as $network) 
                    $newVM['networks'][] = $network->name;
                
                $newVM['datastores'] = array();
                foreach ($vm->datastore as $datastore) 
                    $newVM['datastores'][] = $datastore->info->name;
                
                /*
                $newVM->disco = 0
                $devs = $vm->config->hardware->device
                foreach ($devs as $dev) {
                    if (isset($dev->capacityInKB)) $newVM->disco+= ($dev->capacityInKB / (1024 * 1024))
                }
                */
                $vms[$newVM['uuid']] = $newVM;
            }
            $cache->set('vms', $vms, false, 259200);
        }
        $pms = $cache->get('pms');
        if (!$pms) {
            //'name', 'network', 'datastore', 'hardware'
            $physicalMachines = $vmware->findAllManagedObjects('HostSystem', array('name'));
            
            foreach ($physicalMachines as $pm) {
                $newPM = array();
                $newPM['name'] = $pm->name;
                //$newPM['uuid'] = $pm->config->uuid
                $newPM['networks'] = array();
                foreach ($pm->network as $network) {
                    $newPM['networks'][] = $network->name;
                }
                $newPM['datastores'] = array();
                foreach ($pm->datastore as $datastore) {
                    $newPM['datastores'][] = $datastore->info->name;
                }
                $newPM['memory'] = intval($pm->hardware->memorySize / (1024 * 1024));
                
                $pms[$newPM['name']] = $newPM;
            }
            $cache->set('pms', $pms, false, 259200);
        }
        return array('vms'=>$vms,'pms'=>$pms);
    }
}
