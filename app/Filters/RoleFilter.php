<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class RoleFilter implements FilterInterface
{
  public function before(RequestInterface $request, $arguments = null)
  {
    // Check if user is logged in
    if (!session()->get('logged_in')) {
      return redirect()->to(base_url('auth/login'));
    }

    // Get user's role
    $role = session()->get('role');

    // If no arguments passed, just check if user is logged in
    if (empty($arguments)) {
      return;
    }

    // Check if user has required role
    if (!in_array($role, $arguments)) {
      return redirect()->back()->with('error', 'Anda tidak memiliki akses ke halaman ini');
    }
  }

  public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
  {
    // Do nothing
  }
}
