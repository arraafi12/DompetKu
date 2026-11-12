<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | DompetKu</title>

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
            background: #ffffff;
            padding: 30px 20px;
            border-right: 1px solid #dce7de;
        }

        .brand {
            color: #217346;
            font-size: 25px;
            font-weight: bold;
            margin-bottom: 40px;
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

        .logout {
            position: absolute;
            bottom: 25px;
            left: 20px;
            right: 20px;
            color: #c0392b !important;
        }

        .main {
            margin-left: 240px;
            padding: 35px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .header h1 {
            font-size: 27px;
            margin-bottom: 8px;
        }

        .header p {
            color: #68736b;
        }

        .cards {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }

        .card {
            background: white;
            padding: 25px;
            border-radius: 15px;
            border: 1px solid #dce7de;
        }

        .card p {
            color: #68736b;
            margin-bottom: 15px;
        }

        .card h2 {
            font-size: 25px;
        }

        .balance {
            background: #217346;
            color: white;
        }

        .balance p {
            color: #e0f2e5;
        }

        .income {
            color: #217346;
        }

        .expense {
            color: #d35448;
        }

        .transactions {
            background: white;
            padding: 25px;
            border: 1px solid #dce7de;
            border-radius: 15px;
            overflow-x: auto;
        }

        .transactions h2 {
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 500px;
        }

        th, td {
            padding: 15px 10px;
            text-align: left;
            border-bottom: 1px solid #edf1ed;
        }

        th {
            color: #68736b;
            font-size: 13px;
        }

        .empty {
            text-align: center;
            color: #68736b;
            padding: 30px;
        }

        @media (max-width: 800px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
                border-right: none;
                border-bottom: 1px solid #dce7de;
            }

            .menu {
                display: flex;
                flex-wrap: wrap;
            }

            .logout {
                position: static;
            }

            .main {
                margin-left: 0;
                padding: 20px;
            }

            .cards {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>

    <aside class="sidebar">

        <div class="brand">DompetKu</div>

        <nav class="menu">
            <a href="<?= site_url('dashboard') ?>" class="active">
                Beranda
            </a>

            <a href="<?= site_url('transaksi/tambah') ?>">
                Tambah Transaksi
            </a>

            <a href="<?= site_url('transaksi') ?>">
                Transaksi
            </a>

            <a href="<?= site_url('kategori') ?>">
                Kategori
            </a>

            <a href="<?= site_url('laporan') ?>">
                Laporan Keuangan
            </a>

            <a href="<?= site_url('transaksi') ?>">
                Pencarian & Filter
            </a>

            <a href="<?= site_url('logout') ?>" class="logout">
                Keluar
            </a>
        </nav>

    </aside>

    <main class="main">

        <header class="header">
            <div>
                <h1>Beranda</h1>
                <p>
                    Selamat datang,
                    <?= esc($user->getName()) ?>!
                </p>
            </div>
        </header>

        <section class="cards">

            <div class="card balance">
                <p>Saldo Saat Ini</p>
                <h2>
                    Rp <?= number_format((float) $balance, 0, ',', '.') ?>
                </h2>
            </div>

            <div class="card">
                <p>Total Pemasukan</p>
                <h2 class="income">
                    Rp <?= number_format((float) $income, 0, ',', '.') ?>
                </h2>
            </div>

            <div class="card">
                <p>Total Pengeluaran</p>
                <h2 class="expense">
                    Rp <?= number_format((float) $expense, 0, ',', '.') ?>
                </h2>
            </div>

        </section>

        <section class="transactions">

            <h2>Transaksi Terbaru</h2>

            <table>
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Transaksi</th>
                        <th>Kategori</th>
                        <th>Jenis</th>
                        <th>Nominal</th>
                    </tr>
                </thead>

                <tbody>

                    <?php if (empty($recentTransactions)): ?>

                        <tr>
                            <td colspan="5" class="empty">
                                Belum ada transaksi.
                            </td>
                        </tr>

                    <?php else: ?>

                        <?php foreach ($recentTransactions as $transaction): ?>

                            <tr>
                                <td>
                                    <?= esc(
                                        $transaction->getTransactionDate()
                                            ? $transaction->getTransactionDate()->format('d-m-Y')
                                            : '-'
                                    ) ?>
                                </td>

                                <td>
                                    <?= esc($transaction->getTransactionName()) ?>
                                </td>

                                <td>
                                    <?= esc(
                                        $transaction->getCategory()->getCategoryName()
                                    ) ?>
                                </td>

                                <td>
                                    <?= esc($transaction->getTransactionType()) ?>
                                </td>

                                <td>
                                    Rp <?= number_format(
                                        (float) $transaction->getAmount(),
                                        0,
                                        ',',
                                        '.'
                                    ) ?>
                                </td>
                            </tr>

                        <?php endforeach; ?>

                    <?php endif; ?>

                </tbody>
            </table>

        </section>

    </main>

</body>
</html>
