<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    public function login()
    {
        return view('auth/login');
    }

    public function register()
    {
        if ($this->request->getMethod() !== 'POST') {
            return view('auth/register');
        }

        $rules = [
            'name' => 'required|min_length[3]|max_length[100]',
            'email' => 'required|valid_email|max_length[150]|is_unique[users.email]',
            'phone' => 'required|min_length[8]|max_length[20]|is_unique[users.phone]',
            'password' => 'required|min_length[8]',
            'confirm_password' => 'required|matches[password]',
        ];

        if (!$this->validate($rules)) {
            return view('auth/register', [
                'errors' => $this->validator->getErrors(),
            ]);
        }

        $users = new UserModel();

        $users->insert([
            'name' => trim($this->request->getPost('name')),
            'email' => strtolower(trim($this->request->getPost('email'))),
            'phone' => trim($this->request->getPost('phone')),
            'password_hash' => password_hash(
                $this->request->getPost('password'),
                PASSWORD_DEFAULT
            ),
        ]);

        return redirect()->to(site_url('login'))
            ->with('success', 'Registrasi berhasil. Silakan masuk.');
    }

    public function forgotEmail()
    {
        $data = [
            'errors' => [],
            'recoveredEmail' => null,
            'message' => null,
        ];

        if ($this->request->getMethod() === 'POST') {
            $rules = [
                'name' => 'required',
                'phone' => 'required',
            ];

            if (!$this->validate($rules)) {
                $data['errors'] = $this->validator->getErrors();

                return view('auth/forgot_email', $data);
            }

            $name = trim($this->request->getPost('name'));
            $phone = trim($this->request->getPost('phone'));

            $user = (new UserModel())
                ->where('name', $name)
                ->where('phone', $phone)
                ->first();

            if ($user) {
                $data['recoveredEmail'] = $user['email'];
            } else {
                $data['message'] =
                    'Nama dan nomor telepon tidak cocok dengan data akun.';
            }
        }

        return view('auth/forgot_email', $data);
    }

    public function forgotPassword()
    {
        $users = new UserModel();

        // Langkah kedua: simpan password baru setelah email diperiksa.
        if ($this->request->getPost('action') === 'reset_password') {
            $verifiedEmail = session()->get('reset_verified_email');

            if (!$verifiedEmail) {
                return redirect()->to(site_url('lupa-password'))
                    ->with('error', 'Silakan verifikasi email terlebih dahulu.');
            }

            $rules = [
                'password' => 'required|min_length[8]',
                'confirm_password' => 'required|matches[password]',
            ];

            if (!$this->validate($rules)) {
                return view('auth/forgot_password', [
                    'errors' => $this->validator->getErrors(),
                    'verifiedEmail' => $verifiedEmail,
                ]);
            }

            $user = $users->where('email', $verifiedEmail)->first();

            if (!$user) {
                session()->remove('reset_verified_email');

                return redirect()->to(site_url('lupa-password'))
                    ->with('error', 'Akun tidak ditemukan.');
            }

            $users->update($user['id'], [
                'password_hash' => password_hash(
                    $this->request->getPost('password'),
                    PASSWORD_DEFAULT
                ),
            ]);

            session()->remove('reset_verified_email');

            return redirect()->to(site_url('lupa-password'))
                ->with('success', 'Password berhasil diubah. Silakan masuk.');
        }

        // Langkah pertama: periksa apakah email terdaftar.
        if ($this->request->getPost('action') === 'verify_email') {
            $email = strtolower(trim($this->request->getPost('email')));

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return view('auth/forgot_password', [
                    'errors' => ['Email tidak valid.'],
                    'verifiedEmail' => null,
                ]);
            }

            $user = $users->where('email', $email)->first();

            if (!$user) {
                return view('auth/forgot_password', [
                    'errors' => ['Email tidak ditemukan. Periksa kembali email lu.'],
                    'verifiedEmail' => null,
                ]);
            }

            // Ini hanya pemeriksaan email di database,
            // bukan bukti kepemilikan email.
            session()->set('reset_verified_email', $user['email']);

            return view('auth/forgot_password', [
                'errors' => [],
                'verifiedEmail' => $user['email'],
            ]);
        }

        return view('auth/forgot_password', [
            'errors' => [],
            'verifiedEmail' => null,
        ]);
    }
}
