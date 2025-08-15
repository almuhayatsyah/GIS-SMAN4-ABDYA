<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>
<div class="card mt-4">
  <div class="card-header bg-secondary text-white">
    Log Aktivitas Terbaru
  </div>
  <div class="card-body">
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>Waktu</th>
          <th>Aktivitas</th>
          <th>User</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($log_aktivitas)): ?>
          <tr>
            <td colspan="3" class="text-center">Tidak ada log aktivitas.</td>
          </tr>
        <?php else: ?>
          <?php foreach ($log_aktivitas as $log): ?>
            <tr>
              <td><?= esc($log['waktu']) ?></td>
              <td><?= esc($log['aktivitas']) ?></td>
              <td><?= esc($log['username'] ?? '-') ?></td>
              <td>
                <form action="<?= base_url('pengaturan/log/clear') ?>" method="post" class="d-inline">
                  <?= csrf_field() ?>
                  <button type="submit" class="btn btn-danger btn-sm"
                    onclick="return confirm('Anda yakin ingin menghapus semua log?')">
                    Hapus
                  </button>
                </form>
              </td>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
<?= $this->endSection() ?>