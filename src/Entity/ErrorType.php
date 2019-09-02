<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Entity;

/**
 *
 * @author vinosa
 */
interface ErrorType {
    //put your code here
    const VALIDATION = "Validation";
    const HTTP = "Http";
    const CONTENT = "Content";
}
