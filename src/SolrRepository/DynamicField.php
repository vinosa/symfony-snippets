<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\SolrRepository;

/**
 * Description of DynamicField
 *
 * @author vinosa
 */
class DynamicField {
    //put your code here
    private $fields = [];
    
    public static function fromFields(array $fields,string $substring): DynamicField
    {
        $new = new DynamicField();
        foreach($fields as $key => $value){
            if(strpos($key,$substring) === 0){
                $new->fields[substr($key,strlen($substring))] = $value;
            }
        }
        return $new ;
    }
    
    public function field(string $key)
    {
        if(!isset($this->fields[$key])){
            return null ;
        }
        return $this->fields[$key] ;
    }
}
