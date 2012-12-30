<?php
require_once dirname(__FILE__) . '/../../../lib/WeedPhp/Client.php';
require_once dirname(__FILE__) . '/../../../lib/WeedPhp/Transport/Curl.php';

class ClientTest extends PHPUnit_Framework_TestCase
{
    public function testCreateClient()
    {
        $weedClient = new WeedPhp\Client('http://localhost:9333');
        if($weedClient instanceof WeedPhp\Client) {
            $this->assertTrue(true);
        } else {
            $this->assertTrue(false);
        }
        
        return $weedClient;
    }
    
    /**
     * @depends testCreateClient
     */
    public function testAssign($weedClient)
    {
        $response = $weedClient->assign();
        $response = json_decode($response, true);
        $this->assertEquals(1, $response['count']);
        return $response;
    }
    
    /**
     * @depends testCreateClient
     * @depends testAssign
     */
    public function testStoreFile($weedClient, $assignResponse)
    {
        $volumeServerAddress = $assignResponse['publicUrl'];
        $fid = $assignResponse['fid'];
        $file = "HelloWeed";
        $response = $weedClient->store($volumeServerAddress, $fid, $file);
        $response = json_decode($response, true);
        $this->assertEquals(9, $response['size']);
    }
    
    /**
     * @depends testCreateClient
     * @depends testAssign
     */
    public function testLookup($weedClient, $assignResponse)
    {
        $fid = $assignResponse['fid'];
        $fid = explode(",", $fid);
        $fid = $fid[0];
        $response = $weedClient->lookup($fid);
        $response = json_decode($response, true);
        $this->assertGreaterThanOrEqual(1, count($response['locations']));
    }
    
    /**
     * @depends testCreateClient
     * @depends testAssign
     * @depends testStoreFile
     */
    public function testRetrieveFile($weedClient, $assignResponse)
    {
        $volumeServerAddress = $assignResponse['publicUrl'];
        $fid = $assignResponse['fid'];
        $response = $weedClient->retrieve($volumeServerAddress, $fid);
        $this->assertEquals("HelloWeed", $response);
    }
    
    /**
     * @depends testCreateClient
     * @depends testAssign
     * @depends testRetrieveFile
     */
    public function testDeleteFile($weedClient, $assignResponse)
    {
        $volumeServerAddress = $assignResponse['publicUrl'];
        $fid = $assignResponse['fid'];
        $response = $weedClient->delete($volumeServerAddress, $fid);
        $response = json_decode($response, true);
        $this->assertEquals(35, $response['size']);
        
        $response = $weedClient->retrieve($volumeServerAddress, $fid);
        $this->assertEquals("", $response);
    }
}