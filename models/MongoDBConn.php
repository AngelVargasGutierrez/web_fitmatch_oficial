<?php
class MongoDBConn {
    private $manager;
    public function __construct($db) {
        $this->manager = new MongoDB\Driver\Manager("mongodb://localhost:27017/$db");
    }
    public function getManager() {
        return $this->manager;
    }
} 