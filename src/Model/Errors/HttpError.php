<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Model\Errors;

use App\Entity\ErrorType ;
/**
 * Description of HttpError
 *
 * @author vinosa
 */
class HttpError extends OneOrMoreArchiveErrorsDecorator{
   
    public function __construct(OneOrMoreArchiveErrors $errors) {
        parent::__construct($errors->setType(ErrorType::HTTP));
    }
    
}
