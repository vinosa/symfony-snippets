<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Model\Workflow;

use App\Model\Filesystem\PacketFile;
/**
 * Description of FileAction
 *
 * @author vinosa
 */
class FileAction extends ActionDecorator{
    //put your code here
    
    public function __construct(PacketFile $file,ActionInterface $action ) {
        parent::__construct($action,"file " . $file->fileinfo());
        $this->decorable->setFile($file);
    }
}
