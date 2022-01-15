<?php

namespace App\Interfaces;

interface Saver
{
    public function save(array $data, string $name);
}