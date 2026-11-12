<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kategori | DompetKu</title>

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

        .sidebar {
            position: fixed;
            width: 240px;
            height: 100vh;
            background: white;
            padding: 30px 20px;
            border-right: 1px solid #dce7de;
            
            /* mengatur susunan sidebar */
            display: flex;
            flex-direction:column;
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
            text-decoration: none;
            color: #68736b;
            padding: 14px;
            border-radius: 9px;
            margin-bottom: 8px;
        }

        .menu a.active,
        .menu a:hover {
            background: #ddf5e3;
            color: #175633;
        }
        
        /* Posisi tombol Logout */
        .logout {
            position: static;
            margin-top: auto;
            color: #c0392b !important;
        }

        /* Efek hover tombol Logout */
        .menu a.logout:hover {
            background: transparent;
            color: #c0392b !important;
        }

        .main {
            margin-left: 240px;
            padding: 35px;
        }

        .header {
            margin-bottom: 25px;
        }

        .header h1 {
            margin-bottom: 8px;
        }

        .header p {
            color: #68736b;
        }

        .content {
            display: grid;
            grid-template-columns: minmax(280px, 0.8fr) minmax(400px, 1.5fr);
            gap: 20px;
        }

        .card {
            background: white;
            padding: 25px;
            border: 1px solid #dce7de;
            border-radius: 15px;
        }

        .card h2 {
            font-size: 19px;
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            font-size: 14px;
        }

        input, select {
            width: 100%;
            padding: 12px;
            border: 1px solid #dce7de;
            border-radius: 8px;
            margin-bottom: 18px;
            font-size: 14px;
            background: white;
        }

        button {
            border: none;
            border-radius: 8px;
            padding: 12px 18px;
            background: #217346;
            color: white;
            cursor: pointer;
            font-weight: bold;
        }

        button:hover {
            background: #175633;
        }

        .cancel {
            display: inline-block;
            margin-left: 10px;
            color: #68736b;
            text-decoration: none;
        }

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

        .category-type {
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

        .actions {
            display: flex;
            gap: 8px;
        }

        .edit {
            color: #217346;
            text-decoration: none;
        }

        .delete {
            background: #d9534f;
            padding: 7px 10px;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 14px 10px;
            text-align: left;
            border-bottom: 1px solid #edf1ed;
        }

        th {
            color: #68736b;
            font-size: 13px;
        }

        td {
            font-size: 14px;
        }

        .empty {
            text-align: center;
            color: #68736b;
            padding: 30px;
        }

        @media (max-width: 950px) {
            .content {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 650px) {
            .sidebar {
                position: static;
                width: 100%;
                height: auto;
                display: block;
            }

            .menu {
                display: flex;
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

            .card {
                overflow-x: auto;
            }
        }
    </style>
</head>

<body>

    <aside class="sidebar">
        <div class="brand">DompetKu</div>

        <nav class="menu">
            <a href="<?= site_url('dashboard') ?>">Beranda</a>
            <a href="<?= site_url('transaksi/tambah') ?>">Tambah Transaksi</a>
            <a href="<?= site_url('transaksi') ?>">Transaksi</a>
            <a href="<?= site_url('kategori') ?>" class="active">Kategori</a>
            <a href="<?= site_url('laporan') ?>">Laporan Keuangan</a>
            <a href="<?= site_url('transaksi') ?>">Pencarian & Filter</a>
            <a href="<?= site_url('logout') ?>" class="logout">Keluar</a>
        </nav>
    </aside>

    <main class="main">

        <header class="header">
            <h1>Kategori Transaksi</h1>
            <p>Kelola kategori pemasukan dan pengeluaran keuanganmu.</p>
        </header>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert success">
                <?= esc(session()->getFlashdata('success')) ?>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert error">
                <?= esc(session()->getFlashdata('error')) ?>
            </div>
        <?php endif; ?>

        <?php $errors = session()->getFlashdata('errors') ?? []; ?>

        <?php if (!empty($errors)): ?>
            <div class="alert error">
                <?php foreach ($errors as $error): ?>
                    <div><?= esc($error) ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <section class="content">

            <div class="card">

                <h2>
                    <?= $editCategory ? 'Edit Kategori' : 'Tambah Kategori' ?>
                </h2>

                <form
                    action="<?= $editCategory
                        ? site_url('kategori/perbarui/' . $editCategory->getId())
                        : site_url('kategori/simpan') ?>"
                    method="post"
                >
                    <?= csrf_field() ?>

                    <label>Nama Kategori</label>
                    <input
                        type="text"
                        name="category_name"
                        placeholder="Contoh: Makanan"
                        value="<?= esc(old(
                            'category_name',
                            $editCategory ? $editCategory->getCategoryName() : ''
                        )) ?>"
                        required
                    >

                    <label>Jenis Kategori</label>
                    <select name="category_type" required>
                        <?php $selectedType = old(
                            'category_type',
                            $editCategory ? $editCategory->getCategoryType() : ''
                        ); ?>

                        <option value="">Pilih Jenis</option>

                        <option
                            value="income"
                            <?= $selectedType === 'income' ? 'selected' : '' ?>
                        >
                            Pemasukan
                        </option>

                        <option
                            value="expense"
                            <?= $selectedType === 'expense' ? 'selected' : '' ?>
                        >
                            Pengeluaran
                        </option>
                    </select>

                    <button type="submit">
                        <?= $editCategory ? 'Simpan Perubahan' : 'Tambah Kategori' ?>
                    </button>

                    <?php if ($editCategory): ?>
                        <a href="<?= site_url('kategori') ?>" class="cancel">
                            Batal
                        </a>
                    <?php endif; ?>

                </form>

            </div>

            <div class="card">

                <h2>Daftar Kategori</h2>

                <table>
                    <thead>
                        <tr>
                            <th>Nama Kategori</th>
                            <th>Jenis</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php if (empty($categories)): ?>

                            <tr>
                                <td colspan="3" class="empty">
                                    Belum ada kategori.
                                </td>
                            </tr>

                        <?php else: ?>

                            <?php foreach ($categories as $category): ?>

                                <tr>
                                    <td>
                                        <?= esc($category->getCategoryName()) ?>
                                    </td>

                                    <td>
                                        <?php if ($category->getCategoryType() === 'income'): ?>
                                            <span class="category-type income">
                                                Pemasukan
                                            </span>
                                        <?php else: ?>
                                            <span class="category-type expense">
                                                Pengeluaran
                                            </span>
                                        <?php endif; ?>
                                    </td>

                                    <td>
                                        <div class="actions">
                                            <a
                                                href="<?= site_url(
                                                    'kategori/edit/' . $category->getId()
                                                ) ?>"
                                                class="edit"
                                            >
                                                Edit
                                            </a>

                                            <form
                                                action="<?= site_url(
                                                    'kategori/hapus/' . $category->getId()
                                                ) ?>"
                                                method="post"
                                                onsubmit="return confirm('Yakin ingin menghapus kategori ini?')"
                                            >
                                                <?= csrf_field() ?>

                                                <button type="submit" class="delete">
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
