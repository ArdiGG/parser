<?php
namespace Parser;

class ImageCsvSaver implements Saver
{
    public static ImageCsvSaver $imageCsvSaveInstance;

    public static function getInstance()
    {
        if (!isset(self::$imageCsvSaveInstance)) {
            self::$imageCsvSaveInstance = new ImageCsvSaver();
        }

        return self::$imageCsvSaveInstance;
    }

    public function save($data,$domain)
    {

        $fp = fopen("CSV/".$domain.'.csv', 'a');

        fputcsv($fp, $data);
    }
}