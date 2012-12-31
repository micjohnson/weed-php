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
    	$response = json_decode($response,true);
    	$args = $response['args'];
    	
    	$this->assertTrue($args['get'] === "true");
    	$transport->close();
    }
    
    /**
     * @depends testCreateTransport
     */
    public function testPost($transport)
    {
    	$response = $transport->post('http://httpbin.org/post', array('post'=>'true'));

    	$response = json_decode($response,true);
    	$form = $response['form'];
    	 
    	$this->assertTrue($form['post'] === "true");
    	$transport->close();
    }
    
    /**
     * @depends testCreateTransport
     */
    public function testCustom($transport)
    {
    	$response = $transport->custom('http://httpbin.org/delete', 'DELETE');
 
    	echo $response;
    	$response = json_decode($response,true);
    	$this->assertTrue(count($response) > 0);
    	$transport->close();
    }
}