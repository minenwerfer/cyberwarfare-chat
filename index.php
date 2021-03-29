<?php

include 'config.php';
include 'autoload.php';
include 'security.php';

date_default_timezone_set(DEFAULT_TMZ);

\PluginManager::getInstance()->load([
    '\Plugin\PCore',
    '\Plugin\PClening',
    '\Plugin\PFortuneTeller'
]);

$c = isset($_GET['c']) ? $_GET['c'] : 'Home';
$m = isset($_GET['m']) ? $_GET['m'] : 'index';

$controllerName = "\\Controller\\$c";
$methodName = $m;

$controller = new $controllerName;
$controller->$m();