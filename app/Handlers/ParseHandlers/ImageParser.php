<?php

namespace App\Handlers\ParseHandlers;

use App\Http\Client\HttpClient;
use App\Interfaces\Parse;
use App\Interfaces\Saver;
use App\Handlers\ParseHandlers\LinkParser;
use DOMDocument;
use DOMParentNode;

class ImageParser implements Parse
{
    public static $domain;

    public DOMDocument $dom;

    public HttpClient $httpClient;

    public $imagePath = [];

    public function __construct(DOMDocument $dom, HttpClient $httpClient)
    {
        $this->httpClient = $httpClient;
        $this->dom = $dom;
    }

    public function run($url) : array
    {
        $i = 0;
        foreach ($url as $link) {
            if ($i == 10) {
                break;
            }

            $this->parse($link);

            $i++;
        }

        return $this->imagePath;
    }

    public function parse(string $url) : void
    {
        $htmlstring = $this->httpClient->setUrl($url)->get();

        @$this->dom->loadHTML($htmlstring);

        $elements = $this->dom->getElementsByTagName('img');
        foreach ($elements as $elem) {
            $elementAttribute = $elem->getAttribute('src');
            if (!empty($elementAttribute)) {
                if (!in_array($elementAttribute, $this->imagePath)) {
                    $this->imagePath[] = $elementAttribute;
                }
            }
        }
    }

}