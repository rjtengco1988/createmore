<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Permissions</h3>
                </div>
                <div class="col-sm-6"></div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card mb-12">
                        <div class="card-header">
                            <h3 class="card-title">Permission List</h3>
                        </div>

                        <div class="card-body">
                            <div class="mb-3">
                                <form method="GET" action="<?= base_url('a/permissions'); ?>" class="mb-3 d-flex align-items-center" role="search">
                                    <div class="input-group">
                                        <span class="input-group-text bg-light">
                                            <i class="bi bi-search"></i>
                                        </span>
                                        <input
                                            type="text"
                                            name="name"
                                            id="permissionSearch"
                                            class="form-control"
                                            placeholder="Search by Permission Name"
                                            value="<?= esc($search_name ?? '') ?>"
                                            style="font-size: 1rem; color: #333;">
                                        <button type="button" class="btn btn-outline-secondary clear-btn"
                                            onclick="document.getElementById('permissionSearch').value=''; this.form.submit();">
                                            &times;
                                        </button>
                                        <button type="submit" class="btn btn-primary">Search</button>
                                    </div>
                                </form>
                            </div>

                            <table class="table table-bordered table-hover table-striped">
                                <thead class="table-light">
                                    <tr>
                                        <th class="fw-bold" style="width: 20%;">Permission Name</th>
                                        <th class="fw-bold" style="width: 20%;">Group</th>
                                        <th class="fw-bold" style="width: 20%;">Slug</th>
                                        <th class="fw-bold" style="width: 40%;">Description</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!isset($error) && empty($error)): ?>
                                        <?php if (isset($show_all) && !empty($show_all)) : ?>
                                            <?php foreach ($show_all as $show_all): ?>
                                                <tr class="align-middle">
                                                    <td class="fw-semibold"><?= esc($show_all['name']); ?></td>
                                                    <td><?= esc($show_all['group']); ?></td>
                                                    <td><?= esc($show_all['slug']); ?></td>
                                                    <td class="fw-semibold text-wrap"><?= esc($show_all['description']); ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="4" class="text-center text-muted">
                                                    <i class="bi bi-database" style="font-size: 3rem; opacity: 0.3;"></i>
                                                    <div class="mt-2 small" style="opacity: 0.5;">No data available</div>
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
                        </div>

                        <div class="card-footer clearfix">
                            <?php if (isset($pager) && $show_all): ?>
                                <?= $pager->links('default', 'custom_pagenumber_view'); ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>