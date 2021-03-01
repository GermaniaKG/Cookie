<?php
namespace tests;

use Germania\Cookie\Providers\PimpleServiceProvider;
use Germania\Cookie\CookieGetter;
use Germania\Cookie\CookieSetter;

use Pimple\ServiceProviderInterface;
use Pimple\Container;
use Psr\Log\LoggerInterface;

class PimpleServiceProviderTest extends \PHPUnit\Framework\TestCase
{


    /**
     * @dataProvider provideCtorArguments
     */
    public function testRegisteringServiceProvider( $logger)
    {
        $dic = new Container;

        $sut = new PimpleServiceProvider;
        $sut->register( $dic );

        $this->assertInstanceOf( ServiceProviderInterface::class, $sut );


        $this->assertInstanceOf( LoggerInterface::class, $dic['Cookie.Logger']);
        $this->assertInstanceOf( CookieGetter::class,    $dic[CookieGetter::class]);
        $this->assertInstanceOf( CookieSetter::class,    $dic[CookieSetter::class]);

        $this->assertIsArray(    $dic['Cookie.Config']);
        $this->assertIsCallable( $dic['Cookie.Getter']);
        $this->assertIsCallable( $dic['Cookie.Setter']);

    }

    public function provideCtorArguments()
    {
        $logger = $this->prophesize( LoggerInterface::class );

        return [
            [ null ],
            [ $logger->reveal() ]
        ];
    }
}
