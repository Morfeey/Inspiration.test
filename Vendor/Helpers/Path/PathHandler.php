<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 02.01.2019
 * Time: 11:14
 */

namespace Helpers\Path;
use Helpers\IInnerEssence;

include_once __DIR__."/../IInnerEssence.php";
include_once "HelperPath.php";
include_once "IWay.php";


class PathHandler implements IWay, IInnerEssence
{
    use HelperPath;

    protected $ways;
    protected $resultWay;
    
    public function subLastSlash(): self
    {
        $this->resultWay = self::deleteLastSlashCustom($this->resultWay);
        return $this;
    }
    
    public function addLastSlash(): self
    {
        $this->resultWay = self::getLastSlashCustom($this->resultWay);
        return $this;
    }

    public function add (string $way): self {
        $this->ways [] = $way;
        $this->build();
        return $this;
    }

    public function subtract (string $way): self {
        $ways = $this->ways;
        if (in_array($way, $ways)) {
            $key = array_search($way, $ways);
            unset($ways[$key]);
        }
        $this->ways = $ways;
        $this->build();
        return $this;
    }

    private function build () {
        $result = "";
        foreach ($this->ways as $way) {
            $result .= self::getLastSlashCustom($way);
        }
        $this->resultWay = $result;
        return $this;
    }

    public function getResult (): string {
        return $this->resultWay;
    }

    public function isExists (): bool {
        $way = $this->getResult();
        $result = (is_dir($way) || is_file($way));
        return $result;
    }

    public function __construct(array $ways = null)
    {
        if (!is_null($ways)) {
            if (is_array($ways) && count($ways)!==0) {
                $this->ways = $ways;
                $this->build();
            }
        }else {
            $this->ways = [];
            $this->resultWay = "";
        }
    }

}