<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Model\Filesystem;

/**
 * Description of FilesystemItem
 *
 * @author vinosa
 */
class FilesystemItem {
    //put your code here
    private $type;
    private $filepath;
    private $filename;
    private $extension;
    private $dirpath ;
    
    public function __construct(array $info)
    {
        $this->type = $info["type"];
        $this->filepath = $info["path"];
        $this->filename = $info["basename"];
        if(isset($info["extension"])){
            $this->extension = $info["extension"];
        }
        if(isset($info["dirname"])){
            $this->dirpath = $info["dirname"];
        }
    }
    
    public function isFile(): bool
    {
        return $this->type == "file";
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
        return $this->dirpath ;
    }
    
    public function __toString() {
        return $this->filepath();
    }
    
    public function isDirectory(): bool
    {
        return $this->type == "dir";
    }
    
    public function basename(): string
    {
        return $this->filename ;
    }
}
