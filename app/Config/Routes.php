<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Auth::login');

$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::login');

$routes->get('daftar', 'Auth::register');
$routes->post('daftar', 'Auth::register');

$routes->get('lupa-email', 'Auth::forgotEmail');
$routes->post('lupa-email', 'Auth::forgotEmail');

$routes->get('lupa-password', 'Auth::forgotPassword');
$routes->post('lupa-password', 'Auth::forgotPassword');

$routes->get('doctrine-test', 'DoctrineTest::index');

$routes->get('dashboard', 'Dashboard::index');

$routes->get('kategori', 'Categories::index');
$routes->post('kategori/simpan', 'Categories::store');
$routes->get('kategori/edit/(:num)', 'Categories::edit/$1');
$routes->post('kategori/perbarui/(:num)', 'Categories::update/$1');
$routes->post('kategori/hapus/(:num)', 'Categories::delete/$1');

$routes->get('logout', 'Auth::logout');

// Menampilkan daftar transaksi
$routes->get('transaksi', 'Transactions::index');

// Menampilkan form tambah transaksi
$routes->get('transaksi/tambah', 'Transactions::create');

// Menyimpan transaksi baru
$routes->post('transaksi/simpan', 'Transactions::store');

// Menampilkan form edit transaksi
$routes->get('transaksi/edit/(:num)', 'Transactions::edit/$1');

// Memperbarui transaksi
$routes->post('transaksi/perbarui/(:num)', 'Transactions::update/$1');

// Menghapus transaksi
$routes->post('transaksi/hapus/(:num)', 'Transactions::delete/$1');

// ================================
// PENCARIAN DAN FILTER
// ================================

$routes->get('pencarian', 'Transactions::search');
