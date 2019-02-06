<?php
namespace tests;

use Germania\Cookie\Providers\PimpleServiceProvider;

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

        $this->assertInstanceOf( LoggerInterface::class, $dic['Cookie.Logger'] );
        $this->assertTrue( is_callable( $dic['Cookie.Getter']) );
        $this->assertTrue( is_callable( $dic['Cookie.Setter']) );

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
