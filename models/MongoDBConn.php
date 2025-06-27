<?php
class MongoDBConn {
    private $manager;
    private $db;
    public function __construct($db) {
        $this->db = $db;
        $config = require(__DIR__ . '/../config/database.php');
        $mongo = $config['mongodb'];
        $host = $mongo['host'];
        $port = $mongo['port'];
        $username = $mongo['username'];
        $password = $mongo['password'];
        $auth = ($username && $password) ? "$username:$password@" : '';
        $uri = "mongodb://$auth$host:$port/$db";
        $this->manager = new \MongoDB\Driver\Manager($uri);
    }
    public function getManager() {
        return $this->manager;
    }
    public function getCollection($collection) {
        $config = require(__DIR__ . '/../config/database.php');
        $mongo = $config['mongodb'];
        $host = $mongo['host'];
        $port = $mongo['port'];
        $username = $mongo['username'];
        $password = $mongo['password'];
        $auth = ($username && $password) ? "$username:$password@" : '';
        $uri = "mongodb://$auth$host:$port";
        $client = new \MongoDB\Client($uri);
        return $client->{$this->db}->{$collection};
    }
} 