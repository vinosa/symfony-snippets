<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Model\Deposits;

use Psr\Log\LoggerInterface;
use App\Service\Pac\Model\Packets\PacketInterface;
use GuzzleHttp\Client ;
use App\Entity\Archive ;
use Ramsey\Uuid\UuidInterface ;
use GuzzleHttp\Exception\ClientException ;
use App\Service\Pac\Model\Errors\IgnorableError ;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Doctrine\Common\Annotations\AnnotationReader ;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer ;
use Symfony\Component\Serializer\Normalizer\PropertyNormalizer ;
use Gamez\Symfony\Component\Serializer\Normalizer\UuidNormalizer;
use Symfony\Component\Serializer\Serializer ;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer ;
use Symfony\Component\Serializer\SerializerInterface ;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory ;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor ;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
/**
 * Description of DepositStates
 *
 * @author vinosa
 */
class DepositStates {
    private $logger;
    private $client;
    private $url ;
    private $credentials ;
    
    public function __construct(string $url, LoggerInterface $logger,Client $client,array $credentials) {
        $this->logger = $logger;
        $this->client = $client;
        $this->url = $url;
        $this->credentials = $credentials;
    }
    
    public function state(Archive $archive): DepositState
    {  
        $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));      
        $propertyInfo = new \Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor();
        $serializer = new Serializer( [
            new \Symfony\Component\Serializer\Normalizer\ArrayDenormalizer(),
            new \Symfony\Component\Serializer\Normalizer\ObjectNormalizer($classMetadataFactory, null, null, $propertyInfo),      
            ],[new JsonEncoder()]);
        try{
            $response = $this->client->request('GET', $this->url . (string) $archive->identity()->uuid(),['auth' => $this->credentials]);
            $json = $response->getBody() ;
            $deposit = $serializer->deserialize($json,DepositState::class,"json");
            return $deposit ;
        } catch (ClientException $ex) {
            $this->logger->warning($ex->getMessage());
            throw new IgnorableError($ex->getMessage());
        }  
        catch (NotEncodableValueException $ex) {
            $this->logger->error(" Not encodable: " . $json );
            throw new IgnorableError($ex->getMessage());
        }
    }
}
