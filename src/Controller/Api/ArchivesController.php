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

/**
 * Description of Home
 *
 * @author vinogradov
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
        return new JsonResponse( (object)["count" => (int)$count, "items" => $items,QueryParams::LIMIT=>$filter->maxResults(),QueryParams::OFFSET=>$filter->firstResult()] );
        
    }
    
    
}


