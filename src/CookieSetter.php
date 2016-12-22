<?php
namespace Germania\Cookie;

use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class CookieSetter
{

    /**
     * @var LoggerInterface
     */
    public $logger;


    /**
     * @var array
     */
    public $defaults = [
        'path'     => '',
        'domain'   => '',
        'secure'   => false,
        'httponly' => false
    ];


    /**
     * @param array                $defaults Cookie defaults
     * @param LoggerInterface|null $logger   Optional PSR-3 Logger
     */
    public function __construct( $defaults = array(), LoggerInterface $logger = null)
    {
        $this->defaults = array_merge($this->defaults, $defaults);
        $this->logger = $logger ?: new NullLogger;
    }

    /**
     * See PHP doc for setcookie:
     * http://php.net/manual/de/function.setcookie.php
     *
     * @param  string  $name
     * @param  string  $value
     * @param  int     $expire
     * @return bool
     */
    public function __invoke( $name, $value, $expire )
    {
        extract( $this->defaults );

        $result = setcookie( $name, $value, $expire, $path, $domain, $secure, $httponly);
        $this->logger->info("Set Cookie: ", ['name' => $name, 'result' => $result]);
        return $result;
    }
}
