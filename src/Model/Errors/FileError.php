<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Model\Errors;

/**
 * Description of FileError
 *
 * @author vinosa
 */
class FileError extends OneOrMoreArchiveErrorsDecorator {
    
    public function __construct(PacketFile $file, OneOrMoreArchiveErrors $errors) {
        parent::__construct($errors->setFile($file));
    }
    
}
