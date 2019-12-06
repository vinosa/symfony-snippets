<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace App\Utilities ;
/**
 * Random static utilities functions
 *
 * @author vinogradov
 */
class Utils {
    //put your code here
    /**
     * Produces an array from an object where nested properties
     * have an index based on property name with "." as a separator
     * @param type $input
     * @param string $prefix
     * @return array
     */
    public static function flattenNested($input, string $prefix="",int $start = 0): array
    {
        $result = array();
        foreach ($input as $key => $value)
        {
            if($start>0){
                $result[$key] = self::flattenNested($value,$prefix,$start-1);
                continue;
            }
            if(is_object($value)){
                $ref = new \ReflectionObject($value);
                foreach ($ref->getProperties() as $prop){
                    $prop->setAccessible(true);
                    $propKey = $prop->getName();
                    $propValue = $prop->getValue($value);
                    if(is_array($propValue) || is_object($propValue)){
                        $result = array_merge($result, self::flattenNested($propValue, $prefix . $key . '.' ));
                    }
                    else{
                         $result[$prefix . $key . "." . $propKey] = $propValue;
                    }
                }
            }
            elseif (is_array($value)){
                $result = array_merge($result, self::flattenNested($value, $prefix . $key . '.' ));
            }
            else{
                $result[$prefix . $key] = $value;;
            }
        }   
        return $result;
    }
    /**
     * gets last part of an URL
     * @param string $url
     * @return string
     */
    public static function lastUrlPart(string $url): string
    {
        return basename(parse_url($url,PHP_URL_PATH)) ;
    }
}
