<main class="app-main d-flex align-items-center justify-content-center min-vh-100 bg-light">
    <div class="app-content text-center p-4">
        <!-- Error Image -->
        <img src="<?= base_url('assets/images/500.svg'); ?>"
            alt="500 Error"
            class="img-fluid mb-4"
            style="max-width: 450px; width: 100%; height: auto;">

        <!-- Error Title -->
        <h1 class="fw-bold text-danger">500 - Internal Server Error</h1>

        <!-- Error Description -->
        <p class="text-muted mb-4">
            Oops! Something went wrong on our server.<br>
            This may be caused by a database issue, invalid data, or an unexpected system error.
        </p>

        <!-- Action Button -->
        <a href="<?= base_url('a/dashboard'); ?>"
            class="btn btn-danger px-4 rounded-pill">Go Back Home</a>
    </div>
</main>