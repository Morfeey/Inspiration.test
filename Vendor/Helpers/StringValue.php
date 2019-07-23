<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 11.12.2018
 * Time: 23:08
 */

namespace Helpers;


class StringValue implements IInnerEssence
{
    private $result;

    public function replace (string $search, string $replace) : self {
        $this->result = str_replace($search, $replace, $this->result);
        return $this;
    }

    public function toUp () {
        $this->result = strtoupper($this->result);
        return $this;
    }

    public function toLow () {
        $this->result = strtolower($this->result);
        return $this;
    }

    public function firstCharUp () :self {
        $firstChar = $this->getResult()[0];
        $firstCharUp =
            (new self($firstChar))
            ->toUp()
            ->getResult();
        $result =
            (new self($this->result))
            ->replace($firstChar, $firstCharUp)
            ->getResult();
        $this->result = $result;
        return $this;
    }

    public function firstCharLow () :self {
        $firstChar = $this->getResult()[0];
        $firstCharLow =
            (new self($firstChar))
                ->toLow()
                ->getResult();
        $result =
            (new self($this->result))
                ->replace($firstChar, $firstCharLow)
                ->getResult();
        $this->result = $result;
        return $this;
    }

    public function __construct(string $string)
    {
        $this->result = $string;
    }

    public function toCamelCase () {
        $result = $this->result;
        $explode = explode('_', $result);
        if (count($explode)!==0) {
            $result = "";
            foreach ($explode as $part) {
                $result .= (new self($part))
                    ->firstCharUp()
                    ->getResult()
                ;
            }
        }
        $this->result = $result;
        return $this;
    }

    /**
     * @return string
     */
    public function getResult() : string
    {
        return $this->result;
    }

    public function isContains (string $Item) :bool {
        $str = stristr($this->result, $Item);
        $result = (strlen($str) === 0 || !$str);
        return $result;
    }

    public function isContained (string $inItem) :bool {
        $str = stristr($inItem, $this->result);
        $result = (strlen($str) === 0 || !$str);
        return $result;
    }

}