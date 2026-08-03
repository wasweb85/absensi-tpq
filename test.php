<?php
require 'vendor/autoload.php';
$r = new ReflectionMethod('Endroid\QrCode\Builder\Builder', '__construct');
foreach($r->getParameters() as $p) {
    echo $p->getName() . ' ';
}
echo PHP_EOL;
