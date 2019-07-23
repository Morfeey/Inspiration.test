<?php


namespace Helpers\Configuration\ParserConfig;


use Helpers\Configuration\FileConfig;

class DefaultParser implements IConfigParser
{
    /**
     * @var $fileConfig FileConfig
    */
    protected $fileConfig;

    public function __construct(FileConfig $item = null)
    {
        if (!is_null($item)) {
            $this->setFileConfig($item);
        }
    }

    /**
     * @param FileConfig $item
     * @return self
     */
    public function setFileConfig (FileConfig $item):self {
        $this->fileConfig = $item;
        return $this;
    }

    public function toArray (): array {
        $json = file_get_contents($this->fileConfig->getFile());
        $result = json_decode($json, true);
        return  $result;
    }

}