<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace App\Filters;

use Symfony\Component\HttpFoundation\Request;
/**
 * Description of SearchArchivesRequest
 *
 * @author vinosa
 */
class SearchArchivesFilter implements RepositoryFilter {
    //put your code here
    private $request ;
    private $paramsMap = ["id"=>"o.resourceId.id","site"=>"o.resourceId.site","platform"=>"o.resourceId.platform","status"=>"a.status.id","uuid"=>"a.identity.uuid"];
    use FilterPaginationTrait;
    
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
        if(count($conditions)>0){
            return " where " . implode(" and ",$conditions);
        }
        return "" ;
    }
    
    public function parameters(): array
    {
        $params = [];
        foreach($this->paramsMap as $param => $val){
            if($this->request->query->has($param)){
                $params[$param] = $this->request->query->get($param);
            }
        }
        return $params ;
    }
    
}
