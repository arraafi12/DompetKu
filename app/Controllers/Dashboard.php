<?php

namespace App\Controllers;

use App\Config\Doctrine;
use App\Entities\User;
use App\Entities\Transaction;

class Dashboard extends BaseController
{
    public function index()
    {
        // Memastikan pengguna sudah login
        if (!session()->get('isLoggedIn')) {
            return redirect()->to(site_url('login'));
        }

        try {
            // Menghubungkan Doctrine
            $doctrine = new Doctrine();
            $entityManager = $doctrine->getEntityManager();

            // Mengambil pengguna yang sedang login
            $userId = session()->get('user_id');
            $user = $entityManager->find(User::class, $userId);

            if (!$user) {
                session()->destroy();

                return redirect()->to(site_url('login'));
            }

            // Mengambil total pemasukan
            $income = $entityManager->createQueryBuilder()
                ->select('COALESCE(SUM(t.amount), 0)')
                ->from(Transaction::class, 't')
                ->where('t.user = :user')
                ->andWhere("t.transactionType = :type")
                ->setParameter('user', $user)
                ->setParameter('type', 'income')
                ->getQuery()
                ->getSingleScalarResult();

            // Mengambil total pengeluaran
            $expense = $entityManager->createQueryBuilder()
                ->select('COALESCE(SUM(t.amount), 0)')
                ->from(Transaction::class, 't')
                ->where('t.user = :user')
                ->andWhere("t.transactionType = :type")
                ->setParameter('user', $user)
                ->setParameter('type', 'expense')
                ->getQuery()
                ->getSingleScalarResult();

            // Menghitung saldo
            $balance = (float) $income - (float) $expense;

            // Mengambil 5 transaksi terbaru
            $recentTransactions = $entityManager->createQueryBuilder()
                ->select('t', 'c')
                ->from(Transaction::class, 't')
                ->leftJoin('t.category', 'c')
                ->where('t.user = :user')
                ->setParameter('user', $user)
                ->orderBy('t.transactionDate', 'DESC')
                ->setMaxResults(5)
                ->getQuery()
                ->getResult();

            return view('dashboard/index', [
                'user' => $user,
                'income' => $income,
                'expense' => $expense,
                'balance' => $balance,
                'recentTransactions' => $recentTransactions
            ]);

        } catch (\Throwable $e) {
            log_message('error', 'Dashboard error: ' . $e->getMessage());

            return $this->response
                ->setStatusCode(500)
                ->setBody('Terjadi kesalahan saat memuat Dashboard: ' . esc($e->getMessage()));
        }
    }
}
