<?php
/**
 * @purpose Run All Test Case
 * @Author: cbf
 * @Time: 2022/9/24 10:45
 */
require_once __DIR__ . '/../vendor/autoload.php';

$test_name_namespace = "\\flytoper\\phputils\\test\\";
$test_path = __DIR__ . '/../src/test';

$allFiles = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($test_path), RecursiveIteratorIterator::LEAVES_ONLY);
$phpFiles = new RegexIterator($allFiles, '/\.php$/');

/**
 * @var $v SplFileInfo
 */
foreach ($phpFiles as $k => $v) {
    $origin_class_name = $v->getBasename('.php'); //TestHttp
    $class_name = $test_name_namespace . $origin_class_name; // \flytoper\phputils\test\TestHttp
    $obj = new $class_name();

    $class_methods = get_class_methods($obj);
    foreach ($class_methods as $method) {
        $pre = substr($method, 0, 4);
        if ($pre == 'test' || $pre == 'Test') {
            echo sprintf("%s[%s Start Run %s->%s]%s", PHP_EOL, date('Y-m-d H:i:s'), $origin_class_name, $method, PHP_EOL);
            $t1 = microtime(true);
            $obj->{$method}();
            $t2 = microtime(true);
            echo sprintf("%s[%s End Run %s->%s UseTime:%f]%s", PHP_EOL, date('Y-m-d H:i:s'), $origin_class_name, $method, $t2 - $t1, PHP_EOL);
        }
    }
}


