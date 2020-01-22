<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Model\Deposits;

/**
 * Description of SingleDeposit
 *
 * @author vinosa
 */
class DepositEvent {
    //put your code here
    /**
     *
     * @var string 
     */
    private $depositDate;
    /**
     *
     * @var array 
     */
    private $result;
    
    public function setDepositDate(string $date)
    {
        $this->depositDate = $date ;
    }
    
    public function setResult(DepositResult $result)
    {
        $this->result = $result ;
    }
    
    public function result(): DepositResult
    {
        return $this->result ;
    }
}
