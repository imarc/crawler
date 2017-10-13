#!/usr/bin/env php
<?php

require dirname(__DIR__) . '/vendor/autoload.php';

use Symfony\Component\Console\Application;
use Imarc\Crawler\Commands\CsvCommand;

$application = new Application('Imarc Crawler');

// ... register commands
$application->add(new CsvCommand());

$application->run();
