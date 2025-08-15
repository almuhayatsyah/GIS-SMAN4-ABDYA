<?php

namespace App\Controllers;

use App\Models\LogModel;

class LogController extends BaseController
{
    public function log()
    {
        $logModel = new LogModel();
        $log_aktivitas = $logModel->getRecentLogs(20); // Ambil 20 log terbaru

        $data = [
            'title' => 'Log Pengaturan',
            'log_aktivitas' => $log_aktivitas
        ];

        return view('pengaturan/log', $data);
    }

    public function clearLogs()
    {
        $logModel = new LogModel();
        $logModel->clearOldLogs(); // Panggil method untuk menghapus log lama

        return redirect()->to('/pengaturan/log')->with('success', 'Log berhasil dihapus.');
    }
}
