<?php

class Parser
{
    public function parse($url)
    {


    }
}

interface Parse
{
    public function parse($url);
}

class LinkParser implements Parse
{
    public function parse($url)
    {
        $domain = parse_url($url)['host'];

        $file = file_get_contents($url);

        $urls = preg_match_all('#<a [^>]*href="(.*)"[^>]*>#Ui', $file, $result);


        for ($i = 0; $i < $urls; $i++) {
            $pos = strpos($result[1][$i], $domain);
            if ($pos !== false) {
                echo $result[1][$i] . "\n";
            }

        }

    }
}

interface Data{
    public function create();
}

class ImageCsvSave{
    public static ImageCsvSave $imageCsvSaveInstance;

    public static function getInstance()
    {
        if(!isset(self::$imageCsvSaveInstance)){
            self::$imageCsvSaveInstance = new ImageCsvSave();
        }

        return self::$imageCsvSaveInstance;
    }
    
    public function save($imgPath)
    {
        $fp = fopen('results.csv', 'w');

        fputcsv($fp, $imgPath);
    }
}

class ImageParser implements Parse
{
    public ImageCsvSave $imgCsvSave;

    public function __construct(ImageCsvSave $imgCsvSave)
    {
        $this->imgCsvSave = $imgCsvSave;
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

        $this->imgCsvSave->save($imgPath);

        print_r("file:///home/arkadiy/PhpstormProjects/Parser/results.csv\n");
    }
}



$parser = new ImageParser(ImageCsvSave::getInstance());
$parser->parse($argv[1]);

