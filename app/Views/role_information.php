<main class="app-main">
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





                        <?php elseif (isset($validation) && $validation->getErrors()): ?>

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

                        <?php elseif (isset($search_error) && !empty($search_error)): ?>

                            <div class="aws-banner aws-banner--error" id="awsBanner">
                                <div class="aws-banner__msg">

                                    <ul class="aws-banner__list">

                                        <li><?= esc($search_error) ?></li>

                                    </ul>
                                </div>
                                <button class="aws-banner__close" type="button" aria-label="Dismiss" onclick="closeAwsBanner()">&times;</button>
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



                            <form id="bulkForm" method="post" action="<?= base_url('a/roles/' . esc($rolesInformation['id']) . '/permissions/bulk') ?>">
                                <?= csrf_field() ?>

                                <div class="d-flex gap-2 mb-3">
                                    <button id="bulkRemoveBtn" type="submit" name="action" value="detach" class="btn btn-outline-danger" disabled>
                                        Remove selected
                                    </button>
                                </div>

                                <table class="table table-bordered table-hover table-striped mb-0" id="permTable" style="width:100%">
                                    <thead class="table-light align-middle">
                                        <tr>
                                            <th style="width:44px;" class="text-center">
                                                <input type="checkbox" id="checkAll" class="form-check-input">
                                            </th>
                                            <th class="fw-bold">Name</th>
                                            <th class="fw-bold">Slug</th>
                                            <th class="fw-bold">Description</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td colspan="4" class="text-center py-4 text-muted">Loading…</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </form>

                            <div class="card-footer clearfix">
                                <!-- DataTables has its own pager; you can remove/hide the PHP pager for this table -->
                            </div>

                            <!-- Assets (use your asset pipeline if you prefer local files) -->
                            <link rel="stylesheet" href="https://cdn.datatables.net/v/bs5/dt-1.13.8/datatables.min.css">
                            <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
                            <script src="https://cdn.datatables.net/v/bs5/dt-1.13.8/datatables.min.js"></script>

                            <script>
                                (function() {
                                    const roleId = "<?= esc($rolesInformation['id']) ?>";
                                    const ajaxUrl = "<?= base_url('a/roles/' . esc($rolesInformation['id']) . '/permissions/json') ?>";
                                    const csrfName = "<?= csrf_token() ?>";
                                    let csrfVal = "<?= csrf_hash() ?>";

                                    // Persist selection across pages
                                    const selected = new Set();
                                    const bulkBtn = document.getElementById('bulkRemoveBtn');
                                    const master = document.getElementById('checkAll');
                                    const bulkForm = document.getElementById('bulkForm');

                                    function updateBulkState() {
                                        bulkBtn.disabled = selected.size === 0;
                                        // master checkbox state only reflects current page (updated in drawCallback)
                                    }

                                    // DataTable
                                    const table = $('#permTable').DataTable({
                                        processing: true,
                                        serverSide: true,
                                        searching: true,
                                        lengthMenu: [
                                            [10, 25, 50, 100],
                                            [10, 25, 50, 100]
                                        ],
                                        pageLength: 10,
                                        order: [
                                            [1, 'asc']
                                        ], // by Name
                                        ajax: {
                                            url: ajaxUrl,
                                            type: 'POST',
                                            data: function(d) {
                                                // add CSRF token
                                                d[csrfName] = csrfVal;
                                            },
                                            dataSrc: function(json) {
                                                // update CSRF for next request
                                                if (json.csrfToken) {
                                                    csrfVal = json.csrfToken;
                                                    // also refresh hidden CSRF fields in forms
                                                    document.querySelectorAll(`input[name="${csrfName}"]`).forEach(i => i.value = csrfVal);
                                                }
                                                return json.data;
                                            },
                                            error: function(xhr) {
                                                console.error('DataTables AJAX error:', xhr?.responseText || xhr.statusText);
                                            }
                                        },
                                        columns: [{
                                                data: 'checkbox',
                                                orderable: false,
                                                searchable: false,
                                                className: 'text-center',
                                                width: '44px'
                                            },
                                            {
                                                data: 'name',
                                                render: $.fn.dataTable.render.text()
                                            },
                                            {
                                                data: 'slug',
                                                render: $.fn.dataTable.render.text()
                                            },
                                            {
                                                data: 'description',
                                                render: $.fn.dataTable.render.text()
                                            },
                                        ],
                                        drawCallback: function(settings) {
                                            const api = this.api();
                                            const rows = api.rows({
                                                page: 'current'
                                            }).nodes();

                                            // Reset master & wire up row checkboxes
                                            if (master) {
                                                master.checked = false;
                                                master.indeterminate = false;
                                            }

                                            let total = 0,
                                                checked = 0;
                                            $(rows).find('input.row-check').each(function() {
                                                total++;
                                                const id = this.value;
                                                // restore prior selection
                                                this.checked = selected.has(id);
                                                if (this.checked) checked++;

                                                this.addEventListener('change', () => {
                                                    if (this.checked) selected.add(id);
                                                    else selected.delete(id);
                                                    updateBulkState();

                                                    // reflect page-level state
                                                    const pageChecks = $(rows).find('input.row-check');
                                                    const pageTotal = pageChecks.length;
                                                    const pageChecked = pageChecks.filter(':checked').length;
                                                    if (master) {
                                                        master.checked = (pageChecked === pageTotal && pageTotal > 0);
                                                        master.indeterminate = (pageChecked > 0 && pageChecked < pageTotal);
                                                    }
                                                }, {
                                                    once: true
                                                });
                                            });

                                            if (master) {
                                                master.onclick = (e) => {
                                                    $(rows).find('input.row-check').each(function() {
                                                        this.checked = e.target.checked;
                                                        const id = this.value;
                                                        if (this.checked) selected.add(id);
                                                        else selected.delete(id);
                                                    });
                                                    updateBulkState();
                                                    // reflect final state
                                                    master.checked = e.target.checked;
                                                    master.indeterminate = false;
                                                };
                                            }

                                            updateBulkState();
                                        }
                                    });

                                    // Bind your external search box to DataTables’ search
                                    const extSearch = document.getElementById('externalSearch');
                                    const extBtn = document.getElementById('externalSearchBtn');
                                    if (extBtn && extSearch) {
                                        extBtn.addEventListener('click', function(e) {
                                            e.preventDefault();
                                            table.search(extSearch.value).draw();
                                        });
                                        // also on Enter
                                        extSearch.addEventListener('keydown', function(e) {
                                            if (e.key === 'Enter') {
                                                e.preventDefault();
                                                table.search(extSearch.value).draw();
                                            }
                                        });
                                    }

                                    // Persisted selection → ensure all selected IDs are submitted (even if not on current page)
                                    bulkForm.addEventListener('submit', function(e) {
                                        // remove previous injected hidden inputs
                                        this.querySelectorAll('input[name="ids[]"].__injected').forEach(el => el.remove());
                                        // add new ones from the Set
                                        selected.forEach(id => {
                                            const h = document.createElement('input');
                                            h.type = 'hidden';
                                            h.name = 'ids[]';
                                            h.value = id;
                                            h.classList.add('__injected');
                                            this.appendChild(h);
                                        });
                                    });
                                })();
                            </script>


                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>


</main>