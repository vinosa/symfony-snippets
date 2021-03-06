<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Model\Workflow;

use App\Model\Errors\ActionError ;
/**
 * Description of StopOnFail
 *
 * @author vinosa
 */
class StopOnFail extends ActionDecorator  {
    //put your code here
    public function __construct(ActionInterface $action) {
        
        parent::__construct($action);
        if($action->failed()){
            throw new ActionError($action);
        }
    }
}
