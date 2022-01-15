<?php

namespace App\Commands;

use App\Interfaces\Command;

class ReportCommand implements Command {

    public string $url;

    public function __construct(string $url)
    {
        $this->url = $url;
    }

    public function run()
    {
        $file = file_get_contents('./storage/csvImages/'.$this->url.'.csv');
        print_r($file);
    }
}