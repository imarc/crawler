<?php


class CrawlDefaultProfileTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;
    private $container;
    private $profile;

    protected function setUp()
    {
        $this->container = new \Pimple\Container();
        $this->container['url'] = 'https://www.imarc.com';
        $this->container['options'] = [
            'exclude' => ['pdf', 'css',]
        ];

        $this->profile = new \Imarc\Crawler\Profiles\CrawlDefault($this->container);
    }

    protected function _before()
    {
    }

    protected function _after()
    {
    }

    // tests
    public function testIsInternal()
    {
        $bad = 'https://google.com';
        $good = 'https://www.imarc.com/some-place';
        $this->assertFalse($this->profile->isInternal(new \Spatie\Crawler\Url($bad)));
        $this->assertTrue($this->profile->isInternal(new \Spatie\Crawler\Url($good)));
    }

    public function testIsExcluded()
    {
        $bad = 'https://www.imarc.com/omega.css';
        $good = 'https://www.imarc.com/some-place';
        $this->assertTrue($this->profile->isExcluded(new \Spatie\Crawler\Url($bad)));
        $this->assertFalse($this->profile->isExcluded(new \Spatie\Crawler\Url($good)));
    }

    public function testShouldCrawl()
    {
        $bad = 'https://www.imarc.com/omega.css';
        $badToo = 'https://google.com';
        $good = 'https://www.imarc.com/parseable';

        $this->assertFalse($this->profile->shouldCrawl(new \Spatie\Crawler\Url($bad)));
        $this->assertFalse($this->profile->shouldCrawl(new \Spatie\Crawler\Url($badToo)));
        $this->assertTrue($this->profile->shouldCrawl(new \Spatie\Crawler\Url($good)));
    }
}
