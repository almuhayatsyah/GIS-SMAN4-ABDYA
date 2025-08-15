<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\LogModel;
use CodeIgniter\Controller;

class User extends Controller
{
  protected $userModel;
  protected $logModel;
  public function __construct()
  {
    $this->userModel = new UserModel();
    $this->logModel = new LogModel();
  }

  private function onlyOperator()
  {
    if (session()->get('role') !== 'operator') {
      return redirect()->to('/login');
    }
  }

  public function index()
  {
    $this->onlyOperator();
    $data['users'] = $this->userModel->findAll();
    $data['log_aktivitas'] = $this->logModel->getRecentLogs();
    return view('user/index', $data);
  }

  public function create()
  {
    $this->onlyOperator();
    return view('user/create');
  }

  public function store()
  {
    $this->onlyOperator();
    $username = $this->request->getPost('username');
    // Cek username sudah ada
    if ($this->userModel->where('username', $username)->first()) {
      return redirect()->back()->withInput()->with('error', 'Username sudah digunakan!');
    }
    $data = [
      'username' => $username,
      'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
      'role' => $this->request->getPost('role'),
      'created_at' => date('Y-m-d H:i:s'),
      'updated_at' => date('Y-m-d H:i:s')
    ];
    $this->userModel->insert($data);
    return redirect()->to('/user')->with('success', 'User berhasil ditambahkan.');
  }

  public function edit($id)
  {
    $this->onlyOperator();
    $data['user'] = $this->userModel->find($id);
    return view('user/edit', $data);
  }

  public function update($id)
  {
    $this->onlyOperator();
    $user = $this->userModel->find($id);
    $data = [
      'username' => $this->request->getPost('username'),
      'role' => $this->request->getPost('role'),
      'updated_at' => date('Y-m-d H:i:s')
    ];
    $password = $this->request->getPost('password');
    if ($password) {
      $data['password'] = password_hash($password, PASSWORD_DEFAULT);
    }
    $this->userModel->update($id, $data);
    return redirect()->to('/user')->with('success', 'User berhasil diupdate.');
  }

  public function delete($id)
  {
    $this->onlyOperator();
    $this->userModel->delete($id);
    return redirect()->to('/user')->with('success', 'User berhasil dihapus.');
  }
}
