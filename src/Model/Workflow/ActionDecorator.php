<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Model\Workflow;

use App\Model\Filesystem\PacketFile;
use App\Model\Errors\OneOrMoreArchiveErrors;
/**
 * Description of ActionDecorator
 *
 * @author vinosa
 */
abstract class ActionDecorator implements ActionInterface {
    //put your code here
    /**
     *
     * @var ActionInterface 
     */
    protected $decorable;
    
    public function __construct(ActionInterface $action = null,string $log = "") {
        if(is_null($action)){
            $action = new Action();
        }
        $this->decorable = $action;
        $this->appendToLog($log);
    }
    
    public function setSource(string $source): ActionInterface
    {
        $this->decorable->setSource($source);
        return $this ;
    }
    
    public function setType(string $type): ActionInterface
    {
        $this->decorable->setType($type);
        return $this ;
    }
    
    public function setFile(PacketFile $file): ActionInterface
    {
        $this->decorable->setFile($file);
        return $this ;
    }
    
    
    public function oneOrMoreErrors(): OneOrMoreArchiveErrors 
    {
        return $this->decorable->oneOrMoreErrors();
    }
    
    public function appendToLog(string $log)
    {
        $this->decorable->appendToLog($log);
    }
    
    public function log(): string
    {
        return $this->decorable->log();
    }
    
    public function failed(): bool
    {
        return $this->decorable->failed();
    }
    
    public function fail(): ActionInterface
    {
        $this->decorable->fail();
        return $this;
    }
    
    public function setContent(string $description,string $message=""): ActionInterface
    {
        $this->decorable->setContent($description, $message);
        return $this;
    }
    
    public function __toString() {
        return $this->getLog();
    }
}
