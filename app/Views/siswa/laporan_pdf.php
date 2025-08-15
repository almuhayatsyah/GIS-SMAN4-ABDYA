<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <title><?= esc($title ?? 'Laporan Data Siswa') ?></title>
  <style>
    body {
      font-family: Arial, sans-serif;
      font-size: 9pt;
      /* Ukuran font mungkin perlu dikecilkan jika kolom banyak */
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 15px;
    }

    th,
    td {
      border: 1px solid #333;
      padding: 4px;
      text-align: left;
      word-wrap: break-word;
    }

    /* word-wrap untuk alamat panjang */
    th {
      background-color: #f2f2f2;
      font-weight: bold;
    }

    h1 {
      text-align: center;
      font-size: 14pt;
      margin-bottom: 5px;
    }

    p.tanggal {
      text-align: right;
      font-size: 8pt;
      margin-bottom: 15px;
    }

    .alamat-detail {
      font-size: 8pt;
    }

    .text-center {
      text-align: center;
    }

    .text-right {
      text-align: right;
    }
  </style>
</head>

<body>
  <h1><?= esc($title ?? 'Laporan Data Siswa') ?></h1>
  <p class="tanggal">Tanggal Cetak: <?= date('d M Y H:i:s') ?></p>
  <table>
    <thead>
      <tr>
        <th class="text-center">No</th>
        <th>NISN</th>
        <th>Nama Siswa</th>
        <th>Kelas</th>
        <th>Alamat Lengkap</th>
        <th>Nama Ayah</th>
        <th>Pekerjaan Ayah</th>
        <th>Gaji Ayah (Rp)</th>
        <th>Nama Ibu</th>
        <th>Pekerjaan Ibu</th>
        <th>Gaji Ibu (Rp)</th>
        <th>No. HP Ortu</th>
        <th>Jenis Kelamin</th>
        <th>Status KM</th>
      </tr>
    </thead>
    <tbody>
      <?php if (!empty($siswa_list)): ?>
        <?php $no = 1; ?>
        <?php foreach ($siswa_list as $siswa): ?>
          <tr>
            <td class="text-center"><?= $no++ ?></td>
            <td><?= esc($siswa['nisn']) ?></td>
            <td><?= esc($siswa['nama_siswa']) ?></td>
            <td><?= esc($siswa['nama_kelas'] ?? '-') ?></td>
            <td class="alamat-detail">
              <?= esc($siswa['alamat_lokasi'] ?? '') ?>
              <?php if (!empty($siswa['kecamatan_lokasi'])): ?>, Kec. <?= esc($siswa['kecamatan_lokasi']) ?><?php endif; ?>
              <?php if (!empty($siswa['kabupaten_lokasi'])): ?>, <?= esc($siswa['kabupaten_lokasi']) ?><?php endif; ?>
              <?php if (!empty($siswa['provinsi_lokasi'])): ?>, Prov. <?= esc($siswa['provinsi_lokasi']) ?><?php endif; ?>
            </td>
            <td><?= esc($siswa['nama_ayah'] ?? '-') ?></td>
            <td><?= esc($siswa['pekerjaan_ayah'] ?? '-') ?></td>
            <td class="text-right"><?= !empty($siswa['gaji_ayah']) ? number_format($siswa['gaji_ayah'], 0, ',', '.') : '-' ?></td>
            <td><?= esc($siswa['nama_ibu'] ?? '-') ?></td>
            <td><?= esc($siswa['pekerjaan_ibu'] ?? '-') ?></td>
            <td class="text-right"><?= !empty($siswa['gaji_ibu']) ? number_format($siswa['gaji_ibu'], 0, ',', '.') : '-' ?></td>
            <td><?= esc($siswa['nomor_hp_ortu'] ?? '-') ?></td>
            <td><?= esc($siswa['jenis_kelamin']) ?></td>
            <td><?= ($siswa['status_kurang_mampu'] == 1) ? 'Kurang Mampu' : 'Tidak Kurang Mampu' ?></td>
          </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr>
          <td colspan="14" class="text-center">Tidak ada data siswa.</td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
</body>

</html>