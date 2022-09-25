<?php
/**
 * @purpose Http/CURL Helper
 * @Author: cbf
 * @Time: 2022/9/23 23:24
 */

namespace flytoper\phputils\http;

class Http
{
    // default http headers
    public static $defaultHeaders = [
        'Connection: close',
        'Cache-Control: no-cache',
    ];

    /**
     * Simple get request
     * @param $url string request url
     * @return bool|string curl_exec response
     */
    public static function get($url)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        if (strpos($url, 'https://') !== false) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        }

        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    /**
     * Simple post request
     * @param $url string request url
     * @param $params array request params
     * @return bool|string cur_exec response
     */
    public static function post($url, $params)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_USERAGENT , 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.132 Safari/537.36' );

        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);


        $result = curl_exec($ch);
        if (!$result) {
            $info = curl_getinfo($ch);
            var_dump($info);
        }



        curl_close($ch);
        return $result;
    }

    /**
     * Send Request
     * @param $url string request url
     * @param $post_fields string|array request params
     * @param $timeout int request|connect timeout
     * @param $ext_options array other extend options
     * @param $retries int max retry times
     * @return array [$result, $http_code, $curl_info]
     */
    public static function sendRequest($url, $post_fields = null, $timeout = 5, $ext_options = [], $retries = 3)
    {
        $headers = self::$defaultHeaders;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // use post method
        if ($post_fields) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
        }

        // add headers
        if (isset($ext_options[CURLOPT_HEADER])) {
            if (isset($ext_options[CURLOPT_HEADER]['json'])) {
                $headers[] = 'Content-Type: application/json';
                if (is_string($post_fields)) {
                    $headers[] = 'Content-Length:' . strlen($post_fields);
                }
                unset($ext_options[CURLOPT_HEADER]['json']);
            }
            foreach ($ext_options[CURLOPT_HEADER] as $v) {
                $headers[] = $v;
            }
        } elseif ($ext_options['curl_opt']) {
            curl_setopt_array($ch, $ext_options['curl_opt']);
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        if (strpos($url, 'https://') !== false) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        }

        // set timeout
        if ($timeout > 0) {
            curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        }

        // curl_exec or retry
        $result = false;
        while (($result == false) && (--$retries > 0)) {
            $result = curl_exec($ch);
        }
        $http_info = curl_getinfo($ch);
        curl_close($ch);

        return [
            $result,
            (int)$http_info['http_code'],
            $http_info,
        ];
    }

    public static function postFiles($url, array $files, $post_fields = null, $timeout = 5, $ext_options = [], $reties = 3)
    {
        $headers = self::$defaultHeaders;



    }
}
