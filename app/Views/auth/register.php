<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - DompetKu</title>

    <link rel="icon" type="image/png"
          href="<?= base_url('assets/img/logo dompetku.png') ?>">

    <link rel="stylesheet"
          href="<?= base_url('assets/css/style.css') ?>">
</head>
<body>
<main class="auth-container">
    <section class="auth-card">
        <div class="brand">
            <h1>Buat Akun DompetKu</h1>
        </div>

        <?php foreach (($errors ?? []) as $error): ?>
            <p class="form-message error"><?= esc($error) ?></p>
        <?php endforeach; ?>

        <form action="<?= site_url('daftar') ?>" method="post">
            <?= csrf_field() ?>

            <label for="name">Nama lengkap</label>
            <input
                type="text"
                id="name"
                name="name"
                value="<?= esc(old('name') ?? '') ?>"
                placeholder="Masukkan nama lengkap"
                autocomplete="name"
                required
            >

            <label for="email">Email</label>
            <input
                type="email"
                id="email"
                name="email"
                value="<?= esc(old('email') ?? '') ?>"
                placeholder="Masukkan email"
                autocomplete="email"
                required
            >

            <label for="phone">Nomor telepon</label>
            <input
                type="tel"
                id="phone"
                name="phone"
                value="<?= esc(old('phone') ?? '') ?>"
                placeholder="Contoh: 081234567890"
                autocomplete="tel"
                required
            >

            <label for="password">Password</label>
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

            <label for="confirm_password">Konfirmasi password</label>
            <div class="password-wrapper">
                <input
                    type="password"
                    id="confirm_password"
                    name="confirm_password"
                    placeholder="Ulangi password"
                    autocomplete="new-password"
                    required
                >

                <button
                    type="button"
                    class="toggle-password"
                    data-target="confirm_password"
                    aria-label="Tampilkan konfirmasi password"
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

            <button type="submit">Daftar</button>
        </form>

        <div class="register-link">
            Sudah punya akun?
            <a href="<?= site_url('login') ?>">Masuk sekarang</a>
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
                showPassword ? 'Sembunyikan password' : 'Tampilkan password'
            );

            button.setAttribute('aria-pressed', String(showPassword));

            if (showPassword) {
                button.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg"
                         width="21" height="21" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor"
                         stroke-width="2" stroke-linecap="round"
                         stroke-linejoin="round" aria-hidden="true">
                        <path d="M3 3l18 18"/>
                        <path d="M10.6 10.6a2 2 0 002.8 2.8"/>
                        <path d="M9.9 5.2A11 11 0 0112 5c6.4 0 10 7 10 7a15 15 0 01-3 3.8"/>
                        <path d="M6.2 6.2C3.5 8 2 12 2 12s3.6 7 10 7a10 10 0 004-.8"/>
                    </svg>
                `;
            } else {
                button.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg"
                         width="21" height="21" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor"
                         stroke-width="2" stroke-linecap="round"
                         stroke-linejoin="round" aria-hidden="true">
                        <path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7-10-7-10-7z"/>
                        <circle cx="12" cy="12" r="3"/>
                    </svg>
                `;
            }
        });
    });
</script>
</body>
</html>
