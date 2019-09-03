<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Filters;

use App\Entity\Archive ;
use Symfony\Component\HttpFoundation\Request;
/**
 * Description of SearchErrorsFilter
 *
 * @author vinosa
 */
class SearchErrorsFilter implements RepositoryFilter {
    //put your code here
    use FilterPaginationTrait;
    private $request ;
    private $archive = null ;
    private $paramsMap = ["source"=> "e.archiveError.source","type"=> "e.archiveError.type","extension"=>"e.fileinfo.extension","id"=>"o.resourceId.id","site"=>"o.resourceId.site"];
    
    public function __construct(Request $request) 
    {
        $this->request = $request ;
    }
    
    public function dql(): string
    {
        $conditions = [];
        foreach($this->paramsMap as $param => $val){
            if($this->request->query->has($param)){
                $conditions[] = $val . "=:" . $param  ;
            }
        }
        if(!is_null($this->archive)){
            $conditions[] = "e.archive=:archive" ;
        }
        if(count($conditions)>0){
            return " where " . implode(" and ",$conditions);
        }
        return "" ;
    }
    
    public function withArchive(Archive $archive)
    {
        $this->archive = $archive ;
        return $this ;
    }
    
    public function parameters(): array
    {
        $params = [];
        foreach($this->paramsMap as $param => $val){
            if($this->request->query->has($param)){
                $params[$param] = $this->request->query->get($param);
            }
        }
        if(!is_null($this->archive)){
            $params["archive"] = $this->archive ;
        }
        return $params ;
    }
    
}
