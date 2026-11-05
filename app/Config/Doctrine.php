<?php

namespace App\Config;

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;

class Doctrine
{
    public function getEntityManager(): EntityManager
    {
        // Mengambil konfigurasi database CodeIgniter
        $dbConfig = config('Database')->default;

        // Konfigurasi Doctrine ORM
        $config = ORMSetup::createAttributeMetadataConfig(
            paths: [APPPATH . 'Entities'],
            isDevMode: ENVIRONMENT !== 'production'
        );

        // Mengaktifkan Native Lazy Objects PHP 8.4
        $config->enableNativeLazyObjects(true);

        // Konfigurasi koneksi MySQL
        $connectionParams = [
            'driver'   => 'pdo_mysql',
            'host'     => $dbConfig['hostname'],
            'user'     => $dbConfig['username'],
            'password' => $dbConfig['password'],
            'dbname'   => $dbConfig['database'],
            'port'     => $dbConfig['port'],
            'charset'  => $dbConfig['charset'],
        ];

        // Membuat koneksi database
        $connection = DriverManager::getConnection(
            $connectionParams,
            $config
        );

        // Membuat EntityManager
        return new EntityManager($connection, $config);
    }
}
