<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Model\Workflow;

/**
 * Description of StopOnFail
 *
 * @author vinosa
 */
class StopOnFail extends Action  {
    //put your code here
    public function __construct(ActionInterface $action) {
        $this->errors = $action->errors() ;
        if($action->hasErrors()){
            throw new ArchiveException($action->errors());
        }
    }
}
