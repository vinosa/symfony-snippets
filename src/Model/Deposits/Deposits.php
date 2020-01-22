<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Model\Deposits;

use League\Flysystem\Filesystem ;
use App\Model\Packets\PacketInterface;
use Psr\Log\LoggerInterface;
use App\Model\Workflow\ActionInterface ;
use App\Model\Workflow\SucceedAction ;

/**
 * Description of Deposits
 *
 * @author vinosa
 */
class Deposits {
    //put your code here
    private $filesystem;
    private $logger;
    
    public function __construct(Filesystem $filesystem, LoggerInterface $logger) {
        $this->filesystem = $filesystem;
        $this->logger = $logger;
    }
    
    public function packetDepositAction(PacketInterface $packet): ActionInterface
    {
        $this->logger->debug("depositing via sftp file " . $packet->files()->tar()->fileinfo()->filename());
        $this->filesystem->put($packet->files()->tar()->fileinfo()->filename(), $packet->files()->tar()->contents()); 
        return new SucceedAction();
                   
    }
}
