<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\UserModel;

class Auth extends Controller
{
  public function login()
  {
    return view('auth/login');
  }

  public function loginPost()
  {
    $username = $this->request->getPost('username');
    $password = $this->request->getPost('password');

    $userModel = new UserModel();
    $user = $userModel->where('username', $username)->first();

    if ($user && password_verify($password, $user['password'])) {
      // Set session
      session()->set([
        'id' => $user['id'],
        'username' => $user['username'],
        'role' => $user['role'],
        'logged_in' => true,
        // Tambahkan nama user untuk ditampilkan di pop-up
        'nama_user' => $user['username'] // Ganti 'username' jika ada kolom nama asli seperti 'nama_lengkap'
      ]);

      // Catat log aktivitas login
      helper('log_helper');
      log_activity('Login berhasil', $user['id']);

      // === PERUBAHAN DI SINI: Atur flashdata untuk memicu pop-up ===
      session()->setFlashdata('show_welcome_popup', true);

      // Redirect sesuai role
      if ($user['role'] === 'operator' || $user['role'] === 'kesiswaan' || $user['role'] === 'admin') {
        return redirect()->to('/dashboard'); // Arahkan ke dashboard admin
      } elseif ($user['role'] === 'pengunjung') {
        return redirect()->to('/pengunjung');
      }
    } else {
      return redirect()->to('/login')->with('error', 'Username atau password salah!');
    }
  }

  public function logout()
  {
    session()->destroy();
    return redirect()->to('/');
  }
}
