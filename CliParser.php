<?php

namespace Parser;
require "vendor/autoload.php";

class Loader{
    public function parse($url)
    {
        $parser = new Parser();
        $parser->parse($url);
    }

    public function report($url)
    {
        $parseUrl = parse_url($url);
        $domain = $parseUrl['host'] ?? $parseUrl['path'];
        print_r($domain);
        $file = file_get_contents('CSV/'.$domain.'.csv');
        print_r($file);
    }

    public function help()
    {
        print_r("parse - запускает парсер, принимает обязательный параметр url (php CliParser parse imgpng.ru).\nКоманда report - выводит в консоль результаты анализа для домена, принимает обязательный параметр domain (как с протоколом, так и без)(php CliParser report imgpng.ru).\nКоманда help - выводит список команд с пояснениями.\n");
    }
}

$loader = new Loader();
$action = $argv[1];
$loader->$action($argv[2]);





