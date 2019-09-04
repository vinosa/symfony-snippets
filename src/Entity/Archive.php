<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;


use League\Flysystem\Filesystem ;
use Ramsey\Uuid\Uuid ;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Archive
 *
 * @ORM\Table(schema="arch", name="archive")
 * @ORM\Entity(repositoryClass="App\Repository\ArchiveRepository")
 *
 */
class Archive implements \JsonSerializable, SharedResource
{
    
    public function __construct() {
        $this->identity = new ArchiveIdentity(Uuid::uuid4());
        $this->errors = new ArrayCollection();
        $this->status = new ArchiveStatus(ArchiveStatus::QUEUED) ;
    }
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", precision=0, scale=0, nullable=false, unique=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="main_id", type="integer", precision=0, scale=0, nullable=true, unique=false)
     */
    private $mainId;

    /**
     * @var integer
     *
     * @ORM\Column(name="objects_id", type="integer", precision=0, scale=0, nullable=true, unique=false)
     */
    private $objectsId;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="upd", type="datetime", precision=0, scale=0, nullable=false, unique=false)
     */
    private $upd;

    /** 
     * @var ArchiveStatus
     * @ORM\Embedded(class = "ArchiveStatus",columnPrefix=false)
     * 
     */
    private $status;
     /** 
     * @var ArchiveIdentity
     * @ORM\Embedded(class = "ArchiveIdentity",columnPrefix=false)
     * 
     */
    private $identity;
    /**
     * @var Objects
     *
     * @ORM\ManyToOne(targetEntity="Objects", inversedBy="archives")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="objects_id", referencedColumnName="id", unique=true, nullable=true)
     * })
     */
    private $objects;
    /**
    * @ORM\OneToMany(targetEntity=Error::class, cascade={"persist", "remove"}, mappedBy="archive", orphanRemoval=true)
    */
    protected $errors;
    
    
    
    public function status(): ArchiveStatus
    {
        return $this->status ;
    }

    public function errors(): ArrayCollection
    {
        return $this->errors ;
    }
    
    public function removeErrors()
    {
        foreach ($this->errors as $error) {
            $this->errors->removeElement($error);
        }
    }
    
    public function identity(): ArchiveIdentity
    {
        return $this->identity;
    }
    
    /**
     * Get upd
     *
     * @return \DateTime
     */
    public function getUpd()
    {
        return $this->upd;
    }


    
    
    public function jsonSerialize() {
        $json["archive_id"] = $this->identity->encodedUuid() ;
        $json["version"] = $this->identity->version();        
        $json["upd"] = $this->upd;
        $json["status_id"] = $this->status->id();
        $json["resource"] = $this->objects->resourceId() ;
        $json["uuid"] = (string) $this->identity->uuid() ;
        return (object) Utils::flattenNested($json) ;
    }
    
    public function __toString()
    {
        return $this->status->id() . " archive " . $this->identity->uuid() . " for " . (string) $this->objects->resourceId() ;
    }
    
       
    public function patch(\stdClass $data)
    {
        if(isset($data->status)){
            $this->status = $this->status->patched(new ArchiveStatus($data->status));
        }
    }
    
    public function lockId(): string 
    {
        return (string) $this->identity->uuid();
    }
}

