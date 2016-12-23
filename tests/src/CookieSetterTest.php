<?php
namespace tests;

use Germania\Cookie\CookieSetter;

class CookieSetterTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @todo Remove that "@ silence" operator before running $sut
     */
    public function testInstantiation(  )
    {
        $logger      = null;
        $defaults = [];

        $sut = new CookieSetter( $defaults, $logger);

        $name = "foo";
        $value = "foo";
        $expire = time() + 10;

        // Code smell: silence operator
        $result = @$sut($name, $value, $expire );

        $this->assertInternalType("bool", $result);
    }
}
