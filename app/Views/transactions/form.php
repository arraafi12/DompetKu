<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $transaction ? 'Edit' : 'Tambah' ?> Transaksi | DompetKu</title>

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

        .card {
            max-width: 750px;
            padding: 28px;
            border: 1px solid #dce7de;
            border-radius: 15px;
            background: white;
        }

        .card h2 {
            margin-bottom: 24px;
            font-size: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
            font-weight: bold;
        }

        input,
        select,
        textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #dce7de;
            border-radius: 8px;
            background: white;
            font-size: 14px;
        }

        textarea {
            min-height: 100px;
            resize: vertical;
        }

        input:focus,
        select:focus,
        textarea:focus {
            outline: none;
            border-color: #217346;
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

        .warning {
            padding: 14px;
            margin-bottom: 20px;
            border-radius: 8px;
            background: #fff4d6;
            color: #805b12;
        }

        .warning a {
            color: #175633;
            font-weight: bold;
        }

        .form-actions {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        button {
            padding: 12px 20px;
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

        button:disabled {
            background: #9eaaa1;
            cursor: not-allowed;
        }

        .cancel {
            padding: 12px 18px;
            border-radius: 8px;
            color: #68736b;
            text-decoration: none;
            background: #edf2ee;
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
        }
    </style>
</head>

<body>

    <aside class="sidebar">
        <div class="brand">DompetKu</div>

        <nav class="menu">
            <a href="<?= site_url('dashboard') ?>">Beranda</a>

            <a href="<?= site_url('transaksi/tambah') ?>" class="active">
                Tambah Transaksi
            </a>

            <a href="<?= site_url('transaksi') ?>">Transaksi</a>

            <a href="<?= site_url('kategori') ?>">Kategori</a>

            <a href="<?= site_url('laporan') ?>">Laporan Keuangan</a>

            <a href="<?= site_url('pencarian') ?>">Pencarian & Filter</a>

            <a href="<?= site_url('logout') ?>" class="logout">
                Keluar
            </a>
        </nav>
    </aside>

    <main class="main">

        <header class="header">
            <h1><?= $transaction ? 'Edit Transaksi' : 'Tambah Transaksi' ?></h1>
            <p>
                <?= $transaction
                    ? 'Perbarui informasi transaksi keuanganmu.'
                    : 'Catat pemasukan atau pengeluaran keuanganmu.' ?>
            </p>
        </header>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert error">
                <?= esc(session()->getFlashdata('error')) ?>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert success">
                <?= esc(session()->getFlashdata('success')) ?>
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

        <section class="card">

            <h2>Informasi Transaksi</h2>

            <?php if (empty($categories)): ?>
                <div class="warning">
                    Kamu belum memiliki kategori transaksi.
                    <a href="<?= site_url('kategori') ?>">Buat kategori terlebih dahulu</a>
                    sebelum menyimpan transaksi.
                </div>
            <?php endif; ?>

            <?php
                $selectedType = old(
                    'transaction_type',
                    $transaction ? $transaction->getTransactionType() : 'expense'
                );

                $selectedCategory = old(
                    'category_id',
                    $transaction ? $transaction->getCategory()->getId() : ''
                );

                $selectedDate = old(
                    'transaction_date',
                    $transaction && $transaction->getTransactionDate()
                        ? $transaction->getTransactionDate()->format('Y-m-d')
                        : date('Y-m-d')
                );
            ?>

            <form
                method="post"
                action="<?= $transaction
                    ? site_url('transaksi/perbarui/' . $transaction->getId())
                    : site_url('transaksi/simpan') ?>"
            >

                <?= csrf_field() ?>

                <div class="form-group">
                    <label>Nama Transaksi</label>

                    <input
                        type="text"
                        name="transaction_name"
                        placeholder="Contoh: Membeli makan siang"
                        value="<?= esc(old(
                            'transaction_name',
                            $transaction ? $transaction->getTransactionName() : ''
                        )) ?>"
                        maxlength="150"
                        required
                    >
                </div>

                <div class="form-group">
                    <label>Jenis Transaksi</label>

                    <select name="transaction_type" id="transaction_type" required>
                        <option value="income"
                            <?= $selectedType === 'income' ? 'selected' : '' ?>>
                            Pemasukan
                        </option>

                        <option value="expense"
                            <?= $selectedType === 'expense' ? 'selected' : '' ?>>
                            Pengeluaran
                        </option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Kategori</label>

                    <select name="category_id" id="category_id" required>
                        <option value="">Pilih Kategori</option>

                        <?php foreach ($categories as $category): ?>
                            <option
                                value="<?= $category->getId() ?>"
                                data-type="<?= esc($category->getCategoryType()) ?>"
                                <?= (string) $selectedCategory === (string) $category->getId()
                                    ? 'selected' : '' ?>
                            >
                                <?= esc($category->getCategoryName()) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Nominal (Rp)</label>

                    <input
                        type="number"
                        name="amount"
                        placeholder="Contoh: 50000"
                        value="<?= esc(old(
                            'amount',
                            $transaction ? $transaction->getAmount() : ''
                        )) ?>"
                        min="0.01"
                        step="0.01"
                        required
                    >
                </div>

                <div class="form-group">
                    <label>Tanggal Transaksi</label>

                    <input
                        type="date"
                        name="transaction_date"
                        value="<?= esc($selectedDate) ?>"
                        required
                    >
                </div>

                <div class="form-group">
                    <label>Keterangan (Opsional)</label>

                    <textarea
                        name="description"
                        placeholder="Tambahkan keterangan transaksi..."
                    ><?= esc(old(
                        'description',
                        $transaction ? ($transaction->getDescription() ?? '') : ''
                    )) ?></textarea>
                </div>

                <div class="form-actions">
                    <button
                        type="submit"
                        <?= empty($categories) ? 'disabled' : '' ?>
                    >
                        <?= $transaction ? 'Simpan Perubahan' : 'Simpan Transaksi' ?>
                    </button>

                    <a href="<?= site_url('transaksi') ?>" class="cancel">
                        Batal
                    </a>
                </div>

            </form>

        </section>

    </main>

    <script>
        const typeSelect = document.getElementById('transaction_type');
        const categorySelect = document.getElementById('category_id');
        const initialCategory = <?= json_encode((string) $selectedCategory) ?>;

        function filterCategories() {
            const selectedType = typeSelect.value;
            let selectedStillAvailable = false;

            Array.from(categorySelect.options).forEach(option => {
                if (!option.value) {
                    option.hidden = false;
                    return;
                }

                const available = option.dataset.type === selectedType;
                option.hidden = !available;

                if (option.value === categorySelect.value && available) {
                    selectedStillAvailable = true;
                }
            });

            if (!selectedStillAvailable) {
                categorySelect.value = '';
            }
        }

        typeSelect.addEventListener('change', filterCategories);

        filterCategories();

        if (initialCategory) {
            const option = Array.from(categorySelect.options)
                .find(option => option.value === initialCategory);

            if (option && !option.hidden) {
                categorySelect.value = initialCategory;
            }
        }
    </script>

</body>
</html>
