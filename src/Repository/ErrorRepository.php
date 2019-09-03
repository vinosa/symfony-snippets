<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Repository;

use Doctrine\ORM\EntityRepository;
/**
 * Description of FileErrorRepository
 *
 * @author vinogradov
 */
class ErrorRepository extends EntityRepository {
    //put your code here
    
   
    public function countAll()
    {
        return intval($this->createQueryBuilder('error')
                ->select('COUNT(error)')
                ->getQuery()->getSingleScalarResult());
        
    }
    
    public function findByFilter(RepositoryFilter $filter){
        
        $dql = "select e FROM App\Entity\PacError e ";  
        $dql .= "  join e.archive a join a.objects o  " ;
        $dql .= $filter->dql();
        $query = $this->getEntityManager()->createQuery($dql);
        $query->setMaxResults($filter->maxResults());
        $query->setFirstResult($filter->firstResult()) ;
        return $query->execute($filter->parameters());
    }
    
    public function countByFilter(RepositoryFilter $filter){
        $dql = "select count(e) FROM App\Entity\PacError e ";  
        $dql .= "  join e.archive a join a.objects o  ";
        $dql .= $filter->dql();
        $query = $this->getEntityManager()->createQuery($dql);
        foreach($filter->parameters() as $key => $val){
            $query->setParameter($key, $val);
        }
        return $query->getOneOrNullResult()[1];
    }
}
