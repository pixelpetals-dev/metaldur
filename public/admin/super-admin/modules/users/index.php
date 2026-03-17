<?php
declare(strict_types=1);

require_once __DIR__ . '/../../../bootstrap.php';
require_once __DIR__ . '/../../session.php';

rbac_guard('users.view');

$stmt = $pdo->query("
    SELECT u.id, u.username, u.email,
           r.name AS role_name
      FROM users u
      LEFT JOIN user_roles ur ON ur.user_id = u.id
      LEFT JOIN roles r ON r.id = ur.role_id
     ORDER BY u.username
");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

require SA_INCLUDES_PATH . '/header.php';
?>

<div id="user-table-app" class="row g-5 gx-xl-10 mb-5 mb-xl-10 mt-5">
  <div class="d-flex justify-content-between align-items-center mb-5 w-100">
    <h1 class="fs-3 fw-bold">Users</h1>
    <?php if (rbac_guard_silent('users.create')): ?>
      <a href="/admin/super-admin/users/create" class="btn btn-primary">Create User</a>
    <?php endif; ?>
  </div>

  <div class="card card-flush w-100">
    <div class="card-body">
      <table class="table align-middle table-row-dashed fs-6 gy-5">
        <thead>
          <tr class="text-muted fw-bold fs-7 text-uppercase">
            <th>User Name</th>
            <th>Email</th>
            <th>Role</th>
            <th class="text-end">Actions</th>
          </tr>
        </thead>
        <tbody class="fw-semibold text-gray-600">
          <?php foreach ($users as $u): ?>
            <tr>
              <td><?= htmlspecialchars($u['username']) ?></td>
              <td><?= htmlspecialchars($u['email']) ?></td>
              <td><?= htmlspecialchars($u['role_name'] ?? '—') ?></td>
              <td class="text-end">

                <?php if (rbac_guard_silent('users.view')): ?>
                  <a href="/super-admin/users/view/<?= $u['id'] ?>"
                     class="btn btn-icon btn-light btn-sm me-1">
                    <i class="ki-solid ki-eye fs-3"></i>
                  </a>
                <?php endif; ?>

                <?php if (rbac_guard_silent('users.edit')): ?>
                  <a href="/super-admin/users/edit/<?= $u['id'] ?>"
                     class="btn btn-icon btn-light-warning btn-sm me-1">
                    <i class="ki-solid ki-pencil fs-3"></i>
                  </a>
                <?php endif; ?>

                <?php if (rbac_guard_silent('users.delete')): ?>
                  <button @click="deleteUser(<?= $u['id'] ?>)"
                          class="btn btn-icon btn-light-danger btn-sm">
                    <i class="ki-solid ki-trash fs-3"></i>
                  </button>
                <?php endif; ?>

              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php require SA_INCLUDES_PATH . '/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/vue@3/dist/vue.global.prod.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<script>
const CSRF_TOKEN = "<?= csrf_token() ?>";

Vue.createApp({
  methods: {
    deleteUser(id) {
      if (!confirm('Are you sure you want to delete this user?')) return;

      axios.post(
        `/super-admin/users/delete/${id}`,
        new URLSearchParams({ _csrf: CSRF_TOKEN }),
        { headers: { 'Content-Type': 'application/x-www-form-urlencoded' } }
      )
      .then(() => location.reload())
      .catch(err => {
        const msg = err.response?.data?.error || 'Delete failed';
        alert(msg);
      });
    }
  }
}).mount('#user-table-app');
</script>