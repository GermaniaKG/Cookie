<?php
namespace Germania\Cookie;

use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;


/**
 * Callable wrapper for retrieving and filtering cookie values with logging opportunity.
 */
class CookieGetter
{

    /**
     * @var LoggerInterface
     */
    public $logger;

    /**
     * @var int
     */
    public $input_type;


    /**
     * @var int
     * @see http://php.net/manual/de/filter.filters.php
     */
    public $filter_type = \FILTER_SANITIZE_STRING;


    /**
     * @see http://php.net/manual/de/function.filter-input.php
     * @see http://php.net/manual/de/filter.filters.php
     *
     * @param int                  $input_type  INPUT_GET, INPUT_POST, INPUT_COOKIE, INPUT_SERVER oder INPUT_ENV
     * @param LoggerInterface|null $logger      PSR-3 Logger, default: null
     * @param int                  filter_type  Filter type, default: null
     */
    public function __construct( $input_type, LoggerInterface $logger = null, $filter_type = null)
    {
        $this->input_type  = $input_type;
        $this->logger      = $logger ?: new NullLogger;

        if (!is_null($filter_type)) {
            $this->filter_type = $filter_type;
        }

    }

    /**
     * Returns the cookie value.
     *
     * This method does not use filter_input here since it is hard to unit-test;
     * see: http://stackoverflow.com/questions/4158307/how-to-make-a-phpunit-test-that-depends-on-real-post-get-data
     *
     * @var    string $name Cookie name
     * @return string Cookie value
     */
    public function __invoke( $name )
    {
        switch( $this->input_type ) {
            case \INPUT_GET:
                $input = $_GET;
                break;
            case \INPUT_POST:
                $input = $_POST;
                break;
            case \INPUT_COOKIE:
                $input = $_COOKIE;
                break;
            case \INPUT_SERVER:
                $input = $_SERVER;
                break;
            case \INPUT_ENV:
                $input = $_ENV;
                break;
            default:
                return null;
                break;
        }

        $value = isset($input[$name])
                 ? filter_var( $input[$name], $this->filter_type )
                 : null;

        $this->logger->info("Get Cookie: ", ['name' => $name, 'value' => $value]);
        return $value;
    }
}
