<?php
namespace Germania\Cookie\Providers;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

use Germania\Cookie\CookieGetter;
use Germania\Cookie\CookieSetter;

use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class PimpleServiceProvider implements ServiceProviderInterface
{

    /**
     * @var LoggerInterface
     */
    public $logger;

    /**
     * @var array
     */
    public $cookie_config;


    /**
     * @param array|null $cookie_config Optional: PSR3 Logger instance
     * @param LoggerInterface|null $logger Optional: PSR3 Logger instance
     */
    public function __construct( array $cookie_config = null, LoggerInterface $logger = null)
    {
        $this->cookie_config = $cookie_config ?: [
            "path" =>     null,
            "secure" =>   true,
            "httponly" => true
        ];
        $this->logger = $logger ?: new NullLogger;
    }


    /**
     * @implements ServiceProviderInterface
     */
    public function register(Container $dic)
    {

        /**
         * @return StdClass
         */
        $dic['Cookie.Config'] = function( $dic ) {
            return (object) $this->cookie_config;
        };


        /**
         * @return LoggerInterface
         */
        $dic['Cookie.Logger'] = function( $dic ) {
            return $this->logger;
        };


        /**
         * @return Callable
         */
        $dic['Cookie.Getter'] = function( $dic ) {
            $logger = $dic['Cookie.Logger'];
            return new CookieGetter( \INPUT_COOKIE, $logger );
        };


        /**
         * @return Callable
         */
        $dic['Cookie.Setter'] = function( $dic ) {
            $cookie_config = $dic['Cookie.Config'];
            $logger        = $dic['Cookie.Logger'];

            return new CookieSetter( [
                'path'     => $cookie_config->path,
                'secure'   => $cookie_config->secure,
                'httponly' => $cookie_config->httponly
            ], $logger );
        };

    }
}

