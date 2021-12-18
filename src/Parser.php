<?php

namespace Parser;

class Parser implements Parse
{

    public function parse($url)
    {
        $parsUrl = parse_url($url);
        $domain = $parsUrl['host'] ?? strstr($parsUrl['path'],'/',true);
        $parser = new ImageParser(ImageCsvSaver::getInstance(),$domain);
        if(strpos($url,'http://') !== false){
            $parser->parse($url);
        }else {
            $parser->parse('http://'.$url);
        }
        print_r("file:///home/arkadiy/PhpstormProjects/Parser/CSV/$domain.csv\n");
    }

}