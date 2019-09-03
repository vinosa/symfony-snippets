<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace App\Controller\Pac;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
/**
 * Description of Home
 *
 * @author vinosa
 */
class Home extends \Symfony\Bundle\FrameworkBundle\Controller\Controller
{
   
    
    public function index($locale="fr")
    {
        $number = random_int(0, 100);
       
       
        return $this->render('snippets/home.html.twig', [
            'number' => $number,
            'locale' => $locale
        ]);
    }
}
