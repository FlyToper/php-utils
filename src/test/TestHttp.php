<?php
/**
 * @purpose Test flytoper/phputils/http/Http
 * @Author: cbf
 * @Time: 2022/9/24 10:41
 */

namespace flytoper\phputils\test;

use flytoper\phputils\common\FormatOutput;
use flytoper\phputils\http\Http;

class TestHttp
{

    public function testGet()
    {
        $url = 'https://www.php.net/';
        $res = Http::get($url);

        if ($res === false) {
            FormatOutput::red("Http::get() error", 'b');
        } else {
            FormatOutput::green('Http::get() success', 'b');
        }
    }

    public function testPost()
    {
    }


}