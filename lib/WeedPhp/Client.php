<?php
namespace WeedPhp;

use WeedPhp\Transport\Curl;

class Client
{
    protected $transport;

    protected $storageAddress;

    public function __construct($storageAddress)
    {
        $this->transport = new Curl();
        $this->storageAddress = $storageAddress;
    }

    public function assign($count)
    {
        $assignUrl = $this->storageAddress . '/dir/assign';
        $assignUrl .= '?count' . intval($count);
        $response = $assignUrl = $this->transport->get($assignUrl);
        $this->transport->close();

        return $response;
    }
    
    public function delete($storageVolumeAddress, $id)
    {
        $deleteUrl = $storageVolumeAddress . '/' . $id;
        // TODO check for http://
        $response = $this->transport->custom($deleteUrl, 'delete');
        $this->transport->close();
    
        return $response;
    }

    public function lookup($volumeId)
    {
        $lookupUrl = $this->storageAddress . '/dir/lookup';
        $lookupUrl .= '?volumeId=' . $volumeId;
        $response = $this->transport->get($lookupUrl);
        $this->transport->close();
    
        return $response;
    }
    
    public function retrieve($storageVolumeAddress, $id)
    {
        $retrieveUrl = $storageVolumeAddress . '/' . $id;
        // TODO check for http://
        $response = $this->transport->get($retrieveUrl);
        $this->transport->close();

        return $response;
    }
    
    public function status()
    {
        $statusAddress = $this->storageAddress . '/dir/status';
        $response = $this->transport->get($statusAddress);
        $this->transport->close();
    
        return $response;
    }
    
    public function storeMultiple($fileAddress, $id, array $files)
    {
        $count = count($files);
        $storeUrl = $fileAddress . '/' . $id;
        // TODO check for http://

        $response = array();
        for($i = 1; $i <= $count; $i++) {
            $parameters = array('file'=>$files[$i-1]);
            $response[] = $this->transport->post($storeUrl, $parameters);
            $storeUrl = $fileAddress . '/' . $id . '_' . $i;
        }
        $this->transport->close();

        return $response;
    }
    
    public function store($fileAddress, $id, $file)
    {
        $storeUrl = $fileAddress . 'id';
        $parameters = array('file'=>$file);
        $response = $this->transport->post(storeUrl, $parameters);
        $this->transport->close();
        return $response;
    }
}