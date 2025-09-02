<main class="app-main">
    <?php if (!isset($error) && empty($error)): ?>
        <div class="app-content-header">



            <div class="container-fluid">
                <nav aria-label="Breadcrumb">
                    <ol class="breadcrumb mb-2">
                        <li class="breadcrumb-item"><a href="<?= base_url('a/roles'); ?>">Roles</a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?= esc($rolesInformation['name']); ?></li>
                    </ol>
                </nav>
                <div class="row align-items-center">


                    <div class="col-sm-12 d-flex justify-content-between align-items-center">


                        <!-- Role Title -->
                        <h3 class="mb-0">
                            <?= esc($rolesInformation['name']); ?>
                            <small class="text-muted">Role</small>
                        </h3>

                        <!-- Buttons -->
                        <div class="ms-auto d-flex gap-2">
                            <a href="<?= base_url('a/roles/edit/' . $rolesInformation['id']); ?>" class="btn btn-outline-primary fw-semibold">
                                Edit
                            </a>
                            <a href="<?= base_url('a/roles'); ?>" class="btn btn-outline-secondary text-muted" disabled>
                                Back to Roles
                            </a>
                            <form method="post" action="<?= base_url('a/roles/' . esc($rolesInformation['id'])) ?>" class="ms-2">
                                <?= csrf_field() ?>
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#confirmDelete">
                                    Delete
                                </button>
                            </form>

                        </div>
                    </div>

                    <div class="modal fade" id="confirmDelete" tabindex="-1" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <form method="post" action="<?= base_url('a/roles/' . esc($rolesInformation['id'])) ?>" class="modal-content">
                                <?= csrf_field() ?>
                                <input type="hidden" name="_method" value="DELETE">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="confirmDeleteLabel">Delete role “<?= esc($rolesInformation['name']) ?>”?</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    This action cannot be undone. All attached permissions will remain, but they will no longer be grouped under this role.
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-danger">Delete role</button>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>

        </div>




        <div class="app-content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card mb-12">

                            <?php if (session()->getFlashdata('success')): ?>
                                <div class="aws-banner aws-banner--success" id="awsBanner">
                                    <div class="aws-banner__msg">
                                        <strong>Success!</strong>
                                        <ul class="aws-banner__list">
                                            <li><?= session()->getFlashdata('success') ?></li>

                                        </ul>
                                    </div>
                                    <button class="aws-banner__close" onclick="closeAwsBanner()">&times;</button>
                                </div>

                                <script>
                                    function closeAwsBanner() {
                                        const b = document.getElementById('awsBanner');
                                        b.classList.add('fade-out');
                                        setTimeout(() => b.remove(), 500);
                                    }
                                </script>



                            <?php endif; ?>

                            <div class="card-header">

                                <?php if (!isset($error) && empty($error)): ?>
                                    <div class="row g-3">
                                        <div class="col-md">
                                            <div class="fw-semibold text-muted">Description</div>
                                            <div><?= esc($rolesInformation['description'] ?? '—') ?></div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="fw-semibold text-muted">Permissions</div>
                                            <div class="fs-5"><?= $noAttachedPermission ?? '—' ?></div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="fw-semibold text-muted">Users</div>
                                            <div class="fs-5"><? //= count($users) 
                                                                ?></div>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <div class="row g-3">
                                    <div class="col-md d-flex justify-content-between align-items-center">
                                        <span class="fw-semibold">Permissions</span>
                                        <button class="btn btn-outline-primary fw-semibold" data-bs-toggle="modal" data-bs-target="#addPermModal">
                                            Add Permission
                                        </button>
                                    </div>
                                </div>
                            </div>


                            <div class="card-body">
                                <div class="d-flex gap-2 mb-3">
                                    <input type="search" class="form-control" placeholder="Search permissions…">
                                    <button class="btn btn-outline-secondary">Filter</button>
                                </div>


                                <form method="post" action="<?= base_url('a/roles/' . esc($rolesInformation['id']) . '/permissions/bulk') ?>">
                                    <?= csrf_field() ?>

                                    <div class="d-flex gap-2 mb-3">
                                        <button id="bulkRemoveBtn" type="submit" name="action" value="detach" class="btn btn-outline-danger" disabled>
                                            Remove selected
                                        </button>
                                    </div>

                                    <table class="table table-bordered table-hover table-striped">
                                        <tr>
                                            <th style="width:36px;">
                                                <input class="form-check-input" type="checkbox" id="checkAll" aria-label="Select all permissions">
                                            </th>
                                            <th>Name</th>
                                            <th>Description</th>
                                            <th style="width: 12rem;">Attached</th>

                                        </tr>
                                        <tbody>
                                            <?php if (!isset($error) && empty($error)): ?>
                                                <?php if (isset($attachedPermissions) && !empty($attachedPermissions)) : ?>
                                                    <?php foreach ($attachedPermissions as $show_all): ?>
                                                        <tr class="align-middle">
                                                            <td class="fw-semibold">
                                                                <div class="form-check">
                                                                    <input
                                                                        class="form-check-input row-check"
                                                                        type="checkbox"
                                                                        name="permission_ids[]"
                                                                        value="<?= esc($show_all['id']) ?>"
                                                                        id="perm-<?= esc($show_all['id']) ?>">
                                                                    <label for="perm-<?= esc($show_all['id']) ?>" class="visually-hidden">
                                                                        Select <?= esc($show_all['name']) ?>
                                                                    </label>
                                                                </div>
                                                            </td>
                                                            <td class="fw-semibold"><?= esc($show_all['name']); ?></td>
                                                            <td class="fw-semibold"><?= esc($show_all['description']); ?></td>
                                                            <td class="fw-semibold"><?= esc($show_all['name']); ?></td>

                                                        </tr>
                                                    <?php endforeach; ?>
                                                <?php else: ?>
                                                    <tr>
                                                        <td colspan="4" class="text-center text-muted">
                                                            <i class="bi bi-database" style="font-size: 3rem; opacity: 0.3;"></i>
                                                            <div class="mt-2 small" style="opacity: 0.5;">No permissions yet.</div>
                                                        </td>
                                                    </tr>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <tr>
                                                    <td colspan="4" class="text-center text-danger">
                                                        <i class="bi bi-database-x" style="font-size: 3rem; opacity: 0.5;"></i>
                                                        <div class="mt-2 small" style="opacity: 0.6;">We couldn't load the information right now. Please refresh the page or check back shortly.</div>
                                                    </td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </form>
                            </div>

                            <script>
                                (function() {
                                    const master = document.getElementById('checkAll');
                                    const rows = Array.from(document.querySelectorAll('.row-check'));
                                    const bulkBtn = document.getElementById('bulkRemoveBtn');

                                    function updateMaster() {
                                        const total = rows.length;
                                        const checked = rows.filter(cb => cb.checked).length;
                                        master.checked = checked === total && total > 0;
                                        master.indeterminate = checked > 0 && checked < total;
                                        bulkBtn.disabled = checked === 0;
                                    }

                                    if (master) {
                                        master.addEventListener('change', e => {
                                            rows.forEach(cb => cb.checked = e.target.checked);
                                            updateMaster();
                                        });
                                    }
                                    rows.forEach(cb => cb.addEventListener('change', updateMaster));
                                    updateMaster();
                                })();
                            </script>


                            <div class="card-footer clearfix">
                                <?php if (!isset($error) && empty($error)): ?>
                                    <?php if (isset($pager) && $show_all): ?>
                                        <?= $pager->links('default', 'custom_pagenumber_view'); ?>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php else: ?>

        <div class="d-flex flex-column justify-content-center align-items-center text-center" style="min-height: 300px;">
            <i class="bi bi-exclamation-triangle" style="font-size: 5rem; color: #dc3545; opacity: 0.8;"></i>

            <div class="mt-3 fs-5 fw-semibold" style="opacity: 0.85;">
                <?= $error; ?>
            </div>

            <a href="javascript:history.back()" class="btn btn-outline-secondary mt-4">
                ← Back
            </a>
        </div>




    <?php endif; ?>


</main>