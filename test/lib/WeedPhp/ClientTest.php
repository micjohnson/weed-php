<?php
require_once dirname(__FILE__) . '/../../autoload.php';

use WeedPhp\Client;

class ClientTest extends PHPUnit_Framework_TestCase
{
	public function testCreateClient()
	{
		$weedClient = new Client('http://localhost:9333');
		if($weedClient instanceof Client) {
			$this->assertTrue(true);
		} else {
			$this->assertTrue(false);
		}

		return $weedClient;
	}

	/**
	 * @depends testCreateClient
	 */
	public function testPopulateVolumes($weedClient)
	{
		$response = $weedClient->assign(20);
		$response = json_decode($response, true);
		$volumeServerAddress = $response['publicUrl'];
		$fid = $response['fid'];
		$files = array();
		for($i=0;$i<20;$i++) {
			$files[] = uniqid();
		}
		$response = $weedClient->storeMultiple($volumeServerAddress, $fid, $files);
	}

	/**
	 * @depends testCreateClient
	 */
	public function testStatus($weedClient)
	{
		$response = $weedClient->status();
		$response = json_decode($response, true);
			
		if(array_key_exists('Version', $response)) {
			$this->assertTrue(true);
		} else {
			$this->assertTrue(false);
		}
	}

	/**
	 * @depends testCreateClient
	 */
	public function testVolumeStatus($weedClient)
	{
		$response = $weedClient->volumeStatus();
		$response = json_decode($response, true);
		if(array_key_exists('Version', $response)) {
			$this->assertTrue(true);
		} else {
			$this->assertTrue(false);
		}
	}

	/**
	 * @depends testCreateClient
	 */
	public function testAssignMultiple($weedClient)
	{
		$response = $weedClient->assign(25, "001");
		$response = json_decode($response, true);
		$this->assertEquals(25, $response['count']);

		return $response;
	}

	/**
	 * @depends testCreateClient
	 * @depends testAssignMultiple
	 */
	public function testStoreMultiple($weedClient, $mutlipleAssignResponse)
	{
		$volumeServerAddress = $mutlipleAssignResponse['publicUrl'];
		$fid = $mutlipleAssignResponse['fid'];
		$files = array();
		for($i=0;$i<25;$i++) {
			$files[] = uniqid();
		}
		$response = $weedClient->storeMultiple($volumeServerAddress, $fid, $files);
		$this->assertEquals(25, count($response));
		foreach($response as $individualResponse) {
			$individualResponse = json_decode($individualResponse, true);
			$this->assertGreaterThan(2, $individualResponse['size']);

		}

		return $response;
	}

	/**
	 * @depends testCreateClient
	 * @depends testAssignMultiple
	 * @depends testStoreMultiple
	 */
	public function testDeleteMultiple($weedClient, $multipleAssignResponse, $multipleStoreResponse)
	{
		$count = count($multipleStoreResponse);
		$volumeServerAddress = $multipleAssignResponse['publicUrl'];
		$fid = $multipleAssignResponse['fid'];
		$origFid = $fid;
		for($i = 0;$i < $count; $i++)
		{
			$response = $weedClient->delete($volumeServerAddress, $fid);
			$response = json_decode($response, true);
			$this->assertGreaterThan(2, $response['size']);
			$fid = $origFid . '_' . ($i+1);
		}
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
	public function testVolumeServerStatus($weedClient, $assignResponse)
	{
		$volumeServerAddress = $assignResponse['publicUrl'];
		$response = $weedClient->volumeServerStatus($volumeServerAddress);

		$response = json_decode($response, true);
		if(array_key_exists('Version', $response)) {
			$this->assertTrue(true);
		} else {
			$this->assertTrue(false);
		}
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
	 * @depends testAssignMultiple
	 * @depends testStoreMultiple
	 * @depends testAssign
	 * @depends testStoreFile
	 */
	public function testGrow($weedClient)
	{
		$response = $weedClient->grow(2, "001");
		$this->assertTrue(array_key_exists('count', json_decode($response, true)));
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