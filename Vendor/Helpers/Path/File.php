<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 11.12.2018
 * Time: 23:05
 */

namespace Helpers\Path;


use Helpers\StringValue;

class File
{
    protected $file;
    protected $SPLFileInfo;

    public function getDirectory(): string
    {
        return $this->SPLFileInfo->getPath();
    }

    public function getExtension()
    {
        return $this->SPLFileInfo->getExtension();
    }

    public function getName()
    {
        return $this->SPLFileInfo->getBasename();
    }

    public function getNameWithoutExtension()
    {
        return (new StringValue($this->getName()))
            ->replace
            (
                "." . $this->getExtension(),
                ""
            )
            ->getResult();
    }

    public function copy(Directory $newDirectory = null, $name = null): File
    {
        $Name = (is_null($name)) ? $this->getNameWithoutExtension() : $name;
        $Directory = (is_null($newDirectory)) ? $this->getDirectory() : $newDirectory->getResult();
        /// work in progress
    }

    public function __construct(string $file)
    {
        $this->file = $file;
        $this->SPLFileInfo = new \SplFileInfo($file);
    }
}