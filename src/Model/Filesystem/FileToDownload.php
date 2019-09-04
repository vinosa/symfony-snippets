<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Model\Filesystem;

/**
 * Description of FileToDownload
 *
 * @author vinosa
 */
class FileToDownload {
    //put your code here
    private $file;
    private $url;
    
    public function __construct(PacketFile $file,string $url) 
    {
       $this->file = $file ;
       $this->url = $url ;
    }
    
    public function file(): PacketFile
    {
        return $this->file ;
    }
    
    public function url(): string
    {
        return $this->url ;
    }
}
