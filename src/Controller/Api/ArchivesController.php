<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Controller\Api;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Archive ;
use App\Repository\ArchiveRepository ;
use App\Filters\SearchArchivesFilter;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer ;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer ;
use Symfony\Component\Serializer\Serializer ;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory ;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor ;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Doctrine\Common\Annotations\AnnotationReader ;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer ;
use Symfony\Component\Serializer\Normalizer\PropertyNormalizer ;
use Gamez\Symfony\Component\Serializer\Normalizer\UuidNormalizer;

/**
 * Description of Home
 *
 * @author vinosa
 */
class ArchivesController extends \Symfony\Bundle\FrameworkBundle\Controller\Controller
{
    private $em;
    /**
     *
     * @var ArchivesRepository
     */
    private $archives ;
    
    
    public function __construct(\Doctrine\ORM\EntityManagerInterface $em)
    {
       $this->em = $em ;
       $this->archives = $em->getRepository(Archive::class);
    }
    
    public function index(Request $request)
    {
        $filter = new SearchArchivesFilter($request);
        $items = $this->archives->findByFilter($filter); 
        $count = $this->archives->countByFilter($filter);
        $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));
        $serializer = new Serializer([new UuidNormalizer(),new PropertyNormalizer($classMetadataFactory),new DateTimeNormalizer(),new ObjectNormalizer()],[new JsonEncoder()]);
        
        return new JsonResponse( (object)["count" => (int)$count, "items" => Utils::flattenNested( $serializer->normalize($items,null, ['groups' => ['users']]),"",1),QueryParams::LIMIT=>$filter->maxResults(),QueryParams::OFFSET=>$filter->firstResult()] );
    }
    
    
}


