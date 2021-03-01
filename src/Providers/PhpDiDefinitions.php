<?php
namespace Germania\Cookie\Providers;

use Germania\Cookie\LoggerTrait;
use Germania\Cookie\CookieGetter;
use Germania\Cookie\CookieSetter;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class PhpDiDefinitions {

    use LoggerTrait;

    protected $definitions = array();

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

    public function getArray() : array
    {
        return array(
            'Cookie.Config' => function($dic) : array {
                return $this->cookie_config;
            },

            'Cookie.Logger' => function($dic) : LoggerInterface {
                return $this->logger;
            },

            // BC Layer
            'Cookie.Getter' => function($dic) : callable {
                return $dic->get(CookieGetter::class);
            },

            CookieGetter::class => function($dic) : CookieGetter {
                $logger = $dic->get('Cookie.Logger');
                return new CookieGetter( \INPUT_COOKIE, $logger );
            },


            // BC Layer
            'Cookie.Setter' => function($dic) : callable {
                return $dic->get(CookieSetter::class);
            },

            CookieSetter::class => function( $dic ) : CookieSetter {
                $cookie_config = $dic->get('Cookie.Config');
                $logger        = $dic->get('Cookie.Logger');

                return new CookieSetter( [
                    'path'     => $cookie_config['path'],
                    'secure'   => $cookie_config['secure'],
                    'httponly' => $cookie_config['httponly']
                ], $logger );
            }
        );
    }
}
