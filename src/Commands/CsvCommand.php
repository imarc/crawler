<?php namespace Imarc\Crawler\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Imarc\Crawler\Services\CrawlerService;
use Pimple\Container;

class CsvCommand extends Command
{
    private $url;
    private $container;

    protected function configure()
    {
        $this->container = new Container();

        $this->setName('csv')
            ->setDescription('Crawls a website.')
            ->setHelp("Crawl a site why don't ya.");

        $this->addArgument('url', InputArgument::REQUIRED, 'URL to crawl.');
        $this->addArgument('destination', InputArgument::REQUIRED, 'Write CSV to FILE');
        $this->addOption(
            'show-progress',
            's',
            InputOption::VALUE_NONE,
            "Show the crawl's progress"
        );

        $this->addOption(
            'crawl-external',
            'e',
            InputOption::VALUE_NONE,
            "Crawl external URLs"
        );

        $this->addOption(
            'quiet',
            'q',
            InputOption::VALUE_NONE,
            "Silence all output"
        );

        $this->addOption(
            'exclude',
            null,
            InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY,
            "Exclude certain extensions",
            [
                'css',
                'gif',
                'ico',
                'jpg',
                'jpg',
                'js',
                'pdf',
                'pdf',
                'png',
                'rss',
                'txt',
            ]
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->container['url'] = $input->getArgument('url');
        $this->container['destination'] = $input->getArgument('destination');
        $this->container['output'] = $output;
        $this->container['input'] = $input;
        $this->container['options'] = [
            'showProgress' => $input->getOption('show-progress'),
            'crawlExternal' => $input->getOption('crawl-external'),
            'quiet' => $input->getOption('quiet'),
            'exclude' => $input->getOption('exclude'),
        ];

        $crawler = new CrawlerService($this->container);
        $crawler->crawl();
    }
}
