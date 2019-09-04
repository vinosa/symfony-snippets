<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace App\Model\Workflow;

/**
 *
 * @author vinosa
 */
interface ActionInterface {
    //put your code here
    public function hasErrors(): bool ;
    public function errors(): OneOrMoreArchiveErrors ;
}
