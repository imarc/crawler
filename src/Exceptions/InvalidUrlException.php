<?php namespace Imarc\Crawler\Exceptions;

class InvalidUrlException extends \Exception
{
    public function __construct(
        $message = 'You must specify a valid URL.',
        $code = 0,
        Exception $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
