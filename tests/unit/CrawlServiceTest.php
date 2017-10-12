<?php

use Imarc\Crawler\Services\CrawlerService;
use Imarc\Crawler\Exceptions\InvalidUrlException;

class CrawlServiceTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;
    private $url;

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
        $crawler = new CrawlerService('false');
    }

    // tests
    public function testValidateUrl()
    {
        $crawler = new CrawlerService('https://www.imarc.com');
        $this->assertInstanceOf(CrawlerService::class, $crawler);
    }

    /**
     * @expectedException Imarc\Crawler\Exceptions\InvalidUrlException
     */
    public function testSetUrlException()
    {
        $crawler = new CrawlerService('https://www.imarc.com');
        $crawler->setUrl('bad');
    }

    public function testSetUrlInstance()
    {
        $crawler = new CrawlerService('https://www.imarc.com');
        $this->assertInstanceOf(CrawlerService::class, $crawler->validateUrl());
    }
}
