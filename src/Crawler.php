#!/usr/bin/env php
<?php

$paths = [
    __DIR__ . '/../vendor/autoload.php', // locally
    __DIR__ . '/../../../autoload.php' // dependency
];

foreach ($paths as $path) {
    if (file_exists($path)) {
        require_once $path;
        break;
    }
}


use Symfony\Component\Console\Application;
use Imarc\Crawler\Commands\CsvCommand;

$application = new Application('Imarc Crawler');

// ... register commands
$application->add(new CsvCommand());

$application->run();
