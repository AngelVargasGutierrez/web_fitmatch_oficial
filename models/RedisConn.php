<?php
require __DIR__ . '/../vendor/autoload.php';

class RedisConn {
    private static $instance = null;
    private $redis;

    private function __construct() {
        $this->redis = new Redis();
        $this->redis->connect('127.0.0.1', 6379);
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance->redis;
    }
} 