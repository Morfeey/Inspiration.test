<?php

namespace Helpers\DataBase;

use Helpers\Configuration;

class Config extends Configuration\Configuration
{
    protected $fullConfig;

    /**
     * @param string|null $name
     * @return ConnectItem
     * @throws \Exception
     */
    public function getConnect (string $name = null) {
        $connections = $this->fullConfig['connections'];
        $name =  (!is_null($name)) ?: $this->fullConfig['defaultConnect'];
        if (key_exists($name, $connections)) {
            $connect = $this->fullConfig['connections'][$name];
            return (new ConnectItem($connect));
        }else {
            throw new \Exception("Connect with name '{$name}' not found");
        }

    }

    /**
     * Config constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        parent::__construct();
        $this->fullConfig =
            $this
            ->setFileName('database')
            ->getFileConfig()
                ->getParser()
                    ->toArray();
    }
}