<?php
namespace Germania\Cookie\Providers;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Germania\Cookie\LoggerTrait;
use Germania\Cookie\CookieGetter;
use Germania\Cookie\CookieSetter;

use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class PimpleServiceProvider implements ServiceProviderInterface
{
    use LoggerTrait;

    /**
     * @var array
     */
    public $cookie_config = [
        'path'     => '',
        'domain'   => '',
        'secure'   => true,
        'httponly' => true,
        'samesite' => 'Lax'
    ];


    /**
     * @param array|null $cookie_config Optional: PSR3 Logger instance
     * @param LoggerInterface|null $logger Optional: PSR3 Logger instance
     */
    public function __construct( array $cookie_config = null, LoggerInterface $logger = null)
    {
        if (is_array($cookie_config)):
            $this->cookie_config = array_merge($this->cookie_config, $cookie_config);
        endif;

        $this->logger = $logger ?: new NullLogger;
    }


    /**
     * @implements ServiceProviderInterface
     */
    public function register(Container $dic)
    {

        /**
         * @return array
         */
        $dic['Cookie.Config'] = function() {
            return $this->cookie_config;
        };


        /**
         * @return LoggerInterface
         */
        $dic['Cookie.Logger'] = function() {
            return $this->logger;
        };


        /**
         * @return Callable
         */
        $dic[CookieGetter::class] = function( $dic ) {
            $logger = $dic['Cookie.Logger'];
            return new CookieGetter( \INPUT_COOKIE, $logger );
        };


        /**
         * @return Callable
         */
        $dic['Cookie.Getter'] = function( $dic ) {
            return $dic[CookieGetter::class];
        };


        /**
         * @return Callable
         */
        $dic['Cookie.Setter'] = function( $dic ) {
            return $dic[CookieSetter::class];
        };


        /**
         * @return Callable
         */
        $dic[CookieSetter::class] = function( $dic ) {
            $cookie_config = $dic['Cookie.Config'];
            $logger        = $dic['Cookie.Logger'];

            return new CookieSetter( [
                'path'     => $cookie_config['path'],
                'secure'   => $cookie_config['secure'],
                'httponly' => $cookie_config['httponly']
            ], $logger );
        };

    }
}

