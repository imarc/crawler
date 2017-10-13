<?php

use Imarc\Crawler\Services\CrawlerService;
use Imarc\Crawler\Exceptions\InvalidUrlException;
use Symfony\Component\Console\Tester\ApplicationTester;
use Symfony\Component\Console\Application;

class CrawlServiceTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;
    private $url;
    private $container;
    private $application;

    protected function setUp()
    {
        $application = new Application();
        $applicationTester = new ApplicationTester($application);
        $application->setAutoExit(false);

        $this->container = new \Pimple\Container();
        $this->container['url'] = 'https://imarc.com';
        $this->container['destination'] = '../_output/urls.csv';
        $this->container['options'] = [
            'showProgress' => false,
            'output' => $applicationTester->getOutput(),
        ];
    }

    protected function _before()
    {
    }

    protected function _after()
    {
    }


    /**
     * @expectedException Imarc\Crawler\Exceptions\InvalidUrlException
     */
    public function testInvalidUrlException()
    {
        $this->container['url'] = 'bad';
        $crawler = new CrawlerService($this->container);
    }

    // tests
    public function testValidateUrl()
    {
        $crawler = new CrawlerService($this->container);
        $this->assertInstanceOf(CrawlerService::class, $crawler);
    }

    /**
     * @expectedException Imarc\Crawler\Exceptions\InvalidUrlException
     */
    public function testSetUrlException()
    {
        $crawler = new CrawlerService($this->container);
        $crawler->setUrl('bad');
    }

    public function testSetUrlInstance()
    {
        $crawler = new CrawlerService($this->container);
        $this->assertInstanceOf(CrawlerService::class, $crawler->validateUrl());
    }
}
