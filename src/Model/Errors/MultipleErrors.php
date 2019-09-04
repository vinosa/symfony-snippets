<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Model\Errors;

/**
 * Description of MultipleErrors
 *
 * @author vinosa
 */
class MultipleErrors extends OneOrMoreArchiveErrorsDecorator {
    
    public function __construct() {
        $this->decorable = new ArchiveErrors();
    }
    
    public function addError(OneOrMoreArchiveErrors $error)
    {
        $this->decorable->addOneOrMoreErrors($error);
    }   
}
