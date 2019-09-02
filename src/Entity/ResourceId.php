<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Description of ResourceId
 * 
 * @ORM\Embeddable
 * @author vinosa
 */
class ResourceId {
    //put your code here
     /** @ORM\Column(name="platform",type = "string") */
    private $platform;
    /** @ORM\Column(name="site_name",type = "string") */
    private $site;
    /** @ORM\Column(name="entity_id",type = "integer") */
    private $id ;
    
    public function __construct(string $platform,string $site,int $id) {
        if(!in_array($platform, [])){
            throw new ApplicationError("invalid platform ".$platform);
        }
        $this->platform = $platform ;
        $this->site = $site ;
        $this->id = $id ;
    }
    
    public function platform(): string
    {
        return $this->platform ;
    }
    
    public function site(): string
    {
        return $this->site ;
    }
    
    public function id(): int
    {
        return $this->id ;
    }
    
    public function __toString() {
        return $this->platform."-".$this->site."-".$this->id;
    }
    
    
}
