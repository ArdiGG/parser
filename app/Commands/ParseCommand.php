<?php

namespace App\Commands;

use App\Handlers\ParseHandlers\ImageParser;
use App\Handlers\ParseHandlers\LinkParser;
use App\Http\Client\HttpClient;
use App\Interfaces\Command;
use App\Interfaces\Parse;
use DOMDocument;

class ParseCommand implements Command
{
    public string $url;

    public \DOMDocument $dom;

    public Parse $linkParser;

    public Parse $imageParser;

    public function __construct(string $url, \DOMDocument $dom, Parse $linkParser, Parse $imageParser)
    {
        $this->url = $url;
        $this->dom = $dom;
        $this->linkParser = $linkParser;
        $this->imageParser = $imageParser;
    }

    public function run() : ?array
    {
        $links = $this->linkParser->run($this->url);
        return $this->imageParser->run($links);
    }

}