<?php namespace Imarc\Crawler\Services;

use Imarc\Crawler\Exceptions\InvalidUrlException;
use Imarc\Crawler\Observers\CsvObserver;
use Pimple\Container;
use Respect\Validation\Validator as v;
use Spatie\Crawler\Crawler;
use Spatie\Crawler\CrawlObserver as ObserverInterface;

class CrawlerService {

    private $url;
    private $options;
    private $observer = CsvObserver::class;
    private $crawler;

    public function __construct(Container $container)
    {
        $this->url = $container['url'];
        $this->container = $container;
        $this->observer = $container['crawler'] ?? $this->observer;

        $this->validateUrl();
        $this->initCrawler();
    }

    public function setUrl(string $url)
    {
        $this->url = $url;
        $this->validateUrl();
        return $this;
    }

    public function validateUrl()
    {
        if (v::url()->validate($this->url)) {
            return $this;
        }

        throw new InvalidUrlException();
    }

    public function crawl()
    {
        $this->crawler->startCrawling($this->url);
    }

    public function setObserver(ObserverInterface $observer)
    {
        $this->observer = $observer;
        $this->initCrawler();
    }

    private function initCrawler()
    {
        $observer = new $this->observer($this->container);
        $crawler = Crawler::create()->setCrawlObserver($observer);

        $this->crawler = $crawler;
    }
}
