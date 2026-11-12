<?php

namespace App\Controllers;

use App\Config\Doctrine;
use App\Entities\User;
use App\Entities\Category;
use App\Entities\Transaction;

class DoctrineTest extends BaseController
{
    public function index()
    {
        try {
            // Membuat EntityManager
            $doctrine = new Doctrine();
            $entityManager = $doctrine->getEntityManager();

            // Menguji koneksi database
            $connection = $entityManager->getConnection();
            $connection->executeQuery('SELECT 1');

            // Menguji metadata ketiga Entity
            $entities = [];

            foreach ([
                User::class,
                Category::class,
                Transaction::class
            ] as $entityClass) {

                $metadata = $entityManager->getClassMetadata($entityClass);

                $entities[$entityClass] = $metadata->getTableName();
            }

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Doctrine dan seluruh Entity berhasil dibaca!',
                'database' => $connection->getDatabase(),
                'entities' => $entities
            ]);

        } catch (\Throwable $e) {
            return $this->response
                ->setStatusCode(500)
                ->setJSON([
                    'status' => 'error',
                    'message' => 'Pengujian Doctrine gagal.',
                    'error' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ]);
        }
    }
}
