<?php

namespace App\Controllers;

use App\Config\Doctrine;
use App\Entities\User;
use App\Entities\Category;
use App\Entities\Transaction;
use CodeIgniter\HTTP\RedirectResponse;

class Transactions extends BaseController
{
    // Mengambil EntityManager dan user yang sedang login
    private function getContext()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to(site_url('login'));
        }

        $doctrine = new Doctrine();
        $entityManager = $doctrine->getEntityManager();

        $user = $entityManager->find(
            User::class,
            (int) session()->get('user_id')
        );

        if (!$user) {
            session()->destroy();
            return redirect()->to(site_url('login'));
        }

        return [$entityManager, $user];
    }

    // Mengambil kategori milik user yang sedang login
    private function getCategories($entityManager, $user): array
    {
        return $entityManager->createQueryBuilder()
            ->select('c')
            ->from(Category::class, 'c')
            ->where('c.user = :user')
            ->setParameter('user', $user)
            ->orderBy('c.categoryType', 'ASC')
            ->addOrderBy('c.categoryName', 'ASC')
            ->getQuery()
            ->getResult();
    }

    // Menampilkan pesan dan mengarahkan halaman
    private function redirectMessage(
        string $type,
        string $message,
        string $path
    ): RedirectResponse {
        session()->setFlashdata($type, $message);
        return redirect()->to(site_url($path));
    }

    // Memeriksa format tanggal
    private function isValidDate(string $date): bool
    {
        $parsedDate = \DateTime::createFromFormat('!Y-m-d', $date);

        return $parsedDate !== false
            && $parsedDate->format('Y-m-d') === $date;
    }

    // Halaman daftar transaksi
    public function index()
    {
        return $this->showTransactions(false);
    }

    // Halaman pencarian dan filter
    public function search()
    {
        return $this->showTransactions(true);
    }

    // Mengambil transaksi berdasarkan user dan filter
    private function showTransactions(bool $searchPage)
    {
        $context = $this->getContext();

        if ($context instanceof RedirectResponse) {
            return $context;
        }

        [$entityManager, $user] = $context;

        $keyword = trim((string) $this->request->getGet('q'));
        $type = (string) $this->request->getGet('type');
        $categoryId = (string) $this->request->getGet('category_id');
        $startDate = (string) $this->request->getGet('start_date');
        $endDate = (string) $this->request->getGet('end_date');

        $query = $entityManager->createQueryBuilder()
            ->select('t', 'c')
            ->from(Transaction::class, 't')
            ->leftJoin('t.category', 'c')
            ->where('t.user = :user')
            ->setParameter('user', $user);

        // Pencarian berdasarkan nama dan keterangan transaksi
        if ($keyword !== '') {
            $query->andWhere(
                $query->expr()->orX(
                    'LOWER(t.transactionName) LIKE :keyword',
                    'LOWER(t.description) LIKE :keyword'
                )
            );

            $query->setParameter(
                'keyword',
                '%' . mb_strtolower($keyword) . '%'
            );
        }

        // Filter berdasarkan jenis transaksi
        if (in_array($type, ['income', 'expense'], true)) {
            $query->andWhere('t.transactionType = :type');
            $query->setParameter('type', $type);
        }

        // Filter berdasarkan kategori
        if (ctype_digit($categoryId) && (int) $categoryId > 0) {
            $category = $entityManager->find(
                Category::class,
                (int) $categoryId
            );

            if ($category && $category->getUser()->getId() === $user->getId()) {
                $query->andWhere('t.category = :category');
                $query->setParameter('category', $category);
            }
        }

        // Filter tanggal mulai
        if ($startDate !== '' && $this->isValidDate($startDate)) {
            $query->andWhere('t.transactionDate >= :startDate');
            $query->setParameter('startDate', $startDate);
        }

        // Filter tanggal akhir
        if ($endDate !== '' && $this->isValidDate($endDate)) {
            $query->andWhere('t.transactionDate <= :endDate');
            $query->setParameter('endDate', $endDate);
        }

        // Urutan transaksi terbaru
        $query->orderBy('t.transactionDate', 'DESC')
            ->addOrderBy('t.id', 'DESC');

        $transactions = $query->getQuery()->getResult();

        return view('transactions/index', [
            'transactions' => $transactions,
            'categories' => $this->getCategories($entityManager, $user),
            'filters' => [
                'q' => $keyword,
                'type' => $type,
                'category_id' => $categoryId,
                'start_date' => $startDate,
                'end_date' => $endDate,
            ],
            'searchPage' => $searchPage,
        ]);
    }

    // Menampilkan form tambah transaksi
    public function create()
    {
        $context = $this->getContext();

        if ($context instanceof RedirectResponse) {
            return $context;
        }

        [$entityManager, $user] = $context;

        return view('transactions/form', [
            'categories' => $this->getCategories($entityManager, $user),
            'transaction' => null,
        ]);
    }

    // Menyimpan transaksi baru
    public function store()
    {
        $context = $this->getContext();

        if ($context instanceof RedirectResponse) {
            return $context;
        }

        [$entityManager, $user] = $context;

        $rules = [
            'transaction_name' => 'required|min_length[2]|max_length[150]',
            'transaction_type' => 'required|in_list[income,expense]',
            'category_id' => 'required|is_natural_no_zero',
            'amount' => 'required|numeric|greater_than[0]',
            'transaction_date' => 'required|valid_date[Y-m-d]',
            'description' => 'permit_empty',
        ];

        if (!$this->validate($rules)) {
            session()->setFlashdata('errors', $this->validator->getErrors());
            return redirect()->to(site_url('transaksi/tambah'))->withInput();
        }

        $category = $entityManager->find(
            Category::class,
            (int) $this->request->getPost('category_id')
        );

        if (!$category || $category->getUser()->getId() !== $user->getId()) {
            return $this->redirectMessage(
                'error',
                'Kategori tidak valid.',
                'transaksi/tambah'
            );
        }

        $type = $this->request->getPost('transaction_type');

        if ($category->getCategoryType() !== $type) {
            return $this->redirectMessage(
                'error',
                'Jenis kategori harus sesuai dengan jenis transaksi.',
                'transaksi/tambah'
            );
        }

        $date = $this->request->getPost('transaction_date');
        $transactionDate = \DateTime::createFromFormat('!Y-m-d', $date);

        $transaction = new Transaction();

        $transaction->setUser($user);
        $transaction->setCategory($category);
        $transaction->setTransactionType($type);
        $transaction->setTransactionName(
            trim($this->request->getPost('transaction_name'))
        );
        $transaction->setAmount(
            number_format((float) $this->request->getPost('amount'), 2, '.', '')
        );
        $transaction->setTransactionDate($transactionDate);
        $transaction->setDescription(
            trim((string) $this->request->getPost('description')) ?: null
        );
        $transaction->setCreatedAt(new \DateTime());
        $transaction->setUpdatedAt(new \DateTime());

        try {
            $entityManager->persist($transaction);
            $entityManager->flush();

            return $this->redirectMessage(
                'success',
                'Transaksi berhasil ditambahkan.',
                'transaksi'
            );
        } catch (\Throwable $e) {
            log_message('error', 'Transaction store: ' . $e->getMessage());

            return $this->redirectMessage(
                'error',
                'Transaksi gagal disimpan.',
                'transaksi/tambah'
            );
        }
    }

    // Menampilkan form edit transaksi
    public function edit($id)
    {
        $context = $this->getContext();

        if ($context instanceof RedirectResponse) {
            return $context;
        }

        [$entityManager, $user] = $context;

        $transaction = $entityManager->find(
            Transaction::class,
            (int) $id
        );

        if (!$transaction || $transaction->getUser()->getId() !== $user->getId()) {
            return $this->redirectMessage(
                'error',
                'Transaksi tidak ditemukan.',
                'transaksi'
            );
        }

        return view('transactions/form', [
            'categories' => $this->getCategories($entityManager, $user),
            'transaction' => $transaction,
        ]);
    }

    // Memperbarui transaksi
    public function update($id)
    {
        $context = $this->getContext();

        if ($context instanceof RedirectResponse) {
            return $context;
        }

        [$entityManager, $user] = $context;

        $transaction = $entityManager->find(
            Transaction::class,
            (int) $id
        );

        if (!$transaction || $transaction->getUser()->getId() !== $user->getId()) {
            return $this->redirectMessage(
                'error',
                'Transaksi tidak ditemukan.',
                'transaksi'
            );
        }

        $rules = [
            'transaction_name' => 'required|min_length[2]|max_length[150]',
            'transaction_type' => 'required|in_list[income,expense]',
            'category_id' => 'required|is_natural_no_zero',
            'amount' => 'required|numeric|greater_than[0]',
            'transaction_date' => 'required|valid_date[Y-m-d]',
            'description' => 'permit_empty',
        ];

        if (!$this->validate($rules)) {
            session()->setFlashdata('errors', $this->validator->getErrors());
            return redirect()->to(site_url('transaksi/edit/' . $id))->withInput();
        }

        $category = $entityManager->find(
            Category::class,
            (int) $this->request->getPost('category_id')
        );

        if (!$category || $category->getUser()->getId() !== $user->getId()) {
            return $this->redirectMessage(
                'error',
                'Kategori tidak valid.',
                'transaksi/edit/' . $id
            );
        }

        $type = $this->request->getPost('transaction_type');

        if ($category->getCategoryType() !== $type) {
            return $this->redirectMessage(
                'error',
                'Jenis kategori harus sesuai dengan jenis transaksi.',
                'transaksi/edit/' . $id
            );
        }

        $date = $this->request->getPost('transaction_date');
        $transactionDate = \DateTime::createFromFormat('!Y-m-d', $date);

        $transaction->setCategory($category);
        $transaction->setTransactionType($type);
        $transaction->setTransactionName(
            trim($this->request->getPost('transaction_name'))
        );
        $transaction->setAmount(
            number_format((float) $this->request->getPost('amount'), 2, '.', '')
        );
        $transaction->setTransactionDate($transactionDate);
        $transaction->setDescription(
            trim((string) $this->request->getPost('description')) ?: null
        );
        $transaction->setUpdatedAt(new \DateTime());

        try {
            $entityManager->flush();

            return $this->redirectMessage(
                'success',
                'Transaksi berhasil diperbarui.',
                'transaksi'
            );
        } catch (\Throwable $e) {
            log_message('error', 'Transaction update: ' . $e->getMessage());

            return $this->redirectMessage(
                'error',
                'Transaksi gagal diperbarui.',
                'transaksi/edit/' . $id
            );
        }
    }

    // Menghapus transaksi
    public function delete($id)
    {
        $context = $this->getContext();

        if ($context instanceof RedirectResponse) {
            return $context;
        }

        [$entityManager, $user] = $context;

        $transaction = $entityManager->find(
            Transaction::class,
            (int) $id
        );

        if (!$transaction || $transaction->getUser()->getId() !== $user->getId()) {
            return $this->redirectMessage(
                'error',
                'Transaksi tidak ditemukan.',
                'transaksi'
            );
        }

        try {
            $entityManager->remove($transaction);
            $entityManager->flush();

            return $this->redirectMessage(
                'success',
                'Transaksi berhasil dihapus.',
                'transaksi'
            );
        } catch (\Throwable $e) {
            log_message('error', 'Transaction delete: ' . $e->getMessage());

            return $this->redirectMessage(
                'error',
                'Transaksi gagal dihapus.',
                'transaksi'
            );
        }
    }
}
