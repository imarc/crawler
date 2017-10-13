<?php namespace Imarc\Crawler\Observers;

use Spatie\Crawler\CrawlObserver;
use Spatie\Crawler\Url;
use Symfony\Component\Console\Output\OutputInterface;
use Pimple\Container;
use League\Csv\Writer;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Style\SymfonyStyle;

class CsvObserver implements CrawlObserver
{
    const DATE_FORMAT = 'n/d/y h:sA';
    private $showProgress = true;
    private $output;
    private $destination;
    private $writer;
    private $progress;
    private $totalPages = 0;
    private $timeStart;
    private $timeEnd;
    private $quiet = false;

    public function __construct(Container $container)
    {
        $this->showProgress = $container['options']['showProgress'];
        $this->output = $container['output'];
        $this->destination = $container['destination'];
        $this->writer = Writer::createFromPath($this->destination, 'w+');
        $this->io = new SymfonyStyle($container['input'], $container['output']);
        $this->quiet = $container['options']['quiet'];

        if ($this->quiet) {
            return;
        }

        $this->timeStart = microtime(true);
        $this->writer->insertOne(['url']);
        $formattedDate = date(self::DATE_FORMAT, $this->timeStart);

        $this->io->title("Starting crawl of {$container['url']} at {$formattedDate}.");

        if ($this->showProgress) {
            $this->progress = new ProgressBar($this->output);
            $this->progress->setFormat('%elapsed% %bar% %message%');
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
        if ($this->quiet) {
            return;
        }

        if ($this->showProgress)
        {
            $this->progress->advance();
            $this->progress->setMessage('CRAWLING ' . $url);
        }

        $this->writer->insertOne([(string) $url]);
        $this->totalPages++;
    }

    /**
     * Called when the crawl has ended.
     */
    public function finishedCrawling()
    {
        if ($this->quiet) {
            return;
        }

        if ($this->showProgress) {
            $this->progress->finish();
            $this->output->writeLn("\n");
        }

        $this->timeEnd = microtime(true);
        $formattedDate = date(self::DATE_FORMAT, $this->timeEnd);
        $totalTime = ($this->timeEnd - $this->timeStart);
        $this->io->success("Finished crawling {$this->totalPages} URL(s) at {$formattedDate}.");
        $this->io->block("Total time was {$totalTime} seconds.");
    }
}
