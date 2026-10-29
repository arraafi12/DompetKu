<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password</title>
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

            <h1>Reset Password</h1>

            <p class="subtitle">
                <?= !empty($verifiedEmail)
                    ? 'Masukkan password baru untuk akunmu.'
                    : 'Masukkan email yang terdaftar untuk melanjutkan.' ?>
            </p>
        </div>

        <!-- Notifikasi error validasi -->
        <?php if (!empty($errors)): ?>
            <?php foreach ($errors as $error): ?>
                <p class="form-message error">
                    <?= esc($error) ?>
                </p>
            <?php endforeach; ?>
        <?php endif; ?>

        <!-- Notifikasi error -->
        <?php if (session()->getFlashdata('error')): ?>
            <p class="form-message error">
                <?= esc(session()->getFlashdata('error')) ?>
            </p>
        <?php endif; ?>

        <!-- Notifikasi sukses -->
        <?php if (session()->getFlashdata('success')): ?>
            <p class="form-message success">
                <?= esc(session()->getFlashdata('success')) ?>
            </p>
        <?php endif; ?>

        <?php if (empty($verifiedEmail)): ?>

            <!-- Tahap 1: Verifikasi email -->
            <form action="<?= site_url('lupa-password') ?>" method="post">
                <?= csrf_field() ?>

                <input type="hidden" name="action" value="verify_email">

                <label for="email">Email terdaftar</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="<?= esc(old('email') ?? '') ?>"
                    placeholder="Masukkan email akun"
                    autocomplete="email"
                    required
                >

                <button type="submit">Verifikasi Email</button>
            </form>

        <?php else: ?>

            <!-- Email berhasil ditemukan -->
            <div class="form-message success">
                Email ditemukan:
                <strong><?= esc($verifiedEmail) ?></strong>
            </div>

            <!-- Tahap 2: Membuat password baru -->
            <form action="<?= site_url('lupa-password') ?>" method="post">
                <?= csrf_field() ?>

                <input type="hidden" name="action" value="reset_password">

                <label for="password">Password baru</label>

                <div class="password-wrapper">
                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Minimal 8 karakter"
                        autocomplete="new-password"
                        required
                    >

                    <button
                        type="button"
                        class="toggle-password"
                        data-target="password"
                        aria-label="Tampilkan password"
                        aria-pressed="false"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            width="21"
                            height="21"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            aria-hidden="true"
                        >
                            <path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7-10-7-10-7z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                    </button>
                </div>

                <label for="confirm_password">
                    Konfirmasi password baru
                </label>

                <div class="password-wrapper">
                    <input
                        type="password"
                        id="confirm_password"
                        name="confirm_password"
                        placeholder="Ulangi password baru"
                        autocomplete="new-password"
                        required
                    >

                    <button
                        type="button"
                        class="toggle-password"
                        data-target="confirm_password"
                        aria-label="Tampilkan password"
                        aria-pressed="false"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            width="21"
                            height="21"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            aria-hidden="true"
                        >
                            <path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7-10-7-10-7z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                    </button>
                </div>

                <button type="submit">Simpan Password Baru</button>
            </form>

        <?php endif; ?>

        <div class="register-link">
            <a href="<?= site_url('login') ?>">
                Kembali ke halaman login
            </a>
        </div>

    </section>
</main>

<script>
    document.querySelectorAll('.toggle-password').forEach(function (button) {
        button.addEventListener('click', function () {
            const input = document.getElementById(button.dataset.target);
            const showPassword = input.type === 'password';

            input.type = showPassword ? 'text' : 'password';

            button.setAttribute(
                'aria-label',
                showPassword
                    ? 'Sembunyikan password'
                    : 'Tampilkan password'
            );

            button.setAttribute(
                'aria-pressed',
                String(showPassword)
            );
        });
    });
</script>

</body>
</html>