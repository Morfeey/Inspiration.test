<?php

namespace Helpers;

include_once "Autoloader/IAutoload.php";
include_once "Path/Directory.php";

use Helpers\Autoloader\IAutoload;
use Helpers\Path\Directory;
use Helpers\Path\SearchOption;

class Autoloader implements IAutoload
{
    public function getFiles(): array
    {
        return (new Directory(__DIR__))->getFiles("*.php", SearchOption::searchRecurse());
    }
}

spl_autoload_register(function () {
    foreach ((new Autoloader())->getFiles() as $file) {
        include_once $file;
    }
});