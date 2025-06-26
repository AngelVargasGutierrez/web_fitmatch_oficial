<?php
class MongoDBConn {
    private $manager;
    private $db;
    public function __construct($db) {
        $this->db = $db;
        $this->manager = new MongoDB\Driver\Manager("mongodb://localhost:27017/$db");
    }
    public function getManager() {
        return $this->manager;
    }
    public function getCollection($collection) {
        $client = new \MongoDB\Client("mongodb://localhost:27017");
        return $client->{$this->db}->{$collection};
    }
} 