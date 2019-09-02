<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface ;
use Ramsey\Uuid\Uuid ;
use Base64Url\Base64Url;
/**
 * Description of ArchiveIdentity
 * 
 * @ORM\Embeddable
 * @author vinosa
 */
class ArchiveIdentity {
    //put your code here
    /**
     * The internal primary identity key.
     *
     * @var UuidInterface
     *
     * @ORM\Column(name="uuid", type="uuid", unique=true)
     */
    protected $uuid;
    /**
     * @var string
     *
     * @ORM\Column(name="ark", type="string", length=64, precision=0, scale=0, nullable=true, unique=false)
     */
    private $ark;
    /**
     * @var string
     *
     * @ORM\Column(name="version", type="integer", precision=0, scale=0, nullable=false,  unique=false)
     */
    private $version;
    
    public function __construct(UuidInterface $uuid,int $version = 1) {
        $this->uuid = $uuid;
        $this->version = $version;
    }
    
    public function uuid(): UuidInterface
    {
        return $this->uuid;
    }
    
    public function encodedUuid(): string
    {
        $encoded = Base64Url::encode( $this->uuid );
        return $encoded;
    }
    
    public function version(): int
    {
        return $this->version ;
    }
    
    public function setVersion(int $version): self
    {
        $this->version = $version;
        return $this;
    }
    
}
