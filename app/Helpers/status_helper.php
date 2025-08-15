<?php

if (!function_exists('hitung_status_kurang_mampu')) {
  /**
   * Menghitung status kurang mampu berdasarkan gaji orang tua
   * 
   * @param float|null $gaji_ayah Gaji ayah dalam rupiah
   * @param float|null $gaji_ibu Gaji ibu dalam rupiah
   * @param int $jumlah_anggota_keluarga Jumlah anggota keluarga (default: 4)
   * @return int 1 = Kurang Mampu, 0 = Tidak Kurang Mampu
   */
  function hitung_status_kurang_mampu($gaji_ayah = null, $gaji_ibu = null, $jumlah_anggota_keluarga = 4)
  {
    // Konversi ke float dan handle null values
    $gaji_ayah = $gaji_ayah ? (float) $gaji_ayah : 0;
    $gaji_ibu = $gaji_ibu ? (float) $gaji_ibu : 0;

    // Total pendapatan keluarga
    $total_pendapatan = $gaji_ayah + $gaji_ibu;

    // Jika tidak ada pendapatan sama sekali, otomatis kurang mampu
    if ($total_pendapatan <= 0) {
      return 1;
    }

    // Hitung pendapatan per kapita
    $pendapatan_per_kapita = $total_pendapatan / $jumlah_anggota_keluarga;

    // Kriteria kurang mampu berdasarkan UMR Aceh Barat Daya 2024
    // UMR Aceh Barat Daya: Rp 3.200.000
    // Batas kurang mampu: 60% dari UMR = Rp 1.920.000 per kapita

    $batas_kurang_mampu = 1920000; // Rp 1.920.000 per kapita

    // Jika pendapatan per kapita di bawah batas, maka kurang mampu
    if ($pendapatan_per_kapita < $batas_kurang_mampu) {
      return 1; // Kurang Mampu
    }

    return 0; // Tidak Kurang Mampu
  }
}

if (!function_exists('format_rupiah')) {
  /**
   * Format angka ke format rupiah
   */
  function format_rupiah($angka)
  {
    return 'Rp ' . number_format($angka, 0, ',', '.');
  }
}

if (!function_exists('get_keterangan_status')) {
  /**
   * Mendapatkan keterangan status dalam bahasa Indonesia
   */
  function get_keterangan_status($status)
  {
    return $status == 1 ? 'Kurang Mampu' : 'Tidak Kurang Mampu';
  }
}

if (!function_exists('get_batas_kurang_mampu')) {
  /**
   * Mendapatkan batas gaji untuk status kurang mampu
   */
  function get_batas_kurang_mampu($jumlah_anggota_keluarga = 4)
  {
    $batas_per_kapita = 1920000; // Rp 1.920.000 per kapita
    return $batas_per_kapita * $jumlah_anggota_keluarga;
  }
}
