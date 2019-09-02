<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
/**
 * Description of FileInfo
 * @ORM\Embeddable
 * @author vinosa
 */
class FileInfo {
    //put your code here
    /** @ORM\Column(name="path",type = "string") */
    private $filepath;
    /** @ORM\Column(name="name",type = "string") */
    private $filename;
    /** @ORM\Column(name="extension",type = "string") */
    private $extension;
    
    public function __construct(string $filepath,string $filename="",string $extension="") {
        $this->filepath = $filepath;
        if(empty($filename)){
            $parts = explode("/",$filepath);
            $filename = end($parts);
        }
        $this->filename = $filename ;
        if(empty($extension)){
            $parts = explode(".",$filename);
            $extension = end($parts);
        }
        $this->extension = $extension ;
    }
    
    public function filepath(): string
    {
        return $this->filepath ;
    }
    
    public function filename(): string
    {
        return $this->filename ;
    }
    
    public function extension(): string
    {
        return $this->extension ;
    }
    
    public function dirpath(): string
    {
        $parts = explode("/",$this->filepath());
        array_pop($parts);
        return implode("/",$parts);
    }
    
    public function __toString() {
        return $this->filepath();
    }
}
