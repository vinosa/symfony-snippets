<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Model\Filesystem;

use GuzzleHttp\Exception\RequestException ;
use GuzzleHttp\Psr7\Response ;
use App\Entity\ArchiveError;

/**
 * Description of FileLoader
 *
 * @author vinosa
 */
class FileLoader {
    //put your code here
    private $client;
    private $logger ;
    private $concurrency ;
    private $poolOptions;
    
    public function __construct(\GuzzleHttp\Client $client,\Psr\Log\LoggerInterface $logger,int $concurrency,array $poolOptions) {
        $this->client = $client;
        $this->logger = $logger ;
        $this->concurrency = $concurrency ;
        $this->poolOptions = $poolOptions ;
    }
    
    public function loadFiles( array $items, array $requestOptions = []): ActionInterface
    {
        $errors = new ArchiveErrors();
        $client = $this->client ;                             
        $logger = $this->logger ; 
        $requests = function () use ($client, $items, $logger,$requestOptions) {            
            foreach ($items as $item) {                        
                $logger->debug("loading File from " . $item->url() ) ;                
                yield function($poolOpts) use ($client, $item, $requestOptions) {                                                 
                    if (is_array($poolOpts)){                    
                        $reqOpts = array_merge($poolOpts, $requestOptions);
                    }                                  
                    return $client->requestAsync('GET', $item->url(), $reqOpts);                                
                };
            }
        };                        
        (new \GuzzleHttp\Pool($client, $requests(),[
            'concurrency' => $this->concurrency,
            'options' => $this->poolOptions,
            "fulfilled" => function (Response $response,$index) use ($items){
                $this->logger->debug("saving file into " .  (string) $items[$index]->file()->fileinfo() );
                $items[$index]->file()->withContents( $response->getBody()->getContents() );
            },
            "rejected" => function (RequestException $reason,$index) use ($items,&$errors){
                $errorMessage = "failed loading file from " . $reason->getRequest()->getUri();
                $this->logger->error($errorMessage);
                $errors->addOneOrMoreErrors((new FileError($items[$index]->file(), new HttpError(new ArchiveError($reason->getMessage(),$errorMessage)))));
            }
        ] ) )->promise()->wait() ;
        if($errors->hasErrors()){
            return new FailedAction($errors);
        }
        return new SucceedAction();
    }
    
    
        
}
