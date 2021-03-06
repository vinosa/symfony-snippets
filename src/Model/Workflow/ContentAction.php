<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Model\Workflow;

use App\Entity\ErrorType;
/**
 * Description of ContentAction
 *
 * @author vinosa
 */
class ContentAction extends ActionDecorator {
    //put your code here
    public function __construct(ActionInterface $action = null,string $log = "generating content") {
        parent::__construct($action,$log);
        $this->decorable->setType(ErrorType::CONTENT);
    }
}
