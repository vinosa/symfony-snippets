<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Model\Errors;

use App\Entity\ArchiveStatus ;
/**
 *
 * @author vinosa
 */
interface OneOrMoreArchiveErrors {
    //put your code here
    public function setSource(string $source): OneOrMoreArchiveErrors;
    public function setType(string $type): OneOrMoreArchiveErrors;
    public function setFile(PacketFile $file): OneOrMoreArchiveErrors;
    public function items(): array ;
    public function hasErrors(): bool ;
    public function archiveStatus(): ArchiveStatus;
}
