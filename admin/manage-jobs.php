<?php
$page_title = "Manage Jobs";
session_start();
require_once '../config.php';
require_once '../includes/db.php';
require_once '../includes/functions.php';
require_once '../includes/seo.php';

if (!isAdminLoggedIn()) redirect('../auth/admin_login.php');

// Delete job
if (isset($_GET['delete'])) {
    $job_id = intval($_GET['delete']);
    $conn->query("DELETE FROM applications WHERE job_id = $job_id");
    $conn->query("DELETE FROM jobs WHERE id = $job_id");
    redirect('manage-jobs.php?deleted=1');
}

// Edit job
$edit_job = null;
if (isset($_GET['edit'])) {
    $edit_id = intval($_GET['edit']);
    $edit_job = $conn->query("SELECT * FROM jobs WHERE id = $edit_id")->fetch_assoc();
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['job_id'])) {
    $job_id      = intval($_POST['job_id']);
    $title       = $conn->real_escape_string(trim($_POST['title']));
    $company     = $conn->real_escape_string(trim($_POST['company_name']));
    $description = $conn->real_escape_string(trim($_POST['description']));
    $last_date   = $conn->real_escape_string(trim($_POST['last_date_to_apply']));

    if (empty($title) || empty($company) || empty($description) || empty($last_date)) {
        $error = 'All fields are required.';
        $edit_job = ['id' => $job_id, 'title' => $title, 'company_name' => $company, 'description' => $description, 'last_date_to_apply' => $last_date];
    } else {
        $sql = "UPDATE jobs SET title='$title', company_name='$company', description='$description', last_date_to_apply='$last_date' WHERE id=$job_id";
        $conn->query($sql);
        redirect('manage-jobs.php?updated=1');
    }
}

$search = trim($_GET['search'] ?? '');
$where = $search ? "WHERE title LIKE '%$search%' OR company_name LIKE '%$search%'" : '';
$jobs = $conn->query("SELECT *, (SELECT COUNT(*) FROM applications WHERE job_id = jobs.id) as app_count 
                       FROM jobs $where ORDER BY last_date_to_apply ASC");

$page_seo = generateSEO(['title' => 'Manage Jobs | Admin', 'robots' => 'noindex, nofollow']);
require_once '../includes/header.php';
require_once '../includes/navbar.php';
?>

<main class="container-fluid my-4">
    <div class="admin-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 2.5rem 2rem; border-radius: 10px; margin-bottom: 2rem;">
        <h1 class="display-6 fw-bold mb-2"><i class="fas fa-tasks"></i> Manage Jobs</h1>
        <p class="lead mb-0">Edit or delete posted internship opportunities.</p>
    </div>

    <?php if (isset($_GET['deleted'])): ?>
        <div class="alert alert-success alert-dismissible fade show"><i class="fas fa-check-circle"></i> Job deleted successfully.<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    <?php endif; ?>
    <?php if (isset($_GET['updated'])): ?>
        <div class="alert alert-success alert-dismissible fade show"><i class="fas fa-check-circle"></i> Job updated successfully.<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-danger alert-dismissible fade show"><i class="fas fa-exclamation-circle"></i> <?php echo $error; ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    <?php endif; ?>

    <!-- Edit Form (shown when editing) -->
    <?php if ($edit_job): ?>
    <div class="card mb-4 border-primary">
        <div class="card-header"><i class="fas fa-edit"></i> Edit Job: <?php echo htmlspecialchars($edit_job['title']); ?></div>
        <div class="card-body">
            <form method="POST">
                <input type="hidden" name="job_id" value="<?php echo $edit_job['id']; ?>">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Job Title</label>
                        <input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($edit_job['title']); ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Company Name</label>
                        <input type="text" name="company_name" class="form-control" value="<?php echo htmlspecialchars($edit_job['company_name']); ?>" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="4" required><?php echo htmlspecialchars($edit_job['description']); ?></textarea>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Last Date to Apply</label>
                        <input type="date" name="last_date_to_apply" class="form-control" value="<?php echo $edit_job['last_date_to_apply']; ?>" required>
                    </div>
                </div>
                <div class="mt-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update Job</button>
                    <a href="manage-jobs.php" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
    <?php endif; ?>

    <!-- Search -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="d-flex gap-2">
                <input type="text" name="search" class="form-control" placeholder="Search by job title or company..." value="<?php echo htmlspecialchars($search); ?>">
                <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i> Search</button>
                <?php if ($search): ?><a href="manage-jobs.php" class="btn btn-outline-secondary">Clear</a><?php endif; ?>
            </form>
        </div>
    </div>

    <!-- Jobs Table -->
    <div class="card">
        <div class="card-header"><i class="fas fa-list"></i> All Jobs (<?php echo $jobs->num_rows; ?>)</div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Title</th>
                        <th>Company</th>
                        <th>Last Date</th>
                        <th>Applications</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($jobs->num_rows > 0): ?>
                        <?php while ($job = $jobs->fetch_assoc()):
                            $is_expired = strtotime($job['last_date_to_apply']) < strtotime(date('Y-m-d'));
                        ?>
                        <tr>
                            <td><strong><?php echo htmlspecialchars($job['title']); ?></strong></td>
                            <td><?php echo htmlspecialchars($job['company_name']); ?></td>
                            <td><?php echo date('d M Y', strtotime($job['last_date_to_apply'])); ?></td>
                            <td><span class="badge bg-info"><?php echo $job['app_count']; ?></span></td>
                            <td>
                                <?php if ($is_expired): ?>
                                    <span class="badge bg-danger">Expired</span>
                                <?php else: ?>
                                    <span class="badge bg-success">Active</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="?edit=<?php echo $job['id']; ?>" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i></a>
                                <button class="btn btn-sm btn-danger delete-job-btn" data-job-id="<?php echo $job['id']; ?>" data-job-title="<?php echo htmlspecialchars($job['title']); ?>">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="6" class="text-center text-muted py-4">No jobs found</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<!-- Delete Confirm Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h6 class="modal-title">Confirm Delete</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <i class="fas fa-exclamation-triangle fa-3x text-danger mb-3"></i>
                <p>Delete "<strong id="deleteJobTitle"></strong>"? This will also delete all related applications.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                <a href="#" id="confirmDeleteLink" class="btn btn-danger btn-sm">Yes, Delete</a>
            </div>
        </div>
    </div>
</div>

<script>
document.querySelectorAll('.delete-job-btn').forEach(btn => {
    btn.addEventListener('click', function () {
        document.getElementById('deleteJobTitle').textContent = this.dataset.jobTitle;
        document.getElementById('confirmDeleteLink').href = '?delete=' + this.dataset.jobId;
        new bootstrap.Modal(document.getElementById('deleteModal')).show();
    });
});
</script>

<?php require_once '../includes/footer.php'; ?>