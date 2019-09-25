<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Service\Pac\Model\Filesystem;

use GuzzleHttp\Exception\RequestException ;
use GuzzleHttp\Psr7\Response ;
use App\Model\Workflow\ActionInterface;
use App\Model\Workflow\FailedAction;
use App\Model\Workflow\SucceedAction;
use App\Model\Workflow\FileAction;
use App\Model\Workflow\HttpAction;
use App\Model\Workflow\LoggedAction;
use App\Model\Workflow\MultipleActions;
/**
 * Description of FileLoader
 *
 * @author vinogradov
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
        $actions = [];
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
            "fulfilled" => function (Response $response,$index) use ($items,&$actions){
                $actions[] = new LoggedAction($this->logger,new SucceedAction(new FileAction($items[$index]->file(), new HttpAction(null,"loading"))));
                $items[$index]->file()->withContents( $response->getBody()->getContents() );
            },
            "rejected" => function (RequestException $reason,$index) use ($items,&$errors,&$actions){
                $actions[] = new LoggedAction($this->logger,new FailedAction(new FileAction($items[$index]->file(), new HttpAction(null,"loading")),
                                                                            "failed loading file from " . $reason->getRequest()->getUri(),
                                                                            $reason->getMessage()));
            }
        ] ) )->promise()->wait() ;
        return new MultipleActions($actions);
    }
    
    
        
}
