<?php
/**
 * @purpose Single Case Trait
 * @Author: cbf
 * @Time: 2022/9/24 9:36
 */

namespace flytoper\phputils\common;

trait SingleTrait
{
    private static $instance = null;

    protected function __construct()
    {
    }

    /**
     * Get Class Instance
     * @return static|null
     */
    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

}
