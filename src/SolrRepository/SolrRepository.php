<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\SolrRepository;

use Solarium\Client ;
/**
 * Description of SolrRepository
 *
 * @author vinosa
 */
abstract class SolrRepository {
    //put your code here
    protected $client;
    protected $logger ;
    protected $entityClass;
    protected $fields ;
    
    public function __construct(\Psr\Log\LoggerInterface $logger,string $endpoint,string $entityClass,array $fields,array $config)
    {
        $this->client = new Client($config) ;
        $this->logger = $logger ;
        $this->client->setDefaultEndpoint($endpoint) ;
        $this->entityClass = $entityClass ;
        $this->fields = $fields ;
    }
    
    public function findBy(string $queryString,array $sort = null ,int $limit=10, $offset=0): array
    {
        $query = $this->client->createSelect();
        $query->setQuery($queryString);
        $query->setStart($offset)->setRows($limit);
        $query->setFields($this->fields);
        $resultset = $this->client->select($query);
        $entities = [];
        foreach ($resultset as $document) {
            $entities[] = $this->entity($this->entityClass,$document->getFields()); 
        }
        return $entities ; 
    }
    
    public function findOneBy(string $queryString,array $sort = null )
    {
        $query = $this->client->createSelect();
        $query->setQuery($queryString);
        $query->setStart(0)->setRows(1);
        $query->setFields($this->fields);
        $resultset = $this->client->select($query);
        foreach ($resultset as $document) {
            return $this->entity($this->entityClass,$document->getFields());
        }
        return null ; 
    }
    
    public function countBy(string $queryString): int
    {
        $query = $this->client->createSelect();
        $query->setQuery($queryString);
        $query->setFields($this->fields);
        $query->setStart(0)->setRows(1);
        $resultset = $this->client->select($query);
        return $resultset->getNumFound();
    }
    
    public function entity(string $class,array $fields)
    {
        $reflectionClass = new \ReflectionClass($class);
        $constructor = $reflectionClass->getConstructor() ; 
        if(!is_null($constructor)){
            $parameters = [];
            foreach($constructor->getParameters() as $reflectionParameter){
                $parameters[] = $this->resolveParameter($reflectionParameter,$fields);
            }
            return $reflectionClass->newInstanceArgs($parameters) ;  
        }
        return $reflectionClass->newInstance();     
    }
    
    public function resolveParameter(\ReflectionParameter $parameter,array $fields)
    {
        if(!is_null($parameter->getClass())){
            if($parameter->getClass()->getName() === DynamicField::class){
                return DynamicField::fromFields($fields, $parameter->getName());
            }
            return $this->entity($parameter->getClass()->getName(),$fields);
        }
        if(isset($fields[$parameter->getName()])){
            return $fields[$parameter->getName()] ;
        }
        try{            
            return $parameter->getDefaultValue();            
        } catch (\ReflectionException $ex) {
        }  
        return null;
    }
    
        
}
