<?php

namespace SweetCaptcha;

use RuntimeException;

/**
 * Handles remote negotiation with https://www.sweetcaptcha.com
 *
 * @version 0.1.1
 * @updated July 21, 2017
 */
class SweetCaptcha {

    const API_URL = 'http://www.sweetcaptcha.com/api';

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
     * @param string $appId
     * @param string $key
     * @param string $secret
     */
    function __construct($appId, $key, $secret) {
        $this->appId = $appId;
        $this->key = $key;
        $this->secret = $secret;
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
     * @return bool
     */
    public function check(array $params)
    {
        return $this->api('check', $params) === 'true';
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
            'user_ip'     => $_SERVER['REMOTE_ADDR'],
            'platform'    => 'api'
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

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL,self::API_URL);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close ($ch);

        return $response;
    }
}