#!/usr/bin/env php
<?php
// application.php

require dirname(__DIR__) . '/vendor/autoload.php';

use Symfony\Component\Console\Application;
use Imarc\Crawler\Commands\CrawlCommand;

$application = new Application();

// ... register commands
$application->add(new CrawlCommand());

$application->run();
