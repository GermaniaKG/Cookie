<?php
namespace Germania\Cookie;

use Psr\Log\LoggerInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\NullLogger;
use Psr\Log\LogLevel;

trait LoggerTrait
{

    use LoggerAwareTrait;

    /**
     * PSR-3 LogLevel name
     * @var string
     */
    public $loglevel = LogLevel::INFO;



}
