<?php
namespace Germania\Cookie;

use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class CookieGetter
{
    public $logger;
    public $input_type;

    /**
     * See: http://php.net/manual/de/function.filter-input.php
     * @param int                  $input_type  INPUT_GET, INPUT_POST, INPUT_COOKIE, INPUT_SERVER oder INPUT_ENV
     * @param LoggerInterface|null $logger      PSR-3 Logger
     */
    public function __construct( $input_type, LoggerInterface $logger = null)
    {
        $this->input_type = $input_type;
        $this->logger     = $logger ?: new NullLogger;
    }

    public function __invoke( $name )
    {
        $value = filter_input( $this->input_type, $name, \FILTER_SANITIZE_STRING );

        $this->logger->info("Get Cookie: ", ['name' => $name, 'value' => $value]);
        return $value;
    }
}
