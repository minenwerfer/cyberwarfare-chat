<?php

include 'config.php';
include 'autoload.php';
include 'security.php';

$c = isset($_GET['c']) ? $_GET['c'] : 'Home';
$m = isset($_GET['m']) ? $_GET['m'] : 'index';

$controllerName = "\\Controller\\$c";
$methodName = $m;

$controller = new $controllerName;
$controller->$m();