<?php
/**
 * @purpose Curl helper
 * @Author: cbf
 * @Time: 2022/9/24 9:33
 */

namespace flytoper\phputils\http;

use flytoper\phputils\common\SingleTrait;

class CurlHelper
{
    use SingleTrait;

    /**
     * @var \CurlHandle
     */
    private $ch;
    private $url;
    private $params;
    private $headers;
    private $closeHttps = true;

    protected function __construct()
    {
        $this->ch = curl_init();
    }

    public function url($url)
    {
        $this->url = $url;
        return $this;
    }

    public function params($params)
    {
        $this->params = $params;
        return $this;
    }

    public function headers($header)
    {
        if (is_array($header)) {
            foreach ($header as $v) {
                $this->headers[] = $v;
            }
        } else {
            $this->headers[] = $header;
        }
        return $this;
    }

    public function execute()
    {
        curl_setopt($this->ch, CURLOPT_URL, $this->url);



    }


}
