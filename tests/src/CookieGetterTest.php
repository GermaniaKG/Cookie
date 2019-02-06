<?php
namespace tests;

use Germania\Cookie\CookieGetter;
use Psr\Log\NullLogger;
use Slim\Http\Environment;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Http\Uri;
use Slim\Http\Headers;
use Slim\Http\RequestBody;
use Slim\Http\UploadedFile;

class CookieGetterTest extends \PHPUnit\Framework\TestCase
{

    /**
     * @dataProvider provideTestData
     */
    public function testOnExpectedValues( $cookies, $key, $expected_value, $input_type, $filter_type )
    {
        $logger      = null;

        $sut = new CookieGetter( $input_type, $logger, $filter_type);
        $request = $this->requestFactory( $cookies );

        $response = new Response();

        $runner = function($request, $response) use ($sut, $key){
            // Hard-code into Superglobals
            foreach($request->getCookieParams() as $name => $val) {
                $_COOKIE[ $name ] = $val;
                $_GET[ $name ] = $val;
                $_POST[ $name ] = $val;
                $_SERVER[ $name ] = $val;
                $_ENV[ $name ] = $val;
            }

            return $sut( $key);
        };
        $result = $runner( $request, $response);

        $this->assertEquals($expected_value, $result);
    }


    public function provideTestData() {
        $result = 'john';
        $key    = 'user';

        $cookies = [ $key => $result ];

        return array(
            array( $cookies, $key, $result, null, null ) ,
            array( $cookies, $key, $result, \INPUT_GET, null ) ,
            array( $cookies, $key, $result, \INPUT_POST, null ) ,
            array( $cookies, $key, $result, \INPUT_COOKIE, \FILTER_SANITIZE_STRING ) ,
            array( $cookies, $key, $result, \INPUT_SERVER, null ) ,
            array( $cookies, $key, $result, \INPUT_ENV, null ),
            array( $cookies, $key, null, -11, null )
        );
    }

    public function requestFactory( $cookies )
    {
        $env = Environment::mock();

        $uri = Uri::createFromString('/foo/bar');
        $headers = Headers::createFromEnvironment($env);
        $serverParams = $env->all();
        $body = new RequestBody();
        $uploadedFiles = UploadedFile::createFromEnvironment($env);
        $request = new Request('GET', $uri, $headers, $cookies, $serverParams, $body, $uploadedFiles);
        return $request;
    }

}

