<?php
namespace Germania\Cookie;

use Psr\Log\LoggerInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\NullLogger;

/**
 * Callable wrapper around PHP's setcookie function with logging opportunity.
 */

class CookieSetter implements LoggerAwareInterface
{
    use LoggerTrait;

    /**
     * @var array
     */
    public $defaults = [
        'path'     => '',
        'domain'   => '',
        'secure'   => true,
        'httponly' => true,
        'samesite' => 'Lax'
    ];


    /**
     * @param array                $defaults Cookie defaults
     * @param LoggerInterface|null $logger   Optional PSR-3 Logger
     */
    public function __construct( $defaults = array(), LoggerInterface $logger = null)
    {
        $this->defaults = array_merge($this->defaults, $defaults);
        $this->setLogger($logger ?: new NullLogger);
    }

    /**
     * See PHP doc for setcookie:
     * http://php.net/manual/de/function.setcookie.php
     *
     * @param  string  $name    Cookie name
     * @param  string  $value   Cookie value
     * @param  int     $expires Expiration timestamp
     *
     * @return bool    Result of PHP's setcookie function
     */
    public function __invoke( $name, $value, $expires )
    {
        extract( $this->defaults );

        $result = setcookie( $name, $value, [
            'expires'  => $expires,
            'path'     => $path,
            'domain'   => $domain,
            'secure'   => $secure,
            'httponly' => $httponly,
            'samesite' => $samesite
        ]);
        $this->logger->log($this->loglevel, "Set Cookie: ", ['name' => $name, 'result' => $result]);
        return $result;
    }
}
