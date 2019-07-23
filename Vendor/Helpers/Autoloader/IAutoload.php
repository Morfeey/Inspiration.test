<?php

namespace Helpers\Autoloader;

interface IAutoload {
    public function getFiles(): array;
}