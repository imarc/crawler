<?php namespace Imarc\Crawler\Profiles;

use Spatie\Crawler\CrawlProfile;
use Spatie\Crawler\Url;
use Pimple\Container;

class CrawlDefault implements CrawlProfile
{
    protected $host = '';

    public function __construct(Container $container)
    {
        $this->host = parse_url($container['url'], PHP_URL_HOST);
        $this->exclude = $container['options']['exclude'];
    }

    public function isInternal(Url $url): bool
    {
        return $this->host === $url->host;
    }

    public function isExcluded(Url $url): bool
    {
        $extension = pathinfo((string) $url, PATHINFO_EXTENSION);
        return in_array($extension, $this->exclude);
    }

    public function shouldCrawl(Url $url): bool
    {
        if (!$this->isInternal($url)) {
            return false;
        }

        if ($this->isExcluded($url)) {
            return false;
        }

        return true;
    }
}
