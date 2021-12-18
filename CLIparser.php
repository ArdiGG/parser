<?php

interface Parse
{
    public function parse($url);
}

class Parser
{

    public function parse($url)
    {
        $domain = parse_url($url)['host'];
        $parser = new ImageParser(ImageCsvSave::getInstance(),$domain);
        $parser->parse($url);
        print_r("file:///home/arkadiy/PhpstormProjects/Parser/$domain.csv\n");
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
        print_r("parse - запускает парсер, принимает обязательный параметр url (как с протоколом, так и без).\nКоманда report - выводит в консоль результаты анализа для домена, принимает обязательный параметр domain (как с протоколом, так и без).\nКоманда help - выводит список команд с пояснениями.\n");
    }
}

class LinkParser implements Parse
{
    public $links = [];
    public Parse $imgParser;
    public static $checkedLinks = [];
    public static $domain;
    public function __construct($domain)
    {
        $this->imgParser = new ImageParser(ImageCsvSave::getInstance(),$domain);
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

interface Saver
{
    public function save($data,$domain);
}

class ImageCsvSave implements Saver
{
    public static ImageCsvSave $imageCsvSaveInstance;

    public static function getInstance()
    {
        if (!isset(self::$imageCsvSaveInstance)) {
            self::$imageCsvSaveInstance = new ImageCsvSave();
        }

        return self::$imageCsvSaveInstance;
    }

    public function save($data,$domain)
    {

        $fp = fopen("CSV/".$domain.'.csv', 'a');

        fputcsv($fp, $data);
    }
}

class ImageParser implements Parse
{
    public Saver $imgUrlSave;
    public static $domain;
    public function __construct(Saver $imgUrlSave,$domain)
    {
        $this->imgUrlSave = $imgUrlSave;
        self::$domain = $domain;
    }

    public function parse($url)
    {

        $imgPath = [];

        $html = file_get_contents($url);
        preg_match_all('/<img.*?src=["\'](.*?)["\'].*?>/i', $html, $images, PREG_SET_ORDER);


        foreach ($images as $image) {
            if (strpos($image[1], 'data:image/') !== false) {
                continue;
            }

            if (substr($image[1], 0, 2) == '//') {
                $image[1] = 'http:' . $image[1];
            }

            $ext = strtolower(substr(strrchr($image[1], '.'), 1));
            if (in_array($ext, array('jpg', 'jpeg', 'png'))) {
                $imgPath[] = $image[1];
            }
        }

        $this->imgUrlSave->save($imgPath,self::$domain);


        $linkParser = new LinkParser(self::$domain);
        $linkParser->parse($url);
    }
}

$parser = new Parser();

$action = $argv[1];
$parser->$action($argv[2]);


