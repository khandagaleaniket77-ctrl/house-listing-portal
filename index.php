<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Property Search - Find Your Dream Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/app.css">
</head>
<body class="auth-bg d-flex align-items-center py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4 p-md-5">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
                            <div>
                                <h1 class="h3 mb-1">Find your next home</h1>
                                <p class="text-secondary mb-0">Search by location and flat type.</p>
                            </div>
                            <div class="d-flex gap-2">
                                <a class="btn btn-outline-secondary" href="login.php">Sign in</a>
                                <a class="btn btn-brand-red" href="register.php">Create account</a>
                            </div>
                        </div>

                        <form action="search.php" method="GET" class="row g-3 align-items-end">
                            <div class="col-12 col-md-6">
                                <label class="form-label" for="location">Location</label>
                                <input
                                    class="form-control form-control-lg"
                                    type="text"
                                    id="location"
                                    name="location"
                                    placeholder="e.g., Mumbai, Pune, Bangalore, Delhi"
                                    required
                                >
                            </div>
                            <div class="col-12 col-md-4">
                                <label class="form-label" for="flat_type">Flat type</label>
                                <select class="form-select form-select-lg" id="flat_type" name="flat_type" required>
                                    <option value="" selected disabled>Select Flat Type</option>
                                    <option value="1BHK">1 BHK</option>
                                    <option value="2BHK">2 BHK</option>
                                    <option value="3BHK">3 BHK</option>
                                    <option value="4BHK">4 BHK</option>
                                    <option value="Studio">Studio</option>
                                    <option value="Penthouse">Penthouse</option>
                                </select>
                            </div>
                            <div class="col-12 col-md-2 d-grid">
                                <button type="submit" class="btn btn-brand-red btn-lg">Search</button>
                            </div>
                        </form>

                        <hr class="my-4">
                        <div class="row g-3">
                            <div class="col-12 col-md-4">
                                <div class="p-3 rounded-3 bg-light h-100">
                                    <div class="fw-semibold mb-1">Fast</div>
                                    <div class="text-secondary small">Quick search with clean filters.</div>
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="p-3 rounded-3 bg-light h-100">
                                    <div class="fw-semibold mb-1">Responsive</div>
                                    <div class="text-secondary small">Works great on mobile & desktop.</div>
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="p-3 rounded-3 bg-light h-100">
                                    <div class="fw-semibold mb-1">Secure login</div>
                                    <div class="text-secondary small">Sign in to manage your experience.</div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <p class="text-center text-secondary small mt-3 mb-0">Property Finder</p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>