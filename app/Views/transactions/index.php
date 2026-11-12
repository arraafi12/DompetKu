<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        <?= $searchPage ? 'Pencarian & Filter' : 'Transaksi' ?> | DompetKu
    </title>

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        body {
            background: #f4f8f5;
            color: #202923;
        }

        /* SIDEBAR */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            display: flex;
            flex-direction: column;
            width: 240px;
            height: 100vh;
            padding: 30px 20px;
            background: white;
            border-right: 1px solid #dce7de;
        }

        .brand {
            color: #217346;
            font-size: 25px;
            font-weight: bold;
            margin-bottom: 40px;
        }

        .menu {
            display: flex;
            flex-direction: column;
            flex: 1;
        }

        .menu a {
            display: block;
            padding: 14px;
            margin-bottom: 8px;
            border-radius: 9px;
            text-decoration: none;
            color: #68736b;
        }

        .menu a.active,
        .menu a:hover {
            background: #ddf5e3;
            color: #175633;
        }

        .menu a.logout {
            margin-top: auto;
            color: #c0392b !important;
        }

        .menu a.logout:hover {
            background: transparent;
            color: #c0392b !important;
        }

        /* KONTEN UTAMA */
        .main {
            margin-left: 240px;
            padding: 35px;
        }

        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            flex-wrap: wrap;
            margin-bottom: 25px;
        }

        .header h1 {
            margin-bottom: 8px;
        }

        .header p {
            color: #68736b;
        }

        /* TOMBOL TAMBAH */
        .add-button {
            display: inline-block;
            padding: 12px 18px;
            border-radius: 8px;
            background: #217346;
            color: white;
            text-decoration: none;
            font-weight: bold;
        }

        .add-button:hover {
            background: #175633;
        }

        /* CARD */
        .card {
            background: white;
            padding: 25px;
            border: 1px solid #dce7de;
            border-radius: 15px;
            margin-bottom: 20px;
        }

        .card h2 {
            font-size: 19px;
            margin-bottom: 20px;
        }

        /* FORM FILTER */
        .filter-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(140px, 1fr));
            gap: 15px;
        }

        .filter-item label {
            display: block;
            font-size: 13px;
            font-weight: bold;
            margin-bottom: 8px;
        }

        input,
        select {
            width: 100%;
            padding: 11px;
            border: 1px solid #dce7de;
            border-radius: 8px;
            background: white;
            font-size: 14px;
        }

        .filter-actions {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
            margin-top: 18px;
        }

        button {
            padding: 11px 18px;
            border: none;
            border-radius: 8px;
            background: #217346;
            color: white;
            cursor: pointer;
            font-weight: bold;
        }

        button:hover {
            background: #175633;
        }

        .reset {
            color: #68736b;
            text-decoration: none;
            padding: 10px;
        }

        .reset:hover {
            color: #175633;
        }

        /* PESAN */
        .alert {
            padding: 13px;
            border-radius: 8px;
            margin-bottom: 18px;
        }

        .success {
            background: #ddf5e3;
            color: #175633;
        }

        .error {
            background: #fde8e6;
            color: #b83228;
        }

        /* TABEL TRANSAKSI */
        .table-wrapper {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 750px;
        }

        th,
        td {
            padding: 14px 12px;
            border-bottom: 1px solid #edf1ed;
            text-align: left;
            font-size: 14px;
        }

        th {
            color: #68736b;
            font-size: 13px;
        }

        .type {
            display: inline-block;
            padding: 6px 10px;
            border-radius: 20px;
            font-size: 12px;
        }

        .income {
            background: #ddf5e3;
            color: #175633;
        }

        .expense {
            background: #fde8e6;
            color: #b83228;
        }

        .amount-income {
            color: #217346;
            font-weight: bold;
        }

        .amount-expense {
            color: #d9534f;
            font-weight: bold;
        }

        /* AKSI EDIT DAN HAPUS */
        .actions {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .edit {
            color: #217346;
            text-decoration: none;
        }

        .edit:hover {
            color: #175633;
            text-decoration: underline;
        }

        .delete {
            margin: 0;
        }

        .delete button {
            padding: 7px 10px;
            background: #d9534f;
            font-size: 12px;
        }

        .delete button:hover {
            background: #b83228;
        }

        .empty {
            padding: 35px;
            text-align: center;
            color: #68736b;
        }

        .result-info {
            color: #68736b;
            font-size: 13px;
            margin-bottom: 15px;
        }

        /* RESPONSIVE */
        @media (max-width: 950px) {
            .filter-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 650px) {
            .sidebar {
                position: static;
                display: block;
                width: 100%;
                height: auto;
            }

            .menu {
                flex-direction: row;
                flex-wrap: wrap;
            }

            .menu a.logout {
                margin-top: 0;
            }

            .main {
                margin-left: 0;
                padding: 20px;
            }

            .filter-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>

    <!-- SIDEBAR -->
    <aside class="sidebar">

        <div class="brand">DompetKu</div>

        <nav class="menu">

            <a href="<?= site_url('dashboard') ?>">
                Beranda
            </a>

            <a href="<?= site_url('transaksi/tambah') ?>">
                Tambah Transaksi
            </a>

            <a
                href="<?= site_url('transaksi') ?>"
                class="<?= !$searchPage ? 'active' : '' ?>"
            >
                Transaksi
            </a>

            <a href="<?= site_url('kategori') ?>">
                Kategori
            </a>

            <a href="<?= site_url('laporan') ?>">
                Laporan Keuangan
            </a>

            <a
                href="<?= site_url('pencarian') ?>"
                class="<?= $searchPage ? 'active' : '' ?>"
            >
                Pencarian & Filter
            </a>

            <a href="<?= site_url('logout') ?>" class="logout">
                Keluar
            </a>

        </nav>

    </aside>

    <!-- KONTEN UTAMA -->
    <main class="main">

        <!-- HEADER -->
        <header class="header">

            <div>

                <h1>
                    <?= $searchPage ? 'Pencarian & Filter' : 'Transaksi' ?>
                </h1>

                <p>
                    <?= $searchPage
                        ? 'Cari transaksi berdasarkan nama, jenis, kategori, dan periode.'
                        : 'Lihat dan kelola seluruh transaksi keuanganmu.' ?>
                </p>

            </div>

            <a
                href="<?= site_url('transaksi/tambah') ?>"
                class="add-button"
            >
                + Tambah Transaksi
            </a>

        </header>

        <!-- PESAN SUKSES -->
        <?php if (session()->getFlashdata('success')): ?>

            <div class="alert success">
                <?= esc(session()->getFlashdata('success')) ?>
            </div>

        <?php endif; ?>

        <!-- PESAN ERROR -->
        <?php if (session()->getFlashdata('error')): ?>

            <div class="alert error">
                <?= esc(session()->getFlashdata('error')) ?>
            </div>

        <?php endif; ?>

        <!-- PANEL PENCARIAN DAN FILTER -->
        <?php if ($searchPage): ?>

            <section class="card">

                <h2>Filter Transaksi</h2>

                <form
                    method="get"
                    action="<?= site_url('pencarian') ?>"
                >

                    <div class="filter-grid">

                        <!-- KATA KUNCI -->
                        <div class="filter-item">

                            <label>Kata Kunci</label>

                            <input
                                type="search"
                                name="q"
                                placeholder="Cari nama atau keterangan..."
                                value="<?= esc($filters['q']) ?>"
                            >

                        </div>

                        <!-- JENIS TRANSAKSI -->
                        <div class="filter-item">

                            <label>Jenis Transaksi</label>

                            <select name="type">

                                <option value="">
                                    Semua Jenis
                                </option>

                                <option
                                    value="income"
                                    <?= $filters['type'] === 'income' ? 'selected' : '' ?>
                                >
                                    Pemasukan
                                </option>

                                <option
                                    value="expense"
                                    <?= $filters['type'] === 'expense' ? 'selected' : '' ?>
                                >
                                    Pengeluaran
                                </option>

                            </select>

                        </div>

                        <!-- KATEGORI -->
                        <div class="filter-item">

                            <label>Kategori</label>

                            <select name="category_id">

                                <option value="">
                                    Semua Kategori
                                </option>

                                <?php foreach ($categories as $category): ?>

                                    <option
                                        value="<?= $category->getId() ?>"
                                        <?= $filters['category_id'] == $category->getId()
                                            ? 'selected' : '' ?>
                                    >
                                        <?= esc($category->getCategoryName()) ?>
                                    </option>

                                <?php endforeach; ?>

                            </select>

                        </div>

                        <!-- TANGGAL MULAI -->
                        <div class="filter-item">

                            <label>Dari Tanggal</label>

                            <input
                                type="date"
                                name="start_date"
                                value="<?= esc($filters['start_date']) ?>"
                            >

                        </div>

                        <!-- TANGGAL AKHIR -->
                        <div class="filter-item">

                            <label>Sampai Tanggal</label>

                            <input
                                type="date"
                                name="end_date"
                                value="<?= esc($filters['end_date']) ?>"
                            >

                        </div>

                    </div>

                    <div class="filter-actions">

                        <button type="submit">
                            Terapkan Filter
                        </button>

                        <a
                            href="<?= site_url('pencarian') ?>"
                            class="reset"
                        >
                            Reset Filter
                        </a>

                    </div>

                </form>

            </section>

        <?php else: ?>

            <!-- KHUSUS HALAMAN TRANSAKSI -->
            <div style="margin-bottom: 20px;">

                <a
                    href="<?= site_url('pencarian') ?>"
                    class="add-button"
                >
                    Cari & Filter Transaksi
                </a>

            </div>

        <?php endif; ?>

        <!-- DAFTAR TRANSAKSI -->
        <section class="card">

            <h2>Daftar Transaksi</h2>

            <p class="result-info">
                Ditemukan <?= count($transactions) ?> transaksi.
            </p>

            <div class="table-wrapper">

                <table>

                    <thead>

                        <tr>
                            <th>Tanggal</th>
                            <th>Nama Transaksi</th>
                            <th>Kategori</th>
                            <th>Jenis</th>
                            <th>Nominal</th>
                            <th>Aksi</th>
                        </tr>

                    </thead>

                    <tbody>

                        <?php if (empty($transactions)): ?>

                            <tr>

                                <td colspan="6" class="empty">
                                    Belum ada transaksi yang ditemukan.
                                </td>

                            </tr>

                        <?php else: ?>

                            <?php foreach ($transactions as $transaction): ?>

                                <tr>

                                    <!-- TANGGAL -->
                                    <td>
                                        <?= esc(
                                            $transaction->getTransactionDate()
                                                ->format('d-m-Y')
                                        ) ?>
                                    </td>

                                    <!-- NAMA TRANSAKSI -->
                                    <td>
                                        <?= esc(
                                            $transaction->getTransactionName()
                                        ) ?>
                                    </td>

                                    <!-- KATEGORI -->
                                    <td>
                                        <?= esc(
                                            $transaction->getCategory()
                                                ->getCategoryName()
                                        ) ?>
                                    </td>

                                    <!-- JENIS -->
                                    <td>

                                        <?php if (
                                            $transaction->getTransactionType() === 'income'
                                        ): ?>

                                            <span class="type income">
                                                Pemasukan
                                            </span>

                                        <?php else: ?>

                                            <span class="type expense">
                                                Pengeluaran
                                            </span>

                                        <?php endif; ?>

                                    </td>

                                    <!-- NOMINAL -->
                                    <td>

                                        <?php if (
                                            $transaction->getTransactionType() === 'income'
                                        ): ?>

                                            <span class="amount-income">

                                                + Rp <?= number_format(
                                                    (float) $transaction->getAmount(),
                                                    0,
                                                    ',',
                                                    '.'
                                                ) ?>

                                            </span>

                                        <?php else: ?>

                                            <span class="amount-expense">

                                                - Rp <?= number_format(
                                                    (float) $transaction->getAmount(),
                                                    0,
                                                    ',',
                                                    '.'
                                                ) ?>

                                            </span>

                                        <?php endif; ?>

                                    </td>

                                    <!-- AKSI -->
                                    <td>

                                        <div class="actions">

                                            <a
                                                href="<?= site_url(
                                                    'transaksi/edit/' . $transaction->getId()
                                                ) ?>"
                                                class="edit"
                                            >
                                                Edit
                                            </a>

                                            <form
                                                action="<?= site_url(
                                                    'transaksi/hapus/' . $transaction->getId()
                                                ) ?>"
                                                method="post"
                                                class="delete"
                                                onsubmit="return confirm('Yakin ingin menghapus transaksi ini?')"
                                            >

                                                <?= csrf_field() ?>

                                                <button type="submit">
                                                    Hapus
                                                </button>

                                            </form>

                                        </div>

                                    </td>

                                </tr>

                            <?php endforeach; ?>

                        <?php endif; ?>

                    </tbody>

                </table>

            </div>

        </section>

    </main>

</body>
</html>
