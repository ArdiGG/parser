<?php

namespace App\Handlers\ParseHandlers;

use App\Http\Client\HttpClient;
use App\Interfaces\Parse;

class LinkParser implements Parse
{
    public $links = [];

    public Parse $imgParser;

    public static $checkedLinks = [];

    public static $domain;

    public \DOMDocument $doc;

    public HttpClient $httpClient;

    public int $count = 1;

    public function __construct($domain, \DOMDocument $doc, HttpClient $httpClient)
    {
        $this->httpClient = $httpClient;
        $this->doc = $doc;
        self::$domain = $domain;
    }

    public function run($url) : array
    {
        $this->parse($url);
        $this->links[] = $url;
        try {
            array_walk($this->links, function ($url) {
                $this->parse($url);

            });
        }catch (\Exception $ex){

        }
        return $this->links;
    }

    public function parse(string $url) : void
    {
        if($this->count == 10){
            throw new \Exception();
        }
        $htmlstring = $this->httpClient->setUrl($url)->get();

        @$this->doc->loadHTML($htmlstring);

        $urls = [];
        $elements = $this->doc->getElementsByTagName('a');
        foreach ($elements as $elem) {
            $elemAttribute = $elem->getAttribute('href');
            $pos = strpos($elemAttribute,self::$domain);
            if ($pos !== false) {
                if(!in_array($elemAttribute,$this->links)){
                    $this->links[] = $elemAttribute;
                    $urls[] = $elemAttribute;
                }
            }
        }
        $this->count++;
    }

}