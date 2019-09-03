<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Controller\Api;

use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repository\ErrorRepository ;
use App\Repository\ArchiveRepository ;
use App\Filters\SearchErrorsFilter;
/**
 * Description of FileErrorController
 *
 * @author vinogradov
 */
class ErrorsController  extends \Symfony\Bundle\FrameworkBundle\Controller\Controller {
    //put your code here
    private $errors ;
    private $archives;
    
    public function __construct(ErrorRepository $errors, ArchiveRepository $archives) 
    {
        $this->errors = $errors;
        $this->archives = $archives ;
    }
    
    public function index(Request $request)
    { 
        $filter = new SearchErrorsFilter($request);
        $count = $this->errors->countByFilter($filter);
        $items = $this->errors->findByFilter($filter);                                             
        return new JsonResponse((object) ["items" => $items,"count"=>(int)$count,QueryParams::LIMIT=>$filter->maxResults(),QueryParams::OFFSET=>$filter->firstResult()]);
        
    }
    
    public function errorsByArchive(Request $request,$id)
    {
        $filter = new SearchErrorsFilter($request);
        try{
            $archive = $this->archives->findOneByEncoded($id);
            $filter = $filter->withArchive($archive);
            $count = $this->errors->countByFilter($filter);
            $items = $this->errors->findByFilter($filter); 
            return new JsonResponse((object) ["items" => $items,"count"=>(int)$count,QueryParams::LIMIT=>$filter->maxResults(),QueryParams::OFFSET=>$filter->firstResult()]);
        } 
       catch(ResourceNotFound $ex){
            return (new JsonResponse())->setStatusCode(404);
        }
    }
}
