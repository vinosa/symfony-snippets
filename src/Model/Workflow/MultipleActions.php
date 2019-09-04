<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Model\Workflow;

/**
 * Description of MultipleActions
 *
 * @author vinosa
 */
class MultipleActions extends Action  {
    
    public function __construct(array $actions = []) {
        $this->errors = new MultipleErrors();
        foreach($actions as $action){
            $this->errors->addError($action->errors());
        }        
    }
    
    public function setError(MultipleErrors $errors): self
    {
        $this->error = $errors ;
        return $this ;
    }
    
}
