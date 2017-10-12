<?php namespace Imarc\Crawler\Observers;

use Spatie\Crawler\CrawlObserver;
use Spatie\Crawler\Url;
use Symfony\Component\Console\Output\OutputInterface;
use Pimple\Container;
use League\Csv\Writer;
use Symfony\Component\Console\Helper\ProgressBar;

class CsvObserver implements CrawlObserver
{
    private $showProgress = true;
    private $output;
    private $destination;
    private $writer;
    private $progress;
    private $totalPages = 0;

    public function __construct(Container $container)
    {
        $this->showProgress = $container['options']['showProgress'];
        $this->output = $container['output'];
        $this->destination = $container['destination'];
        $this->writer = Writer::createFromPath($this->destination, 'w+');
        $this->writer->insertOne(['url']);
        $this->output->writeLn('Starting crawl at');

        if ($this->showProgress) {
            $this->progress = new ProgressBar($this->output);
            $this->progress->start();
        }
    }
    /**
     * Called when the crawler will crawl the url.
     *
     * @param \Spatie\Crawler\Url $url
     */
    public function willCrawl(Url $url)
    {
    }

    /**
     * Called when the crawler has crawled the given url.
     *
     * @param \Spatie\Crawler\Url                      $url
     * @param \Psr\Http\Message\ResponseInterface|null $response
     */
    public function hasBeenCrawled(Url $url, $response, Url $foundOnUrl = null)
    {
        if ($this->showProgress)
        {
            $this->progress->advance(1);
            $this->progress->setMessage((string) $url);
        }

        $this->writer->insertOne([(string) $url]);
        $this->totalPages++;
    }

    /**
     * Called when the crawl has ended.
     */
    public function finishedCrawling()
    {
        if ($this->showProgress) {
            $this->progress->finish();
        }

        $this->output->writeLn('Finished crawl at');
    }
}
