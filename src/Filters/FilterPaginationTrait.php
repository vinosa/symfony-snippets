<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Filters;

use App\Controller\Api\QueryParams ;
/**
 * Description of FilterPaginationTrait
 *
 * @author vinogradov
 */
trait FilterPaginationTrait {
    //put your code here
    public function firstResult(): int
    {
        if($this->request->query->has(QueryParams::OFFSET)){
            return $this->request->query->get(QueryParams::OFFSET);
        }
        return 0;
    }
    
    public function maxResults(): int
    {
        if($this->request->query->has(QueryParams::LIMIT)){
            return $this->request->query->get(QueryParams::LIMIT);
        }
        return 10;
    }
}
