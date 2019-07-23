<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 11.12.2018
 * Time: 23:05
 */

namespace Helpers\Path;

include_once __DIR__."/../IInnerEssence.php";
include_once "IPathOption.php";
include_once "SearchOption.php";
include_once "PathHandler.php";

use Helpers\IInnerEssence;

class Directory implements IInnerEssence
{
    use HelperPath;
    protected $Directory;

    /**
     * Search Directories  (opening {a,b,c}, for search on 'a'|'b'|'c')
     * @param string|* $Pattern
     * @param IPathOption|null $searchOption |default SearchOption::searchOnlyHere()
     * @return array
     */
    public function getDirectories(string $Pattern = "*", IPathOption $searchOption = null): array
    {
        $searchOption = (is_null($searchOption)) ? new SearchOption() : $searchOption;
        $GetDirectories = function (string $Directory) use (&$GetDirectories, $Pattern, $searchOption) {
            $result = [];
            $Directory = $this->getLastSlashCustom($Directory);
            $List = glob($Directory . $Pattern, GLOB_BRACE | GLOB_MARK | GLOB_ONLYDIR);
            foreach ($List as $Item) {
                $Item = $this->deleteLastSlashCustom($Item);
                array_push($result, $Item);
                $Temp = $GetDirectories($Item);
                if ($searchOption->searchIsRecurse && count($Temp) != 0) {
                    foreach ($Temp as $Item) {
                        array_push($result, $Item);
                    }
                }
            }
            return $result;
        };
        $result = $GetDirectories($this->Directory);
        return $result;
    }

    /**
     * Search Files  (opening {a,b,c}, for search on 'a'|'b'|'c')
     * @param string|* $Pattern
     * @param IPathOption|SearchOption $searchOption
     * @return array
     */
    public function getFiles(string $Pattern = "*", IPathOption $searchOption = null): array
    {
        $searchOption = (is_null($searchOption)) ? new SearchOption() : $searchOption;
        $GetFiles = function (string $Directory) use (&$GetFiles, $Pattern, $searchOption) {
            $result = [];
            $Dir = $this->getLastSlashCustom($Directory);
            $Directories = glob("$Dir*", GLOB_ONLYDIR | GLOB_MARK);
            $Files = function () use ($Dir, $Pattern) {
                $result = [];
                $files = glob($Dir . $Pattern, GLOB_BRACE);
                foreach ($files as $Item) {
                    if (is_file($Item)) {
                        array_push($result, $Item);
                    }
                }
                return $result;
            };
            $result = array_merge($result, $Files());
            if ($searchOption->searchIsRecurse) {
                foreach ($Directories as $Item) {
                    $result = array_merge($result, $GetFiles($Item));
                }
            }
            return $result;
        };
        $result = $GetFiles($this->Directory);
        return $result;
    }

    public function getLastDir(): self
    {
        $exp = explode("/", $this->Directory);
        $result = $exp[count($exp) - 1];
        $this->Directory = $result;
        return $this;
    }

    /** exit to the parent directory
     * @return Directory
     */
    public function parent(): self
    {
        $ListLevels = explode("/", $this->Directory);
        unset($ListLevels[count($ListLevels) - 1]);
        $result = "";
        foreach ($ListLevels as $level) {
            $result .= $level . "/";
        }
        $this->Directory = $this->deleteLastSlashCustom($result);
        return $this;
    }

    /** merging of paths
     * @param string $MergedDirectory
     * @param string|null $Directory
     * @return Directory
     */
    public function merge(string $MergedDirectory, string $Directory = null): self
    {
        $Directory = (is_null($Directory)) ? $this->Directory : $Directory;
        $this->Directory = $this->getLastSlashCustom($Directory) . $MergedDirectory;
        return $this;
    }

    public function deleteLastSlash(): self
    {
        $this->Directory = self::deleteLastSlashCustom($this->Directory);
        return $this;
    }

    public function getResult() :string
    {
        return $this->Directory;
    }

    public function setLastSlash(): self
    {
        $this->Directory = self::getLastSlashCustom($this->Directory);
        return $this;
    }

    /**
     * Directory constructor.
     * @param $Directories | default self::getDocumentRoot
     */
    public function __construct($Directories = null)
    {
        $PathHandler = new PathHandler();
        $params = func_get_args();
        if (!is_null($params) && count($params) != 0) {
            foreach ($params as $ItemDirectory) {
                $PathHandler->add($ItemDirectory);
            }
            $this->Directory = $PathHandler->getResult();
        } else {
            $this->Directory = self::getDocumentRoot();
        }
    }
}