<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Model\Workflow;

/**
 * Description of Action
 *
 * @author vinosa
 */
abstract class Action implements ActionInterface {
    //put your code here
    /**
     *
     * @var OneOrMoreArchiveErrors 
     */
    protected $errors ;
    
    public function __construct(OneOrMoreArchiveErrors $errors) {
        $this->errors = $errors;
    }
    
    public function hasErrors(): bool 
    {
        return $this->errors->hasErrors() ;
    }
    
    public function errors(): OneOrMoreArchiveErrors 
    {
        return $this->errors ;
    }
}
