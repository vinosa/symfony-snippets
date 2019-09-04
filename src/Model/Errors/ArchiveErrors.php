<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Model\Errors;

use App\Entity\ArchiveError ;
use App\Entity\ArchiveStatus ;
/**
 * Description of PacErrors
 *
 * @author vinosa
 */
class ArchiveErrors  implements \IteratorAggregate, OneOrMoreArchiveErrors {
    //put your code here
    private $items = [];
    
    public function add(ArchiveError $error)
    {
        $this->items[] = $error;
    }
    
    public function getIterator() {
        return new \ArrayIterator($this->items);
    }
    
    public function setSource(string $source): OneOrMoreArchiveErrors
    {
        array_map(function($item) use ($source){$item->setSource($source);},$this->items) ;
        return $this ;
    }
    
    public function setType(string $type): OneOrMoreArchiveErrors
    {
        array_map(function($item) use ($type){$item->setType($type);},$this->items) ;
        return $this ;
    }
    
    public function setFile(PacketFile $file): OneOrMoreArchiveErrors
    {
        array_map(function($item) use ($file) {$item->setFile($file);},$this->items) ;
        return $this ;
    }
    
    public function items(): array
    {
        return $this->items ;
    }
    
    public function addOneOrMoreErrors(OneOrMoreArchiveErrors $error)
    {
        foreach($error->items() as $item){
            $this->add($item);
        }
    }
    
    public function __toString() {
        $str = "Errors: ";
        foreach($this->items as $item){
            $str .= (string) $item . " ";
        }
        return $str ;
    }
    
    public function hasErrors(): bool
    {
        return count($this->items) > 0 ;
    }
    
    public function archiveStatus(): ArchiveStatus
    {
        if($this->hasErrors()){
            return $this->items[0]->archiveStatus();
        }
        return new ArchiveStatus(ArchiveStatus::ERROR);
    }
}
