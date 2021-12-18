<?php

namespace Parser;

class LinkParser implements Parse
{
    public $links = [];
    public Parse $imgParser;
    public static $checkedLinks = [];
    public static $domain;
    public function __construct($domain)
    {
        $this->imgParser = new ImageParser(ImageCsvSaver::getInstance(),$domain);
        self::$domain = $domain;
    }

    public function parse($url)
    {

        $file = file_get_contents($url);

        $urls = preg_match_all('#<a [^>]*href="(.*)"[^>]*>#Ui', $file, $result);


        for ($i = 0; $i < $urls; $i++) {
            $pos = strpos($result[1][$i],self::$domain);
            if ($pos !== false) {
                $this->links[] = $result[1][$i];
            }

        }
        $this->parseImgByLinks($url);
    }

    public function parseImgByLinks($url)
    {
        foreach ($this->links as $link) {
            if (in_array($link, self::$checkedLinks)) {
                continue;
            } else {
                self::$checkedLinks[] = $link;
                $this->imgParser->parse($link,self::$domain);
            }
        }
    }
}