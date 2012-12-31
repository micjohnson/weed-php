<?php
require_once dirname(__FILE__) . '/../../../autoload.php';

use WeedPhp\Transport\Curl;

/**
 * 
 * @author micjohnson
 * 
 * TODO test get, post, custom
 *
 */
class CurlTest extends PHPUnit_Framework_TestCase
{
    public function testCreateTransport()
    {
        $transport = new Curl();
        if($transport instanceof Curl) {
            $this->assertTrue(true);
        } else {
            $this->assertTrue(false);
        }
        return $transport;
    }
    
    /**
     * @depends testCreateTransport
     */
    public function testGet($transport)
    {
    	$response = $transport->get('http://httpbin.org/get?get=true');
    	echo $response;
    }
}