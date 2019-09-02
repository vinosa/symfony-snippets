<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
/**
 * Description of ArchiveStatus
 *
 * @ORM\Embeddable
 * @author vinosa
 */
class ArchiveStatus {
    //put your code here
    const PENDING = "pending" ;
    const QUEUED = "queued";
    const INCOMPLETE = "incomplete";
    const INVALID = "invalid";
    const READY = "ready" ;
    const SIGNALLED = "signalled";
    const ERROR = "error";
    
    /**
     * @var string
     *
     * @ORM\Column(name="status_id", type="string", length=10, precision=0, scale=0, nullable=false, unique=false)
     */
    private $id = "queued";

    /**
     * @var string
     *
     * @ORM\Column(name="status_title", type="string", length=255, precision=0, scale=0, nullable=true, unique=false)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="status_detail", type="string", length=255, precision=0, scale=0, nullable=true, unique=false)
     */
    private $detail;
    private $validIds = [self::PENDING,self::QUEUED,self::ERROR,self::INCOMPLETE,self::INVALID,self::READY,self::SIGNALLED];
    
    public function __construct(string $id,string $title="",string $detail="")
    {
        if(!in_array($id, $this->validIds)){
            throw new InvalidArgumentException("invalid status ID for Archive");
        }
        $this->id = $id;
        $this->title = $title ;
        $this->detail = $detail;
    }
    
    public function id(): string
    {
        return $this->id ;
    }
    
    public function ready(): self
    {
        $this->id = self::READY;
        return $this;
    }
    
    public function queued(): self
    {
        $this->id = self::QUEUED;
        return $this;
    }
    
    public function incomplete(): self
    {
        $this->id = self::INCOMPLETE;
        return $this;
    }
    
    public function invalid(): self
    {
        $this->id = self::INVALID;
        return $this;
    }
    
    public function signalled(): self
    {
        $this->id = self::SIGNALLED;
        return $this;
    }
    
    public function isQueued(): bool
    {
        return $this->id === self::QUEUED;
    }
    
    public function isIncomplete(): bool
    {
        return $this->id === self::INCOMPLETE;
    }
    
    public function isInvalid(): bool
    {
        return $this->id === self::INVALID;
    }
    
    public function isSignalled(): bool
    {
        return $this->id === self::SIGNALLED;
    }
    
    public function isError(): bool
    {
        return $this->id === self::ERROR;
    }
    
    public function isQueueable(): bool
    {
        return $this->isIncomplete() || $this->isInvalid() || $this->isSignalled() || $this->isError() ;
    }
    
    public function patched(ArchiveStatus $patch): ArchiveStatus
    {
        if($patch->isSignalled()){
            $this->signalled();
        }
        if($patch->isQueued() && $this->isQueueable()){
            $this->queued();
        }
        return $this ;
    }
    
   
}
