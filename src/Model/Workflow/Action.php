<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Model\Workflow;

use App\Entity\ArchiveError;
use App\Model\Errors\OneOrMoreArchiveErrors;
use App\Model\Filesystem\PacketFile;
use App\Model\Errors\ApplicationError ;
/**
 * Description of Action
 *
 * @author vinosa
 */
class Action implements ActionInterface {
    protected $message;
    protected $description;
    protected $file = null;
    protected $source = "";
    protected $type = "";
    protected $failed = false;
    protected $log = "";
    
    public function __construct(string $log = ""){
        $this->log = $log;
    }
    
    public function setContent(string $description,string $message=""): ActionInterface
    {
        $this->description = $description;
        $this->message = $message ;
        return $this ;
    }
    
    public function setSource(string $source): ActionInterface
    {
        $this->source = $source;
        return $this ;
    }
    
    public function setType(string $type): ActionInterface
    {
        $this->type = $type;
        return $this ;
    }
    
    public function setFile(PacketFile $file): ActionInterface
    {
        $this->file = $file;
        return $this ;
    }
    
    public function appendTolog(string $log)
    {
        $this->log .= " " . $log;
    }
    
    public function oneOrMoreErrors(): OneOrMoreArchiveErrors
    {  
        if(!$this->failed){
            throw new ApplicationError("succeed action can not have errors");
        }
        return (new ArchiveError($this->description,$this->message))->setType($this->type)->setSource($this->source)->setFile($this->file);
    }
        
    public function log(): string
    {
        return $this->log;
    }
    
    public function failed(): bool
    {
        return $this->failed;
    }
    
    public function fail(): ActionInterface
    {
        $this->failed = true;
        return $this;
    }
}
