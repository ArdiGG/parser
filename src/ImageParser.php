<?php

namespace Parser;

class ImageParser implements Parse
{
    public Saver $imgUrlSave;
    public static $domain;

    public function __construct(Saver $imgUrlSave, $domain)
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

            $this->imgUrlSave->save($imgPath, self::$domain);


            $linkParser = new LinkParser(self::$domain);
            $linkParser->parse($url);

    }
}