<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title><?= esc($title) ?></title>
  <style>
    body {
      font-family: sans-serif;
      font-size: 10px;
    }

    .table {
      width: 100%;
      border-collapse: collapse;
    }

    .table th,
    .table td {
      border: 1px solid #ddd;
      padding: 6px;
    }

    .table th {
      background-color: #f2f2f2;
      text-align: left;
    }

    h1 {
      text-align: center;
    }
  </style>
</head>

<body>
  <h1><?= esc($title) ?></h1>
  <table class="table">
    <thead>
      <tr>
        <th>No</th>
        <th>NISN</th>
        <th>Nama Siswa</th>
        <th>Kelas</th>
        <th>Alamat</th>
        <th>Koordinat</th>
        <th>Status KM</th>
      </tr>
    </thead>
    <tbody>
      <?php if (empty($semuaSiswa)): ?>
        <tr>
          <td colspan="7">Tidak ada data.</td>
        </tr>
      <?php else: ?>
        <?php foreach ($semuaSiswa as $no => $s): ?>
          <tr>
            <td><?= $no + 1 ?></td>
            <td><?= esc($s['nisn']) ?></td>
            <td><?= esc($s['nama_siswa']) ?></td>
            <td><?= esc($s['nama_kelas']) ?></td>
            <td>
              <?php
              $alamat = implode(', ', array_filter([$s['alamat_lokasi'], 'Kec. ' . $s['kecamatan_lokasi'], $s['kabupaten_lokasi'], $s['provinsi_lokasi']]));
              echo esc($alamat);
              ?>
            </td>
            <td><?= esc($s['latitude']) ?>, <?= esc($s['longitude']) ?></td>
            <td><?= ($s['status_kurang_mampu'] == 1) ? 'Kurang Mampu' : 'Tidak' ?></td>
          </tr>
        <?php endforeach; ?>
      <?php endif; ?>
    </tbody>
  </table>
</body>

</html>