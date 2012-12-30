<?php

require_once dirname(__FILE__) . '/../../../../lib/WeedPhp/Transport/Curl.php';

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
        $transport = new WeedPhp\Transport\Curl();
        if($transport instanceof WeedPhp\Transport\Curl) {
            $this->assertTrue(true);
        } else {
            $this->assertTrue(false);
        }
        return $transport;
    }
}