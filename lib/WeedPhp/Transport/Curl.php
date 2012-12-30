<?php 
namespace WeedPhp\Transport;

class Curl
{
    protected $curl = 0;

    public function get($url)
    {
        return $this->doRequest($url, array(
                CURLOPT_AUTOREFERER => 1,
                CURLOPT_FOLLOWLOCATION => 1,
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_HEADER => 0,
        ));
    }

    public function post($url, $data)
    {
        return $this->doRequest($url, array(
                CURLOPT_AUTOREFERER => 1,
                CURLOPT_FOLLOWLOCATION => 1,
                CURLOPT_RETURNTRANSFER=>1,
                CURLOPT_HEADER => 0,
                CURLOPT_POSTFIELDS => $data,
        ));
    }

    public function custom($url, $command)
    {
        return $this->doRequest($url, array(
                CURLOPT_AUTOREFERER => 1,
                CURLOPT_FOLLOWLOCATION => 1,
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_CUSTOMREQUEST => $command,
        ));
    }

    private function doRequest($url, $options)
    {
        $this->initCurl();
        $this->setCurlOptions($options);
        $this->setCurlTargetUrl($url);
        return $this->execCurl();
    }

    private function setCurlTargetUrl($url)
    {
        curl_setopt($this->curl, CURLOPT_URL, $url);
    }

    private function setCurlOptions(array $options)
    {
        foreach($options as $option=>$value) {
            curl_setopt($this->curl, $option, $value);
        }
    }

    private function initCurl()
    {
        if($this->curl === 0) {
            $this->curl = curl_init();
        }
    }

    private function execCurl()
    {
        return curl_exec($this->curl);
    }

    public function close()
    {
        if($this->curl !== 0) {
            curl_close($this->curl);
            $this->curl = 0;
        }
    }
}