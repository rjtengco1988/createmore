<main class="app-main">
  <!-- Page header -->
  <div class="app-content-header border-bottom">
    <div class="container-fluid">
      <div class="row align-items-center">
        <div class="col">
          <nav aria-label="breadcrumb" class="mb-1">
            <ol class="breadcrumb mb-0">
              <li class="breadcrumb-item"><a href="<?= base_url('a/roles/'); ?>">Roles</a></li>
              <li class="breadcrumb-item"><a href="<?= base_url('a/role-information/' . esc($role['id'])); ?>">Role : <?= esc($role['name']); ?></a></li>
              <li class="breadcrumb-item active" aria-current="page">Edit Role</li>
            </ol>
          </nav>
          <h3 class="mb-0">Edit Role</h3>
          <p class="text-muted small mb-0">Update role details. Only the name and description are editable.</p>
        </div>

      </div>
    </div>
  </div>

  <!-- Content -->
  <div class="app-content">
    <div class="container-fluid">
      <form id="roleEditForm" method="POST" action="<?= base_url('a/role-information/edit/' . esc($role['id'])); ?>">
        <?= csrf_field() ?>

        <div class="row g-3">
          <!-- Role details -->
          <div class="col-12 col-lg-7">
            <div class="card card-primary card-outline mb-4">
              <?php if (isset($validation) && $validation->getErrors()): ?>

                <div class="aws-banner aws-banner--error" id="awsBanner">
                  <div class="aws-banner__msg">
                    <strong>Validation Failed.</strong>
                    <ul class="aws-banner__list">
                      <?php foreach ($validation->getErrors() as $err): ?>
                        <li><?= esc($err) ?></li>
                      <?php endforeach; ?>
                    </ul>
                  </div>
                  <button class="aws-banner__close" type="button" aria-label="Dismiss" onclick="closeAwsBanner()">&times;</button>
                </div>

              <?php elseif (session()->getFlashdata('success')): ?>
                <div class="aws-banner aws-banner--success" id="awsBanner">
                  <div class="aws-banner__msg">
                    <strong>Success!</strong>
                    <ul class="aws-banner__list">
                      <li><?= session()->getFlashdata('success') ?></li>

                    </ul>
                  </div>
                  <button class="aws-banner__close" onclick="closeAwsBanner()">&times;</button>
                </div>
              <?php endif; ?>

              <script>
                function closeAwsBanner() {
                  const b = document.getElementById('awsBanner');
                  b.classList.add('fade-out');
                  setTimeout(() => b.remove(), 500);
                }
              </script>
              <div class="card-header">
                <h5 class="card-title mb-0">Role Information</h5>
              </div>
              <div class="card-body">
                <!-- Name (editable) -->
                <div class="mb-3">
                  <label for="name" class="form-label">Name <span class="text-danger">*</span>
                    <a href="#" tabindex="0" data-bs-toggle="tooltip" data-bs-placement="right"
                      title="This is the display name of the role. It helps identify the role within the system and should be easily recognizable by administrators.">
                      <i class="bi bi-info-circle ms-1"></i>
                    </a>
                  </label>
                  <input
                    type="text"
                    class="form-control <?= isset($validation) && $validation->hasError('name') ? 'is-invalid' : '' ?>"
                    id="name"
                    name="name"
                    value="<?= old('name', esc($role['name'])) ?>"
                    placeholder="e.g., Content Manager"
                    aria-describedby="nameHelp">
                  <div id="nameHelp" class="form-text">Names are limited to 128 characters or fewer. Names may only contain alphanumeric characters, spaces, and the following special characters: + = , . @ -</div>
                </div>

                <!-- Slug (read-only) -->
                <div class="mb-3">
                  <label for="slug" class="form-label">Slug
                    <a href="#" tabindex="0" data-bs-toggle="tooltip" data-bs-placement="right" title="The slug is a unique, URL-friendly identifier used to reference this role in the system. It must be lowercase and can only contain letters, numbers, and hyphens.">
                      <i class="bi bi-info-circle ms-1"></i>
                    </a>
                  </label>
                  <input
                    type="text"
                    class="form-control"
                    id="slug"
                    name="slug"
                    value="<?= esc($role['slug']) ?>"
                    readonly
                    tabindex="-1"
                    aria-describedby="slugHelp">
                  <div id="slugHelp" class="form-text">Immutable unique identifier used by the system.</div>
                </div>

                <!-- Description (editable) -->
                <div class="mb-3">
                  <label for="description" class="form-label">Description
                    <a href="#" tabindex="0" data-bs-toggle="tooltip" data-bs-placement="right"
                      title="Provide a short explanation of what this role is intended for. This helps administrators understand its purpose at a glance.">
                      <i class="bi bi-info-circle ms-1"></i>
                    </a>
                  </label>
                  <textarea
                    class="form-control <?= isset($validation) && $validation->hasError('description') ? 'is-invalid' : '' ?>"
                    id="description"
                    name="description"
                    rows="4"
                    placeholder="Describe what this role is for..."
                    maxlength="500"><?= old('description', esc($role['description'])) ?></textarea>
                  <div id="descriptionHelp" class="form-text">Description must be 200 characters or fewer.</div>
                </div>
              </div>

              <div class="card-footer d-flex justify-content-end">
                <small class="text-muted me-auto">Fields left blank will keep their current values.</small>
                <div class="d-flex gap-2">
                  <a href="<?= base_url('a/roles/'); ?>" class="btn btn-outline-secondary">Cancel</a>
                  <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
              </div>
            </div>
          </div>

      </form>

      <!-- Meta & Permissions -->
      <div class="col-12 col-lg-5">
        <!-- Metadata -->
        <div class="card card-primary card-outline mb-4">
          <div class="card-header">
            <h6 class="card-title mb-0">Metadata</h6>
          </div>
          <div class="card-body">
            <div class="mb-3">
              <label class="form-label">Created By</label>
              <input type="text" class="form-control" value="<?= esc($role['created_by']) ?>" readonly tabindex="-1">
            </div>
            <div class="mb-0">
              <label class="form-label">Created At</label>
              <input type="text" class="form-control" value="<?= esc($role['created_at']) ?>" readonly tabindex="-1">
              <!-- Example: format created_at on the server as 'Sep 1, 2025 • 14:32' -->
            </div>
          </div>
        </div>

        <!-- Attached permissions (read-only list) -->
        <div class="card">
          <div class="card-header d-flex align-items-center justify-content-between">
            <h6 class="card-title mb-0">Attached Permissions</h6>
            <span class="badge bg-light text-body-secondary">
              <?= count($permissions ?? []) ?> total
            </span>
          </div>
          <div class="card-body p-0">
            <div class="p-3">
              <input
                type="search"
                class="form-control form-control-sm"
                placeholder="Quick filter permissions..."
                oninput="filterPermissions(this.value)"
                aria-label="Filter permissions">
            </div>
            <div class="table-responsive">
              <table class="table table-sm table-hover align-middle mb-0" id="permTable">
                <thead class="table-light">
                  <tr>
                    <th scope="col">Permission</th>
                    <th scope="col" class="text-nowrap">Slug</th>
                    <th scope="col" class="d-none d-md-table-cell">Description</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if (!empty($permissions)) : ?>
                    <?php foreach ($permissions as $p) : ?>
                      <tr>
                        <td><?= esc($p['permission_name']) ?></td>
                        <td><code><?= esc($p['permission_slug']); ?></code></td>
                        <td class="d-none d-md-table-cell"><?= esc($p['permission_description']) ?></td>
                      </tr>
                    <?php endforeach ?>
                  <?php else : ?>
                    <tr>
                      <td colspan="3" class="text-center text-muted py-4">No permissions attached to this role.</td>
                    </tr>
                  <?php endif ?>
                </tbody>
              </table>
            </div>
          </div>
          <div class="card-footer text-muted small">
            Permission membership is managed elsewhere. This list is read-only here.
          </div>
        </div>
      </div>
    </div>


    <!-- Sticky footer actions for small screens -->
    <div class="card mt-3 d-md-none position-sticky bottom-0 start-0 end-0 shadow-sm">
      <div class="card-body d-flex gap-2">
        <a href="/roles" class="btn btn-outline-secondary w-50">Cancel</a>
        <button type="submit" form="roleEditForm" class="btn btn-primary w-50">Save</button>
      </div>
    </div>
  </div>
  </div>
</main>

<!-- Minimal, unobtrusive filtering (no dependency) -->
<script>
  function filterPermissions(query) {
    const q = (query || '').toLowerCase().trim();
    const rows = document.querySelectorAll('#permTable tbody tr');
    rows.forEach(tr => {
      const txt = tr.innerText.toLowerCase();
      tr.style.display = txt.includes(q) ? '' : 'none';
    });
  }
</script>


<script>
  document.addEventListener('DOMContentLoaded', function() {
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    const tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
      return new bootstrap.Tooltip(tooltipTriggerEl);
    });
  });
</script>