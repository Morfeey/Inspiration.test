<?php


namespace Helpers\Configuration;


use Helpers\Path\Directory;
use Helpers\Path\PathHandler;
use mysql_xdevapi\Exception;

class Configuration
{
    /**
     * @var $directoryAppConfiguration Directory;
    */
    protected $directoryAppConfiguration;
    /**
     * @var $directoryDefaultConfiguration Directory
    */
    protected $directoryDefaultConfiguration;

    protected $fileName;
    protected $extension;

    public function setDefaultConfigurationDirectory (Directory $item) {
        $this->directoryDefaultConfiguration = $item;
        return $this;
    }

    public function setAppConfigurationDirectory (Directory $item) {
        $this->directoryAppConfiguration = $item;
        return $this;
    }

    public function setExtensionFile (string $extension = 'json') {
        $this->extension = $extension;
        return $this;
    }

    public function setFileName (string $fileName) {
        $this->fileName = $fileName;
        return $this;
    }

    /**
     * @return FileConfig
     * @throws \Exception
     */
    public function getFileConfig (): FileConfig {
        $fileConfig = new FileConfig();
        $directory = null;
        $file = $this->fileName;

        if (!empty($file) && !is_null($file)) {
            $file = $this->fileName . "." . $this->extension;
            if (!is_null($this->directoryDefaultConfiguration)) {
                $file_ = (new PathHandler([$this->directoryDefaultConfiguration->getResult(), $file]))->subLastSlash()->getResult();
                if (file_exists($file_)) {
                    $directory = $this->directoryDefaultConfiguration;
                }
            }
            if (!is_null($this->directoryAppConfiguration)) {
                $file_ = (new PathHandler([$this->directoryAppConfiguration->getResult(), $file]))->subLastSlash()->getResult();
                if (file_exists($file_)) {
                    $directory = $this->directoryAppConfiguration;
                }
            }

            if (is_null($directory)) {
                throw new \Exception('Config file not found');
            }

            $fileConfig
                ->setDirectory($directory->getResult())
                ->setExtension($this->extension)
                ->setFile($this->fileName);

        }else {
            throw new \Exception("File name config is: '{$this->fileName}'", 2000);
        }

        return $fileConfig;
    }

    public function __construct()
    {
        $rootPath = PathHandler::getDocumentRoot();
        $AppPathHandler = new PathHandler([$rootPath, 'App', 'config']);
        $this->directoryAppConfiguration = new Directory($AppPathHandler->getResult());
        $this->directoryDefaultConfiguration = null;
        $this->setExtensionFile();
    }
}