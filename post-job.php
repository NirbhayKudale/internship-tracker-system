<?php
$page_title = "Post New Job";
session_start();
require_once '../config.php';
require_once '../includes/db.php';
require_once '../includes/functions.php';
require_once '../includes/seo.php';

if (!isAdminLoggedIn()) redirect('../auth/admin_login.php');

$success = $error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title       = $conn->real_escape_string(trim($_POST['title']));
    $company     = $conn->real_escape_string(trim($_POST['company_name']));
    $description = $conn->real_escape_string(trim($_POST['description']));
    $last_date   = $conn->real_escape_string(trim($_POST['last_date_to_apply']));

    if (empty($title) || empty($company) || empty($description) || empty($last_date)) {
        $error = 'All fields are required.';
    } elseif (strtotime($last_date) < strtotime(date('Y-m-d'))) {
        $error = 'Last date to apply cannot be in the past.';
    } else {
        $sql = "INSERT INTO jobs (title, company_name, description, last_date_to_apply) 
                VALUES ('$title', '$company', '$description', '$last_date')";
        if ($conn->query($sql)) {
            $success = 'Job posted successfully!';
        } else {
            $error = 'Failed to post job. Please try again.';
        }
    }
}

$page_seo = generateSEO(['title' => 'Post New Job | Admin', 'robots' => 'noindex, nofollow']);
require_once '../includes/header.php';
require_once '../includes/navbar.php';
?>

<main class="container my-4">
    <div class="admin-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 2.5rem 2rem; border-radius: 10px; margin-bottom: 2rem;">
        <h1 class="display-6 fw-bold mb-2"><i class="fas fa-plus-circle"></i> Post New Job</h1>
        <p class="lead mb-0">Create a new internship opportunity for students.</p>
    </div>

    <?php if ($success): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle"></i> <?php echo $success; ?>
            <a href="manage-jobs.php" class="alert-link ms-2">View all jobs</a>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"><i class="fas fa-briefcase"></i> Job Details</div>
                <div class="card-body">
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Job Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control" placeholder="e.g. Frontend Developer Intern" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Company Name <span class="text-danger">*</span></label>
                            <input type="text" name="company_name" class="form-control" placeholder="e.g. TechCorp Solutions" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Job Description <span class="text-danger">*</span></label>
                            <textarea name="description" class="form-control" rows="6" placeholder="Describe the role, responsibilities, and requirements..." required></textarea>
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Last Date to Apply <span class="text-danger">*</span></label>
                            <input type="date" name="last_date_to_apply" class="form-control" min="<?php echo date('Y-m-d'); ?>" required>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-paper-plane"></i> Post Job
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

<?php require_once '../includes/footer.php'; ?>