<?php

namespace App\Services;

use App\Interfaces\Command;

class ParseService
{
    public Command $command;

    public function __construct(Command $command)
    {
        $this->command = $command;
    }

    public function execute() : ?array
    {
        return $this->command->run();
    }
}