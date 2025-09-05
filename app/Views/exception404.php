<main class="app-main d-flex align-items-center justify-content-center min-vh-100 bg-light">
    <!--begin::App Content-->
    <div class="app-content text-center p-4">
        <!-- Error Image -->
        <img src="<?= base_url('assets/images/404.svg'); ?>"
            alt="404 Error"
            class="img-fluid mb-4"
            style="max-width: 450px; width: 100%; height: auto;">

        <!-- Error Title -->
        <h1 class="fw-bold text-danger">404 - Page Not Found</h1>

        <!-- Error Description -->
        <p class="text-muted mb-4">
            Sorry, the page you are looking for doesn’t exist, has been removed,
            or is temporarily unavailable.
        </p>

        <!-- Action Button -->
        <a href="<?= base_url('a/dashboard'); ?>"
            class="btn btn-danger px-4 rounded-pill">Go Back Home</a>
    </div>
    <!--end::App Content-->
</main>