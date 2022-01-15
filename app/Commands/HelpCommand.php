<?php

namespace App\Commands;

use App\Interfaces\Command;

class HelpCommand implements Command
{
    public function run()
    {
        print_r("parse - запускает парсер, принимает обязательный параметр url (php CliParser parse imgpng.ru).\nКоманда report - выводит в консоль результаты анализа для домена, принимает обязательный параметр domain (как с протоколом, так и без)(php CliParser report imgpng.ru).\nКоманда help - выводит список команд с пояснениями.\n");
    }
}