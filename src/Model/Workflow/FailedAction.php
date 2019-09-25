<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Model\Workflow;

/**
 * Description of FailedAction
 *
 * @author vinosa
 */
class FailedAction extends ActionDecorator {
    //put your code here
    
    public function hasErrors(): bool
    {
        return true ;
    }
    
    public function __construct(ActionInterface $action = null,string $description="",string $message="",$log = "failed ") {
        parent::__construct($action->fail()->setContent($description, $message), $log . $message);
    }
}
