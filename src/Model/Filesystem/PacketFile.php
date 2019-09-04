<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Model\Filesystem;

use League\Flysystem\Filesystem;
use App\Entity\FileInfo ;

/**
 * Description of File
 *
 * @author vinosa
 */
class PacketFile {
    /**
     *
     * @var Filesystem 
     */
    private $filesystem;
    /**
     *
     * @var FileInfo 
     */
    private $fileinfo;
    /**
     *
     * @var DirectoryTreeNode 
     */
    private $directory;
    
    public function __construct(Filesystem $filesystem, DirectoryTreeNode $directory,string $filename,string $extension = "") {
        $this->filesystem = $filesystem;
        $this->directory = $directory ;
        $this->fileinfo = new FileInfo((string) $directory . "/" . $filename, $filename, $extension) ;
    }
    
    public function contents()
    {
        return $this->filesystem->read($this->fileinfo->filepath());
    }
    
    public function withContents(string $contents): PacketFile
    {
        $this->filesystem->put($this->fileinfo->filepath(), $contents) ;
        return $this ;
    }
    
    public function directory(): DirectoryTreeNode
    {
        return $this->directory ;
    }
           
    public function isImage(): bool
    {
        return in_array($this->fileinfo->extension(), ["jpg","jpeg","png"]);
    }
    
    public function isPdf(): bool
    {
        return $this->fileinfo->extension() === "pdf";
    }
    
    public function isXml(): bool
    {
        return $this->fileinfo->extension() === "xml";
    }
    
    public function isJpg(): bool
    {
        return $this->fileinfo->extension() === "jpg";
    }
    
    public function __toString() {
        return $this->contents() ;
    }
    
    public function md5(): string
    {
        return md5($this->contents()) ;
    }
       
    public function fileinfo(): FileInfo
    {
        return $this->fileinfo ;
    }
    
}
