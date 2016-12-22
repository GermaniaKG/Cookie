<?php
namespace Germania\Cookie;

use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class CookieGetter
{
    public $logger;
    public $input_type;

    public $filter_type = \FILTER_SANITIZE_STRING;

    /**
     * See: http://php.net/manual/de/function.filter-input.php
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
     * @var    string $name Cookie name
     * @return string Cookie value
     */
    public function __invoke( $name )
    {
        $value = filter_input( $this->input_type, $name, $this->filter_type );

        $this->logger->info("Get Cookie: ", ['name' => $name, 'value' => $value]);
        return $value;
    }
}
