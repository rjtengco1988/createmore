<main class="app-main">
    <!-- Header -->
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-sm-8">

                    <h3 class="fw-semibold mb-0">Set up role for your application</h3>
                    <div class="text-muted small">Step 2 of 3</div>
                </div>

            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="app-content">
        <div class="container-fluid">
            <div class="row g-4">
                <div class="col-12">



                    <!-- Card -->
                    <div class="card card-primary card-outline">

                        <?php if (session()->getFlashdata('error')): ?>

                            <div class="aws-banner aws-banner--error" id="awsBanner">
                                <div class="aws-banner__msg">
                                    <strong>Validation Error.</strong>
                                    <ul class="aws-banner__list">

                                        <li><?= session()->getFlashdata('error') ?></li>

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
                            <div class="d-flex flex-column">
                                <span class="card-title text-dark fw-bold mb-1">
                                    Attach permissions
                                </span>
                                <small class="text-muted">
                                    Select one or more permissions to link with this role. These permissions define what actions users with this role can perform.
                                </small>
                            </div>
                        </div>

                        <form action="<?= base_url('a/roles/attach-permissions'); ?>" method="POST" novalidate>
                            <?= csrf_field() ?>
                            <div class="card-body">
                                <!-- Role summary -->
                                <div class="row g-3 mb-2">
                                    <div class="col-12 col-lg-4">
                                        <label class="form-label">Role Name</label>
                                        <input type="text"
                                            class="form-control-plaintext"
                                            value="<?= esc(session()->get('roleDefinition')['name'] ?? '') ?>"
                                            readonly>
                                    </div>
                                    <div class="col-12 col-lg-4">
                                        <label class="form-label">Role Slug</label>
                                        <input type="text"
                                            class="form-control-plaintext"
                                            value="<?= esc(session()->get('roleDefinition')['slug'] ?? '') ?>"
                                            readonly>
                                    </div>
                                    <div class="col-12 col-lg-4">
                                        <label class="form-label">Role Description</label>
                                        <input type="text"
                                            class="form-control-plaintext"
                                            value="<?= esc(session()->get('roleDefinition')['description'] ?? '') ?>"
                                            readonly>
                                    </div>
                                </div>

                                <hr class="my-4">

                                <?php
                                // From server if you have preselected IDs (editing scenario)
                                $attachedPermissionIds = $attachedPermissionIds ?? [];
                                $csrfName  = csrf_token();
                                $csrfHash  = csrf_hash();
                                ?>
                                <div class="mb-3">
                                    <label for="permSearch" class="form-label mb-1"><strong>Search permissions</strong></label>
                                    <div class="d-flex gap-2">
                                        <input id="permSearch" type="search" class="form-control" placeholder="Search by name, slug, or description…">
                                        <button type="button" class="btn btn-outline-secondary" id="permClear">Clear</button>
                                    </div>
                                    <div class="form-text text-muted">Type to filter. Press Enter or wait a moment.</div>
                                </div>

                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="permSelectPage">
                                        <label for="permSelectPage" class="form-check-label">Select all on this page</label>
                                    </div>
                                    <div class="small text-muted"><span id="permSelectedCount">0</span> selected</div>
                                </div>




                                <div class="table-responsive border rounded">
                                    <table class="table table-bordered table-hover table-striped mb-0" id="permTable">
                                        <thead class="table-light align-middle">
                                            <tr>
                                                <th style="width:44px;"></th>
                                                <th data-sort="name" class="fw-bold sortable">Name</th>
                                                <th data-sort="slug" class="fw-bold sortable">Slug</th>
                                                <th data-sort="description" class="fw-bold sortable">Description</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td colspan="4" class="text-center py-4 text-muted">Loading…</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="d-flex justify-content-between align-items-center mt-3">
                                    <div class="small text-muted" id="permMeta"></div>
                                    <nav>
                                        <ul class="pagination pagination-sm mb-0" id="permPager"></ul>
                                    </nav>
                                </div>

                                <!-- Hidden inputs for selected IDs -->
                                <div id="permSelectedContainer" class="d-none"></div>

                                <script>
                                    (function() {
                                        const apiUrl = "<?= base_url('a/api/permissions'); ?>";

                                        // If CSRF on GET in your app, include token:
                                        const csrfName = "<?= esc($csrfName) ?>";
                                        const csrfHash = "<?= esc($csrfHash) ?>";

                                        const preSel = new Set(<?= json_encode(array_map('intval', $attachedPermissionIds)) ?>);

                                        const state = {
                                            q: "",
                                            page: 1,
                                            perPage: 10,
                                            sort: "name",
                                            dir: "asc",
                                            selected: new Set(preSel)
                                        };

                                        const el = {
                                            search: document.getElementById('permSearch'),
                                            clear: document.getElementById('permClear'),
                                            tbody: document.querySelector('#permTable tbody'),
                                            pager: document.getElementById('permPager'),
                                            meta: document.getElementById('permMeta'),
                                            selectPage: document.getElementById('permSelectPage'),
                                            selCount: document.getElementById('permSelectedCount'),
                                            selContainer: document.getElementById('permSelectedContainer'),
                                            sortHeaders: document.querySelectorAll('#permTable thead th.sortable')
                                        };

                                        // Sorting
                                        el.sortHeaders.forEach(th => {
                                            th.style.cursor = 'pointer';
                                            th.addEventListener('click', () => {
                                                const col = th.getAttribute('data-sort');
                                                if (state.sort === col) state.dir = state.dir === 'asc' ? 'desc' : 'asc';
                                                else {
                                                    state.sort = col;
                                                    state.dir = 'asc';
                                                }
                                                state.page = 1;
                                                load();
                                            });
                                        });

                                        // Debounce search
                                        let t = null;

                                        function doSearch() {
                                            clearTimeout(t);
                                            t = setTimeout(() => {
                                                state.q = (el.search.value || '').trim();
                                                state.page = 1;
                                                load();
                                            }, 350);
                                        }
                                        el.search.addEventListener('input', doSearch);
                                        el.search.addEventListener('keydown', e => {
                                            if (e.key === 'Enter') {
                                                e.preventDefault();
                                                doSearch();
                                            }
                                        });
                                        el.clear.addEventListener('click', () => {
                                            el.search.value = '';
                                            state.q = '';
                                            state.page = 1;
                                            load();
                                        });

                                        el.selectPage.addEventListener('change', () => {
                                            const checks = el.tbody.querySelectorAll('input.perm-check');
                                            checks.forEach(ch => {
                                                ch.checked = el.selectPage.checked;
                                                const id = parseInt(ch.value, 10);
                                                if (ch.checked) state.selected.add(id);
                                                else state.selected.delete(id);
                                            });
                                            syncSelected();
                                        });

                                        function syncSelected() {
                                            el.selCount.textContent = String(state.selected.size);
                                            el.selContainer.innerHTML = '';
                                            state.selected.forEach(id => {
                                                const inp = document.createElement('input');
                                                inp.type = 'hidden';
                                                inp.name = 'permissions[]';
                                                inp.value = String(id);
                                                el.selContainer.appendChild(inp);
                                            });

                                            const onPage = Array.from(el.tbody.querySelectorAll('input.perm-check'));
                                            const checked = onPage.filter(c => c.checked).length;
                                            el.selectPage.checked = onPage.length && checked === onPage.length;
                                            el.selectPage.indeterminate = checked > 0 && checked < onPage.length;
                                        }

                                        function makePager(pages, page) {
                                            el.pager.innerHTML = '';
                                            if (pages <= 1) return;

                                            const mk = (lbl, p, dis = false, act = false) => {
                                                const li = document.createElement('li');
                                                li.className = 'page-item' + (dis ? ' disabled' : '') + (act ? ' active' : '');
                                                const a = document.createElement('a');
                                                a.className = 'page-link';
                                                a.href = '#';
                                                a.textContent = lbl;
                                                a.addEventListener('click', e => {
                                                    e.preventDefault();
                                                    if (dis || act) return;
                                                    state.page = p;
                                                    load();
                                                });
                                                li.appendChild(a);
                                                return li;
                                            };

                                            el.pager.appendChild(mk('«', Math.max(1, page - 1), page === 1));
                                            const win = 5;
                                            let s = Math.max(1, page - Math.floor(win / 2));
                                            let e = Math.min(pages, s + win - 1);
                                            if (e - s + 1 < win) s = Math.max(1, e - win + 1);
                                            for (let i = s; i <= e; i++) el.pager.appendChild(mk(String(i), i, false, i === page));
                                            el.pager.appendChild(mk('»', Math.min(pages, page + 1), page === pages));
                                        }

                                        function setText(node, val) {
                                            node.textContent = val ?? '';
                                        }

                                        async function load() {
                                            el.tbody.innerHTML = '<tr><td colspan="4" class="text-center py-4 text-muted">Loading…</td></tr>';
                                            el.selectPage.checked = false;
                                            el.selectPage.indeterminate = false;

                                            const params = new URLSearchParams({
                                                q: state.q,
                                                page: state.page,
                                                perPage: state.perPage,
                                                sort: state.sort,
                                                dir: state.dir
                                            });

                                            // Include CSRF token if your app requires it on GET
                                            // params.append('<?= esc($csrfName) ?>', '<?= esc($csrfHash) ?>');

                                            try {
                                                const res = await fetch(`${apiUrl}?${params.toString()}`, {
                                                    headers: {
                                                        'Accept': 'application/json'
                                                    }
                                                });
                                                const json = await res.json();
                                                if (!json.ok) throw new Error('API error');

                                                const {
                                                    rows,
                                                    total,
                                                    page,
                                                    perPage,
                                                    pages,
                                                    sort,
                                                    dir
                                                } = json.data;

                                                // Meta
                                                const start = total ? ((page - 1) * perPage) + 1 : 0;
                                                const end = Math.min(total, page * perPage);
                                                el.meta.textContent = total ? `Showing ${start}-${end} of ${total}` : 'No results';

                                                // Rows
                                                if (!rows.length) {
                                                    el.tbody.innerHTML = '<tr><td colspan="4" class="text-center py-4 text-muted">No permissions found.</td></tr>';
                                                } else {
                                                    const frag = document.createDocumentFragment();
                                                    rows.forEach(r => {
                                                        const id = parseInt(r.id, 10);
                                                        const tr = document.createElement('tr');

                                                        const td0 = document.createElement('td');
                                                        td0.className = 'text-center';
                                                        const cb = document.createElement('input');
                                                        cb.type = 'checkbox';
                                                        cb.className = 'form-check-input perm-check';
                                                        cb.value = String(id);
                                                        cb.checked = state.selected.has(id);
                                                        cb.addEventListener('change', () => {
                                                            cb.checked ? state.selected.add(id) : state.selected.delete(id);
                                                            syncSelected();
                                                        });
                                                        td0.appendChild(cb);

                                                        const td1 = document.createElement('td');
                                                        setText(td1, r.name);
                                                        const td2 = document.createElement('td');
                                                        setText(td2, r.slug);
                                                        const td3 = document.createElement('td');
                                                        td3.className = 'text-muted';
                                                        setText(td3, r.description);

                                                        tr.appendChild(td0);
                                                        tr.appendChild(td1);
                                                        tr.appendChild(td2);
                                                        tr.appendChild(td3);
                                                        frag.appendChild(tr);
                                                    });
                                                    el.tbody.innerHTML = '';
                                                    el.tbody.appendChild(frag);
                                                }

                                                makePager(pages, page);
                                                syncSelected();

                                                // optional: show current sort in header (add icons if you like)
                                            } catch (err) {
                                                console.error(err);
                                                el.tbody.innerHTML = el.tbody.innerHTML = `
    <td colspan="4" class="text-center text-danger">
        <i class="bi bi-database-x" style="font-size: 3rem; opacity: 0.5;"></i>
        <div class="mt-2 small" style="opacity: 0.6;">
            We couldn't load the information right now. Please refresh the page or check back shortly.
        </div>
    </td>
`;
                                                el.meta.textContent = '';
                                            }
                                        }

                                        load();
                                    })();
                                </script>



                            </div>

                            <div class="card-footer bg-white d-flex justify-content-end align-items-center">
                                <div class="small text-muted me-auto" id="selectionHint" role="status" aria-live="polite"></div>
                                <div class="d-flex gap-2">
                                    <a href="<?= base_url('a/roles'); ?>" class="btn btn-outline-secondary">Cancel</a>
                                    <a href="<?= base_url('a/roles/create'); ?>" class="btn btn-outline-secondary">Previous</a>
                                    <button type="submit" class="btn btn-primary">Attach</button>
                                </div>
                            </div>

                        </form>
                    </div>
                    <!-- /Card -->

                </div>
            </div>
        </div>
    </div>
</main>