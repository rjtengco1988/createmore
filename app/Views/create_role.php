<main class="app-main">
    <!--begin::App Content Header-->
    <div class="app-content-header">
        <!--begin::Container-->
        <div class="container-fluid">
            <!--begin::Row-->
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="fw-semibold mb-0" style="font-size: 2 rem;">
                        Set up role for your application
                    </h3>
                </div>
            </div>
            <!--end::Row-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::App Content Header-->
    <!--begin::App Content-->
    <div class="app-content">
        <!--begin::Container-->
        <div class="container-fluid">
            <!--begin::Row-->
            <div class="row g-4">

                <div class="col-md-12">

                    <div class="accordion my-4" id="howItWorksAccordion">
                        <div class="accordion-item border rounded shadow-sm">
                            <h2 class="accordion-header" id="headingHowItWorks">
                                <button class="accordion-button text-dark fw-bold px-3 py-2 border-0" type="button"
                                    data-bs-toggle="collapse"
                                    data-bs-target="#collapseHowItWorks"
                                    aria-expanded="true"
                                    aria-controls="collapseHowItWorks"
                                    style="background-color: transparent; box-shadow: none;">
                                    How it works
                                </button>
                            </h2>
                            <div id="collapseHowItWorks" class="accordion-collapse collapse show" aria-labelledby="headingHowItWorks">
                                <div class="accordion-body">
                                    <div class="row text-center">
                                        <!-- Step 1 -->
                                        <div class="col-md-4 mb-4">
                                            <img src="<?= base_url('assets/images/undraw_solution-mindset_pit7.svg'); ?>" alt="Define Role" class="mb-3" height="120">
                                            <h6 class="fw-bold">1. Define your role</h6>
                                            <p class="text-muted small">
                                                Set a name, slug, and description to create a new role for your application users.
                                            </p>
                                        </div>

                                        <!-- Step 2 -->
                                        <div class="col-md-4 mb-4">
                                            <img src="<?= base_url('assets/images/undraw_secure-login_m11a.svg'); ?>" alt="Attach Permissions" class="mb-3" height="120">
                                            <h6 class="fw-bold">2. Attach permissions</h6>
                                            <p class="text-muted small">
                                                Assign permissions to the role by selecting access levels for different modules.
                                            </p>
                                        </div>

                                        <!-- Step 3 -->
                                        <div class="col-md-4 mb-4">
                                            <img src="<?= base_url('assets/images/undraw_team-spirit_18vw.svg'); ?>" alt="Assign Role" class="mb-3" height="120">
                                            <h6 class="fw-bold">3. Assign role to users</h6>
                                            <p class="text-muted small">
                                                Link the defined role to one or more user accounts to grant them appropriate access.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>



                </div>

                <!--begin::Col-->
                <div class="col-md-12">

                    <!--begin::Quick Example-->
                    <div class="card card-primary card-outline mb-4">

                        <?php if (isset($validation) && $validation->getErrors()): ?>
                            <style>
                                .aws-banner {
                                    background: #d13212;
                                    color: #fff;
                                    padding: 10px 16px;
                                    font-family: sans-serif;
                                    display: flex;
                                    align-items: center;
                                    justify-content: space-between;
                                    gap: 12px;
                                    animation: fadeIn .5s ease-in-out
                                }

                                .aws-banner.fade-out {
                                    animation: fadeOut .5s ease-in-out forwards
                                }

                                .aws-banner__msg {
                                    display: flex;
                                    gap: 10px;
                                    align-items: flex-start
                                }

                                .aws-banner__list {
                                    margin: 0;
                                    padding-left: 18px
                                }

                                .aws-banner__close {
                                    background: none;
                                    border: none;
                                    color: #fff;
                                    font-size: 18px;
                                    cursor: pointer
                                }

                                @keyframes fadeIn {
                                    from {
                                        opacity: 0;
                                        transform: translateY(-10px)
                                    }

                                    to {
                                        opacity: 1;
                                        transform: translateY(0)
                                    }
                                }

                                @keyframes fadeOut {
                                    from {
                                        opacity: 1;
                                        transform: translateY(0)
                                    }

                                    to {
                                        opacity: 0;
                                        transform: translateY(-10px)
                                    }
                                }
                            </style>
                            <div class="aws-banner" id="awsBanner">
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
                            <script>
                                function closeAwsBanner() {
                                    const b = document.getElementById('awsBanner');
                                    b.classList.add('fade-out');
                                    setTimeout(() => b.remove(), 500);
                                }
                            </script>
                        <?php endif; ?>

                        <!--begin::Header-->
                        <div class="card-header">
                            <div class="card-title text-dark fw-bold">Define your role</div>
                        </div>
                        <!--end::Header-->
                        <!--begin::Form-->
                        <form action="<?= base_url('a/roles/create'); ?>" method="POST">
                            <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
                            <!--begin::Body-->
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="inputName" class="form-label">
                                        Name of your role
                                        <a href="#" tabindex="0" data-bs-toggle="tooltip" data-bs-placement="right"
                                            title="This is the display name of the role. It helps identify the role within the system and should be easily recognizable by administrators.">
                                            <i class="bi bi-info-circle ms-1"></i>
                                        </a>
                                    </label>
                                    <input type="text" class="form-control <?= isset($validation) && $validation->hasError('roleName') ? 'is-invalid' : '' ?>" id="inputName" maxlength="128" name="roleName" value="<?= set_value('roleName'); ?>">
                                    <div class=" form-text text-muted">
                                        Names are limited to 128 characters or fewer. Names may only contain alphanumeric characters, spaces, and the following special characters: + = , . @ -
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="inputSlug" class="form-label">
                                        Slug
                                        <a href="#" tabindex="0" data-bs-toggle="tooltip" data-bs-placement="right" title="The slug is a unique, URL-friendly identifier used to reference this role in the system. It must be lowercase and can only contain letters, numbers, and hyphens.">
                                            <i class="bi bi-info-circle ms-1"></i>
                                        </a>
                                    </label>
                                    <input type="text" class="form-control <?= isset($validation) && $validation->hasError('roleSlug') ? 'is-invalid' : '' ?>" id="inputSlug" name="roleSlug" />
                                    <div class="form-text text-muted">
                                        A slug must be unique and contain only lowercase letters, numbers, and hyphens. No spaces or special characters are allowed.
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="inputDescription" class="form-label">
                                        Description
                                        <a href="#" tabindex="0" data-bs-toggle="tooltip" data-bs-placement="right"
                                            title="Provide a short explanation of what this role is intended for. This helps administrators understand its purpose at a glance.">
                                            <i class="bi bi-info-circle ms-1"></i>
                                        </a>
                                    </label>
                                    <textarea class="form-control <?= isset($validation) && $validation->hasError('roleDescription') ? 'is-invalid' : '' ?>" id="inputDescription" rows="3" maxlength="200" name="roleDescription"><?= set_value('roleDescription') ?></textarea>
                                    <div class="form-text text-muted">
                                        Description must be 200 characters or fewer.
                                    </div>
                                </div>
                            </div>
                            <!--end::Body-->
                            <!--begin::Footer-->
                            <div class="card-footer bg-white d-flex justify-content-end gap-2">
                                <a href="<?= base_url('a/roles'); ?>" class="btn btn-outline-secondary">
                                    Cancel
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    Create Role
                                </button>
                            </div>
                            <!--end::Footer-->
                        </form>
                        <!--end::Form-->
                    </div>
                    <!--end::Quick Example-->


                </div>
                <!--end::Col-->

            </div>
            <!--end::Row-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::App Content-->
</main>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        const tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>