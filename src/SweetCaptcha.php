<?php

namespace SweetCaptcha;

use RuntimeException;

/**
 * Handles remote negotiation with https://www.sweetcaptcha.com
 *
 * @version 0.1.0
 * @updated July 21, 2017
 */
class SweetCaptcha {

    const API_URL = 'sweetcaptcha.com';
    const API_PORT = 80;

    /**
     * @var string
     */
    private $appId;

    /**
     * @var string
     */
    private $key;

    /**
     * @var string
     */
    private $secret;

    /**
     * @var string
     */
    private $path;

    /**
     * @param string $appId
     * @param string $key
     * @param string $secret
     * @param string $path
     */
    function __construct($appId, $key, $secret, $path) {
        $this->appId = $appId;
        $this->key = $key;
        $this->secret = $secret;
        $this->path = $path;
    }


    /**
     * @param array $params
     *
     * @return string
     */
    public function getHtml(array $params = [])
    {
        return $this->api('get_html', $params);
    }

    /**
     * @param array $params
     *
     * @return string
     */
    public function check(array $params)
    {
        return $this->api('check', $params);
    }

    /**
     * @param string $method
     * @param array $params
     *
     * @return string
     */
    private function api($method, $params) {

        $basic = array(
            'method'      => $method,
            'appid'       => $this->appId,
            'key'         => $this->key,
            'path'        => $this->path,
            'user_ip'     => $_SERVER['REMOTE_ADDR'],
            'platform'    => 'php'
        );

        return $this->call(array_merge(isset($params[0]) ? $params[0] : $params, $basic));
    }

    /**
     * @param array $params
     *
     * @throws RuntimeException
     *
     * @return string
     */
    private function call(array $params) {
        $paramData = "";
        foreach ($params as $paramName => $paramValue) {
            $paramData .= urlencode($paramName) .'='. urlencode($paramValue) .'&';
        }

        if (!($fs = fsockopen(self::API_URL, self::API_PORT, $errno, $errstr, 10))) {
            throw new RuntimeException("Couldn't connect to server");
        }

        $req = "POST /api.php HTTP/1.0\r\n";
        $req .= "Host: ".self::API_URL."\r\n";
        $req .= "Content-Type: application/x-www-form-urlencoded\r\n";
        $req .= "Referer: " . $_SERVER['HTTP_HOST']. "\r\n";
        $req .= "Content-Length: " . strlen($paramData) . "\r\n\r\n";
        $req .= $paramData;

        $response = '';
        fwrite($fs, $req);

        while (!feof($fs)) {
            $response .= fgets($fs, 1160);
        }

        fclose($fs);

        $response = explode("\r\n\r\n", $response, 2);

        return $response[1];
    }
}