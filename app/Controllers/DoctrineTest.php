<?php
namespace App\Controllers;

use App\Config\Doctrine;
use App\Entities\User;

class DoctrineTest extends BaseController
{
    public function index()
    {
        try {
            $doctrine = new Doctrine();
            $entityManager = $doctrine->getEntityManager();

            // Menguji koneksi database
            $connection = $entityManager->getConnection();
            $connection->executeQuery('SELECT 1');

            // Menguji pemetaan Entity User
            $metadata = $entityManager->getClassMetadata(User::class);

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Doctrine berhasil terhubung!',
                'database' => $connection->getDatabase(),
                'entity' => $metadata->getTableName()
            ]);

        } catch (\Throwable $e) {
            return $this->response
                ->setStatusCode(500)
                ->setJSON([
                    'status' => 'error',
                    'message' => 'Doctrine gagal terhubung.',
                    'error' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ]);
        }
    }
}
