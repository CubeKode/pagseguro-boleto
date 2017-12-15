<?php

include_once "Config.php";
include_once "Utils.php";
include_once "Curl.php";

class TransactionDetails extends Utils
{
    private $code = null;
    private $url = array(
        'v2' => 'v2/transactions/',
        'v3' => 'v3/transactions/',
    );
    private $version = 'v3';
    public $result = false;

    public function __construct($code, $version = 'v3')
    {
        $this->code = $code;
        if (strlen($this->code) !== 32 && strlen($this->code) !== 36) {
            //PagSeguro error code 13003
            throw new InvalidArgumentException('invalid transactionCode value: ' . $this->code);
        }
        $this->setVersion($version);
        $this->result = $this->send();
    }

    public function setVersion($version)
    {
        if (!isset($this->url[$version])) {
            throw new InvalidArgumentException('invalid API version: ' . $this->version);
        }
        $this->version = $version;
    }

    public function send()
    {
        $url = URL::getWs() . $this->url[$this->version] . $this->code . '/?email=' . Conf::getEmail() . '&token=' . Conf::getToken();

        $curl = new Curl($url);
        $curl->setCustomRequest('GET');
        return $data = $curl->exec();
    }
}