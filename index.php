<?php

include_once "Vendor/Helpers/bootstrap.php";

print "<pre>";
print_r((new \Helpers\DataBase\Config())->getConnect());
print "</pre>";