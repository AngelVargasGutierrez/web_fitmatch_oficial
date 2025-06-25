<?php
// Configuración de la base de datos MySQL para HeidiSQL
return [
    'mysql' => [
        'host' => 'localhost',
        'port' => 3306,
        'database' => 'fitmatch',
        'username' => 'root',
        'password' => '',
        'charset' => 'utf8mb4',
        'options' => [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    ],
    'mongodb' => [
        'host' => 'localhost',
        'port' => 27017,
        'database' => 'fitmatch',
        'username' => '',
        'password' => '',
        'options' => []
    ],
    'redis' => [
        'host' => 'localhost',
        'port' => 6379,
        'password' => null,
        'database' => 0
    ]
]; 