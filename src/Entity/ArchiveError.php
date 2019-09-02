<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Description of ArchiveError
 *
 * @ORM\Embeddable
 * @author vinosa
 */
class ArchiveError implements OneOrMoreArchiveErrors {
    //put your code here
    /** @ORM\Column(name="source",type = "string") */
    private $source;
    /** @ORM\Column(name="type",type = "string") */
    private $type;
    /** @ORM\Column(name="description",type = "string") */
    private $description;
    /** @ORM\Column(name="message",type = "string") */
    private $message ;
    private $file = null;
    
    public function __construct(string $description,string $message="")
    {
        $this->description = $description;
        $this->message = $message ;
    }
    
    public function description(): string
    {
        return $this->description ;
    }
    
    public function message(): string
    {
        return $this->message ;
    }
    
    public function source(): string
    {
        return $this->source ;
    }
    
    public function type(): string
    {
        return $this->type ;
    }
    
    public function file(): ?PacketFile
    {
        return $this->file ;
    }
    
    public function setSource(string $source): OneOrMoreArchiveErrors
    {
        $this->source = $source ;
        return $this ;
    }
    
    public function setType(string $type): OneOrMoreArchiveErrors
    {
        $this->type = $type ;
        return $this ;
    }
    
    public function items(): array
    {
        return [$this];
    }
    
    public function setFile(PacketFile $file): OneOrMoreArchiveErrors
    {
        $this->file = $file;
        return $this ;
    }
    
    public function hasErrors(): bool
    {
        return true ;
    }
    
    public function __toString()
    {
        $str = $this->description ;
        if(!empty($this->type)){
            $str .= "-" . $this->type;
        }
        if(!empty($this->source)){
            $str .= "-" . $this->source;
        }
        return $str ;
    }
    
    public function archiveStatus(): ArchiveStatus
    {
        if($this->type == ErrorType::HTTP){
            return new ArchiveStatus(ArchiveStatus::INCOMPLETE);
        }
        if($this->type == ErrorType::VALIDATION){
            return new ArchiveStatus(ArchiveStatus::INVALID);
        }
        return new ArchiveStatus(ArchiveStatus::ERROR);
    }
}
