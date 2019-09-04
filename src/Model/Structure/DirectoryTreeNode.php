<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Model\Structure;

use League\Flysystem\Filesystem;
/**
 * Description of PathTree
 *
 * @author vinosa
 */
class DirectoryTreeNode {
    //put your code here
    private $directory ;
    private $id ;
    private $parentNode = null;
    private $childNodes = [];
    
    public function __construct(string $directory,string $id = "")
    {
        $this->directory = str_replace("/", "", $directory) ;
        $this->id = $id ;        
    }
    
    public function appendChild(DirectoryTreeNode $child): DirectoryTreeNode
    {
        $exists = $this->root()->find( $child->id() ); // ID is unique by tree
        if(!is_null($exists)){
            throw new ApplicationError("cannot append child with ID " . $child->id() . " , this id already exists in the tree") ;
        }
        if($this->hasChild($child->directory())){
            throw new ApplicationError("cannot append child with DIR " . $child->directory() . " , this child directory already exists on the node") ;
        }
        $child->parentNode = $this ;
        $this->childNodes[] = $child;
        return $child ;
    }
    
    public function directory(): string
    {      
        return $this->directory ;
    }
    
    public function parent(): ?DirectoryTreeNode
    {
        return $this->parentNode ;
    }
    
    public function root(): DirectoryTreeNode
    {
        $root = $this ;
        while(!is_null($root->parent())){
            $root = $root->parent() ;
        }
        return $root ;
    }
    
    public function id(): string
    {
        return strtolower( $this->id ) ;
    }
    
    public function path(): string
    {
        $directories = [$this->directory()];
        $parent = $this->parent();
        while(!is_null($parent)){
            $directories[] = $parent->directory() ;
            $parent = $parent->parent();
        }
        $directories = array_reverse($directories) ;
        return implode( "/", $directories ) ;
    }
    
    public function children(): array
    {
        return $this->childNodes ;
    }
    
    public function find(string $id): ?DirectoryTreeNode
    {
        if($id == ""){
            return null ;
        }
        if($this->id() === $id){
            return $this ;
        }
        foreach($this->children() as $child){
            $find = $child->find($id);
            if(!is_null($find)){
                return $find ;
            }
        }
        return null ;
    }
    
    
    public function pathToChild(DirectoryTreeNode $node,array $existingPath = []): ?string
    {
        if($this === $node){
            return implode("/", $existingPath ) ;
        }
        foreach($this->children() as $child){
            $childPath = $existingPath ;
            $childPath[] = $child->directory() ;
            $path = $child->pathToChild( $node, $childPath ) ;
            if(!is_null($path)){
                return $path ;
            }
        }
        return null ;
    }
    
    public function pathTo(DirectoryTreeNode $node): ?string
    {
        $path = $this->pathToChild($node, []) ;
        if( !is_null($path) ){
            return $path ;
        }
        $parent = $this->parent() ;
        $existingPath = [];
        while(is_null($path) && !is_null($parent)){
            $existingPath[] = "..";
            $path = $parent->pathToChild($node, $existingPath);
            $parent = $parent->parent() ;
        }
        return $path ;
    }
    
    public function __toString() {
        return $this->path() ;
    }
    
    public function hasChild(string $directory): bool
    {
        foreach($this->children() as $child){
            if( $child->directory() == $directory){
                return true ;
            }
        }
        return false ;
    }
    
    public function allChildren(): array
    {
        $children = [] ;
        foreach($this->children() as $child){
            $children = array_merge($children,[$child], $child->allChildren() );
        }
        return $children ;
    }
    
    public function dynamic(Filesystem $filesystem): DirectoryTreeNode
    {
        foreach($filesystem->listContents($this) as $info){
            $item = new FilesystemItem($info);
            if($item->isDirectory()){
                if($this->hasChild($item->basename())){
                    continue ;
                }
                $this->appendChild(new DirectoryTreeNode($item->basename()));
            }
        }
        foreach($this->children() as $child){
            $child = $child->dynamic($filesystem) ;
        }
        return $this ;
    }
    
    public function child(string $dirname): DirectoryTreeNode
    {
        foreach($this->children() as $child){
            if($child->directory() === $dirname){
                return $child ;
            }
        }
        return $this->appendChild(new DirectoryTreeNode($dirname)) ;
    }
    
    public function pathToFile(PacketFile $file): string
    {
        $path = $this->pathTo($file->directory()) ;
        if(strlen($path)>0){
            $path .= "/";
        }
        $path .= $file->fileinfo()->filename();
        return $path ;
    }
}
