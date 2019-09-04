<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Model\Validation;

use App\Entity\ArchiveError;


/**
 * Description of XmlSchemaValidation
 *
 * @author vinosa
 */
class XmlValidation {
   
    
    public function validation(PacketFile $file,string $schemaPath): ActionInterface
    {
        $dom = new \DOMDocument();
        $dom->loadXML((string) $file);
        libxml_use_internal_errors(true);   
        $valid = $dom->schemaValidate($schemaPath); 
        libxml_use_internal_errors(false);   
        $exploded = explode("/",$schemaPath);
        $xmlns = end( $exploded );     
        $error = null ;
        if(!$valid){
            $description = "xml validation failed with " . $xmlns ;
            return new FailedAction(new OpenEditionError(new FileError($file,new ValidationError(new ArchiveError($description)))));
        }
        return new SucceedAction();
    }
    
    public function detailedValidation(PacketFile $file,string $schemaPath): ActionInterface
    {
        libxml_use_internal_errors(true);            
        $dom = new \DOMDocument();
        $dom->loadXML((string) $file);
        $valid = $dom->schemaValidate($schemaPath);     
        $error = null ;
        $message = json_encode(libxml_display_errors()) ;
        libxml_use_internal_errors(false);
        if(!$valid){
            $exploded = explode("/",$schemaPath);
            $xmlns = end( $exploded );
            $description = "xml validation failed with " . $xmlns ;           
            return new FailedAction(new OpenEditionError(new FileError($file,new ValidationError(new ArchiveError($description,$message)))));
        }
        return new SucceedAction();
    }
    
}
function libxml_display_error($error)
{   
    unset($error->file); 
    $error->message = trim($error->message);  
    return $error;
}

function libxml_display_errors() {
    $res = [] ;
    $errors = libxml_get_errors();
    foreach ($errors as $error) {
        $res[] = libxml_display_error($error);
    }
    libxml_clear_errors();
    return $res;
}

