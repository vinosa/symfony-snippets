<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Model\Errors;

use App\Entity\ArchiveStatus;
/**
 * Description of OneOrMoreArchiveErrorsDecorator
 *
 * @author vinosa
 */
abstract class OneOrMoreArchiveErrorsDecorator implements OneOrMoreArchiveErrors {
    //put your code here
    /**
     *
     * @var OneOrMoreArchiveErrors 
     */
    protected $decorable;
    
    public function __construct(OneOrMoreArchiveErrors $errors) {
        $this->decorable = $errors;
    }
    
    public function setSource(string $source): OneOrMoreArchiveErrors
    {
        $this->decorable->setSource($source);
        return $this ;
    }
    
    public function setType(string $type): OneOrMoreArchiveErrors
    {
        $this->decorable->setType($type);
        return $this ;
    }
    
    public function setFile(PacketFile $file): OneOrMoreArchiveErrors
    {
        $this->decorable->setFile($file);
        return $this ;
    }
    
    public function items(): array 
    {
        return $this->decorable->items();
    }
    
    public function hasErrors(): bool
    {
        return $this->decorable->hasErrors();
    }
    
    public function __toString() {
        return (string) $this->decorable;
    }
    
    public function archiveStatus(): ArchiveStatus
    {
        return $this->decorable->archiveStatus();
    }
}
