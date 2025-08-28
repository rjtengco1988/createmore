<main class="app-main">
    <?php if (!isset($error) && empty($error)): ?>
        <div class="app-content-header">

            <div class="container-fluid">
                <div class="row align-items-center">

                    <div class="col-sm-12 d-flex justify-content-between align-items-center">
                        <!-- Role Title -->
                        <h3 class="mb-0">
                            <?= esc($rolesInformation['name']); ?>
                            <small class="text-muted">Role</small>
                        </h3>

                        <!-- Buttons -->
                        <div class="ms-auto d-flex gap-2">
                            <a href="<?= base_url('a/roles/create'); ?>" class="btn btn-outline-primary fw-semibold">
                                Edit
                            </a>
                            <form class="d-inline" method="post" action="/a/roles/<?= esc($rolesInformation['id']) ?>/delete">
                                <button type="submit" class="btn btn-outline-danger">
                                    Delete
                                </button>
                            </form>

                            <!-- Delete -->
                            <a href="<?= base_url('a/roles/create'); ?>" class="btn btn-outline-secondary text-muted" disabled>
                                Cancel
                            </a>

                            <!-- Create -->

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
                            </div>



                            <div class="card-body">
                                <div class="card-header d-flex align-items-center">
                                    <span class="fw-semibold">Permissions</span>
                                    <button class="btn btn-outline-primary fw-semibold ms-auto"
                                        data-bs-toggle="modal"
                                        data-bs-target="#addPermModal">
                                        Add Permission
                                    </button>
                                </div>
                                <table class="table table-bordered table-hover table-striped">
                                    <thead class="table-light">
                                        <tr>

                                            <th class="fw-bold" style="width: 90%;">Name</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!isset($error) && empty($error)): ?>
                                            <?php if (isset($attachedPermissions) && !empty($attachedPermissions)) : ?>
                                                <?php foreach ($attachedPermissions as $show_all): ?>
                                                    <tr class="align-middle">

                                                        <td class="fw-semibold"><?= esc($show_all['name']); ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <tr>
                                                    <td colspan="2" class="text-center text-muted">
                                                        <i class="bi bi-database" style="font-size: 3rem; opacity: 0.3;"></i>
                                                        <div class="mt-2 small" style="opacity: 0.5;">No permissions yet.</div>
                                                    </td>
                                                </tr>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="2" class="text-center text-danger">
                                                    <i class="bi bi-database-x" style="font-size: 3rem; opacity: 0.5;"></i>
                                                    <div class="mt-2 small" style="opacity: 0.6;">We couldn't load the information right now. Please refresh the page or check back shortly.</div>
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>

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