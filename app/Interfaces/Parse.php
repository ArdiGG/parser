<?php

namespace App\Interfaces;

interface Parse
{
    public function run($url) : array;
    public function parse(string $url) : void;
}