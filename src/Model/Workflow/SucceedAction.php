<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Model\Workflow;

/**
 * Description of CompletedAction
 *
 * @author vinosa
 */
class SucceedAction extends Action {
    //put your code here
    
    public function __construct() {
        $this->errors = new EmptyError() ;
    }
    
    public function hasErrors(): bool
    {
        return false ;
    }
}
