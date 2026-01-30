<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
echo "SESSION CONFIG:\n";
print_r($app['config']->get('session'));
