<?php

use App\Commands\HelpCommand;
use App\Commands\ParseCommand;
use App\Commands\ReportCommand;
use App\Handlers\ParseHandlers\ImageParser;
use App\Handlers\ParseHandlers\LinkParser;
use App\Http\Client\HttpClient;
use App\Savers\ImageCsvSaver;
use App\Services\ParseService;

require "vendor/autoload.php";
require "./config/console.php";

$options = getopt($longopt['options'], $longopt['aliases']);

$commandName = key($options);

if($commandName == 'help'){
    print 1;
    $service = new ParseService(new HelpCommand());
} else {

    $url = $options[$commandName];

    $name = hash("md5", $url);
}
if($commandName == 'parse') {
    $parsedUrl = parse_url($url);

    if (empty($parsedUrl['scheme'])) {
        $url = 'https://' . $this->url;
        $parsedUrl = parse_url($this->url);
    }

    $domain = $parsedUrl['host'];

    $dom = new DOMDocument();
    $httpClient = new HttpClient();

    $service = new ParseService(new ParseCommand($url, $dom, new LinkParser($domain, $dom, $httpClient), new ImageParser($dom, $httpClient)));
} else if($commandName == 'report') {
    $service = new ParseService(new ReportCommand($name));
}
$data = $service->execute();
if (!is_null($data)) {
    ImageCsvSaver::getInstance()->save($data, $name);
    print "file::./storage/csvImages/" . $name . '.csv';
}
