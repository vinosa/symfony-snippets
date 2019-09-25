<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Model\Workflow;

use App\Model\Filesystem\PacketFile;
use App\Model\Errors\OneOrMoreArchiveErrors;
use App\Model\Errors\ArchiveErrors ;
/**
 * Description of MultipleActions
 *
 * @author vinosa
 */
class MultipleActions implements ActionInterface  {
    
    private $actions;
    
    public function __construct(array $actions) {
       $this->actions = $actions ;
    }
      
    public function oneOrMoreErrors(): OneOrMoreArchiveErrors 
    {
        $errors = new ArchiveErrors();
        foreach($this->actions as $action){
            if($action->failed()){
                $errors->addOneOrMoreErrors($action->oneOrMoreErrors());
            }
        }
        return $errors ;
    }
    
    public function setSource(string $source): ActionInterface
    {
        array_map(function(ActionInterface $action) use ($source) {$action->setSource($source);},$this->actions);
        return $this;
    }
    
    public function setType(string $type): ActionInterface
    {
        array_map(function(ActionInterface $action) use ($type) {$action->setType($type);},$this->actions);
        return $this;
    }
    
    
    public function setFile(PacketFile $file): ActionInterface
    {
        array_map(function(ActionInterface $action) use ($file) {$action->setFile($file);},$this->actions);
        return $this;
    }
    
    public function appendToLog(string $log)
    {
        array_map(function(ActionInterface $action) use ($log) {$action->appendToLog($log);},$this->actions);
    }
    
    public function log(): string
    {
        return implode("\n",array_map(function(ActionInterface $action){return $action->log();},$this->actions));
    }
    
    public function failed(): bool
    {
        foreach($this->actions as $action){
            if($action->failed()){
                return true;
            }
        }
        return false;
    }
    
    public function fail(): ActionInterface
    {
        $this->actions[0]->fail();
        return $this;
    }
    
    public function setContent(string $description,string $message=""): ActionInterface
    {
        return $this;
    }
    
}
