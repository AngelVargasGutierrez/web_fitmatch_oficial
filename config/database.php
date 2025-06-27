<?php
// Configuración de la base de datos MySQL para HeidiSQL
return [
    'mysql' => [
        'host' => '161.132.56.161',
        'port' => 3307,
        'database' => 'fitmatch',
        'username' => 'admin',
        'password' => 'Upt2025',
        'charset' => 'utf8mb4',
        'options' => [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    ],
    'mongodb' => [
        'host' => '161.132.56.161',
        'port' => 27017,
        'database' => 'fitmatch',
        'username' => '',
        'password' => '',
        'options' => []
    ],
    'redis' => [
        'host' => '161.132.56.161',
        'port' => 6379,
        'password' => null,
        'database' => 0
    ]
]; 