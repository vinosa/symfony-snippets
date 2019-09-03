<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

use App\Entity\Archive ;
use Base64Url\Base64Url;
use Doctrine\DBAL\Types\ConversionException ;
use App\Entity\ArchiveStatus ;
/**
 * Description of ArchiveRepository
 *
 * @author vinogradov
 */
class ArchiveRepository extends EntityRepository {
    //put your code here
    public function findByFilter(RepositoryFilter $filter){
        
        $dql = "select a FROM App\Entity\Archive a  join a.objects o " . $filter->dql();        
        $query = $this->getEntityManager()->createQuery($dql);
        $query = $query->setMaxResults($filter->maxResults());
        $query->setFirstResult($filter->firstResult()) ;
        return $query->execute($filter->parameters());
    }
    
    public function countByFilter(RepositoryFilter $filter){
        
        $dql = "select count(a) FROM App\Entity\Archive a  join a.objects o " . $filter->dql();        
        $query = $this->getEntityManager()->createQuery($dql);
        foreach($filter->parameters() as $key => $val){
            $query->setParameter($key, $val);
        }
        return $query->getOneOrNullResult()[1];
    }
        
    public function distinctSites(int $limit = 10,int $offset = 0): array
    {
        $dql = "select distinct(o.resourceId.site) as site FROM App\Entity\Archive a join a.objects o "; 
        $query = $this->getEntityManager()->createQuery($dql);
        $query = $query->setMaxResults($limit);
        $query->setFirstResult($offset) ;
        return $query->execute();
    }
    
    public function countDistinctSites()
    {
        $dql = "select count(distinct(o.resourceId.site))  FROM App\Entity\Archive a  join a.objects o "; 
        $query = $this->getEntityManager()->createQuery($dql);
        return $query->getOneOrNullResult()[1];
    }
    
    public function findOneByEncoded(string $encoded): Archive
    {
        $decoded = Base64Url::decode($encoded);
        try{
            $archive = $this->findOneBy(["identity.uuid" => $decoded]) ;
            if(is_null($archive)){
                throw new ResourceNotFound("no archive with this ID");
            }
            return $archive ;
        } catch (ConversionException $ex) {
            throw new ResourceNotFound("no archive with this ID");
        }
    }
    
    public function findQueued(): array
    {
        return $this->findBy(["status.id" => ArchiveStatus::QUEUED]) ;
    }
}
