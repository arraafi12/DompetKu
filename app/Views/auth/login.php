<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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
            </div>

            <form action="<?= site_url('login') ?>" method="post">
                <?= csrf_field() ?>

                <label for="email">Email</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    placeholder="Masukkan email"
                    autocomplete="email"
                    required
                >

                <label for="password">Password</label>

                <div class="password-wrapper">
                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Masukkan password"
                        autocomplete="current-password"
                        required
                    >

                    <button
                        type="button"
                        class="toggle-password"
                        id="togglePassword"
                        aria-label="Tampilkan password"
                        aria-pressed="false"
                    >
                        <svg
                            id="eyeIcon"
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

                <button type="submit">Masuk</button>
            </form>

            <div class="recovery-links">
                <a href="<?= site_url('lupa-email') ?>">Lupa Email?</a>
                <a href="<?= site_url('lupa-password') ?>">Lupa Password?</a>
            </div>

            <div class="register-link">
                Belum punya akun?
                <a href="<?= site_url('daftar') ?>">Daftar sekarang</a>
            </div>

        </section>
    </main>

    <script>
        const passwordInput = document.getElementById('password');
        const toggleButton = document.getElementById('togglePassword');
        const eyeIcon = document.getElementById('eyeIcon');

        toggleButton.addEventListener('click', function () {
            const showPassword = passwordInput.type === 'password';

            passwordInput.type = showPassword ? 'text' : 'password';

            toggleButton.setAttribute(
                'aria-label',
                showPassword
                    ? 'Sembunyikan password'
                    : 'Tampilkan password'
            );

            toggleButton.setAttribute(
                'aria-pressed',
                String(showPassword)
            );

            if (showPassword) {
                eyeIcon.innerHTML = `
                    <path d="M3 3l18 18"/>
                    <path d="M10.6 10.6a2 2 0 002.8 2.8"/>
                    <path d="M9.9 5.2A11 11 0 0112 5c6.4 0 10 7 10 7a15 15 0 01-3 3.8"/>
                    <path d="M6.2 6.2C3.5 8 2 12 2 12s3.6 7 10 7a10 10 0 004-.8"/>
                `;
            } else {
                eyeIcon.innerHTML = `
                    <path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7-10-7-10-7z"/>
                    <circle cx="12" cy="12" r="3"/>
                `;
            }
        });
    </script>
</body>
</html>