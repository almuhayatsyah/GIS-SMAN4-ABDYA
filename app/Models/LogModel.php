<?php

namespace App\Models;

use CodeIgniter\Model;

class LogModel extends Model
{
  protected $table = 'log';
  protected $primaryKey = 'id';
  protected $allowedFields = ['aktivitas', 'user_id', 'waktu', 'nisn'];

  public function getRecentLogs($limit = 10)
  {
    return $this->select('log.*, user.username')
      ->join('user', 'user.id = log.user_id', 'left')
      ->orderBy('log.waktu', 'DESC')
      ->findAll($limit);
  }

  public function getLogsByUser($userId)
  {
    return $this->where('user_id', $userId)
      ->orderBy('waktu', 'DESC')
      ->findAll();
  }

  // Tambahkan ini untuk hapus semua log
  public function clearOldLogs()
  {
    return $this->truncate(); // Kosongkan semua isi tabel log
  }
}
