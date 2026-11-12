<?php

namespace App\Controllers;

use App\Config\Doctrine;
use App\Entities\User;
use App\Entities\Category;
use App\Entities\Transaction;

class Categories extends BaseController
{
    // Mengambil EntityManager dan pengguna yang sedang login
    private function getUserAndEntityManager()
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

    // Menampilkan daftar kategori
    public function index()
    {
        $result = $this->getUserAndEntityManager();

        if ($result instanceof \CodeIgniter\HTTP\RedirectResponse) {
            return $result;
        }

        [$entityManager, $user] = $result;

        $categories = $entityManager->createQueryBuilder()
            ->select('c')
            ->from(Category::class, 'c')
            ->where('c.user = :user')
            ->setParameter('user', $user)
            ->orderBy('c.categoryType', 'ASC')
            ->addOrderBy('c.categoryName', 'ASC')
            ->getQuery()
            ->getResult();

        return view('categories/index', [
            'categories' => $categories,
            'editCategory' => null,
        ]);
    }

    // Menyimpan kategori baru
    public function store()
    {
        $result = $this->getUserAndEntityManager();

        if ($result instanceof \CodeIgniter\HTTP\RedirectResponse) {
            return $result;
        }

        [$entityManager, $user] = $result;

        $rules = [
            'category_name' => 'required|min_length[2]|max_length[100]',
            'category_type' => 'required|in_list[income,expense]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->to(site_url('kategori'))
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $category = new Category();

        $category->setUser($user);
        $category->setCategoryName(
            trim($this->request->getPost('category_name'))
        );
        $category->setCategoryType(
            $this->request->getPost('category_type')
        );
        $category->setCreatedAt(new \DateTime());
        $category->setUpdatedAt(new \DateTime());

        try {
            $entityManager->persist($category);
            $entityManager->flush();

            return redirect()->to(site_url('kategori'))
                ->with('success', 'Kategori berhasil ditambahkan.');

        } catch (\Throwable $e) {
            log_message('error', 'Category store error: ' . $e->getMessage());

            return redirect()->to(site_url('kategori'))
                ->with('error', 'Kategori gagal disimpan.');
        }
    }

    // Menampilkan form edit kategori
    public function edit($id)
    {
        $result = $this->getUserAndEntityManager();

        if ($result instanceof \CodeIgniter\HTTP\RedirectResponse) {
            return $result;
        }

        [$entityManager, $user] = $result;

        $category = $entityManager->find(Category::class, (int) $id);

        if (!$category || $category->getUser()->getId() !== $user->getId()) {
            return redirect()->to(site_url('kategori'))
                ->with('error', 'Kategori tidak ditemukan.');
        }

        $categories = $entityManager->createQueryBuilder()
            ->select('c')
            ->from(Category::class, 'c')
            ->where('c.user = :user')
            ->setParameter('user', $user)
            ->orderBy('c.categoryType', 'ASC')
            ->getQuery()
            ->getResult();

        return view('categories/index', [
            'categories' => $categories,
            'editCategory' => $category,
        ]);
    }

    // Memperbarui kategori
    public function update($id)
    {
        $result = $this->getUserAndEntityManager();

        if ($result instanceof \CodeIgniter\HTTP\RedirectResponse) {
            return $result;
        }

        [$entityManager, $user] = $result;

        $category = $entityManager->find(Category::class, (int) $id);

        if (!$category || $category->getUser()->getId() !== $user->getId()) {
            return redirect()->to(site_url('kategori'))
                ->with('error', 'Kategori tidak ditemukan.');
        }

        $rules = [
            'category_name' => 'required|min_length[2]|max_length[100]',
            'category_type' => 'required|in_list[income,expense]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->to(site_url('kategori/edit/' . $id))
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $category->setCategoryName(
            trim($this->request->getPost('category_name'))
        );
        $category->setCategoryType(
            $this->request->getPost('category_type')
        );
        $category->setUpdatedAt(new \DateTime());

        try {
            $entityManager->flush();

            return redirect()->to(site_url('kategori'))
                ->with('success', 'Kategori berhasil diperbarui.');

        } catch (\Throwable $e) {
            log_message('error', 'Category update error: ' . $e->getMessage());

            return redirect()->to(site_url('kategori'))
                ->with('error', 'Kategori gagal diperbarui.');
        }
    }

    // Menghapus kategori
    public function delete($id)
    {
        $result = $this->getUserAndEntityManager();

        if ($result instanceof \CodeIgniter\HTTP\RedirectResponse) {
            return $result;
        }

        [$entityManager, $user] = $result;

        $category = $entityManager->find(Category::class, (int) $id);

        if (!$category || $category->getUser()->getId() !== $user->getId()) {
            return redirect()->to(site_url('kategori'))
                ->with('error', 'Kategori tidak ditemukan.');
        }

        // Kategori tidak boleh dihapus jika masih digunakan transaksi
        $transactionCount = $entityManager->createQueryBuilder()
            ->select('COUNT(t.id)')
            ->from(Transaction::class, 't')
            ->where('t.category = :category')
            ->setParameter('category', $category)
            ->getQuery()
            ->getSingleScalarResult();

        if ((int) $transactionCount > 0) {
            return redirect()->to(site_url('kategori'))
                ->with(
                    'error',
                    'Kategori tidak dapat dihapus karena masih digunakan transaksi.'
                );
        }

        try {
            $entityManager->remove($category);
            $entityManager->flush();

            return redirect()->to(site_url('kategori'))
                ->with('success', 'Kategori berhasil dihapus.');

        } catch (\Throwable $e) {
            log_message('error', 'Category delete error: ' . $e->getMessage());

            return redirect()->to(site_url('kategori'))
                ->with('error', 'Kategori gagal dihapus.');
        }
    }
}
