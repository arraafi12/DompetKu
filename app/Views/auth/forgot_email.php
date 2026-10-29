<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Email</title>
    <link rel="icon" type="image/png"
          href="<?= base_url('assets/img/logo dompetku.png') ?>">
    <link rel="stylesheet"
          href="<?= base_url('assets/css/style.css') ?>">
</head>
<body>

<main class="auth-container">
    <section class="auth-card">
        <div class="brand">
            <img
                src="<?= base_url('assets/img/logo dompetku.png') ?>"
                alt="Logo DompetKu"
                class="brand-logo"
            >

            <h1>Pemulihan Email</h1>

            <p class="subtitle">
                Masukkan nama dan nomor telepon yang terdaftar
                untuk menemukan email akun DompetKu
            </p>
        </div>

        <!-- Pesan error validasi -->
        <?php if (!empty($errors)): ?>
            <?php foreach ($errors as $error): ?>
                <p class="form-message error">
                    <?= esc($error) ?>
                </p>
            <?php endforeach; ?>
        <?php endif; ?>

        <!-- Pesan jika data tidak cocok -->
        <?php if (!empty($message)): ?>
            <p class="form-message error">
                <?= esc($message) ?>
            </p>
        <?php endif; ?>

        <!-- Pesan jika email ditemukan -->
        <?php if (!empty($recoveredEmail)): ?>
            <div class="form-message success">
                Email akun berhasil ditemukan:
                <strong><?= esc($recoveredEmail) ?></strong>
            </div>
        <?php endif; ?>

        <!-- Form pemulihan email -->
        <form action="<?= site_url('lupa-email') ?>" method="post">
            <?= csrf_field() ?>

            <label for="name">Nama lengkap</label>
            <input
                type="text"
                id="name"
                name="name"
                value="<?= esc(old('name') ?? '') ?>"
                placeholder="Masukkan nama terdaftar"
                autocomplete="name"
                maxlength="100"
                required
            >

            <label for="phone">Nomor telepon terdaftar</label>
            <input
                type="tel"
                id="phone"
                name="phone"
                value="<?= esc(old('phone') ?? '') ?>"
                placeholder="Masukkan nomor telepon"
                autocomplete="tel"
                maxlength="20"
                required
            >

            <button type="submit">Verifikasi</button>
        </form>

        <!-- Navigasi kembali -->
        <div class="register-link">
            <a href="<?= site_url('login') ?>">
                Kembali ke halaman login
            </a>
        </div>

    </section>
</main>

</body>
</html>