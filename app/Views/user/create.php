<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>
<div class="container-fluid">
  <h1 class="h3 mb-4 text-gray-800">Tambah User</h1>
  <?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger"> <?= session()->getFlashdata('error') ?> </div>
  <?php endif; ?>
  <form action="<?= base_url('user/store') ?>" method="post">
    <?= csrf_field() ?>
    <div class="form-group">
      <label for="username">Username</label>
      <input type="text" name="username" id="username" class="form-control" required autofocus value="<?= old('username') ?>">
    </div>
    <div class="form-group">
      <label for="password">Password</label>
      <input type="password" name="password" id="password" class="form-control" required>
    </div>
    <div class="form-group">
      <label for="role">Role</label>
      <select name="role" id="role" class="form-control" required>
        <option value="operator" <?= old('role') == 'operator' ? 'selected' : '' ?>>Operator</option>
        <option value="kesiswaan" <?= old('role') == 'kesiswaan' ? 'selected' : '' ?>>Kesiswaan</option>
        <option value="pengunjung" <?= old('role') == 'pengunjung' ? 'selected' : '' ?>>Pengunjung</option>
      </select>
    </div>
    <button type="submit" class="btn btn-primary">Simpan</button>
    <a href="<?= base_url('user') ?>" class="btn btn-secondary">Batal</a>
  </form>
</div>
<?= $this->endSection(); ?>