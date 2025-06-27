<?php
require __DIR__ . '/../vendor/autoload.php';

class RedisConn {
    private static $instance = null;
    private $redis;

    private function __construct() {
        $config = require(__DIR__ . '/../config/database.php');
        $redisConf = $config['redis'];
        $host = $redisConf['host'];
        $port = $redisConf['port'];
        $password = $redisConf['password'];
        $database = $redisConf['database'];
        $this->redis = new Redis();
        $this->redis->connect($host, $port);
        if ($password) {
            $this->redis->auth($password);
        }
        if ($database) {
            $this->redis->select($database);
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance->redis;
    }
} 