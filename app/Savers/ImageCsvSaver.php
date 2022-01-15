<?php
namespace App\Savers;

use App\Interfaces\Saver;

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

    public function save(array $data, string $name)
    {

        $fp = fopen("./storage/csvImages/".$name.'.csv', 'a');

        fputcsv($fp, $data);

    }
}