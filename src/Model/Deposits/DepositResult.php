<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Model\Deposits;

use App\Entity\ErrorInfo ;
use App\Entity\ErrorSource;
/**
 * Description of DepositResult
 *
 * @author vinosa
 */
class DepositResult implements \JsonSerializable {
    //put your code here
    private $startDate;
    private $state ;
    private $serviceName ;
    private $endDate ;
    private $errorCode;
    private $message;
    
    public function setStartDate(string $date)
    {
        $this->startDate = $date ;
    }
    
    public function setEndDate(string $date)
    {
        $this->endDate = $date ;
    }
    
    public function setState(string $state)
    {
        $this->state = $state ;
    }
    
    public function setErrorCode(string $code)
    {
        $this->errorCode = $code ;
    }
    
    public function setMessage(string $message)
    {
        $this->message = $message ;
    }
    
    public function setServiceName(string $name)
    {
        $this->serviceName = $name;
    }
    
    public function errorInfo(): ErrorInfo
    {
        return new ErrorInfo($this->errorCode,json_encode($this),$this->state, ErrorSource::CINES);
    }
    
    public function jsonSerialize() {
        return (object)["startDate"=>$this->startDate,"state"=>$this->state,"serviceName"=>$this->serviceName,"endDate"=>$this->endDate,
                        "errorCode"=>$this->errorCode,"message"=>$this->message];
    }
   
}
