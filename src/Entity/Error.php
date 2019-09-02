<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\EntityNotFoundException ;
/**
 * Validation
 *
 * @ORM\Table(name="error", indexes={@ORM\Index(name="fk_archive_id", columns={"archive_id"})})
 * @ORM\Entity(repositoryClass="App\Repository\ErrorRepository")
 */
class Error implements \JsonSerializable {
    //put your code here
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false, options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var int|null
     *
     * @ORM\Column(name="archive_id", type="integer", nullable=true, options={"unsigned"=true})
     */
    private $archiveId;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="upd", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $upd ;
    /** @ORM\Embedded(class = "FileInfo",columnPrefix="file_") */
    private $fileinfo;
    /** 
     * @var ArchiveError
     * @ORM\Embedded(class = "ArchiveError",columnPrefix=false)
     * 
     */
    private $archiveError;
    /**
     * @var string|null
     *
     * @ORM\Column(name="url", type="string", length=255, nullable=true)
     */
    private $url;
     
    /**
     * @var Archive
     *
     * @ORM\ManyToOne(targetEntity="Archive",inversedBy="errors")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="archive_id", referencedColumnName="id", unique=true, nullable=true)
     * })
     */
    private $archive;   
    
    public function __construct(ArchiveError $error) {
        $this->archiveError = $error;
        if(!is_null($error->file())){
            $this->fileinfo = $error->file()->fileinfo() ;
        }
    }
         
    public function setArchive(?Archive $archive ): self
    {
        $this->archive = $archive;
        return $this;
    }
    
 
    public function jsonSerialize() {
        $json["url"] = $this->url;
        $json["fileinfo"] = $this->fileinfo;  
        $json["source"] = $this->archiveError->source();
        $json["type"] = $this->archiveError->type() ;
        $json["description"] = $this->archiveError->description() ;
        $json["message"] = $this->archiveError->message() ;
        try{
            if(!empty($this->archive)){
                $json["resource"] = $this->archive->objects()->resourceId() ;
            }
        } catch (EntityNotFoundException $ex) {
        }       
        return (object) Utils::flattenNested($json) ;
    }
    
    
    public function __toString()
    {
        return (string) $this->archiveError ;
    }
    
}
