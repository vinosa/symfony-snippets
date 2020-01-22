<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Model\Deposits;

/**
 * Description of DepositIdentifier
 *
 * @author vinosa
 */
class DepositIdentifier {
    //put your code here
    /**
     *
     * @var string
     */
    private $value;
    /**
     *
     * @var string
     */
    private $type;
       
    public function setValue(string $value){
        $this->value = $value ;
    }
    
    public function setType($type)
    {
        $this->type = $type ;
    }
    
    public function type(): string
    {
        return $this->type ;
    }
    
    public function __toString() {
        return $this->value;
    }
}
