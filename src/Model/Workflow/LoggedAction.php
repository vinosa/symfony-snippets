<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Model\Workflow;

use Psr\Log\LoggerInterface;

/**
 * Description of LoggedAction
 *
 * @author vinosa
 */
class LoggedAction extends ActionDecorator {
    //put your code here
    public function __construct(LoggerInterface $logger,ActionInterface $action ) {
        parent::__construct($action);
        $logger->debug($action->log());
    }
}
