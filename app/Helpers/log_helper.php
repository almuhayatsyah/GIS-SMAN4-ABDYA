<?php

if (!function_exists('log_activity')) {
    function log_activity($aktivitas, $user_id = null, $nisn = null)
    {
        $logModel = new \App\Models\LogModel();
        $data = [
            'aktivitas' => $aktivitas,
            'user_id'   => $user_id,
            'nisn'      => $nisn,
            'waktu'     => date('Y-m-d H:i:s')
        ];
        $logModel->insert($data);
    }
}
