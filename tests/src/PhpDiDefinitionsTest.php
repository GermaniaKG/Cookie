<?php
namespace tests;

use Germania\Cookie\Providers\PhpDiDefinitions;
use Germania\Cookie\CookieGetter;
use Germania\Cookie\CookieSetter;
use Psr\Log\LoggerInterface;

class PhpDiDefinitionsTest extends \PHPUnit\Framework\TestCase
{


    public function testRegisteringServiceProvider()
    {

        $sut = new PhpDiDefinitions;
        $builder = new \DI\ContainerBuilder();
        $builder->addDefinitions( $sut->getArray() );
        $container = $builder->build();

        $this->assertInstanceOf( LoggerInterface::class, $container->get('Cookie.Logger'));
        $this->assertInstanceOf( CookieGetter::class, $container->get(CookieGetter::class));
        $this->assertInstanceOf( CookieSetter::class, $container->get(CookieSetter::class));

        $this->assertIsArray( $container->get('Cookie.Config'));
        $this->assertIsCallable( $container->get('Cookie.Getter'));
        $this->assertIsCallable( $container->get('Cookie.Setter'));

    }
}
