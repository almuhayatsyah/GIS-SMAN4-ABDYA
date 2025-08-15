<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>
<div class="container-fluid">
  <h1 class="h3 mb-4 text-gray-800">Edit User</h1>
  <form action="<?= base_url('user/update/' . $user['id']) ?>" method="post">
    <?= csrf_field() ?>
    <div class="form-group">
      <label for="username">Username</label>
      <input type="text" name="username" id="username" class="form-control" value="<?= esc($user['username']) ?>" required autofocus>
    </div>
    <div class="form-group">
      <label for="password">Password (isi jika ingin ganti)</label>
      <input type="password" name="password" id="password" class="form-control">
    </div>
    <div class="form-group">
      <label for="role">Role</label>
      <select name="role" id="role" class="form-control" required>
        <option value="operator" <?= $user['role'] == 'operator' ? 'selected' : '' ?>>Operator</option>
        <option value="kesiswaan" <?= $user['role'] == 'kesiswaan' ? 'selected' : '' ?>>Kesiswaan</option>
        <option value="pengunjung" <?= $user['role'] == 'pengunjung' ? 'selected' : '' ?>>Pengunjung</option>
      </select>
    </div>
    <button type="submit" class="btn btn-primary">Update</button>
    <a href="<?= base_url('user') ?>" class="btn btn-secondary">Batal</a>
  </form>
</div>
<?= $this->endSection(); ?>