<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 02.01.2019
 * Time: 11:52
 */

namespace Helpers\Path;


trait HelperPath
{
    public static function getDocumentRoot () {
        return $_SERVER["DOCUMENT_ROOT"];
    }
    public  static function getLastSlashCustom(string $Directory)
    {
        $Directory = str_replace("\\", "/", $Directory);
        $Directory = rtrim($Directory, "/");
        $result = $Directory . "/";
        return $result;
    }
    public static function deleteLastSlashCustom(string $Directory)
    {
        $Directory = str_replace("\\", "/", $Directory);
        $result = rtrim($Directory, "/");
        return $result;
    }
}