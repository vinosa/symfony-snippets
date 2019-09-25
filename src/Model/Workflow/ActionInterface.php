<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace App\Model\Workflow;

use App\Model\Errors\OneOrMoreArchiveErrors ;
use App\Model\Filesystem\PacketFile;
/**
 *
 * @author vinosa
 */
interface ActionInterface {
    //put your code here
    public function oneOrMoreErrors(): OneOrMoreArchiveErrors ;
    public function setSource(string $source): ActionInterface;   
    public function setType(string $type): ActionInterface;   
    public function setFile(PacketFile $file): ActionInterface;
    public function appendToLog(string $log);
    public function log(): string;
    public function failed(): bool;   
    public function fail(): ActionInterface;
    public function setContent(string $description,string $message=""): ActionInterface;
}
