<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Filters;

/**
 *
 * @author vinosa
 */
interface RepositoryFilter {
    //put your code here
    public function dql(): string;
    public function parameters(): array;
    public function firstResult(): int;
    public function maxResults(): int;
}
