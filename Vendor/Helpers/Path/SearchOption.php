<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 11.12.2018
 * Time: 23:34
 */

namespace Helpers\Path;
 include_once "IPathOption.php";

class SearchOption implements IPathOption
{
    public $searchIsRecurse;

    public static function searchOnlyHere()
    {
        return new self();
    }
    public static function searchRecurse()
    {
        return new self (true);
    }

    public function __construct($searchIsRecurse = false)
    {
        $this->searchIsRecurse = $searchIsRecurse;
    }
}