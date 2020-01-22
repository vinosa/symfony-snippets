<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Model\Deposits;
use App\Entity\ArchiveStatus;
use App\Entity\ArchiveRemoteIdentity ;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * Description of Deposit
 *
 * @author vinosa
 */
class DepositState {
    //put your code here
    /**
     *
     * @var ArrayCollection 
     */
    private $identifiers ;
    /**
     *
     * @var string 
     */
    private $status;
    /**
     *
     * @var ArrayCollection 
     */
    private $deposits ;
    /**
     *
     * @var string 
     */
    private $depositIdentifier;
   
    public function __construct()
    {
        $this->deposits = new ArrayCollection();
        $this->identifiers = new ArrayCollection();
    }
   
    
    public function addDeposits(DepositEvent $deposit)
    {
        $this->deposits->add($deposit);
    }
    
    public function removeDeposits(DepositEvent $deposit)
    {
        $this->deposits->removeElement($deposit);
    }
    
    public function getDeposits()
    {
        return $this->deposits;
    }

    public function setDeposits(array $deposits)
    {
        $this->deposits->clear();
        foreach ($deposits as $dep) {
            $this->addDeposits($dep);
        }
    }
    
    public function addIdentifiers(DepositIdentifier $identifier)
    {
        $this->identifiers->add($identifier);
    }
    
    public function removeIdentifiers(DepositIdentifier $identifier)
    {
        $this->identifiers->removeElement($identifiers);
    }
    
    public function setIdentifiers(array $identifiers)
    {
        $this->identifiers->clear();
        foreach ($identifiers as $id) {
            $this->addIdentifiers($id);
        }
    }
    
    public function setStatus(string $status)
    {
        $this->status = $status;
    }
    
    public function getStatus(): string
    {
        return $this->status ;
    }
    
    public function setDepositIdentifier($depositIdentifier)
    {
        $this->depositIdentifier = $depositIdentifier ;
    }
    
    public function status(): ArchiveStatus
    {
        $status = new ArchiveStatus(strtolower($this->status));
        if($status->isFinished() && $this->identifiers->filter( function(DepositIdentifier $id) { return $id->type()== "ARK";} )->count() === 0){
            return $status->processing();
        }
        return $status ;
    }
        
    public function lastResult(): DepositResult
    {
        return $this->deposits->last()->result() ;
    }
    
    public function __toString() {
        return " DEPOSIT " . $this->depositIdentifier . " with status " . $this->status();
    }
    
    public function remoteIdentity(): ArchiveRemoteIdentity
    {
        return new ArchiveRemoteIdentity($this->identifiers->filter( function(DepositIdentifier $id) { return $id->type()== "ARK";} )->first(),
                                         $this->identifiers->filter( function(DepositIdentifier $id) { return $id->type()== "ARKID";} )->first());
    }
    
}
