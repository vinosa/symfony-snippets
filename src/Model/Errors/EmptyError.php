<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Model\Errors;

/**
 * Description of EmptyError
 *
 * @author vinosa
 */
class EmptyError extends OneOrMoreArchiveErrorsDecorator {
    //put your code here
      
    public function __construct() {
        $this->decorable = new ArchiveErrors();
    }
    
    public function hasErrors(): bool 
    {
        return false ;
    }
}
